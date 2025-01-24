<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaiementDon extends Model
{
    use HasFactory;

    protected $fillable = ['donateur_id', 'pharmacie_id', 'montant', 'date_paiement'];

    public function donateur()
    {
        return $this->belongsTo(Donateur::class);
    }

    public function pharmacie()
    {
        return $this->belongsTo(Pharmacie::class);
    }
}
