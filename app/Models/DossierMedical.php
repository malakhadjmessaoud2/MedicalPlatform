<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DossierMedical extends Model
{
    use HasFactory;

    protected $fillable = ['patient_id',
    'tel',
    'adresse',
    'allergies',
    'groupe_sanguin',
    'antecedents_medicaux'
];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
