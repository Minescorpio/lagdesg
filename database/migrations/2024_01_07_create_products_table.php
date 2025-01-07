<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('barcode')->unique()->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('cost_price', 10, 2);
            $table->boolean('is_weighable')->default(false);
            $table->boolean('has_free_price')->default(false);
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('image_path')->nullable();
            $table->boolean('track_stock')->default(true);
            $table->integer('min_stock_alert')->default(0);
            $table->decimal('vat_rate', 5, 2);
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
}; 