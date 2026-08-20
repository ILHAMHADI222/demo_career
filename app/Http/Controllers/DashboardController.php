<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\CandidateProfile;
use App\Models\Interview;
use App\Models\Job;
use App\Models\Offer;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    public function __invoke(): View|RedirectResponse
    {
        $user = auth()->user();

        if ($user->hasAnyRole(['SUPERADMIN', 'ADMIN'])) {
            $allApplications = Application::query()
                ->with([
                    'job.department',
                    'job.division',
                    'candidateProfile.educations',
                    'candidateProfile.experiences',
                    'candidateProfile.documents.documentType',
                ])
                ->latest()
                ->get();

            return view('dashboard.admin', [
                'activeJobs' => Job::query()->where('status', 'PUBLISHED')->count(),
                'totalApplicants' => CandidateProfile::query()->count(),
                'newApplicants' => Application::query()->whereDate('created_at', today())->count(),
                'documentReview' => Application::query()->where('status', 'DOCUMENT_REVIEW')->count(),
                'interview' => Application::query()->whereIn('status', ['HR_INTERVIEW', 'USER_INTERVIEW', 'FINAL_INTERVIEW'])->count(),
                'test' => Application::query()->where('status', 'TECHNICAL_TEST')->count(),
                'offering' => Application::query()->where('status', 'OFFERING')->count(),
                'hired' => Application::query()->where('status', 'HIRED')->count(),
                'rejected' => Application::query()->where('status', 'REJECTED')->count(),
                'totalAdmin' => User::query()->whereHas('roles', fn ($q) => $q->whereIn('name', ['SUPERADMIN', 'ADMIN']))->count(),
                'recentApplications' => $allApplications->take(12),
                'allApplications' => $allApplications,
                'statusOptions' => [
                    'ADMINISTRATIVE_REVIEW' => 'Lolos Administrasi',
                    'TECHNICAL_TEST' => 'Psikotest / Test',
                    'HR_INTERVIEW' => 'Interview HRD',
                    'FINAL_INTERVIEW' => 'Final Interview',
                ],
            ]);
        }

        $profile = $user->candidateProfile()->with(['applications.job', 'documents.documentType'])->first();

        if ($profile && $profile->profile_completion < 100) {
            return redirect()->route('candidate.profile')->with('success', 'Silakan lengkapi seluruh data diri wajib sebelum masuk dashboard kandidat.');
        }

        $discAssessments = \App\Models\DiscAssessmentParticipant::query()
            ->where('user_id', $user->id)
            ->with(['assessment', 'latestSession'])
            ->latest()
            ->get();

        return view('dashboard.candidate', [
            'profile' => $profile,
            'activeApplications' => $profile?->applications()->whereNotIn('status', ['REJECTED', 'WITHDRAWN', 'CANCELLED', 'HIRED'])->count() ?? 0,
            'applications' => $profile?->applications()->with('job')->latest()->limit(10)->get() ?? collect(),
            'upcomingInterviews' => Interview::query()->whereHas('application', fn ($q) => $q->where('candidate_profile_id', $profile?->id))->whereIn('status', ['SCHEDULED', 'CONFIRMED'])->latest()->limit(3)->get(),
            'offers' => Offer::query()->whereHas('application', fn ($q) => $q->where('candidate_profile_id', $profile?->id))->latest()->limit(3)->get(),
            'discAssessments' => $discAssessments,
        ]);
    }
}
