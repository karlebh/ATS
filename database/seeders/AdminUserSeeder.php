<?php

namespace Database\Seeders;

use App\Constants\UserRole;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! DB::table('users')->where('email', 'admin@atsjobmanager.online')->first()) {
            $user =  User::factory()->create([
                'email' => 'admin@atsjobmanager.online',
                'username' => 'Admin',
                'password' => 'password',
                'role' => UserRole::ADMIN,
            ]);
        }
    }
}
