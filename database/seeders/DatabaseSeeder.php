<?php

namespace Database\Seeders;

use App\Enums\CareerEnums;
use App\Models\Department;
use App\Models\DocumentType;
use App\Models\Job;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $permissions = collect(CareerEnums::PERMISSIONS)->mapWithKeys(fn (string $permission) => [
            $permission => Permission::query()->firstOrCreate(
                ['name' => $permission],
                ['label' => Str::headline($permission)]
            ),
        ]);

        $superadmin = Role::query()->firstOrCreate(['name' => 'SUPERADMIN'], ['label' => 'Superadmin']);
        $admin = Role::query()->firstOrCreate(['name' => 'ADMIN'], ['label' => 'Admin HRD']);
        $user = Role::query()->firstOrCreate(['name' => 'USER'], ['label' => 'Candidate']);

        $superadmin->permissions()->sync($permissions->pluck('id'));
        $admin->permissions()->sync($permissions->except(['manage_admins', 'manage_roles', 'manage_permissions', 'manage_settings'])->pluck('id'));
        $user->permissions()->sync([]);

        $root = User::query()->firstOrCreate(
            ['email' => 'superadmin@hallonet.test'],
            ['name' => 'Superadmin Hallonet', 'password' => Hash::make('Password123!'), 'email_verified_at' => now()]
        );
        $root->roles()->syncWithoutDetaching([$superadmin->id]);

        $hrd = User::query()->firstOrCreate(
            ['email' => 'hrd@hallonet.test'],
            ['name' => 'HRD Hallonet', 'password' => Hash::make('Password123!'), 'email_verified_at' => now()]
        );
        $hrd->roles()->syncWithoutDetaching([$admin->id]);

        foreach (CareerEnums::DOCUMENT_TYPES as $type) {
            DocumentType::query()->firstOrCreate(['code' => $type], ['name' => Str::headline($type), 'is_required' => in_array($type, ['CV', 'KTP', 'IJAZAH'], true)]);
        }

        $it = Department::query()->firstOrCreate(['code' => 'IT'], ['name' => 'Information Technology']);
        $network = Department::query()->firstOrCreate(['code' => 'NETWORK'], ['name' => 'Network Operation']);

        Job::query()->firstOrCreate(
            ['slug' => 'network-engineer-hallonet'],
            [
                'title' => 'Network Engineer',
                'job_code' => 'JOB-2026-000001',
                'department_id' => $network->id,
                'location' => 'Jakarta',
                'employment_type' => 'FULL_TIME',
                'work_arrangement' => 'ONSITE',
                'number_of_openings' => 3,
                'minimum_education' => 'D3/S1',
                'minimum_experience' => '1 Tahun',
                'description' => 'Bertanggung jawab mengelola, memonitor, dan meningkatkan kualitas jaringan Hallonet.',
                'responsibilities' => 'Monitoring jaringan, troubleshooting, dokumentasi, dan koordinasi incident response.',
                'requirements' => 'Memahami TCP/IP, routing, switching, Mikrotik/Cisco, dan komunikasi teknis yang baik.',
                'required_skills' => ['TCP/IP', 'Mikrotik', 'Routing', 'Switching'],
                'benefits' => 'Asuransi kesehatan, jenjang karier, pelatihan sertifikasi.',
                'show_salary' => false,
                'registration_start_at' => now()->subDay(),
                'deadline_at' => now()->addMonth(),
                'recruiter_id' => $hrd->id,
                'status' => 'PUBLISHED',
            ]
        );

        Job::query()->firstOrCreate(
            ['slug' => 'backend-developer-hallonet'],
            [
                'title' => 'Backend Developer',
                'job_code' => 'JOB-2026-000002',
                'department_id' => $it->id,
                'location' => 'Bandung',
                'employment_type' => 'FULL_TIME',
                'work_arrangement' => 'HYBRID',
                'number_of_openings' => 2,
                'minimum_education' => 'S1',
                'minimum_experience' => '2 Tahun',
                'description' => 'Membangun layanan backend yang aman, scalable, dan mudah dipelihara untuk ekosistem Hallonet.',
                'responsibilities' => 'Mendesain API, optimasi database, testing, code review, dan deployment support.',
                'requirements' => 'Menguasai PHP/Laravel, SQL, REST API, queue, dan praktik secure coding.',
                'required_skills' => ['Laravel', 'MySQL', 'REST API', 'Queue'],
                'benefits' => 'Asuransi kesehatan, perangkat kerja, hybrid work, budget training.',
                'show_salary' => false,
                'registration_start_at' => now()->subDay(),
                'deadline_at' => now()->addMonth(),
                'recruiter_id' => $hrd->id,
                'status' => 'PUBLISHED',
            ]
        );
    }
}
