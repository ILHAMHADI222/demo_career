<?php

namespace Tests\Feature;

use App\Models\DiscAssessment;
use App\Models\DiscAssessmentSession;
use App\Models\QuestionBank;
use App\Models\User;
use Database\Seeders\DiscMasterSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiscAdminAssessmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_assessment_including_durations(): void
    {
        $this->seed([\Database\Seeders\DatabaseSeeder::class, DiscMasterSeeder::class]);

        $admin = User::factory()->create();
        $admin->assignRole('ADMIN');

        $bank = QuestionBank::query()->firstOrFail();

        $assessment = DiscAssessment::create([
            'title' => 'Asesmen Awal',
            'code' => 'TEST-001',
            'question_bank_id' => $bank->id,
            'question_bank_version' => $bank->version,
            'duration_minutes' => 20,
            'duration_stage_1' => 10,
            'duration_stage_2' => 10,
            'result_visibility' => 'HRD_ONLY',
            'status' => 'DRAFT',
            'created_by' => $admin->id,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.disc.assessments.update', $assessment), [
            'title' => 'Asesmen Diperbarui',
            'code' => 'TEST-001-MOD',
            'question_bank_id' => $bank->id,
            'duration_stage_1' => 15,
            'duration_stage_2' => 20,
            'result_visibility' => 'PARTICIPANT_AFTER_COMPLETION',
            'status' => 'PUBLISHED',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('disc_assessments', [
            'id' => $assessment->id,
            'title' => 'Asesmen Diperbarui',
            'code' => 'TEST-001-MOD',
            'duration_stage_1' => 15,
            'duration_stage_2' => 20,
            'duration_minutes' => 35,
            'status' => 'PUBLISHED',
        ]);
    }

    public function test_admin_can_delete_assessment_without_sessions(): void
    {
        $this->seed([\Database\Seeders\DatabaseSeeder::class, DiscMasterSeeder::class]);

        $admin = User::factory()->create();
        $admin->assignRole('ADMIN');

        $bank = QuestionBank::query()->firstOrFail();

        $assessment = DiscAssessment::create([
            'title' => 'Asesmen Hapus',
            'code' => 'TEST-DEL-001',
            'question_bank_id' => $bank->id,
            'question_bank_version' => $bank->version,
            'duration_minutes' => 20,
            'duration_stage_1' => 10,
            'duration_stage_2' => 10,
            'result_visibility' => 'HRD_ONLY',
            'status' => 'DRAFT',
            'created_by' => $admin->id,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.disc.assessments.destroy', $assessment));

        $response->assertRedirect(route('admin.disc.assessments.index'));
        $response->assertSessionHas('success');
        $this->assertSoftDeleted('disc_assessments', [
            'id' => $assessment->id,
        ]);
    }

    public function test_admin_cannot_delete_assessment_with_active_sessions(): void
    {
        $this->seed([\Database\Seeders\DatabaseSeeder::class, DiscMasterSeeder::class]);

        $admin = User::factory()->create();
        $admin->assignRole('ADMIN');

        $candidate = User::factory()->create();

        $bank = QuestionBank::query()->firstOrFail();

        $assessment = DiscAssessment::create([
            'title' => 'Asesmen Dengan Sesi',
            'code' => 'TEST-SES-001',
            'question_bank_id' => $bank->id,
            'question_bank_version' => $bank->version,
            'duration_minutes' => 20,
            'duration_stage_1' => 10,
            'duration_stage_2' => 10,
            'result_visibility' => 'HRD_ONLY',
            'status' => 'PUBLISHED',
            'created_by' => $admin->id,
        ]);

        DiscAssessmentSession::create([
            'disc_assessment_id' => $assessment->id,
            'participant_id' => $candidate->id,
            'session_token' => 'token-test-12345',
            'status' => 'IN_PROGRESS',
            'started_at' => now(),
            'last_activity_at' => now(),
            'current_question_index' => 1,
            'security_score' => 100,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.disc.assessments.destroy', $assessment));

        $response->assertSessionHasErrors('error');
        $this->assertNotSoftDeleted('disc_assessments', [
            'id' => $assessment->id,
        ]);
    }
}
