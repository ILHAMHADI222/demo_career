<?php

namespace Tests\Feature;

use App\Models\DiscAssessment;
use App\Models\DiscAssessmentSession;
use App\Models\QuestionBank;
use App\Models\User;
use Database\Seeders\DiscMasterSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiscSecurityAndAssessmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_participant_api_never_exposes_scoring_keys(): void
    {
        $this->seed(DiscMasterSeeder::class);

        $user = User::factory()->create();
        $bank = QuestionBank::query()->where('code', 'DISC-ISP')->firstOrFail();

        $assessment = DiscAssessment::query()->create([
            'title' => 'Test Assessment',
            'code' => 'TEST-DISC-01',
            'question_bank_id' => $bank->id,
            'question_bank_version' => $bank->version,
            'duration_minutes' => 30,
            'status' => 'PUBLISHED',
            'created_by' => $user->id,
        ]);

        $response = $this->actingAs($user)->post(route('disc.assessment.start', $assessment));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $session = DiscAssessmentSession::query()->where('disc_assessment_id', $assessment->id)->where('participant_id', $user->id)->latest()->firstOrFail();

        // Call Question API
        $apiResponse = $this->actingAs($user)->getJson(route('api.disc.question', $session->session_token));
        $apiResponse->assertStatus(200);

        $json = $apiResponse->json();
        $this->assertArrayHasKey('options', $json);
        $this->assertCount(4, $json['options']);

        // CRITICAL SECURITY ASSERTION: No most_mapping, least_mapping, or score in client payload
        foreach ($json['options'] as $opt) {
            $this->assertArrayNotHasKey('most_mapping', $opt, 'Security violation: most_mapping exposed to participant');
            $this->assertArrayNotHasKey('least_mapping', $opt, 'Security violation: least_mapping exposed to participant');
            $this->assertArrayNotHasKey('raw_score', $opt);
            $this->assertArrayHasKey('id', $opt);
            $this->assertArrayHasKey('label', $opt);
            $this->assertArrayHasKey('statement', $opt);
        }
    }
}
