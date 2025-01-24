<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'prenom', 'date_naissance'];

    public function rendezVous()
    {
        return $this->hasMany(RendezVous::class);
    }

    public function ordonnances()
    {
        return $this->hasMany(Ordonnance::class);
    }

    public function demandesDon()
    {
        return $this->hasMany(DemandeDon::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function dossierMedical()
    {
        return $this->hasOne(DossierMedical::class);
    }
    public function assurances()
    {
        return $this->belongsToMany(Assurance::class);
    }
}
