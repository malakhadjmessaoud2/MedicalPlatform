<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Don extends Model
{
    use HasFactory;

    protected $fillable = ['medicament_id', 'montant', 'quantite', 'pharmacie_id', 'donateur_id', 'date', 'type_don'];

    public function medicament()
    {
        return $this->belongsTo(Medicament::class);
    }

    public function pharmacie()
    {
        return $this->belongsTo(Pharmacie::class);
    }

    public function donateur()
    {
        return $this->belongsTo(User::class, 'donateur_id');
    }
}
