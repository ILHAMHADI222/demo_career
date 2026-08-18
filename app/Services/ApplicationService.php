<?php

namespace App\Services;

use App\Models\Application;
use App\Models\CandidateProfile;
use App\Models\DocumentType;
use App\Models\Job;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ApplicationService
{
    public function __construct(private NumberGenerator $numbers) {}

    public function draft(Job $job, CandidateProfile $candidate): Application
    {
        if (! $job->isOpen()) {
            throw new RuntimeException('Lowongan tidak aktif atau deadline sudah lewat.');
        }

        $existing = Application::query()
            ->where('job_id', $job->id)
            ->where('candidate_profile_id', $candidate->id)
            ->whereNotIn('status', ['REJECTED', 'WITHDRAWN', 'CANCELLED'])
            ->first();

        if ($existing) {
            return $existing;
        }

        return DB::transaction(fn () => Application::query()->create([
            'application_number' => $this->numbers->applicationNumber(),
            'job_id' => $job->id,
            'candidate_profile_id' => $candidate->id,
            'status' => 'DRAFT',
            'current_step' => 1,
        ]));
    }

    public function submit(Application $application): Application
    {
        return DB::transaction(function () use ($application) {
            $application->loadMissing(['job.questions', 'candidateProfile.documents.documentType']);

            if (! $application->job->isOpen()) {
                throw new RuntimeException('Lowongan tidak aktif atau deadline sudah lewat.');
            }

            if ($application->candidateProfile->profile_completion < 100) {
                throw new RuntimeException('Data diri kandidat belum lengkap. Silakan lengkapi seluruh data diri wajib terlebih dahulu.');
            }

            // Step dokumen dinonaktifkan sementara.
            // Aktifkan kembali blok ini jika upload dokumen wajib ingin dipakai lagi.
            // $requiredDocumentTypeIds = DocumentType::query()->where('is_required', true)->pluck('id');
            // $uploadedRequiredDocumentTypeIds = $application->candidateProfile->documents
            //     ->whereIn('status', ['PENDING', 'VERIFIED'])
            //     ->pluck('document_type_id')
            //     ->unique();
            //
            // if ($requiredDocumentTypeIds->diff($uploadedRequiredDocumentTypeIds)->isNotEmpty()) {
            //     throw new RuntimeException('Berkas wajib belum lengkap. Silakan upload semua berkas yang ditandai wajib.');
            // }

            $requiredQuestions = $application->job->questions->where('required', true)->pluck('id');
            $answeredQuestions = $application->answers()->whereIn('job_question_id', $requiredQuestions)->count();
            if ($answeredQuestions < $requiredQuestions->count()) {
                throw new RuntimeException('Pertanyaan wajib belum dijawab.');
            }

            $application->forceFill(['submitted_at' => now()])->save();
            $application->transitionTo('SUBMITTED', auth()->user(), 'SUBMIT_APPLICATION', 'Lamaran dikirim kandidat.');

            return $application->refresh();
        });
    }
}
