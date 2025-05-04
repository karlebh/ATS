<?php

namespace Database\Seeders;

use App\Models\MaterialInventory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MaterialInventorySeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MaterialInventory::factory(40)->create(['user_id' => 1,]);
    }
}
