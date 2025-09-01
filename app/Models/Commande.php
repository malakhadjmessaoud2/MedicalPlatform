<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'montant_total',
        'date',
        'statut',
    ];

    /**
     * Relations
     */

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function lignesCommande()
    {
        return $this->hasMany(LigneCommande::class, 'commande_id');
    }

    public function paiement()
    {
        return $this->hasOne(Paiement::class, 'commande_id');
    }
}
