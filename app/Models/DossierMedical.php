<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DossierMedical extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'allergies',
        'groupe_sanguin',
        'antecedents_medicaux',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function documentsMedicaux()
    {
        return $this->hasMany(DocumentsMedecaux::class, 'dossier_medical_id');
    }
}
