<?php

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
        Schema::create('material_trackings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_id')->references('id')->on('jobs');
            $table->string('material_status'); //Available, Ordered, Delayed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_trackings');
    }
};
