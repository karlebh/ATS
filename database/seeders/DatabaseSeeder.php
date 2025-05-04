<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\MaterialInventory;
use App\Models\MaterialList;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            ClientSeeder::class,
            ItemSeeder::class,
            RoleSeeder::class,

            RouterSeeder::class,
            PurchaseOrderSeeder::class,
            MaterialListSeeder::class,
            CommentSeeder::class,

            MaterialInventorySeeder::class,
            MaterialListSeeder::class,

            InspectionTravelerSeeder::class,
            InspectionTravelerItemSeeder::class,
            InspectionTravelerOperationSeeder::class,

            TaskSeeder::class,
            DepartmentSeeder::class,
        ]);
    }
}
