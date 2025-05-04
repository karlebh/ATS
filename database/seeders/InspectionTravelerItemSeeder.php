<?php

namespace Database\Seeders;

use App\Models\InspectionTravelerItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InspectionTravelerItemSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InspectionTravelerItem::factory(40)->create();
    }
}
