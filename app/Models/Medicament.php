<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicament extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'description', 'stock', 'prix', 'est_don'];

    public function ordonnances()
    {
        return $this->belongsToMany(Ordonnance::class);
    }

    public function dons()
    {
        return $this->hasMany(Don::class);
    }

    public function demandesDon()
    {
        return $this->hasMany(DemandeDon::class);
    }
}
