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
    ];

    protected $casts = [
        'date' => 'datetime',
        'temperature' => 'decimal:1',
        'poids' => 'decimal:2',
        'taille' => 'decimal:2',
        'imc' => 'decimal:2',
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
