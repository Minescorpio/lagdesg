<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('restrict');
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->enum('payment_method', ['cash', 'card', 'transfer'])->default('cash');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('tax_amount', 10, 2);
            $table->decimal('amount_received', 10, 2)->default(0);
            $table->decimal('change_amount', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'completed', 'voided'])->default('completed');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales');
    }
}; 