<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicament extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'est_don',
        'picture',
        'qrcode',
    ];

    public function ordonnances()
    {
        return $this->belongsToMany(Ordonnance::class);
    }

    public function mouvementsStock()
    {
        return $this->hasMany(MouvementStock::class, 'medicament_id');
    }

    public function lignesCommande()
    {
        return $this->hasMany(LigneCommande::class, 'medicament_id');
    }
}
