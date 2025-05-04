<?php

namespace Database\Seeders;

use App\Constants\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usersExit = User::query()
            ->where('email', 'karleb@gmail.com')
            ->orWhere('email', 'floorteam@gmail.com')
            ->orWhere('email', 'admin.ops@atsjobmanager.online')
            ->orWhere('email', 'floor.ops@atsjobmanager.online')
            ->first();

        if (! $usersExit) {
            User::factory()->create([
                'email' => 'karleb@gmail.com',
                'password' => 'password',
                'role' => UserRole::ADMIN,
            ]);
            User::factory()->create([
                'email' => 'floorteam@gmail.com',
                'password' => 'password',
                'role' => UserRole::FLOOR_TEAM,
            ]);
            User::factory()->create([
                'username' => 'atsadmin',
                'email' => 'admin.ops@atsjobmanager.online',
                'password' => 'Ats-admin-001',
                'role' => UserRole::ADMIN,
            ]);
            User::factory()->create([
                'username' => 'ats-floor-member',
                'email' => 'floor.ops@atsjobmanager.online',
                'password' => 'Ats-floor-001',
                'role' => UserRole::FLOOR_TEAM,
            ]);
        }

        User::factory(40)->create(['role' => UserRole::FLOOR_TEAM]);
    }
}
