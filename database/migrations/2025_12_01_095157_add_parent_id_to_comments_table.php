<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            // CORRECTION : Supprimer la clé étrangère d'abord
            // Laravel génère le nom 'comments_parent_id_foreign' par défaut
            $table->dropForeign(['parent_id']); 
            
            // Ensuite, on peut supprimer la colonne sans erreur
            $table->dropColumn('parent_id');
        });
    }
};