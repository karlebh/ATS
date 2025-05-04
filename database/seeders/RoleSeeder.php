<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'admin', 'guard_name' => 'web'],
            ['name' => 'floor_team', 'guard_name' => 'web'],
            ['name' => 'inspection_team', 'guard_name' => 'web'],
        ];


        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name' => $role['name'],
                'guard_name' => $role['guard_name']
            ]);
        }
    }
}
