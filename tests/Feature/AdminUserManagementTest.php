<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\DiscMasterSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_hrd_can_create_internal_user_with_allowed_stages(): void
    {
        $this->seed([DatabaseSeeder::class, DiscMasterSeeder::class]);

        $hrd = User::factory()->create();
        $hrd->assignRole('ADMIN');

        $response = $this->actingAs($hrd)->post(route('admin.users.store'), [
            'name' => 'Internal Candidate Test',
            'email' => 'internal_candidate@test.com',
            'password' => 'Password123!',
            'allowed_stages' => [1, 3], // Hanya Deret dan DISC
        ]);

        $response->assertSessionHas('success');

        $created = User::where('email', 'internal_candidate@test.com')->firstOrFail();
        $this->assertTrue($created->hasRole('USER'));
        $this->assertFalse($created->hasRole('ADMIN'));
        $this->assertFalse($created->hasRole('SUPERADMIN'));

        $this->assertEquals([1, 3], $created->getAllowedStages());
        $this->assertTrue($created->canTakeStage(1));
        $this->assertFalse($created->canTakeStage(2));
        $this->assertTrue($created->canTakeStage(3));
    }

    public function test_hrd_cannot_assign_admin_or_superadmin_role(): void
    {
        $this->seed([DatabaseSeeder::class, DiscMasterSeeder::class]);

        $hrd = User::factory()->create();
        $hrd->assignRole('ADMIN');

        $superadminRole = Role::where('name', 'SUPERADMIN')->firstOrFail();

        $response = $this->actingAs($hrd)->post(route('admin.users.store'), [
            'name' => 'Hacker Test',
            'email' => 'hacker@test.com',
            'password' => 'Password123!',
            'roles' => [$superadminRole->id],
            'allowed_stages' => [1, 2, 3],
        ]);

        $response->assertSessionHas('success');

        $created = User::where('email', 'hacker@test.com')->firstOrFail();
        // Role must stay USER because HRD cannot grant SUPERADMIN
        $this->assertTrue($created->hasRole('USER'));
        $this->assertFalse($created->hasRole('SUPERADMIN'));
    }

    public function test_hrd_can_update_internal_user_allowed_stages(): void
    {
        $this->seed([DatabaseSeeder::class, DiscMasterSeeder::class]);

        $hrd = User::factory()->create();
        $hrd->assignRole('ADMIN');

        $candidate = User::factory()->create(['allowed_stages' => [1, 2, 3]]);
        $candidate->assignRole('USER');

        $response = $this->actingAs($hrd)->put(route('admin.users.update', $candidate), [
            'name' => 'Candidate Updated',
            'email' => $candidate->email,
            'allowed_stages' => [2], // Hanya Tahap 2
        ]);

        $response->assertSessionHas('success');

        $candidate->refresh();
        $this->assertEquals('Candidate Updated', $candidate->name);
        $this->assertEquals([2], $candidate->getAllowedStages());
        $this->assertFalse($candidate->canTakeStage(1));
        $this->assertTrue($candidate->canTakeStage(2));
        $this->assertFalse($candidate->canTakeStage(3));
    }

    public function test_hrd_cannot_delete_superadmin_or_other_hrd(): void
    {
        $this->seed([DatabaseSeeder::class, DiscMasterSeeder::class]);

        $hrd = User::factory()->create();
        $hrd->assignRole('ADMIN');

        $superadmin = User::where('email', 'superadmin@hallonet.test')->firstOrFail();

        $response = $this->actingAs($hrd)->delete(route('admin.users.destroy', $superadmin));
        $response->assertStatus(403);
    }
}
