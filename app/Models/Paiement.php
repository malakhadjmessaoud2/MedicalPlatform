<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{

    use HasFactory;

    protected $fillable = ['patient_id', 'pharmacie_id', 'medicament_id', 'montant', 'date_paiement'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function pharmacie()
    {
        return $this->belongsTo(Pharmacie::class);
    }

    public function medicament()
    {
        return $this->belongsTo(Medicament::class);
    }
}
