<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity', 10, 3);
            $table->decimal('minimum_quantity', 10, 3)->default(0);
            $table->string('location')->nullable();
            $table->enum('movement_type', ['in', 'out', 'adjustment', 'loss', 'return']);
            $table->text('reason')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->string('reference')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
}; 