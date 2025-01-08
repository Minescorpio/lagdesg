<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table pour le journal des événements (Grand Livre)
        Schema::create('nf525_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('event_type'); // Type d'événement (vente, annulation, etc.)
            $table->string('document_number')->unique(); // Numéro chronologique unique
            $table->foreignId('user_id')->constrained();
            $table->morphs('auditable'); // Polymorphic relation pour lier différents types d'événements
            $table->json('before_state')->nullable();
            $table->json('after_state')->nullable();
            $table->string('hash'); // Hash de sécurité
            $table->string('previous_hash')->nullable(); // Hash précédent pour chaînage
            $table->timestamp('event_timestamp');
            $table->timestamps();
        });

        // Ajout des champs NF525 à la table des ventes
        Schema::table('sales', function (Blueprint $table) {
            $table->string('nf525_receipt_number')->unique()->after('reference'); // Numéro de ticket unique
            $table->string('nf525_hash')->after('meta_data'); // Hash de sécurité
            $table->timestamp('nf525_timestamp')->after('nf525_hash'); // Horodatage certifié
            $table->boolean('is_training_mode')->default(false); // Mode formation
            $table->boolean('is_copy')->default(false); // Indique si c'est une copie
            $table->integer('copy_number')->nullable(); // Numéro de copie
            $table->string('cancellation_reason')->nullable(); // Raison d'annulation
            $table->string('original_receipt_number')->nullable(); // Numéro du ticket original en cas d'avoir
        });

        // Table pour les compteurs journaliers
        Schema::create('nf525_daily_counters', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('sales_count');
            $table->integer('cancelled_sales_count');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('total_tax', 10, 2);
            $table->decimal('total_cancelled', 10, 2);
            $table->string('daily_hash'); // Hash de clôture journalière
            $table->timestamps();
        });

        // Table pour les archives fiscales
        Schema::create('nf525_fiscal_archives', function (Blueprint $table) {
            $table->id();
            $table->date('archive_date');
            $table->string('archive_file_path');
            $table->string('archive_hash');
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verification_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nf525_fiscal_archives');
        Schema::dropIfExists('nf525_daily_counters');
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn([
                'nf525_receipt_number',
                'nf525_hash',
                'nf525_timestamp',
                'is_training_mode',
                'is_copy',
                'copy_number',
                'cancellation_reason',
                'original_receipt_number'
            ]);
        });
        Schema::dropIfExists('nf525_audit_logs');
    }
}; 