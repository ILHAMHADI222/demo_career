<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\CandidateProfile;
use App\Models\Department;
use App\Models\Interview;
use App\Models\Job;
use App\Models\Offer;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        $user = auth()->user();

        if ($user->hasAnyRole(['SUPERADMIN', 'ADMIN'])) {
            $query = Application::query()
                ->with([
                    'job.department',
                    'job.division',
                    'candidateProfile.educations',
                    'candidateProfile.experiences',
                ]);

            if ($request->filled('search')) {
                $search = $request->string('search')->toString();
                $query->where(function ($q) use ($search) {
                    $q->where('application_number', 'like', "%{$search}%")
                        ->orWhereHas('candidateProfile', function ($qp) use ($search) {
                            $qp->where('full_name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%")
                                ->orWhere('whatsapp', 'like', "%{$search}%");
                        })
                        ->orWhereHas('job', function ($qj) use ($search) {
                            $qj->where('title', 'like', "%{$search}%");
                        });
                });
            }

            if ($request->filled('job_id')) {
                $query->where('job_id', $request->input('job_id'));
            }

            if ($request->filled('status')) {
                $query->where('status', $request->input('status'));
            }

            $applications = $query->latest()->paginate(5)->withQueryString();
            $jobsList = Job::query()->orderBy('title')->get();

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
                'applications' => $applications,
                'jobsList' => $jobsList,
                'statusOptions' => [
                    'DOCUMENT_REVIEW' => 'Review Berkas',
                    'ADMINISTRATIVE_REVIEW' => 'Lolos Administrasi',
                    'TECHNICAL_TEST' => 'Psikotest / Test',
                    'HR_INTERVIEW' => 'Interview HRD',
                    'USER_INTERVIEW' => 'Interview User',
                    'FINAL_INTERVIEW' => 'Final Interview',
                    'OFFERING' => 'Offering',
                    'HIRED' => 'Hired (Diterima)',
                    'REJECTED' => 'Rejected (Ditolak)',
                ],
            ]);
        }

        $profile = $user->candidateProfile()->with(['applications.job', 'documents.documentType'])->first();

        if ($profile && $profile->profile_completion < 100) {
            return redirect()->route('candidate.profile')->with('success', 'Silakan lengkapi seluruh data diri wajib sebelum masuk dashboard kandidat.');
        }

        $technicalTestApp = $profile?->applications()->where('status', 'TECHNICAL_TEST')->with('job')->first();
        if ($technicalTestApp) {
            $defaultAssessment = \App\Models\DiscAssessment::query()->where('status', 'PUBLISHED')->latest()->first();
            if ($defaultAssessment) {
                \App\Models\DiscAssessmentParticipant::firstOrCreate(
                    ['disc_assessment_id' => $defaultAssessment->id, 'user_id' => $user->id],
                    ['application_id' => $technicalTestApp->id, 'status' => 'ASSIGNED', 'invited_at' => now(), 'max_attempts' => 1]
                );
            }
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
            'upcomingInterviews' => Interview::query()
                ->whereHas('application', fn ($q) => $q->where('candidate_profile_id', $profile?->id))
                ->whereIn('status', ['SCHEDULED', 'CONFIRMED'])
                ->with(['interviewer', 'application.job'])
                ->orderBy('scheduled_date')
                ->orderBy('start_time')
                ->limit(5)
                ->get(),
            'offers' => Offer::query()
                ->whereHas('application', fn ($q) => $q->where('candidate_profile_id', $profile?->id))
                ->with('application.job')
                ->latest()
                ->limit(5)
                ->get(),
            'discAssessments' => $discAssessments,
            'technicalTestApp' => $technicalTestApp,
        ]);
    }
}
