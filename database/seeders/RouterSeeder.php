<?php

namespace Database\Seeders;

use App\Models\MaterialList;
use App\Models\PurchaseOrder;
use App\Models\Router;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RouterSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Router::factory(40)
            ->has(PurchaseOrder::factory())
            ->has(MaterialList::factory()->count(rand(1, 5)))
            ->create();
    }
}
