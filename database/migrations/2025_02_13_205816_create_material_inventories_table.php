<?php

use App\Constants\JobProgress;
use App\Constants\MaterialStatus;
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
        Schema::create('material_inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->string('code')->unique();
            $table->string('title');
            $table->integer('quantity')->default(0);
            $table->longText('description')->nullable();
            $table->timestamp('purchased_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamp('ordered_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_inventories');
    }
};
