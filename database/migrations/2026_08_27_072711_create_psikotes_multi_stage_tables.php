<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambahkan kolom durasi sub-tes pada disc_assessments jika belum ada
        Schema::table('disc_assessments', function (Blueprint $table) {
            $table->unsignedInteger('duration_stage_1')->default(10)->after('duration_minutes'); // Deret Hitung (Menit)
            $table->unsignedInteger('duration_stage_2')->default(10)->after('duration_stage_1'); // Analogi Verbal (Menit)
            $table->unsignedInteger('duration_stage_3')->default(0)->after('duration_stage_2');  // Kepribadian (0 = Tanpa Batas Waktu)
        });

        // 2. Buat tabel soal psikotes tahap 1 (Deret Hitung) & tahap 2 (Analogi Verbal)
        Schema::create('psikotes_stage_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_bank_id')->constrained('question_banks')->cascadeOnDelete();
            $table->string('stage', 40)->index(); // STAGE_1_DERET, STAGE_2_ANALOGI
            $table->unsignedSmallInteger('question_number')->index();
            $table->text('question_text');
            $table->json('options_json')->nullable(); // Untuk pilihan ganda Tahap 2: {"A": "...", "B": "...", "C": "...", "D": "..."}
            $table->string('correct_answer_1')->nullable(); // Kunci jawaban 1 (atau opsi benar Tahap 2 e.g. "A")
            $table->string('correct_answer_2')->nullable(); // Kunci jawaban 2 (untuk deret angka ke-2)
            $table->text('explanation')->nullable();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['question_bank_id', 'stage', 'question_number'], 'psikotes_q_bank_stage_idx');
        });

        // 3. Tambahkan kolom tracking tahap & skor sub-tes pada disc_assessment_sessions
        Schema::table('disc_assessment_sessions', function (Blueprint $table) {
            $table->unsignedTinyInteger('current_stage')->default(1)->after('status'); // 1, 2, 3
            $table->json('stage_1_answers_json')->nullable()->after('option_order_json');
            $table->json('stage_2_answers_json')->nullable()->after('stage_1_answers_json');
            $table->decimal('stage_1_score', 5, 2)->nullable()->after('scoring_snapshot_json');
            $table->decimal('stage_2_score', 5, 2)->nullable()->after('stage_1_score');
            $table->unsignedSmallInteger('stage_1_correct_count')->default(0)->after('stage_2_score');
            $table->unsignedSmallInteger('stage_2_correct_count')->default(0)->after('stage_1_correct_count');
            $table->timestamp('stage_1_started_at')->nullable()->after('started_at');
            $table->timestamp('stage_1_completed_at')->nullable()->after('stage_1_started_at');
            $table->timestamp('stage_2_started_at')->nullable()->after('stage_1_completed_at');
            $table->timestamp('stage_2_completed_at')->nullable()->after('stage_2_started_at');
            $table->timestamp('stage_3_started_at')->nullable()->after('stage_2_completed_at');
            $table->timestamp('stage_3_completed_at')->nullable()->after('stage_3_started_at');
        });
    }

    public function down(): void
    {
        Schema::table('disc_assessment_sessions', function (Blueprint $table) {
            $table->dropColumn([
                'current_stage',
                'stage_1_answers_json',
                'stage_2_answers_json',
                'stage_1_score',
                'stage_2_score',
                'stage_1_correct_count',
                'stage_2_correct_count',
                'stage_1_started_at',
                'stage_1_completed_at',
                'stage_2_started_at',
                'stage_2_completed_at',
                'stage_3_started_at',
                'stage_3_completed_at',
            ]);
        });

        Schema::dropIfExists('psikotes_stage_questions');

        Schema::table('disc_assessments', function (Blueprint $table) {
            $table->dropColumn(['duration_stage_1', 'duration_stage_2', 'duration_stage_3']);
        });
    }
};
