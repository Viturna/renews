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
        Schema::create('daily_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('theme_id')->constrained();
            $table->date('publish_date')->unique(); // Une seule vidéo par jour ? ou par thème ? A voir.
            $table->string('video_url');
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('unlock_quiz_at'); // ex: publish_date + 1 day at 08:00
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_contents');
    }
};
