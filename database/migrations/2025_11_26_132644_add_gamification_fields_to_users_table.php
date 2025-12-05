<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('current_level_id')->nullable()->constrained('levels'); 
            $table->integer('current_xp')->default(0);
            $table->integer('currency_balance')->default(0);
        });
    }

   public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Important : supprimer la clé étrangère AVANT les colonnes
            $table->dropForeign(['current_level_id']);
            $table->dropColumn(['current_level_id', 'current_xp', 'currency_balance']);
        });
    }
};