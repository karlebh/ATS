<?php

namespace Database\Seeders;

use App\Models\InspectionTravelerOperation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InspectionTravelerOperationSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InspectionTravelerOperation::factory(40)->create();
    }
}
