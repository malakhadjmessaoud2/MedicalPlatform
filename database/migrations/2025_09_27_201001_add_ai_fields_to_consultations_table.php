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
        Schema::table('consultations', function (Blueprint $table) {
            $table->text('compte_rendu_ia')->nullable();
            $table->text('resume_ia')->nullable();
            $table->text('recommandations_ia')->nullable();
            $table->text('lettre_sortie_ia')->nullable();
            $table->timestamp('compte_rendu_generated_at')->nullable();
            $table->timestamp('resume_generated_at')->nullable();
            $table->timestamp('lettre_sortie_generated_at')->nullable();
            $table->string('ai_service_used')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropColumn([
                'compte_rendu_ia',
                'resume_ia',
                'recommandations_ia',
                'lettre_sortie_ia',
                'compte_rendu_generated_at',
                'resume_generated_at',
                'lettre_sortie_generated_at',
                'ai_service_used'
            ]);
        });
    }
};
