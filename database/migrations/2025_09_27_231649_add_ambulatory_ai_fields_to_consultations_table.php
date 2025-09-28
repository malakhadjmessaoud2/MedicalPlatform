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
            // Champs pour la téléconsultation
            $table->text('teleconsultation_report_ia')->nullable();
            $table->text('diagnostic_summary_ia')->nullable();
            $table->text('treatment_plan_ia')->nullable();
            $table->timestamp('teleconsultation_generated_at')->nullable();

            // Champs pour les recommandations
            $table->text('hygiene_instructions_ia')->nullable();
            $table->text('follow_up_plan_ia')->nullable();
            $table->text('specialist_referral_ia')->nullable();
            $table->text('medication_instructions_ia')->nullable();
            $table->timestamp('recommendations_generated_at')->nullable();

            // Champs pour le résumé en ligne
            $table->text('online_summary_ia')->nullable();
            $table->text('key_points_ia')->nullable();
            $table->text('next_steps_ia')->nullable();
            $table->timestamp('summary_generated_at')->nullable();

            // Champs pour le plan de suivi
            $table->text('monitoring_points_ia')->nullable();
            $table->text('warning_signs_ia')->nullable();
            $table->text('appointment_schedule_ia')->nullable();
            $table->timestamp('followup_generated_at')->nullable();

            // Champ pour le document complet
            $table->timestamp('complete_document_generated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropColumn([
                'teleconsultation_report_ia',
                'diagnostic_summary_ia',
                'treatment_plan_ia',
                'teleconsultation_generated_at',
                'hygiene_instructions_ia',
                'follow_up_plan_ia',
                'specialist_referral_ia',
                'medication_instructions_ia',
                'recommendations_generated_at',
                'online_summary_ia',
                'key_points_ia',
                'next_steps_ia',
                'summary_generated_at',
                'monitoring_points_ia',
                'warning_signs_ia',
                'appointment_schedule_ia',
                'followup_generated_at',
                'complete_document_generated_at'
            ]);
        });
    }
};
