<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class DocumentsMedecaux extends Model
{
    use HasFactory;

    protected $table = 'documents_medicaux';

    protected $fillable = [
        'dossier_medical_id',
        'notes',
        'file',
    ];

    /**
     * Relations
     */

    public function dossierMedical()
    {
        return $this->belongsTo(DossierMedical::class, 'dossier_medical_id');
    }
}
