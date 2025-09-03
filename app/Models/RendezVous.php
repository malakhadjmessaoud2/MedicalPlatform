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
        'patient_id',
        'medecin_id',
        'date_debut',
        'date_fin',
        'type',
        'description',
        'statut',
        'lien_en_ligne',
        'payment_token'
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
    ];

    public function medecin()
    {
        return $this->belongsTo(User::class, 'medecin_id');
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    // public function consultations()
    // {
    //     return $this->hasMany(Consultation::class, 'rendezvous_id');
    // }

    public function getCouleurAttribute($value)
    {
        if ($value) return $value;
        return match ($this->type) {
            'consultation' => '#10B981', // vert
            'examen' => '#F59E0B',       // jaune
            'intervention' => '#EF4444',     // rouge
            'autre' => '#6B7280'         // gris
        };
    }
}
