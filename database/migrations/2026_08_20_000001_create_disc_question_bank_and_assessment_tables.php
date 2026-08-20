<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('disc_profiles');
        Schema::dropIfExists('disc_normalization_tables');
        Schema::dropIfExists('disc_assessment_security_events');
        Schema::dropIfExists('disc_assessment_answers');
        Schema::dropIfExists('disc_assessment_sessions');
        Schema::dropIfExists('disc_assessment_participants');
        Schema::dropIfExists('disc_assessments');
        Schema::dropIfExists('disc_question_options');
        Schema::dropIfExists('disc_questions');
        Schema::dropIfExists('question_banks');

        Schema::create('question_banks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('assessment_type')->default('DISC');
            $table->string('version')->default('1.0');
            $table->string('status')->default('DRAFT')->index(); // DRAFT, REVIEW, PUBLISHED, ARCHIVED
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('disc_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_bank_id')->constrained('question_banks')->cascadeOnDelete();
            $table->unsignedSmallInteger('question_number')->index();
            $table->text('instruction')->nullable();
            $table->boolean('active')->default(true)->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['question_bank_id', 'question_number', 'deleted_at'], 'disc_q_bank_num_unique');
        });

        Schema::create('disc_question_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disc_question_id')->constrained('disc_questions', indexName: 'disc_opt_q_id_fk')->cascadeOnDelete();
            $table->unsignedTinyInteger('option_number');
            $table->string('option_label', 10); // A, B, C, D
            $table->text('statement');
            $table->string('most_mapping', 5); // D, I, S, C, *
            $table->string('least_mapping', 5); // D, I, S, C, *
            $table->unsignedTinyInteger('display_order')->default(1);
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->index(['disc_question_id', 'option_number'], 'disc_opt_q_num_idx');
        });

        Schema::create('disc_assessments', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('code')->unique();
            $table->foreignId('question_bank_id')->constrained('question_banks')->restrictOnDelete();
            $table->string('question_bank_version')->default('1.0');
            $table->unsignedInteger('duration_minutes')->default(30);
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->boolean('randomize_questions')->default(false);
            $table->boolean('randomize_options')->default(false);
            $table->string('result_visibility')->default('HRD_ONLY'); // HRD_ONLY, PARTICIPANT_AFTER_COMPLETION, PARTICIPANT_AFTER_HRD_APPROVAL
            $table->string('status')->default('DRAFT')->index(); // DRAFT, PUBLISHED, CLOSED, ARCHIVED
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('disc_assessment_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disc_assessment_id')->constrained('disc_assessments', indexName: 'disc_part_assess_fk')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('application_id')->nullable()->constrained('applications')->nullOnDelete();
            $table->unsignedTinyInteger('max_attempts')->default(1);
            $table->string('status')->default('ASSIGNED')->index(); // ASSIGNED, IN_PROGRESS, COMPLETED, EXPIRED
            $table->timestamp('invited_at')->useCurrent();
            $table->timestamps();
            $table->unique(['disc_assessment_id', 'user_id'], 'disc_part_user_uniq');
        });

        Schema::create('disc_assessment_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disc_assessment_id')->constrained('disc_assessments', indexName: 'disc_sess_assess_fk')->cascadeOnDelete();
            $table->foreignId('disc_assessment_participant_id')->nullable()->constrained('disc_assessment_participants', indexName: 'disc_sess_part_fk')->cascadeOnDelete();
            $table->foreignId('participant_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('attempt_number')->default(1);
            $table->string('session_token', 80)->unique();
            $table->string('status')->default('INITIALIZED')->index(); // INITIALIZED, IN_PROGRESS, COMPLETED, EXPIRED
            $table->unsignedSmallInteger('current_question_index')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('question_order_json')->nullable();
            $table->json('option_order_json')->nullable();
            $table->json('question_snapshot_json')->nullable();
            $table->json('scoring_snapshot_json')->nullable();
            $table->json('raw_scores_json')->nullable();
            $table->json('normalized_scores_json')->nullable();
            $table->string('disc_profile_code', 20)->nullable()->index();
            $table->string('disc_profile_name')->nullable();
            $table->json('interpretation_json')->nullable();
            $table->unsignedInteger('security_score')->default(100);
            $table->timestamps();
            $table->index(['disc_assessment_id', 'status'], 'disc_sess_stat_idx');
        });

        Schema::create('disc_assessment_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disc_assessment_session_id')->constrained('disc_assessment_sessions', indexName: 'disc_ans_sess_fk')->cascadeOnDelete();
            $table->foreignId('disc_question_id')->constrained('disc_questions', indexName: 'disc_ans_q_fk')->cascadeOnDelete();
            $table->foreignId('most_option_id')->nullable()->constrained('disc_question_options', indexName: 'disc_ans_most_opt_fk')->nullOnDelete();
            $table->foreignId('least_option_id')->nullable()->constrained('disc_question_options', indexName: 'disc_ans_least_opt_fk')->nullOnDelete();
            $table->timestamp('most_selected_at')->nullable();
            $table->timestamp('least_selected_at')->nullable();
            $table->boolean('is_autosaved')->default(true);
            $table->timestamps();
            $table->unique(['disc_assessment_session_id', 'disc_question_id'], 'disc_sess_q_uniq');
        });

        Schema::create('disc_assessment_security_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disc_assessment_session_id')->constrained('disc_assessment_sessions', indexName: 'disc_sec_sess_fk')->cascadeOnDelete();
            $table->string('event_type', 60)->index(); // TAB_SWITCH, WINDOW_BLUR, FULLSCREEN_EXIT, etc.
            $table->json('metadata_json')->nullable();
            $table->timestamp('occurred_at')->useCurrent();
            $table->timestamps();
            $table->index(['disc_assessment_session_id', 'event_type'], 'disc_sec_type_idx');
        });

        Schema::create('disc_normalization_tables', function (Blueprint $table) {
            $table->id();
            $table->string('code')->index(); // e.g. DISC_STANDARD_V1
            $table->string('version')->default('1.0');
            $table->string('chart_type', 30)->index(); // GRAPH_1_MOST, GRAPH_2_LEAST, GRAPH_3_CHANGE
            $table->integer('raw_score')->index();
            $table->decimal('d_score', 6, 2)->default(0);
            $table->decimal('i_score', 6, 2)->default(0);
            $table->decimal('s_score', 6, 2)->default(0);
            $table->decimal('c_score', 6, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['code', 'version', 'chart_type', 'raw_score'], 'disc_norm_uniq');
        });

        Schema::create('disc_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('profile_code', 30)->unique();
            $table->string('profile_name');
            $table->text('description');
            $table->text('strengths')->nullable();
            $table->text('potential_limitations')->nullable();
            $table->text('communication_style')->nullable();
            $table->text('work_style')->nullable();
            $table->text('development_suggestions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disc_profiles');
        Schema::dropIfExists('disc_normalization_tables');
        Schema::dropIfExists('disc_assessment_security_events');
        Schema::dropIfExists('disc_assessment_answers');
        Schema::dropIfExists('disc_assessment_sessions');
        Schema::dropIfExists('disc_assessment_participants');
        Schema::dropIfExists('disc_assessments');
        Schema::dropIfExists('disc_question_options');
        Schema::dropIfExists('disc_questions');
        Schema::dropIfExists('question_banks');
    }
};
