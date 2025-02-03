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
            $table->string('po_number');
            $table->string('client_name');
            $table->string('client_email');
            $table->string('client_company_name');
            $table->string('job_number');
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->decimal('budget', 15, 2);
            $table->string('progress')->default('null');
            $table->string('status')->default('null');
            $table->string('current_team')->default('inspection_team');
            $table->json('files')->nullable();
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
