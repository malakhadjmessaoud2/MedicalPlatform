<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto; // pour la photo de profil jetstream profile_photo_path
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'role',
        'dateNaissance',
        'tel',
        'adresse',
        'specialite',
        'adresse_cabinet',
        'experience',
        'formation',
        'langues',
        'score',
        'nbrAvis',
        'notations',
        'prixConsultation',
        'isActive',
        'DiplômeOrCNOM'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'two_factor_confirmed_at' => 'datetime',
        'score' => 'float',
        'nbrAvis' => 'integer',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];


    public function rendezVousCommeMedecin()
    {
        return $this->hasMany(RendezVous::class, 'medecin_id');
    }

    public function rendezVousCommePatient()
    {
        return $this->hasMany(RendezVous::class, 'patient_id');
    }

    // Alias pour la relation rendez-vous (utilisé dans le contrôleur admin)
    public function rendezVous()
    {
        return $this->rendezVousCommeMedecin();
    }



    public function demandesDon()
    {
        return $this->hasMany(DemandeDon::class);
    }

    // public function consultations()
    // {
    //     return $this->hasMany(Consultation::class, 'patient_id');
    // }

    // public function consultationsEffectuees()
    // {
    //     return $this->hasMany(Consultation::class, 'medecin_id');
    // }



    /**
     * Vérifie si l'utilisateur est un médecin
     */
    public function isMedecin()
    {
        return $this->role == 'medecin';
    }

    /**
     * Vérifie si l'utilisateur est un patient
     */
    public function isPatient()
    {
        return $this->role == 'patient';
    }

    public function isDonateur()
    {
        return $this->role == 'donateur';
    }

    public function isOperateurPharmacie()
    {
        return $this->role == 'operateurpharmacie';
    }

    /**
     * Vérifie si l'utilisateur est un administrateur
     */
    public function isAdmin()
    {
        return $this->role == 'admin';
    }

    /**
     * Accessor: retourne un tableau de langues parlées par le médecin.
     * Accepte plusieurs formats en base: JSON (array), CSV (string), ou null.
     *
     * @return array<int, string>
     */
    public function getLanguesArrayAttribute(): array
    {
        $raw = $this->attributes['langues'] ?? null;

        if (is_array($this->langues)) {
            return array_values(array_filter(array_map(static function ($v) {
                return is_string($v) ? trim($v) : '';
            }, $this->langues)));
        }

        if (is_string($raw)) {
            // Essayer JSON d'abord
            $decoded = json_decode($raw, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return array_values(array_filter(array_map(static function ($v) {
                    return is_string($v) ? trim($v) : '';
                }, $decoded)));
            }
            // Fallback: CSV
            $parts = array_map('trim', explode(',', $raw));
            return array_values(array_filter($parts, static function ($v) {
                return $v !== '';
            }));
        }

        return [];
    }
}
