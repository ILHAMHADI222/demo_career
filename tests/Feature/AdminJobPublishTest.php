<?php

namespace Tests\Feature;

use App\Models\Job;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\DiscMasterSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminJobPublishTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([DatabaseSeeder::class, DiscMasterSeeder::class]);
    }

    protected function createJob(array $attributes = []): Job
    {
        static $counter = 1;
        $counter++;

        return Job::query()->create(array_merge([
            'title' => 'Test Job ' . $counter,
            'slug' => 'test-job-' . $counter . '-' . uniqid(),
            'job_code' => 'JOB-TEST-' . str_pad($counter, 5, '0', STR_PAD_LEFT),
            'location' => 'Jakarta',
            'employment_type' => 'FULL_TIME',
            'work_arrangement' => 'ONSITE',
            'number_of_openings' => 1,
            'description' => 'Test Job Description',
            'status' => 'DRAFT',
        ], $attributes));
    }

    public function test_hrd_can_publish_job_with_custom_active_days(): void
    {
        $hrd = User::factory()->create();
        $hrd->assignRole('ADMIN');

        $job = $this->createJob([
            'status' => 'CLOSED',
            'deadline_at' => now()->subDays(5),
            'registration_start_at' => now()->subDays(15),
        ]);

        $this->assertFalse($job->isOpen());

        $response = $this->actingAs($hrd)->post(route('admin.jobs.publish', $job), [
            'active_days' => 14,
        ]);

        $response->assertSessionHas('success');

        $job->refresh();
        $this->assertEquals('PUBLISHED', $job->status);
        $this->assertNotNull($job->deadline_at);
        $this->assertTrue($job->deadline_at->isFuture());
        $this->assertTrue($job->isOpen());

        // Calling index page should not revert it to CLOSED
        $indexResponse = $this->actingAs($hrd)->get(route('admin.jobs.index'));
        $indexResponse->assertOk();

        $job->refresh();
        $this->assertEquals('PUBLISHED', $job->status);
        $this->assertTrue($job->isOpen());
    }

    public function test_publishing_expired_job_without_params_defaults_to_30_days(): void
    {
        $hrd = User::factory()->create();
        $hrd->assignRole('ADMIN');

        $job = $this->createJob([
            'status' => 'CLOSED',
            'deadline_at' => now()->subDays(2),
        ]);

        $response = $this->actingAs($hrd)->post(route('admin.jobs.publish', $job));
        $response->assertSessionHas('success');

        $job->refresh();
        $this->assertEquals('PUBLISHED', $job->status);
        $this->assertTrue($job->deadline_at->isFuture());
        $this->assertEquals(now()->addDays(30)->toDateString(), $job->deadline_at->toDateString());
    }

    public function test_hrd_can_publish_job_without_deadline(): void
    {
        $hrd = User::factory()->create();
        $hrd->assignRole('ADMIN');

        $job = $this->createJob([
            'status' => 'DRAFT',
            'deadline_at' => now()->addDays(5),
        ]);

        $response = $this->actingAs($hrd)->post(route('admin.jobs.publish', $job), [
            'active_days' => -1,
        ]);

        $response->assertSessionHas('success');

        $job->refresh();
        $this->assertEquals('PUBLISHED', $job->status);
        $this->assertNull($job->deadline_at);
        $this->assertTrue($job->isOpen());
    }

    public function test_hrd_can_close_job(): void
    {
        $hrd = User::factory()->create();
        $hrd->assignRole('ADMIN');

        $job = $this->createJob([
            'status' => 'PUBLISHED',
            'deadline_at' => now()->addDays(10),
        ]);

        $response = $this->actingAs($hrd)->post(route('admin.jobs.close', $job));
        $response->assertSessionHas('success');

        $job->refresh();
        $this->assertEquals('CLOSED', $job->status);
        $this->assertFalse($job->isOpen());
    }
}
