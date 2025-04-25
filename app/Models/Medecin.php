<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medecin extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'nom', 'prenom', 'specialite',
        'adresse_cabinet',
        'experience',
        'formation',
        'langues',
        'score',
    ];
        /**
     * Obtenir un tableau des langues parlées
     */
    public function getLanguesArrayAttribute()
    {
        if (empty($this->langues)) {
            return [];
        }

        return array_map('trim', explode(',', $this->langues));
    }

    /**
     * Vérifier si le médecin parle une langue spécifique
     */
    public function parleLangne($langue)
    {
        return in_array($langue, $this->langues_array);
    }

    /**
     * Relation avec le modèle User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rendezVous()
    {
        return $this->hasMany(RendezVous::class);
    }

    public function ordonnances()
    {
        return $this->hasMany(Ordonnance::class);
    }

    public function certifications()
    {
        return $this->hasMany(Certification::class);
    }

    public function dossiersMedicaux()
    {
        return $this->hasMany(DossierMedical::class);
    }
}
