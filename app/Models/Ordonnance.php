<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ordonnance extends Model
{
    use HasFactory;

    protected $fillable = [
        'consultation_id',
        'medicaments',
        'notes',
        'file',
    ];
    protected $casts = [
        'medicaments' => 'array', // JSON array PHP
    ];
    public function consultation()
    {
        return $this->belongsTo(Consultation::class, 'consultation_id');
    }

}
