<?php

namespace App\Http\Controllers;

use App\Models\DiscAssessment;
use App\Models\DiscAssessmentAnswer;
use App\Models\DiscAssessmentParticipant;
use App\Models\DiscAssessmentSecurityEvent;
use App\Models\DiscAssessmentSession;
use App\Models\DiscQuestion;
use App\Models\DiscQuestionOption;
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
        abort_unless($assessment->isAvailable(), 403, 'Asesmen ini belum tersedia atau sudah ditutup.');

        $participant = DiscAssessmentParticipant::query()
            ->where('disc_assessment_id', $assessment->id)
            ->where('user_id', auth()->id())
            ->first();

        // If not assigned explicitly, check if user is a candidate or create auto-assignment if open
        if (!$participant) {
            $participant = DiscAssessmentParticipant::create([
                'disc_assessment_id' => $assessment->id,
                'user_id' => auth()->id(),
                'status' => 'ASSIGNED',
                'invited_at' => now(),
            ]);
        }

        $latestSession = $participant->latestSession;

        return view('disc.instruction', compact('assessment', 'participant', 'latestSession'));
    }

    public function startSession(DiscAssessment $assessment): RedirectResponse
    {
        abort_unless($assessment->isAvailable(), 403, 'Asesmen tidak tersedia.');

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
            return back()->withErrors(['error' => 'Anda telah mencapai batas maksimal percobaan untuk asesmen ini.']);
        }

        // Prepare Questions and Options order
        $bank = $assessment->questionBank;
        $questions = $bank->activeQuestions()->with('activeOptions')->get();

        if ($questions->count() < 24) {
            return back()->withErrors(['error' => 'Bank Soal asesmen ini belum lengkap (kurang dari 24 soal). Hubungi HRD.']);
        }

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

        $token = 'DISC-' . strtoupper(Str::random(12)) . '-' . time();

        $session = DiscAssessmentSession::create([
            'disc_assessment_id' => $assessment->id,
            'disc_assessment_participant_id' => $participant->id,
            'participant_id' => auth()->id(),
            'attempt_number' => $completedAttempts + 1,
            'session_token' => $token,
            'status' => 'IN_PROGRESS',
            'current_question_index' => 0,
            'started_at' => now(),
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
        AuditLogger::log('START_ASSESSMENT_SESSION', 'DISC', $session);

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

        return view('disc.take', compact('session', 'assessment'));
    }

    /**
     * Secure Question API - NEVER returns scoring keys
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

        // Current Answer for this question
        $answer = DiscAssessmentAnswer::query()
            ->where('disc_assessment_session_id', $session->id)
            ->where('disc_question_id', $questionId)
            ->first();

        // Calculate answered questions count
        $answeredCount = DiscAssessmentAnswer::query()
            ->where('disc_assessment_session_id', $session->id)
            ->whereNotNull('most_option_id')
            ->whereNotNull('least_option_id')
            ->count();

        // Calculate remaining seconds
        $durationSeconds = $session->assessment->duration_minutes * 60;
        $elapsedSeconds = $session->started_at ? now()->diffInSeconds($session->started_at) : 0;
        $remainingSeconds = max(0, $durationSeconds - $elapsedSeconds);

        return response()->json([
            'index' => $index,
            'total_questions' => $totalQuestions,
            'question_id' => $question->id,
            'question_number' => $index + 1,
            'instruction' => $question->instruction,
            'options' => $sortedOptions,
            'current_answer' => [
                'most_option_id' => $answer?->most_option_id,
                'least_option_id' => $answer?->least_option_id,
            ],
            'answered_count' => $answeredCount,
            'time_remaining_seconds' => $remainingSeconds,
        ]);
    }

    /**
     * Autosave Answer API
     */
    public function saveAnswerApi(string $token, Request $request): JsonResponse
    {
        $session = DiscAssessmentSession::query()
            ->where('session_token', $token)
            ->where('participant_id', auth()->id())
            ->firstOrFail();

        if ($session->isCompleted()) {
            return response()->json(['error' => 'Asesmen sudah selesai dan tidak dapat diubah.'], 403);
        }

        $data = $request->validate([
            'question_id' => ['required', 'exists:disc_questions,id'],
            'most_option_id' => ['nullable', 'exists:disc_question_options,id'],
            'least_option_id' => ['nullable', 'exists:disc_question_options,id'],
        ]);

        if (!empty($data['most_option_id']) && !empty($data['least_option_id'])) {
            if ($data['most_option_id'] === $data['least_option_id']) {
                return response()->json(['error' => 'Pilihan PALING (Most) dan PALING TIDAK (Least) tidak boleh sama pada butir yang sama.'], 422);
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
            'saved' => true,
            'answer_id' => $answer->id,
            'message' => 'Jawaban berhasil disimpan.',
        ]);
    }

    /**
     * Security Event Logger API
     */
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
            'event_type' => strtoupper($data['event_type']),
            'metadata_json' => $data['metadata'] ?? [],
            'occurred_at' => now(),
        ]);

        // Penalty calculation on security score
        $penalties = [
            'TAB_SWITCH' => 3,
            'WINDOW_BLUR' => 2,
            'FULLSCREEN_EXIT' => 5,
            'COPY_ATTEMPT' => 5,
            'PRINT_ATTEMPT' => 5,
            'DEVTOOLS_SHORTCUT' => 5,
        ];

        $penalty = $penalties[strtoupper($data['event_type'])] ?? 1;
        $newScore = max(0, $session->security_score - $penalty);
        $session->update(['security_score' => $newScore, 'last_activity_at' => now()]);

        return response()->json(['recorded' => true, 'security_score' => $newScore]);
    }

    /**
     * Submit and Seal Completed Assessment
     */
    public function submitSessionApi(string $token, DiscScoringEngine $scoringEngine): JsonResponse
    {
        $session = DiscAssessmentSession::query()
            ->where('session_token', $token)
            ->where('participant_id', auth()->id())
            ->firstOrFail();

        if ($session->isCompleted()) {
            return response()->json([
                'success' => true,
                'redirect_url' => route('disc.assessment.completed', $token),
            ]);
        }

        $answers = $session->answers;
        $validAnswersCount = $answers->filter(fn ($a) => $a->most_option_id && $a->least_option_id && $a->most_option_id !== $a->least_option_id)->count();

        if ($validAnswersCount < 24) {
            return response()->json([
                'error' => "Harap lengkapi semua 24 butir pertanyaan dengan memilih 1 Paling (Most) dan 1 Paling Tidak (Least). (Saat ini terisi: {$validAnswersCount}/24)",
            ], 422);
        }

        // Process Deterministic Scoring Server-Side
        $result = $scoringEngine->processSession($session);

        DB::transaction(function () use ($session, $result) {
            $session->update([
                'status' => 'COMPLETED',
                'completed_at' => now(),
                'raw_scores_json' => $result['raw_scores'],
                'normalized_scores_json' => $result['normalized_scores'],
                'disc_profile_code' => $result['profile_code'],
                'disc_profile_name' => $result['profile_name'],
                'interpretation_json' => $result['interpretation'],
            ]);

            if ($session->assessmentParticipant) {
                $session->assessmentParticipant->update(['status' => 'COMPLETED']);
            }
        });

        AuditLogger::log('SUBMIT_ASSESSMENT', 'DISC', $session, [
            'profile' => $result['profile_code'],
        ]);

        return response()->json([
            'success' => true,
            'redirect_url' => route('disc.assessment.completed', $token),
        ]);
    }

    public function completed(string $token): View
    {
        $session = DiscAssessmentSession::query()
            ->where('session_token', $token)
            ->where('participant_id', auth()->id())
            ->firstOrFail();

        $assessment = $session->assessment;

        return view('disc.completed', compact('session', 'assessment'));
    }
}
