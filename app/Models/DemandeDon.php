<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeDon extends Model
{
    use HasFactory;

    protected $fillable = ['medicament_id', 'quantite_demandee', 'patient_id', 'date_demande', 'status', 'certification_id'];

    public function medicament()
    {
        return $this->belongsTo(Medicament::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function certification()
    {
        return $this->belongsTo(Certification::class);
    }
}
