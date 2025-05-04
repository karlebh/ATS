<?php

namespace Database\Seeders;

use App\Models\Part;
use App\Models\PurchaseOrder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PurchaseOrderSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PurchaseOrder::factory(40)
            ->has(Part::factory()->count(rand(1, 5)))
            ->create();
    }
}
