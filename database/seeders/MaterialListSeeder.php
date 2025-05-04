<?php

namespace Database\Seeders;

use App\Models\MaterialList;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MaterialListSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MaterialList::factory(40)->create();
    }
}
