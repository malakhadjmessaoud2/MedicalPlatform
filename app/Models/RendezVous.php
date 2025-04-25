<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RendezVous extends Model
{
    use HasFactory;

    protected $table = 'rendez_vous';

    protected $fillable = [
        'medecin_id',
        'patient_id',
        'titre',
        'date_debut',
        'date_fin',
        'description',
        'type',
        'statut',
        'family_info'
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'est_bloque' => 'boolean',
    ];

    public function medecin()
    {
        return $this->belongsTo(Medecin::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function getCouleurAttribute($value)
    {
        if ($value) return $value;

        return match($this->type) {
            'consultation' => '#10B981', // vert
            'suivi' => '#F59E0B',       // jaune
            'urgence' => '#EF4444',     // rouge
            default => '#6B7280'         // gris
        };
    }
}
