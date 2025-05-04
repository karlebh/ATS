<?php

use App\Constants\JobProgress;
use App\Constants\TravelerStatus;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Masterminds\HTML5\Serializer\Traverser;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inspection_travelers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->string('traveler_number')->unique();
            $table->string('shop_name');
            $table->string('shop_email');
            $table->date('start_at');
            $table->date('due_at');
            $table->string('status')->default(TravelerStatus::CREATED);
            $table->date('completed_at')->nullable();
            $table->json('files')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_travelers');
    }
};
