<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class RendezVous extends Model
{
    use HasFactory;

    protected $table = 'rendez_vous';

    protected $fillable = [
        'patient_id',
        'medecin_id',
        'date_debut',
        'date_fin',
        'type',
        'description',
        'statut',
        'lien_en_ligne',
        'payment_token'
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
    ];

    /**
     * Allowed status values for a rendez-vous lifecycle.
     */
    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_PAYED = 'payed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_COMPLETED = 'completed';

    /** @var array<string, string[]> */
    private static array $allowedTransitions = [
        self::STATUS_PENDING   => [self::STATUS_CONFIRMED, self::STATUS_REJECTED, self::STATUS_CANCELLED, self::STATUS_PAYED, self::STATUS_COMPLETED],
        self::STATUS_CONFIRMED => [self::STATUS_PAYED, self::STATUS_CANCELLED, self::STATUS_PENDING, self::STATUS_REJECTED, self::STATUS_COMPLETED],
        self::STATUS_PAYED     => [self::STATUS_COMPLETED, self::STATUS_CANCELLED, self::STATUS_PENDING, self::STATUS_CONFIRMED, self::STATUS_REJECTED],
        self::STATUS_CANCELLED => [self::STATUS_PENDING, self::STATUS_CONFIRMED, self::STATUS_PAYED, self::STATUS_COMPLETED, self::STATUS_REJECTED],
        self::STATUS_REJECTED  => [self::STATUS_PENDING, self::STATUS_CONFIRMED, self::STATUS_CANCELLED, self::STATUS_PAYED, self::STATUS_COMPLETED],
        self::STATUS_COMPLETED => [self::STATUS_PENDING, self::STATUS_CONFIRMED, self::STATUS_PAYED, self::STATUS_CANCELLED, self::STATUS_REJECTED],
    ];

    /**
     * Determine if a status transition is allowed at this moment.
     * Le médecin a un contrôle total sur les statuts des rendez-vous.
     */
    public function canTransitionTo(string $newStatus): bool
    {
        $current = $this->statut;
        $allowed = self::$allowedTransitions[$current] ?? [];

        // Vérifier si la transition est dans la liste des transitions autorisées
        if (!in_array($newStatus, $allowed, true)) {
            return false;
        }

        // Le médecin peut effectuer toutes les transitions autorisées
        // sans restrictions temporelles ou autres contraintes
        return true;
    }

    /**
     * Perform a safe transition. Le médecin a un contrôle total sur les statuts.
     */
    public function transitionTo(string $newStatus): self
    {
        // Log de la transition pour audit
        Log::info('RENDEZVOUS_STATUS_TRANSITION', [
            'rendezvous_id' => $this->id,
            'old_status' => $this->statut,
            'new_status' => $newStatus,
            'can_transition' => $this->canTransitionTo($newStatus)
        ]);

        // Effectuer la transition même si elle n'est pas "standardement" autorisée
        // Le médecin a un contrôle total sur les statuts
        $this->statut = $newStatus;
        $this->save();

        return $this;
    }

    /**
     * If the RDV is payed and its end time is past, mark it completed.
     */
    public function autoCompleteIfNeeded(): bool
    {
        if ($this->statut === self::STATUS_PAYED && $this->date_fin && now()->greaterThan($this->date_fin)) {
            $this->statut = self::STATUS_COMPLETED;
            $this->save();
            return true;
        }
        return false;
    }

    public function medecin()
    {
        return $this->belongsTo(User::class, 'medecin_id');
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    // public function consultations()
    // {
    //     return $this->hasMany(Consultation::class, 'rendezvous_id');
    // }

    public function getCouleurAttribute($value)
    {
        if ($value) return $value;
        return match ($this->type) {
            'consultation' => '#10B981', // vert
            'examen' => '#F59E0B',       // jaune
            'intervention' => '#EF4444',     // rouge
            'autre' => '#6B7280'         // gris
        };
    }
}
