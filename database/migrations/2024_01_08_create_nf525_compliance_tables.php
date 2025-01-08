<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table pour les journaux d'audit NF525
        Schema::create('nf525_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('event_type');
            $table->string('document_number')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->string('hash');
            $table->json('data');
            $table->timestamps();
        });

        // Ajout des champs NF525 à la table des ventes
        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'reference')) {
                $table->string('reference')->nullable();
            }
            $table->string('nf525_receipt_number')->after('reference');
            $table->string('nf525_hash');
            $table->timestamp('nf525_timestamp');
        });

        // Table pour les compteurs journaliers
        Schema::create('nf525_daily_counters', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('sales_count');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('total_vat', 10, 2);
            $table->string('hash');
            $table->timestamps();
        });

        // Table pour les archives fiscales
        Schema::create('nf525_fiscal_archives', function (Blueprint $table) {
            $table->id();
            $table->date('archive_date');
            $table->string('file_path');
            $table->string('hash');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nf525_audit_logs');
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['nf525_receipt_number', 'nf525_hash', 'nf525_timestamp']);
        });
        Schema::dropIfExists('nf525_daily_counters');
        Schema::dropIfExists('nf525_fiscal_archives');
    }
}; 