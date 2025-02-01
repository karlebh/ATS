<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Item;
use App\Models\Job;
use App\Models\PurchaseOrder;
use App\Models\User;
use App\Models\Vendor;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ClientSeeder::class,
            JobSeeder::class,
            PurchaseOrderSeeder::class,
            VendorSeeder::class,
            ItemSeeder::class,
        ]);
    }
}
