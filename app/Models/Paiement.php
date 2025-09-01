<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{

    use HasFactory;

    protected $fillable = [
        'commande_id',
        'consultation_id',
        'don_id',
        'montant',
        'type',
        'datePaiement',
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class, 'commande_id');
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class, 'consultation_id');
    }

    public function demandeDon()
    {
        return $this->belongsTo(DemandeDon::class, 'don_id');
    }
}
