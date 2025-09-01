<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pharmacie extends Model
{
    use HasFactory;

    protected $fillable = [
        'operateurpharmacie_id',
        'nom',
        'localisation',
        'tel',
        'email',
        'site_web',
        'horaires',
        'image',
        'description',
    ];
    public function operateurPharmacie()
    {
        return $this->belongsTo(User::class, 'operateurpharmacie_id');
    }

    public function medicaments()
    {
        return $this->hasMany(Medicament::class);
    }

    public function mouvementsStock()
    {
        return $this->hasMany(MouvementStock::class);
    }

    // public function commandes()
    // {
    //     return $this->hasMany(Commande::class);
    // }
}
