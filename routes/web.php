<?php

use App\Http\Controllers\AdminApplicationController;
use App\Http\Controllers\AdminDocumentTypeController;
use App\Http\Controllers\AdminJobController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\DashboardController;
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
    });
});
