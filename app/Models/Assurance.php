<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assurance extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'type_couverture'];

    public function patients()
    {
        return $this->belongsToMany(Patient::class);
    }

}
