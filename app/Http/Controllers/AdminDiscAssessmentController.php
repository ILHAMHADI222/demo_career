<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\DiscAssessment;
use App\Models\DiscAssessmentParticipant;
use App\Models\DiscAssessmentSession;
use App\Models\PsikotesStageQuestion;
use App\Models\QuestionBank;
use App\Models\User;
use App\Services\AuditLogger;
use App\Services\DiscReportExporter;
use App\Services\DiscScoringEngine;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminDiscAssessmentController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();
        $keyword = $request->string('keyword')->toString();

        $assessments = DiscAssessment::query()
            ->with(['questionBank', 'creator'])
            ->withCount(['participants', 'sessions'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($keyword, fn ($q) => $q->where(fn ($sub) => $sub->where('title', 'like', "%{$keyword}%")->orWhere('code', 'like', "%{$keyword}%")))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => DiscAssessment::count(),
            'published' => DiscAssessment::where('status', 'PUBLISHED')->count(),
            'draft' => DiscAssessment::where('status', 'DRAFT')->count(),
            'closed' => DiscAssessment::where('status', 'CLOSED')->count(),
            'total_participants' => DiscAssessmentParticipant::count(),
            'completed_sessions' => DiscAssessmentSession::where('status', 'COMPLETED')->count(),
        ];

        $questionBanks = QuestionBank::query()
            ->where('status', 'PUBLISHED')
            ->orderBy('name')
            ->get();

        return view('admin.disc.assessments.index', compact('assessments', 'questionBanks', 'stats', 'status', 'keyword'));
    }

    public function create(): View
    {
        $questionBanks = QuestionBank::query()
            ->where('status', 'PUBLISHED')
            ->orderBy('name')
            ->get();

        $applications = Application::query()
            ->with(['candidateProfile', 'job'])
            ->whereIn('status', ['DOCUMENT_REVIEW', 'ADMINISTRATIVE_REVIEW', 'HR_INTERVIEW', 'USER_INTERVIEW', 'TECHNICAL_TEST'])
            ->latest()
            ->get();

        return view('admin.disc.assessments.create', compact('questionBanks', 'applications'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:disc_assessments,code'],
            'question_bank_id' => ['required', 'exists:question_banks,id'],
            'duration_stage_1' => ['required', 'integer', 'min:1', 'max:120'], // Deret Hitung
            'duration_stage_2' => ['required', 'integer', 'min:1', 'max:120'], // Analogi Verbal
            'duration_stage_3' => ['nullable', 'integer'], // Untimed / Kepribadian
            'start_at' => ['nullable', 'date'],
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
            'randomize_questions' => ['nullable', 'boolean'],
            'randomize_options' => ['nullable', 'boolean'],
            'result_visibility' => ['required', 'in:HRD_ONLY,PARTICIPANT_AFTER_COMPLETION,PARTICIPANT_AFTER_HRD_APPROVAL'],
            'status' => ['required', 'in:DRAFT,PUBLISHED,CLOSED'],
            'candidate_user_ids' => ['nullable', 'array'],
            'candidate_user_ids.*' => ['exists:users,id'],
        ]);

        $bank = QuestionBank::findOrFail($data['question_bank_id']);
        $totalDuration = ($data['duration_stage_1'] ?? 10) + ($data['duration_stage_2'] ?? 10) + ($data['duration_stage_3'] ?? 0);

        $assessment = DB::transaction(function () use ($data, $bank, $totalDuration) {
            $created = DiscAssessment::create([
                'title' => $data['title'],
                'code' => $data['code'],
                'question_bank_id' => $bank->id,
                'question_bank_version' => $bank->version,
                'duration_minutes' => $totalDuration > 0 ? $totalDuration : 30,
                'duration_stage_1' => $data['duration_stage_1'] ?? 10,
                'duration_stage_2' => $data['duration_stage_2'] ?? 10,
                'duration_stage_3' => 0, // Tahap 3 selalu tanpa batas waktu
                'start_at' => $data['start_at'] ?? null,
                'end_at' => $data['end_at'] ?? null,
                'randomize_questions' => !empty($data['randomize_questions']),
                'randomize_options' => !empty($data['randomize_options']),
                'result_visibility' => $data['result_visibility'],
                'status' => $data['status'],
                'created_by' => auth()->id(),
            ]);

            if (!empty($data['candidate_user_ids'])) {
                foreach ($data['candidate_user_ids'] as $userId) {
                    DiscAssessmentParticipant::firstOrCreate(
                        ['disc_assessment_id' => $created->id, 'user_id' => $userId],
                        ['status' => 'ASSIGNED', 'invited_at' => now()]
                    );
                }
            }

            return $created;
        });

        AuditLogger::log('CREATE_ASSESSMENT', 'PSIKOTES', $assessment);

        return redirect()->route('admin.disc.assessments.show', $assessment)->with('success', 'Jadwal Asesmen Psikotes berhasil dibuat.');
    }

    public function show(DiscAssessment $assessment): View
    {
        $assessment->load([
            'questionBank',
            'creator',
            'participants.user.candidateProfile',
            'participants.latestSession',
            'sessions.participant.candidateProfile',
        ]);

        $applications = Application::query()
            ->with(['candidateProfile', 'job'])
            ->whereNotIn('id', $assessment->participants->pluck('application_id')->filter())
            ->latest()
            ->get();

        $candidates = User::query()
            ->whereHas('roles', fn ($q) => $q->where('name', 'USER'))
            ->whereNotIn('id', $assessment->participants->pluck('user_id'))
            ->orderBy('name')
            ->get();

        return view('admin.disc.assessments.show', compact('assessment', 'applications', 'candidates'));
    }

    public function edit(DiscAssessment $assessment): View
    {
        $questionBanks = QuestionBank::query()->where('status', 'PUBLISHED')->get();

        return view('admin.disc.assessments.edit', compact('assessment', 'questionBanks'));
    }

    public function update(Request $request, DiscAssessment $assessment): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:disc_assessments,code,' . $assessment->id],
            'question_bank_id' => ['nullable', 'exists:question_banks,id'],
            'duration_stage_1' => ['required', 'integer', 'min:1', 'max:120'],
            'duration_stage_2' => ['required', 'integer', 'min:1', 'max:120'],
            'duration_stage_3' => ['nullable', 'integer'],
            'start_at' => ['nullable', 'date'],
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
            'randomize_questions' => ['nullable', 'boolean'],
            'randomize_options' => ['nullable', 'boolean'],
            'result_visibility' => ['required', 'in:HRD_ONLY,PARTICIPANT_AFTER_COMPLETION,PARTICIPANT_AFTER_HRD_APPROVAL'],
            'status' => ['required', 'in:DRAFT,PUBLISHED,CLOSED,ARCHIVED'],
        ]);

        $totalDuration = ($data['duration_stage_1'] ?? 10) + ($data['duration_stage_2'] ?? 10) + ($data['duration_stage_3'] ?? 0);

        $payload = [
            'title' => $data['title'],
            'code' => $data['code'],
            'duration_minutes' => $totalDuration > 0 ? $totalDuration : 30,
            'duration_stage_1' => $data['duration_stage_1'] ?? 10,
            'duration_stage_2' => $data['duration_stage_2'] ?? 10,
            'duration_stage_3' => 0,
            'start_at' => $data['start_at'] ?? null,
            'end_at' => $data['end_at'] ?? null,
            'randomize_questions' => !empty($data['randomize_questions']),
            'randomize_options' => !empty($data['randomize_options']),
            'result_visibility' => $data['result_visibility'],
            'status' => $data['status'],
        ];

        if (!empty($data['question_bank_id'])) {
            $bank = QuestionBank::find($data['question_bank_id']);
            if ($bank) {
                $payload['question_bank_id'] = $bank->id;
                $payload['question_bank_version'] = $bank->version;
            }
        }

        $assessment->update($payload);

        AuditLogger::log('UPDATE_ASSESSMENT', 'PSIKOTES', $assessment);

        return back()->with('success', 'Jadwal Asesmen Psikotes berhasil diperbarui.');
    }

    public function assignParticipants(Request $request, DiscAssessment $assessment): RedirectResponse
    {
        $data = $request->validate([
            'user_ids' => ['required', 'array'],
            'user_ids.*' => ['exists:users,id'],
        ]);

        $count = 0;
        foreach ($data['user_ids'] as $userId) {
            $created = DiscAssessmentParticipant::firstOrCreate(
                ['disc_assessment_id' => $assessment->id, 'user_id' => $userId],
                ['status' => 'ASSIGNED', 'invited_at' => now()]
            );
            if ($created->wasRecentlyCreated) {
                $count++;
            }
        }

        AuditLogger::log('ASSIGN_PARTICIPANTS', 'DISC', $assessment, ['count' => $count]);

        return back()->with('success', "{$count} peserta berhasil ditambahkan ke asesmen ini.");
    }

    public function monitor(DiscAssessment $assessment): View
    {
        $assessment->load([
            'participants.user.candidateProfile',
            'participants.latestSession.securityEvents',
        ]);

        $activeSessions = DiscAssessmentSession::query()
            ->where('disc_assessment_id', $assessment->id)
            ->with(['participant.candidateProfile', 'securityEvents'])
            ->latest('last_activity_at')
            ->get();

        return view('admin.disc.assessments.monitor', compact('assessment', 'activeSessions'));
    }

    public function resultShow(DiscAssessmentSession $session): View
    {
        $session->load([
            'assessment.questionBank.deretQuestions',
            'assessment.questionBank.analogiQuestions',
            'participant.candidateProfile',
            'answers.question.options',
            'answers.mostOption',
            'answers.leastOption',
            'securityEvents',
        ]);

        $scoringEngine = app(\App\Services\DiscScoringEngine::class);

        // If candidate answered stage 3 questions but session is not completed yet, calculate live score snapshot
        if ($session->answers->isNotEmpty() && (empty($session->disc_profile_code) || empty($session->raw_scores_json))) {
            try {
                $scoringResult = $scoringEngine->calculateSessionScore($session);
                $session->update([
                    'raw_scores_json' => $scoringResult['raw_scores'],
                    'normalized_scores_json' => $scoringResult['normalized_scores'],
                    'disc_profile_code' => $scoringResult['profile_code'] ?? ($scoringResult['profile']['code'] ?? null),
                    'disc_profile_name' => $scoringResult['profile_name'] ?? ($scoringResult['profile']['name'] ?? null),
                    'interpretation_json' => $scoringResult['interpretation'] ?? [],
                ]);
                $session->refresh();
            } catch (\Throwable $e) {
                // Keep existing values
            }
        }

        $bank = $session->assessment->questionBank;
        $deretQuestions = $bank && $bank->deretQuestions->isNotEmpty()
            ? $bank->deretQuestions
            : PsikotesStageQuestion::where('stage', 'STAGE_1_DERET')->where('active', true)->orderBy('question_number')->get();

        $analogiQuestions = $bank && $bank->analogiQuestions->isNotEmpty()
            ? $bank->analogiQuestions
            : PsikotesStageQuestion::where('stage', 'STAGE_2_ANALOGI')->where('active', true)->orderBy('question_number')->get();

        return view('admin.disc.results.show', compact('session', 'deretQuestions', 'analogiQuestions'));
    }

    public function publish(DiscAssessment $assessment): RedirectResponse
    {
        $old = ['status' => $assessment->status];
        $assessment->update(['status' => 'PUBLISHED']);
        AuditLogger::log('PUBLISH_ASSESSMENT', 'DISC', $assessment, $old, ['status' => 'PUBLISHED']);

        return back()->with('success', "Asesmen '{$assessment->title}' berhasil dibuka (Status: Aktif / Published). Kandidat dapat mulai mengerjakan.");
    }

    public function close(DiscAssessment $assessment): RedirectResponse
    {
        $old = ['status' => $assessment->status];
        $assessment->update(['status' => 'CLOSED']);
        AuditLogger::log('CLOSE_ASSESSMENT', 'DISC', $assessment, $old, ['status' => 'CLOSED']);

        return back()->with('success', "Asesmen '{$assessment->title}' berhasil ditutup (Status: Closed). Peserta baru tidak dapat mengerjakan asesmen ini.");
    }

    public function updateParticipantAttempts(Request $request, DiscAssessment $assessment, DiscAssessmentParticipant $participant): RedirectResponse
    {
        $data = $request->validate([
            'max_attempts' => ['required', 'integer', 'min:1', 'max:50'],
        ]);

        $oldAttempts = $participant->max_attempts ?: 1;
        $participant->update([
            'max_attempts' => $data['max_attempts'],
            'status' => 'ASSIGNED',
        ]);

        AuditLogger::log('UPDATE_PARTICIPANT_ATTEMPTS', 'DISC', $assessment, ['old_max_attempts' => $oldAttempts], ['new_max_attempts' => $data['max_attempts']]);

        return back()->with('success', "Batas kesempatan tes untuk peserta '{$participant->user->name}' berhasil diubah menjadi {$data['max_attempts']} kali.");
    }

    public function exportSessionCsv(DiscAssessmentSession $session, DiscReportExporter $exporter): StreamedResponse
    {
        return $exporter->exportSessionCsv($session);
    }

    public function destroy(DiscAssessment $assessment): RedirectResponse
    {
        if ($assessment->sessions()->exists()) {
            return back()->withErrors([
                'error' => "Asesmen '{$assessment->title}' tidak dapat dihapus karena sudah memiliki {$assessment->sessions()->count()} sesi pengerjaan oleh kandidat. Anda dapat mengubah statusnya menjadi 'CLOSED' untuk menghentikan pengerjaan."
            ]);
        }

        DB::transaction(function () use ($assessment) {
            $assessment->participants()->delete();
            $assessment->delete();
        });

        AuditLogger::log('DELETE_ASSESSMENT', 'PSIKOTES', $assessment);

        return redirect()->route('admin.disc.assessments.index')->with('success', "Asesmen '{$assessment->title}' berhasil dihapus.");
    }
}
