<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeDon extends Model
{
    use HasFactory;

    protected $fillable = [
        'pharmacie_id',
        'donateur_id',
        'patient_id',
        'quantite_demandee',
        'date_demande',
        'status',
        'certification_id',
    ];

    public function pharmacie()
    {
        return $this->belongsTo(Pharmacie::class);
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function donateur()
    {
        return $this->belongsTo(User::class, 'donateur_id');
    }

    public function certification()
    {
        return $this->belongsTo(Certification::class);
    }
}
