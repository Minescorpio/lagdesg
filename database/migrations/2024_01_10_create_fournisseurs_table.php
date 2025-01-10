<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('fournisseurs', function (Blueprint $table) {
            $table->string('email')->nullable()->after('nom');
            $table->string('telephone')->nullable()->after('email');
        });
    }

    public function down()
    {
        Schema::table('fournisseurs', function (Blueprint $table) {
            $table->dropColumn(['email', 'telephone']);
        });
    }
}; 