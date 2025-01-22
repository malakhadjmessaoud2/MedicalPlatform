<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pharmacie extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'localisation'];

    public function medicaments()
    {
        return $this->hasMany(Medicament::class);
    }

    public function demandesDon()
    {
        return $this->hasMany(DemandeDon::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function dons()
    {
        return $this->hasMany(Don::class);
    }}
