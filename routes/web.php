<?php

use App\Http\Controllers\AdminApplicationController;
use App\Http\Controllers\AdminDiscAssessmentController;
use App\Http\Controllers\AdminDiscQuestionBankController;
use App\Http\Controllers\AdminDocumentTypeController;
use App\Http\Controllers\AdminJobController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ParticipantDiscAssessmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CareerController::class, 'home'])->name('career.home');
Route::get('/career', [CareerController::class, 'home']);
Route::get('/jobs', [CareerController::class, 'jobs'])->name('jobs.index');
Route::get('/jobs/{job:slug}', [CareerController::class, 'show'])->name('jobs.show');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
    Route::get('/register/otp', [AuthController::class, 'otpForm'])->name('register.otp.form');
    Route::post('/register/otp', [AuthController::class, 'verifyOtp'])->name('register.otp.verify');
    Route::post('/register/otp/resend', [AuthController::class, 'resendOtp'])->name('register.otp.resend');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/candidate/profile', [CandidateController::class, 'profile'])->name('candidate.profile');
    Route::put('/candidate/profile', [CandidateController::class, 'updateProfile'])->name('candidate.profile.update');
    Route::post('/candidate/documents', [CandidateController::class, 'uploadDocument'])->name('candidate.documents.store');
    Route::get('/candidate/documents/{document}/view', [CandidateController::class, 'viewDocument'])->name('candidate.documents.view');
    Route::delete('/candidate/documents/{document}', [CandidateController::class, 'deleteDocument'])->name('candidate.documents.destroy');
    Route::post('/jobs/{job:slug}/apply', [CandidateController::class, 'apply'])->name('jobs.apply');

    // Participant DISC Assessment Flow
    Route::get('/assessment/disc/{assessment}/instruction', [ParticipantDiscAssessmentController::class, 'instruction'])->name('disc.assessment.instruction');
    Route::post('/assessment/disc/{assessment}/start', [ParticipantDiscAssessmentController::class, 'startSession'])->name('disc.assessment.start');
    Route::get('/assessment/disc/take/{token}', [ParticipantDiscAssessmentController::class, 'take'])->name('disc.assessment.take');
    Route::get('/assessment/disc/completed/{token}', [ParticipantDiscAssessmentController::class, 'completed'])->name('disc.assessment.completed');

    // Secure Participant API Endpoints
    Route::prefix('api/disc/session/{token}')->name('api.disc.')->group(function (): void {
        Route::get('/question', [ParticipantDiscAssessmentController::class, 'getQuestionApi'])->name('question');
        Route::post('/answer', [ParticipantDiscAssessmentController::class, 'saveAnswerApi'])->name('answer');
        Route::post('/security-event', [ParticipantDiscAssessmentController::class, 'logSecurityEventApi'])->name('security');
        Route::post('/submit', [ParticipantDiscAssessmentController::class, 'submitSessionApi'])->name('submit');
    });

    // Admin & HRD DISC Modules
    Route::prefix('admin')->name('admin.')->group(function (): void {
        Route::middleware('permission:view_applications')->group(function (): void {
            Route::get('/applications', [AdminApplicationController::class, 'index'])->name('applications.index');
            Route::get('/applications/export/excel', [AdminApplicationController::class, 'exportExcel'])->name('applications.export.excel');
            Route::get('/applications/{application}', [AdminApplicationController::class, 'show'])->name('applications.show');
        });

        Route::middleware('permission:change_application_status')->put('/applications/{application}/status', [AdminApplicationController::class, 'updateStatus'])->name('applications.status');
        Route::middleware('permission:manage_applications')->post('/applications/{application}/notes', [AdminApplicationController::class, 'storeNote'])->name('applications.notes.store');
        Route::middleware('permission:manage_interviews')->post('/applications/{application}/interviews', [AdminApplicationController::class, 'storeInterview'])->name('applications.interviews.store');
        Route::middleware('permission:manage_tests')->post('/applications/{application}/tests', [AdminApplicationController::class, 'storeTest'])->name('applications.tests.store');
        Route::middleware('permission:manage_offers')->post('/applications/{application}/offers', [AdminApplicationController::class, 'storeOffer'])->name('applications.offers.store');

        Route::middleware('permission:view_jobs')->get('/jobs', [AdminJobController::class, 'index'])->name('jobs.index');
        Route::middleware('permission:create_jobs')->get('/jobs/create', [AdminJobController::class, 'create'])->name('jobs.create');
        Route::middleware('permission:create_jobs')->post('/jobs', [AdminJobController::class, 'store'])->name('jobs.store');
        Route::middleware('permission:update_jobs')->get('/jobs/{job}/edit', [AdminJobController::class, 'edit'])->name('jobs.edit');
        Route::middleware('permission:update_jobs')->put('/jobs/{job}', [AdminJobController::class, 'update'])->name('jobs.update');
        Route::middleware('permission:publish_jobs')->post('/jobs/{job}/publish', [AdminJobController::class, 'publish'])->name('jobs.publish');
        Route::middleware('permission:close_jobs')->post('/jobs/{job}/close', [AdminJobController::class, 'close'])->name('jobs.close');
        Route::middleware('permission:update_jobs')->delete('/jobs/{job}', [AdminJobController::class, 'destroy'])->name('jobs.destroy');

        Route::middleware('permission:manage_master_data')->get('/document-types', [AdminDocumentTypeController::class, 'index'])->name('document-types.index');
        Route::middleware('permission:manage_master_data')->post('/document-types', [AdminDocumentTypeController::class, 'store'])->name('document-types.store');
        Route::middleware('permission:manage_master_data')->put('/document-types/{documentType}', [AdminDocumentTypeController::class, 'update'])->name('document-types.update');

        Route::middleware('permission:manage_users')->get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::middleware('permission:manage_users')->post('/users', [AdminUserController::class, 'store'])->name('users.store');
        Route::middleware('permission:manage_users')->put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::middleware('permission:manage_users')->delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        // DISC Question Bank Management
        Route::prefix('disc')->name('disc.')->group(function (): void {
            Route::resource('banks', AdminDiscQuestionBankController::class);
            Route::post('banks/{bank}/questions', [AdminDiscQuestionBankController::class, 'storeQuestion'])->name('banks.questions.store');
            Route::delete('banks/{bank}/questions/{question}', [AdminDiscQuestionBankController::class, 'deleteQuestion'])->name('banks.questions.destroy');
            Route::post('banks/{bank}/import-standard', [AdminDiscQuestionBankController::class, 'importStandardQuestions'])->name('banks.import-standard');
            Route::get('banks/{bank}/scoring-keys', [AdminDiscQuestionBankController::class, 'scoringKeys'])->name('banks.scoring-keys');
            Route::put('banks/{bank}/scoring-keys', [AdminDiscQuestionBankController::class, 'updateScoringKeys'])->name('banks.scoring-keys.update');
            Route::post('banks/{bank}/publish', [AdminDiscQuestionBankController::class, 'publish'])->name('banks.publish');
            Route::post('banks/{bank}/duplicate', [AdminDiscQuestionBankController::class, 'duplicate'])->name('banks.duplicate');

            // DISC Assessment Management
            Route::resource('assessments', AdminDiscAssessmentController::class);
            Route::post('assessments/{assessment}/publish', [AdminDiscAssessmentController::class, 'publish'])->name('assessments.publish');
            Route::post('assessments/{assessment}/close', [AdminDiscAssessmentController::class, 'close'])->name('assessments.close');
            Route::post('assessments/{assessment}/assign', [AdminDiscAssessmentController::class, 'assignParticipants'])->name('assessments.assign');
            Route::put('assessments/{assessment}/participants/{participant}/attempts', [AdminDiscAssessmentController::class, 'updateParticipantAttempts'])->name('assessments.participants.attempts');
            Route::get('assessments/{assessment}/monitor', [AdminDiscAssessmentController::class, 'monitor'])->name('assessments.monitor');
            Route::get('results/{session}', [AdminDiscAssessmentController::class, 'resultShow'])->name('results.show');
            Route::get('sessions/{session}/export-csv', [AdminDiscAssessmentController::class, 'exportSessionCsv'])->name('sessions.export-csv');
        });
    });
});
