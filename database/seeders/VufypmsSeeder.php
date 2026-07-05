<?php

namespace Database\Seeders;

use App\Models\ProjectDomain;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class VufypmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@vufypms.test'],
            [
                'name' => 'System Admin',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'supervisor@vufypms.test'],
            [
                'name' => 'Default Supervisor',
                'role' => 'supervisor',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'student@vufypms.test'],
            [
                'name' => 'Default Student',
                'role' => 'student',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        foreach (['Web Development', 'AI', 'Data Science', 'Networking', 'Mobile Applications'] as $domainName) {
            ProjectDomain::updateOrCreate(['name' => $domainName], ['is_active' => true]);
        }

        Semester::updateOrCreate(
            ['name' => 'Spring 2026'],
            [
                'start_date' => '2026-01-20',
                'end_date' => '2026-06-30',
                'proposal_deadline' => '2026-02-20',
                'final_deadline' => '2026-06-10',
                'is_active' => true,
            ]
        );
    }
}
