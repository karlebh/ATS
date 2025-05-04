<?php

use App\Models\PurchaseOrder;
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
        Schema::create('parts', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->foreignIdFor(PurchaseOrder::class);
            $table->integer('number')->unique();
            $table->string('name');
            $table->integer('quantity');
            $table->decimal('price', 15, 2);
            $table->string('finish');
            $table->integer('rev');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parts');
    }
};
