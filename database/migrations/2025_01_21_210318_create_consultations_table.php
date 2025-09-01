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
        Schema::create('consultations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('dossier_medical_id')->nullable()->constrained('dossier_medicals')->onDelete('cascade');
            $table->foreignId('rendezvous_id')->nullable()->constrained('rendez_vous')->onDelete('cascade');

            $table->date('date')->nullable();
            $table->enum('type', ['first_consultation', 'controle', 'urgent', 'routine', 'other'])->default('first_consultation');
            $table->text('motif')->nullable();
            $table->text('symptomes')->nullable();
            // Constantes vitales
            $table->string('tension_arterielle')->nullable();
            $table->integer('frequence_cardiaque')->nullable();
            $table->decimal('temperature', 4, 1)->nullable();
            $table->integer('saturation_o2')->nullable();
            $table->integer('score_glasgow')->nullable();
            // Diagnostic
            $table->text('examen_physique')->nullable();
            $table->text('diagnostic_presume')->nullable();
            $table->text('medicaments_prescrits')->nullable();
            $table->text('propositions_suivi')->nullable();
            $table->text('instructions_particulieres')->nullable();

            // Mesures physiques
            $table->decimal('poids', 5, 2)->nullable();
            $table->decimal('taille', 5, 2)->nullable();
            $table->decimal('imc', 5, 2)->nullable();

            // Habitudes / suivi
            $table->text('habitudes_vie')->nullable();
            $table->text('traitement_actuel')->nullable();
            $table->text('evolution_symptomes')->nullable();
            $table->text('effets_secondaires')->nullable();
            $table->text('examens_controle')->nullable();

            // Symptômes spécifiques
            $table->text('symptomes_aigus')->nullable();
            $table->date('debut_symptomes')->nullable();
            $table->string('gravite')->nullable();
            $table->string('orientation_patient')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
