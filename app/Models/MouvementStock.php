<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MouvementStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'pharmacie_id',
        'medicament_id',
        'quantite',
        'dateMov',
        'dateExpiration',
        'prix',
        'type',
    ];

    public function pharmacie()
    {
        return $this->belongsTo(Pharmacie::class, 'pharmacie_id');
    }

    public function medicament()
    {
        return $this->belongsTo(Medicament::class, 'medicament_id');
    }
}
