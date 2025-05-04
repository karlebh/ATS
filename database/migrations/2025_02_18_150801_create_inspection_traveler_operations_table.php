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
        Schema::create('inspection_traveler_operations', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->foreignIdFor(InspectionTraveler::class);
            $table->integer('outside_ops')->comment('Stores the operation code, which can be an integer or string identifier.');
            $table->integer('vendor')->comment('Stores the quantity as vendor, implemented based off the UI.');
            $table->dateTime('out_by')->nullable();
            $table->dateTime('back_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_traveler_operations');
    }
};
