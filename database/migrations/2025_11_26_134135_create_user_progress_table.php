<?php

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
        Schema::create('user_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('daily_content_id')->constrained()->onDelete('cascade');
            
            $table->boolean('video_watched')->default(false);
            $table->timestamp('watched_at')->nullable();
            
            $table->boolean('quiz_completed')->default(false);
            $table->integer('quiz_score')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            $table->timestamps();
            
            // Un user ne peut avoir qu'une ligne de progression par jour
            $table->unique(['user_id', 'daily_content_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_progress');
    }
};
