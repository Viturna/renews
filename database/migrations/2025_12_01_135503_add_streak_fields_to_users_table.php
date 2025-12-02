<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('current_streak')->default(0); // Le nombre de jours d'affilée
            $table->timestamp('last_streak_at')->nullable(); // La dernière fois que la flamme a été validée
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['current_streak', 'last_streak_at']);
        });
    }
};