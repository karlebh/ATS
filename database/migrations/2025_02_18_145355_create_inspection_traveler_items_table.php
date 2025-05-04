<?php

use App\Models\InspectionTraveler;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inspection_traveler_items', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->foreignIdFor(InspectionTraveler::class);
            $table->string('part_number')->unique();
            $table->integer('quantity');
            $table->text('description');
            $table->string('finish')->nullable();
            $table->string('rev', 10)->nullable();
            $table->string('department')->nullable();
            $table->string('ht_stress');
            $table->timestamp('ship_out')->nullable();
            $table->timestamp('shipped')->nullable();
            $table->string('deburr');
            $table->string('tooling_check');
            $table->string('process_review');
            $table->string('fai_completed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_traveler_items');
    }
};
