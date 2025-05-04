<?php

namespace Database\Seeders;

use App\Models\InspectionTraveler;
use App\Models\InspectionTravelerItem;
use App\Models\InspectionTravelerOperation;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InspectionTravelerSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InspectionTraveler::factory(40)->create();
    }
}
