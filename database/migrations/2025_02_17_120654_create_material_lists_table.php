<?php

use App\Models\Router;
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
        Schema::create('material_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Router::class);
            $table->string('uuid');
            $table->string('description');
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->string('vendor_email');
            $table->integer('invoice_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_lists');
    }
};
