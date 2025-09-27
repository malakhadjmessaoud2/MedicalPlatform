<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedecinRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'medecin_id',
        'patient_id',
        'note',
    ];
}


