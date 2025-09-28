<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;

    protected $fillable = [
        'dossier_medical_id',
        'rendezvous_id',
        'date',
        'type',
        'motif',
        'symptomes',
        'tension_arterielle',
        'frequence_cardiaque',
        'temperature',
        'saturation_o2',
        'score_glasgow',
        'examen_physique',
        'diagnostic_presume',
        'medicaments_prescrits',
        'propositions_suivi',
        'instructions_particulieres',
        'poids',
        'taille',
        'imc',
        'habitudes_vie',
        'traitement_actuel',
        'evolution_symptomes',
        'effets_secondaires',
        'examens_controle',
        'symptomes_aigus',
        'debut_symptomes',
        'gravite',
        'orientation_patient',

        // Champs IA - Génération générale
        'compte_rendu_ia',
        'resume_ia',
        'recommandations_ia',
        'lettre_sortie_ia',
        'compte_rendu_generated_at',
        'resume_generated_at',
        'lettre_sortie_generated_at',
        'ai_service_used',

        // Champs IA - Médecine ambulatoire
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
        'complete_document_generated_at',
    ];

    protected $casts = [
        'date' => 'datetime',
        'temperature' => 'decimal:1',
        'poids' => 'decimal:2',
        'taille' => 'decimal:2',
        'imc' => 'decimal:2',

        // Casts pour les timestamps IA
        'compte_rendu_generated_at' => 'datetime',
        'resume_generated_at' => 'datetime',
        'lettre_sortie_generated_at' => 'datetime',
        'teleconsultation_generated_at' => 'datetime',
        'recommendations_generated_at' => 'datetime',
        'summary_generated_at' => 'datetime',
        'followup_generated_at' => 'datetime',
        'complete_document_generated_at' => 'datetime',
    ];

    public function dossierMedical()
    {
        return $this->belongsTo(DossierMedical::class, 'dossier_medical_id');
    }

    public function rendezVous()
    {
        return $this->belongsTo(RendezVous::class, 'rendezvous_id');
    }
    public function ordonnances()
{
    return $this->hasOne(Ordonnance::class, 'consultation_id');
}
}
