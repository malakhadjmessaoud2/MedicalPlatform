<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donateur extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'type'];

    public function dons()
    {
        return $this->hasMany(Don::class);
    }

    public function paiementsDon()
    {
        return $this->hasMany(PaiementDon::class);
    }
}
