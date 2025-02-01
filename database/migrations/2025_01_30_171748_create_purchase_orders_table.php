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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('po_number');
            $table->unsignedBigInteger('client_id')->references('id')->on('users');
            $table->decimal('budget', 15, 2);
            $table->string('progress')->default('null');
            $table->string('status')->default('null');
            $table->string('current_team')->default('null');
            $table->string('timeline')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
