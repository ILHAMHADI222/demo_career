<?php

namespace Tests\Feature;

use App\Models\DiscAssessment;
use App\Models\DiscAssessmentSession;
use App\Models\QuestionBank;
use App\Models\User;
use Database\Seeders\DiscMasterSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiscFullAssessmentFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_disc_assessment_lifecycle_and_deterministic_scoring(): void
    {
        $this->seed(DiscMasterSeeder::class);

        $candidate = User::factory()->create();
        $bank = QuestionBank::query()->where('code', 'DISC-ISP')->firstOrFail();

        $assessment = DiscAssessment::query()->create([
            'title' => 'Asesmen Rekrutmen ISP 2026',
            'code' => 'DISC-RECRUIT-2026',
            'question_bank_id' => $bank->id,
            'question_bank_version' => $bank->version,
            'duration_minutes' => 30,
            'randomize_questions' => false,
            'randomize_options' => false,
            'result_visibility' => 'PARTICIPANT_AFTER_COMPLETION',
            'status' => 'PUBLISHED',
            'created_by' => $candidate->id,
        ]);

        // 1. Instruction & Start Session
        $this->actingAs($candidate)->get(route('disc.assessment.instruction', $assessment))->assertStatus(200);

        $startResponse = $this->actingAs($candidate)->post(route('disc.assessment.start', $assessment));
        $startResponse->assertRedirect();

        $session = DiscAssessmentSession::query()->where('disc_assessment_id', $assessment->id)->where('participant_id', $candidate->id)->firstOrFail();
        $this->assertEquals('IN_PROGRESS', $session->status);
        $this->assertEquals(100, $session->security_score);

        // 2. Answer all 24 questions
        $questions = $bank->activeQuestions()->with('activeOptions')->get();
        $this->assertCount(24, $questions);

        foreach ($questions as $idx => $q) {
            $opts = $q->activeOptions;
            $this->assertCount(4, $opts);

            // Choose Option 0 as Most, Option 1 as Least
            $saveRes = $this->actingAs($candidate)->postJson(route('api.disc.answer', $session->session_token), [
                'question_id' => $q->id,
                'most_option_id' => $opts[0]->id,
                'least_option_id' => $opts[1]->id,
            ]);

            $saveRes->assertStatus(200);
            $saveRes->assertJson(['saved' => true]);
        }

        // 3. Log security event (e.g. Tab switch)
        $secRes = $this->actingAs($candidate)->postJson(route('api.disc.security', $session->session_token), [
            'event_type' => 'TAB_SWITCH',
            'metadata' => ['test' => true],
        ]);
        $secRes->assertStatus(200);
        $secRes->assertJson(['recorded' => true]);

        $session->refresh();
        $this->assertEquals(97, $session->security_score); // 100 - 3

        // 4. Submit Assessment
        $submitRes = $this->actingAs($candidate)->postJson(route('api.disc.submit', $session->session_token));
        $submitRes->assertStatus(200);
        $submitRes->assertJson(['success' => true]);

        // 5. Verify Session Completion and Immutability
        $session->refresh();
        $this->assertTrue($session->isCompleted());
        $this->assertNotNull($session->completed_at);
        $this->assertNotEmpty($session->disc_profile_code);
        $this->assertNotEmpty($session->disc_profile_name);
        $this->assertNotNull($session->raw_scores_json);
        $this->assertNotNull($session->normalized_scores_json);

        // 6. Access Completed Page
        $this->actingAs($candidate)->get(route('disc.assessment.completed', $session->session_token))->assertStatus(200);

        // 7. HRD Result & Export CSV
        $hrdUser = User::factory()->create();
        $this->actingAs($hrdUser)->get(route('admin.disc.results.show', $session))->assertStatus(200);
        $this->actingAs($hrdUser)->get(route('admin.disc.sessions.export-csv', $session))->assertStatus(200);
    }
}
