<?php

use App\Constants\JobProgress;
use App\Constants\UserRole;
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
            $table->unsignedBigInteger('router_id')->nullable()->constrained('routers')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('po_number')->unique();
            $table->string('client_name');
            $table->string('client_email');
            $table->string('client_company_name');
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->decimal('budget', 15, 2)->default(0);
            $table->string('progress')->default('null');
            $table->enum('status', [
                JobProgress::COMPLETED,
                JobProgress::IN_PROGRESS,
                JobProgress::IN_QUEUE,
                JobProgress::SECONDARY_OPS,
                JobProgress::CREATED,
            ])
                ->default(JobProgress::CREATED);
            $table->string('current_team')->default('null');
            $table->json('files')->nullable();
            $table->boolean('archived')->default(false);
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
