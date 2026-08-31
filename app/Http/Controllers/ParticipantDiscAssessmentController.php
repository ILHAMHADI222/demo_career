<?php

namespace App\Http\Controllers;

use App\Models\DiscAssessment;
use App\Models\DiscAssessmentAnswer;
use App\Models\DiscAssessmentParticipant;
use App\Models\DiscAssessmentSecurityEvent;
use App\Models\DiscAssessmentSession;
use App\Models\DiscQuestion;
use App\Models\DiscQuestionOption;
use App\Models\PsikotesStageQuestion;
use App\Services\AuditLogger;
use App\Services\DiscScoringEngine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ParticipantDiscAssessmentController extends Controller
{
    public function instruction(DiscAssessment $assessment): View
    {
        abort_unless($assessment->isAvailable(), 403, 'Asesmen Psikotes ini belum tersedia atau sudah ditutup.');

        $participant = DiscAssessmentParticipant::query()
            ->where('disc_assessment_id', $assessment->id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$participant) {
            $participant = DiscAssessmentParticipant::create([
                'disc_assessment_id' => $assessment->id,
                'user_id' => auth()->id(),
                'status' => 'ASSIGNED',
                'invited_at' => now(),
            ]);
        }

        $latestSession = $participant->latestSession;

        $completedAttempts = DiscAssessmentSession::query()
            ->where('disc_assessment_id', $assessment->id)
            ->where('participant_id', auth()->id())
            ->where('status', 'COMPLETED')
            ->count();

        $maxAttempts = (int) ($participant->max_attempts ?: 1);
        $canStartNew = $completedAttempts < $maxAttempts;

        return view('disc.instruction', compact('assessment', 'participant', 'latestSession', 'completedAttempts', 'maxAttempts', 'canStartNew'));
    }

    public function startSession(DiscAssessment $assessment): RedirectResponse
    {
        abort_unless($assessment->isAvailable(), 403, 'Asesmen Psikotes tidak tersedia.');

        $participant = DiscAssessmentParticipant::firstOrCreate(
            ['disc_assessment_id' => $assessment->id, 'user_id' => auth()->id()],
            ['status' => 'ASSIGNED', 'invited_at' => now(), 'max_attempts' => 1]
        );

        // Check if there is an active session
        $activeSession = DiscAssessmentSession::query()
            ->where('disc_assessment_id', $assessment->id)
            ->where('participant_id', auth()->id())
            ->where('status', 'IN_PROGRESS')
            ->first();

        if ($activeSession) {
            return redirect()->route('disc.assessment.take', $activeSession->session_token);
        }

        // Check attempts
        $completedAttempts = DiscAssessmentSession::query()
            ->where('disc_assessment_id', $assessment->id)
            ->where('participant_id', auth()->id())
            ->where('status', 'COMPLETED')
            ->count();

        $maxAttempts = $participant->max_attempts ?: 1;
        if ($completedAttempts >= $maxAttempts) {
            return back()->withErrors(['error' => 'Anda telah menyelesaikan batas kesempatan tes untuk asesmen psikotes ini.']);
        }

        // Prepare Questions and Options order
        $bank = $assessment->questionBank;
        $questions = $bank->activeQuestions()->with('activeOptions')->get();

        $questionIds = $questions->pluck('id')->all();
        if ($assessment->randomize_questions) {
            shuffle($questionIds);
        }

        $optionOrderMap = [];
        $questionSnapshot = [];
        $scoringSnapshot = [];

        foreach ($questions as $q) {
            $opts = $q->activeOptions;
            $optIds = $opts->pluck('id')->all();
            if ($assessment->randomize_options) {
                shuffle($optIds);
            }
            $optionOrderMap[$q->id] = $optIds;

            $questionSnapshot[$q->id] = [
                'number' => $q->question_number,
                'instruction' => $q->instruction,
                'options' => $opts->map(fn ($o) => [
                    'id' => $o->id,
                    'label' => $o->option_label,
                    'statement' => $o->statement,
                ])->values()->all(),
            ];

            $scoringSnapshot[$q->id] = $opts->mapWithKeys(fn ($o) => [
                $o->id => [
                    'most' => $o->most_mapping,
                    'least' => $o->least_mapping,
                ],
            ])->all();
        }

        $token = 'PSI-' . strtoupper(Str::random(12)) . '-' . time();

        $session = DiscAssessmentSession::create([
            'disc_assessment_id' => $assessment->id,
            'disc_assessment_participant_id' => $participant->id,
            'participant_id' => auth()->id(),
            'attempt_number' => $completedAttempts + 1,
            'session_token' => $token,
            'status' => 'IN_PROGRESS',
            'current_stage' => 1,
            'current_question_index' => 0,
            'started_at' => now(),
            'stage_1_started_at' => now(),
            'last_activity_at' => now(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'question_order_json' => $questionIds,
            'option_order_json' => $optionOrderMap,
            'question_snapshot_json' => $questionSnapshot,
            'scoring_snapshot_json' => $scoringSnapshot,
            'security_score' => 100,
        ]);

        $participant->update(['status' => 'IN_PROGRESS']);
        AuditLogger::log('START_ASSESSMENT_SESSION', 'PSIKOTES', $session);

        return redirect()->route('disc.assessment.take', $token);
    }

    public function take(string $token): View|RedirectResponse
    {
        $session = DiscAssessmentSession::query()
            ->where('session_token', $token)
            ->where('participant_id', auth()->id())
            ->firstOrFail();

        if ($session->isCompleted()) {
            return redirect()->route('disc.assessment.completed', $token);
        }

        $assessment = $session->assessment;
        $bank = $assessment->questionBank;

        // Current Stage Dispatcher
        if ($session->current_stage == 1) {
            if (!$session->stage_1_started_at) {
                $session->update(['stage_1_started_at' => now()]);
                $session->refresh();
            }
            $questions = $bank ? $bank->deretQuestions : collect();
            if ($questions->isEmpty()) {
                $questions = PsikotesStageQuestion::where('stage', 'STAGE_1_DERET')->where('active', true)->orderBy('question_number')->get();
            }
            $durationMinutes = (int) ($assessment->duration_stage_1 ?: 10);
            $targetEndAt = $session->stage_1_started_at->copy()->addMinutes($durationMinutes);
            $targetTimestampMs = $targetEndAt->timestamp * 1000;
            $savedAnswers = $session->stage_1_answers_json ?? [];

            return view('disc.take_stage1', compact('session', 'assessment', 'questions', 'targetTimestampMs', 'durationMinutes', 'savedAnswers'));
        }

        if ($session->current_stage == 2) {
            if (!$session->stage_2_started_at) {
                $session->update(['stage_2_started_at' => now()]);
                $session->refresh();
            }

            $questions = $bank ? $bank->analogiQuestions : collect();
            if ($questions->isEmpty()) {
                $questions = PsikotesStageQuestion::where('stage', 'STAGE_2_ANALOGI')->where('active', true)->orderBy('question_number')->get();
            }
            $durationMinutes = (int) ($assessment->duration_stage_2 ?: 10);
            $targetEndAt = $session->stage_2_started_at->copy()->addMinutes($durationMinutes);
            $targetTimestampMs = $targetEndAt->timestamp * 1000;
            $savedAnswers = $session->stage_2_answers_json ?? [];

            return view('disc.take_stage2', compact('session', 'assessment', 'questions', 'targetTimestampMs', 'durationMinutes', 'savedAnswers'));
        }

        // Stage 3: Untimed Personality Test
        if (!$session->stage_3_started_at) {
            $session->update(['stage_3_started_at' => now()]);
            $session->refresh();
        }

        return view('disc.take', compact('session', 'assessment'));
    }

    public function submitStage1(Request $request, string $token): RedirectResponse
    {
        $session = DiscAssessmentSession::query()
            ->where('session_token', $token)
            ->where('participant_id', auth()->id())
            ->firstOrFail();

        if ($session->isCompleted()) {
            return redirect()->route('disc.assessment.completed', $token);
        }

        $answers = $request->input('answers', []);
        $bank = $session->assessment->questionBank;
        $questions = $bank ? $bank->deretQuestions : collect();
        if ($questions->isEmpty()) {
            $questions = PsikotesStageQuestion::where('stage', 'STAGE_1_DERET')->where('active', true)->orderBy('question_number')->get();
        }

        $correctCount = 0;
        $totalQuestions = $questions->count();

        foreach ($questions as $q) {
            $userAns1 = trim(strtolower($answers[$q->id]['ans_1'] ?? ''));
            $userAns2 = trim(strtolower($answers[$q->id]['ans_2'] ?? ''));

            $correctAns1 = trim(strtolower($q->correct_answer_1 ?? ''));
            $correctAns2 = trim(strtolower($q->correct_answer_2 ?? ''));

            // Check if matches
            if ($userAns1 === $correctAns1 && $userAns2 === $correctAns2) {
                $correctCount++;
            }
        }

        $score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100, 2) : 0;

        $session->update([
            'stage_1_answers_json' => $answers,
            'stage_1_score' => $score,
            'stage_1_correct_count' => $correctCount,
            'stage_1_completed_at' => now(),
            'current_stage' => 2,
            'stage_2_started_at' => now(),
            'last_activity_at' => now(),
        ]);

        AuditLogger::log('SUBMIT_STAGE_1', 'PSIKOTES', $session, ['score' => $score, 'correct_count' => $correctCount]);

        return redirect()->route('disc.assessment.take', $token)->with('stage_success', 'Sub-Tes 1 (Deret Hitung) berhasil diselesaikan! Silakan lanjutkan ke Sub-Tes 2 (Analogi Verbal).');
    }

    public function submitStage2(Request $request, string $token): RedirectResponse
    {
        $session = DiscAssessmentSession::query()
            ->where('session_token', $token)
            ->where('participant_id', auth()->id())
            ->firstOrFail();

        if ($session->isCompleted()) {
            return redirect()->route('disc.assessment.completed', $token);
        }

        $answers = $request->input('answers', []);
        $bank = $session->assessment->questionBank;
        $questions = $bank ? $bank->analogiQuestions : collect();
        if ($questions->isEmpty()) {
            $questions = PsikotesStageQuestion::where('stage', 'STAGE_2_ANALOGI')->where('active', true)->orderBy('question_number')->get();
        }

        $correctCount = 0;
        $totalQuestions = $questions->count();

        foreach ($questions as $q) {
            $userOption = strtoupper(trim($answers[$q->id] ?? ''));
            $correctOption = strtoupper(trim($q->correct_answer_1 ?? ''));

            if ($userOption === $correctOption && !empty($userOption)) {
                $correctCount++;
            }
        }

        $score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100, 2) : 0;

        $session->update([
            'stage_2_answers_json' => $answers,
            'stage_2_score' => $score,
            'stage_2_correct_count' => $correctCount,
            'stage_2_completed_at' => now(),
            'current_stage' => 3,
            'stage_3_started_at' => now(),
            'last_activity_at' => now(),
        ]);

        AuditLogger::log('SUBMIT_STAGE_2', 'PSIKOTES', $session, ['score' => $score, 'correct_count' => $correctCount]);

        return redirect()->route('disc.assessment.take', $token)->with('stage_success', 'Sub-Tes 2 (Analogi Verbal) berhasil diselesaikan! Silakan selesaikan Sub-Tes 3 (Tes Kepribadian) tanpa batasan waktu.');
    }

    /**
     * API for Stage 3 questions
     */
    public function getQuestionApi(string $token, Request $request): JsonResponse
    {
        $session = DiscAssessmentSession::query()
            ->where('session_token', $token)
            ->where('participant_id', auth()->id())
            ->firstOrFail();

        if ($session->isCompleted()) {
            return response()->json(['completed' => true, 'redirect' => route('disc.assessment.completed', $token)]);
        }

        $questionOrder = $session->question_order_json ?? [];
        $totalQuestions = count($questionOrder);

        $index = $request->integer('index', $session->current_question_index);
        $index = max(0, min($totalQuestions - 1, $index));

        $session->update([
            'current_question_index' => $index,
            'last_activity_at' => now(),
        ]);

        $questionId = $questionOrder[$index] ?? null;
        if (!$questionId) {
            return response()->json(['error' => 'Question not found'], 404);
        }

        $question = DiscQuestion::with('activeOptions')->findOrFail($questionId);
        $optionOrder = $session->option_order_json[$questionId] ?? [];

        $optionsCollection = $question->activeOptions->keyBy('id');
        $sortedOptions = [];

        if (!empty($optionOrder)) {
            foreach ($optionOrder as $optId) {
                if (isset($optionsCollection[$optId])) {
                    $opt = $optionsCollection[$optId];
                    $sortedOptions[] = [
                        'id' => $opt->id,
                        'label' => $opt->option_label,
                        'statement' => $opt->statement,
                    ];
                }
            }
        } else {
            foreach ($question->activeOptions as $opt) {
                $sortedOptions[] = [
                    'id' => $opt->id,
                    'label' => $opt->option_label,
                    'statement' => $opt->statement,
                ];
            }
        }

        $answer = DiscAssessmentAnswer::query()
            ->where('disc_assessment_session_id', $session->id)
            ->where('disc_question_id', $questionId)
            ->first();

        $allAnswers = DiscAssessmentAnswer::query()
            ->where('disc_assessment_session_id', $session->id)
            ->get()
            ->keyBy('disc_question_id');

        $answeredIndices = [];
        foreach ($questionOrder as $i => $qId) {
            $ans = $allAnswers->get($qId);
            if ($ans && $ans->most_option_id && $ans->least_option_id) {
                $answeredIndices[] = $i;
            }
        }

        return response()->json([
            'index' => $index,
            'total' => $totalQuestions,
            'total_questions' => $totalQuestions,
            'question_id' => $question->id,
            'question_number' => $index + 1,
            'instruction' => $question->instruction ?: 'Pilih satu pernyataan PALING dan satu pernyataan PALING TIDAK menggambarkan diri Anda.',
            'options' => $sortedOptions,
            'question' => [
                'id' => $question->id,
                'number' => $index + 1,
                'instruction' => $question->instruction ?: 'Pilih satu pernyataan PALING dan satu pernyataan PALING TIDAK menggambarkan diri Anda.',
                'options' => $sortedOptions,
            ],
            'current_answer' => [
                'most_option_id' => $answer?->most_option_id,
                'least_option_id' => $answer?->least_option_id,
            ],
            'answered_indices' => $answeredIndices,
        ]);
    }

    public function saveAnswerApi(string $token, Request $request): JsonResponse
    {
        $session = DiscAssessmentSession::query()
            ->where('session_token', $token)
            ->where('participant_id', auth()->id())
            ->firstOrFail();

        if ($session->isCompleted()) {
            return response()->json(['error' => 'Sesi tes telah selesai'], 400);
        }

        $data = $request->validate([
            'question_id' => ['required', 'exists:disc_questions,id'],
            'most_option_id' => ['nullable', 'exists:disc_question_options,id'],
            'least_option_id' => ['nullable', 'exists:disc_question_options,id'],
        ]);

        if (!empty($data['most_option_id']) && !empty($data['least_option_id'])) {
            if ($data['most_option_id'] === $data['least_option_id']) {
                return response()->json(['error' => 'Pilihan Most dan Least tidak boleh pada pernyataan yang sama.'], 422);
            }
        }

        $answer = DiscAssessmentAnswer::updateOrCreate(
            [
                'disc_assessment_session_id' => $session->id,
                'disc_question_id' => $data['question_id'],
            ],
            [
                'most_option_id' => $data['most_option_id'] ?? null,
                'least_option_id' => $data['least_option_id'] ?? null,
                'most_selected_at' => !empty($data['most_option_id']) ? now() : null,
                'least_selected_at' => !empty($data['least_option_id']) ? now() : null,
                'is_autosaved' => true,
            ]
        );

        $session->update(['last_activity_at' => now()]);

        return response()->json([
            'success' => true,
            'saved' => true,
            'saved_at' => now()->toIso8601String()
        ]);
    }

    public function submitSessionApi(string $token, Request $request, DiscScoringEngine $scoringEngine): JsonResponse|RedirectResponse
    {
        $session = DiscAssessmentSession::query()
            ->where('session_token', $token)
            ->where('participant_id', auth()->id())
            ->firstOrFail();

        if ($session->isCompleted()) {
            $redirectUrl = route('disc.assessment.completed', $token);
            return $request->wantsJson()
                ? response()->json(['success' => true, 'redirect' => $redirectUrl, 'redirect_url' => $redirectUrl])
                : redirect()->route('disc.assessment.completed', $token);
        }

        DB::transaction(function () use ($session, $scoringEngine) {
            $scoringResult = $scoringEngine->calculateSessionScore($session);

            $session->update([
                'status' => 'COMPLETED',
                'completed_at' => now(),
                'stage_3_completed_at' => now(),
                'raw_scores_json' => $scoringResult['raw_scores'],
                'normalized_scores_json' => $scoringResult['normalized_scores'],
                'disc_profile_code' => $scoringResult['profile_code'] ?? ($scoringResult['profile']['code'] ?? null),
                'disc_profile_name' => $scoringResult['profile_name'] ?? ($scoringResult['profile']['name'] ?? null),
                'interpretation_json' => $scoringResult['interpretation'] ?? [],
            ]);

            if ($session->assessmentParticipant) {
                $session->assessmentParticipant->update(['status' => 'COMPLETED']);
            }
        });

        AuditLogger::log('COMPLETE_ASSESSMENT_SESSION', 'PSIKOTES', $session);

        $redirectUrl = route('disc.assessment.completed', $token);
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'redirect' => $redirectUrl,
                'redirect_url' => $redirectUrl,
            ]);
        }

        return redirect()->route('disc.assessment.completed', $token);
    }

    public function logSecurityEventApi(string $token, Request $request): JsonResponse
    {
        $session = DiscAssessmentSession::query()
            ->where('session_token', $token)
            ->where('participant_id', auth()->id())
            ->firstOrFail();

        $data = $request->validate([
            'event_type' => ['required', 'string', 'max:60'],
            'metadata' => ['nullable', 'array'],
        ]);

        DiscAssessmentSecurityEvent::create([
            'disc_assessment_session_id' => $session->id,
            'event_type' => $data['event_type'],
            'metadata_json' => $data['metadata'] ?? null,
            'occurred_at' => now(),
        ]);

        $penalty = match ($data['event_type']) {
            'TAB_SWITCH', 'WINDOW_BLUR' => 5,
            'FULLSCREEN_EXIT' => 10,
            'DEVTOOLS_OPEN' => 20,
            default => 2,
        };

        $newScore = max(0, $session->security_score - $penalty);
        $session->update(['security_score' => $newScore, 'last_activity_at' => now()]);

        return response()->json(['success' => true, 'security_score' => $newScore]);
    }

    public function completed(string $token): View
    {
        $session = DiscAssessmentSession::query()
            ->where('session_token', $token)
            ->where('participant_id', auth()->id())
            ->with(['assessment'])
            ->firstOrFail();

        $assessment = $session->assessment;

        return view('disc.completed', compact('session', 'assessment'));
    }
}
