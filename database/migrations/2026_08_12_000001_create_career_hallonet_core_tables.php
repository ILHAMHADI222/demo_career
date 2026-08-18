<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('label');
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('label');
            $table->timestamps();
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->primary(['role_id', 'user_id']);
        });

        Schema::create('permission_role', function (Blueprint $table) {
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->primary(['permission_id', 'role_id']);
        });

        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('divisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('code');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['department_id', 'code']);
        });

        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('division_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('document_types', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->boolean('is_required')->default(false);
            $table->timestamps();
        });

        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('job_code')->unique();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('division_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('position_id')->nullable()->constrained()->nullOnDelete();
            $table->string('location')->index();
            $table->string('employment_type')->index();
            $table->string('work_arrangement')->index();
            $table->unsignedInteger('number_of_openings')->default(1);
            $table->string('minimum_education')->nullable();
            $table->string('minimum_experience')->nullable();
            $table->longText('description');
            $table->longText('responsibilities')->nullable();
            $table->longText('requirements')->nullable();
            $table->json('required_skills')->nullable();
            $table->longText('benefits')->nullable();
            $table->decimal('salary_min', 15, 2)->nullable();
            $table->decimal('salary_max', 15, 2)->nullable();
            $table->boolean('show_salary')->default(false);
            $table->timestamp('registration_start_at')->nullable();
            $table->timestamp('deadline_at')->nullable()->index();
            $table->foreignId('recruiter_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('DRAFT')->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('job_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained()->cascadeOnDelete();
            $table->text('question');
            $table->string('type');
            $table->boolean('required')->default(false);
            $table->json('options')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });

        Schema::create('candidate_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('candidate_number')->unique();
            $table->string('photo')->nullable();
            $table->string('full_name');
            $table->string('nik')->nullable()->index();
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('gender')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('email')->index();
            $table->string('phone')->nullable()->index();
            $table->string('whatsapp')->nullable();
            $table->text('address')->nullable();
            $table->string('province')->nullable();
            $table->string('city')->nullable()->index();
            $table->string('postal_code')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->unsignedTinyInteger('profile_completion')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('candidate_educations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_profile_id')->constrained()->cascadeOnDelete();
            $table->string('education_level');
            $table->string('institution');
            $table->string('major')->nullable();
            $table->year('start_year')->nullable();
            $table->year('graduation_year')->nullable();
            $table->decimal('gpa', 4, 2)->nullable();
            $table->string('status')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('candidate_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_profile_id')->constrained()->cascadeOnDelete();
            $table->string('company_name');
            $table->string('position');
            $table->string('employment_type')->nullable();
            $table->string('location')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('currently_working')->default(false);
            $table->text('job_description')->nullable();
            $table->text('reason_for_leaving')->nullable();
            $table->timestamps();
        });

        Schema::create('candidate_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_profile_id')->constrained()->cascadeOnDelete();
            $table->string('skill');
            $table->string('level');
            $table->unsignedTinyInteger('years_of_experience')->default(0);
            $table->timestamps();
        });

        Schema::create('candidate_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('document_type_id')->constrained()->restrictOnDelete();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('mime_type');
            $table->unsignedBigInteger('file_size');
            $table->string('status')->default('PENDING')->index();
            $table->text('verification_note')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_number')->unique();
            $table->foreignId('job_id')->constrained()->restrictOnDelete();
            $table->foreignId('candidate_profile_id')->constrained()->restrictOnDelete();
            $table->string('status')->default('DRAFT')->index();
            $table->unsignedTinyInteger('current_step')->default(1);
            $table->decimal('score', 5, 2)->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->text('withdraw_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['job_id', 'candidate_profile_id', 'deleted_at'], 'applications_unique_active_candidate_job');
            $table->index(['job_id', 'status']);
            $table->index(['candidate_profile_id', 'status']);
            $table->index('created_at');
        });

        Schema::create('application_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->foreignId('job_question_id')->constrained()->cascadeOnDelete();
            $table->json('answer')->nullable();
            $table->timestamps();
            $table->unique(['application_id', 'job_question_id']);
        });

        Schema::create('application_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->string('from_status')->nullable();
            $table->string('to_status');
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('reason')->nullable();
            $table->text('note')->nullable();
            $table->timestamp('changed_at')->useCurrent();
            $table->timestamps();
            $table->index(['application_id', 'changed_at']);
        });

        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->foreignId('interviewer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('scheduled_date');
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->string('timezone')->default('Asia/Jakarta');
            $table->string('method');
            $table->string('location')->nullable();
            $table->string('meeting_url')->nullable();
            $table->text('instruction')->nullable();
            $table->string('status')->default('SCHEDULED')->index();
            $table->timestamps();
        });

        Schema::create('interview_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interview_id')->constrained()->cascadeOnDelete();
            $table->foreignId('evaluator_id')->constrained('users')->cascadeOnDelete();
            $table->json('criteria_scores');
            $table->text('strengths')->nullable();
            $table->text('weaknesses')->nullable();
            $table->text('note')->nullable();
            $table->string('recommendation');
            $table->timestamps();
        });

        Schema::create('candidate_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->string('test_type');
            $table->string('title');
            $table->text('instruction')->nullable();
            $table->string('test_url')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('deadline_at')->nullable();
            $table->decimal('score', 6, 2)->nullable();
            $table->decimal('maximum_score', 6, 2)->default(100);
            $table->decimal('passing_score', 6, 2)->default(70);
            $table->string('result')->default('PENDING')->index();
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('position');
            $table->string('employment_type');
            $table->decimal('salary', 15, 2)->nullable();
            $table->json('allowances')->nullable();
            $table->json('benefits')->nullable();
            $table->date('start_date')->nullable();
            $table->string('contract_duration')->nullable();
            $table->string('probation_duration')->nullable();
            $table->string('offer_letter')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->string('candidate_response')->nullable();
            $table->timestamp('candidate_response_at')->nullable();
            $table->text('note')->nullable();
            $table->string('status')->default('DRAFT')->index();
            $table->timestamps();
        });

        Schema::create('hr_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->string('type')->default('GENERAL');
            $table->text('note');
            $table->timestamps();
        });

        Schema::create('talent_pools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_profile_id')->constrained()->cascadeOnDelete();
            $table->string('category');
            $table->string('reason')->nullable();
            $table->unsignedTinyInteger('rating')->default(3);
            $table->foreignId('added_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('added_at')->useCurrent();
            $table->timestamp('next_follow_up_at')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('event');
            $table->string('channel')->default('IN_APP');
            $table->string('title');
            $table->text('body');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->string('event')->unique();
            $table->string('subject');
            $table->text('body');
            $table->boolean('email_enabled')->default(true);
            $table->boolean('in_app_enabled')->default(true);
            $table->timestamps();
        });

        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->string('recipient')->index();
            $table->string('subject');
            $table->string('template')->nullable();
            $table->string('status')->default('QUEUED')->index();
            $table->string('provider_message_id')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->text('failure_reason')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action')->index();
            $table->string('module')->index();
            $table->string('entity_type')->nullable();
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->json('old_value')->nullable();
            $table->json('new_value')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            $table->index(['entity_type', 'entity_id']);
        });

        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('group');
            $table->string('key');
            $table->json('value')->nullable();
            $table->boolean('encrypted')->default(false);
            $table->timestamps();
            $table->unique(['group', 'key']);
        });
    }

    public function down(): void
    {
        foreach (array_reverse([
            'roles', 'permissions', 'role_user', 'permission_role', 'departments', 'divisions', 'positions', 'skills', 'document_types', 'jobs', 'job_questions', 'candidate_profiles', 'candidate_educations', 'candidate_experiences', 'candidate_skills', 'candidate_documents', 'applications', 'application_answers', 'application_status_histories', 'interviews', 'interview_evaluations', 'candidate_tests', 'offers', 'hr_notes', 'talent_pools', 'notifications', 'notification_templates', 'email_logs', 'audit_logs', 'system_settings',
        ]) as $table) {
            Schema::dropIfExists($table);
        }
    }
};
