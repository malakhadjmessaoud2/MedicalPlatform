<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        Log::info('[ProfileUpdate] Start', [
            'user_id' => $user->id,
            'role' => $user->role,
            'input_keys' => array_keys($input),
            'has_photo_key' => array_key_exists('photo', $input),
            'has_profile_photo_key' => array_key_exists('profile_photo', $input),
            'photo_is_file' => isset($input['photo']) && $input['photo'] instanceof \Illuminate\Http\UploadedFile,
            'profile_photo_is_file' => isset($input['profile_photo']) && $input['profile_photo'] instanceof \Illuminate\Http\UploadedFile,
            'photo_value' => isset($input['photo']) ? get_class($input['photo']) : 'not_set',
            'profile_photo_value' => isset($input['profile_photo']) ? get_class($input['profile_photo']) : 'not_set',
        ]);
        // Base rules common to all roles
        $rules = [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'profile_photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
            // Also accept Jetstream's default 'photo' key
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ];

        // Role-specific rules
        if ($user->role === 'patient') {
            $rules = array_merge($rules, [
                'dateNaissance' => ['nullable', 'date'],
                'tel' => ['nullable', 'string', 'max:50'],
                'adresse' => ['nullable', 'string', 'max:255'],
            ]);
        } elseif ($user->role === 'medecin') {
            $rules = array_merge($rules, [
                'specialite' => ['nullable', 'string', 'max:255'],
                'adresse_cabinet' => ['nullable', 'string', 'max:255'],
                'experience' => ['nullable', 'integer', 'min:0'],
                'formation' => ['nullable', 'string'],
                'langues' => ['nullable', 'string'],
                'prixConsultation' => ['nullable', 'integer', 'min:60'],
                // Validate only when an actual file is present
                'DiplômeOrCNOM' => ['sometimes', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:1024'],
            ]);
        } elseif ($user->role === 'donateur') {
            // No extra mandatory fields currently
        } elseif ($user->role === 'operateurpharmacie') {
            // No extra mandatory fields currently
        }

        // Ignore non-file DiplômeOrCNOM to avoid false validation errors
        if (
            $user->role === 'medecin'
            && array_key_exists('DiplômeOrCNOM', $input)
            && !($input['DiplômeOrCNOM'] instanceof \Illuminate\Http\UploadedFile)
        ) {
            unset($input['DiplômeOrCNOM']);
        }

        Validator::make($input, $rules)->validateWithBag('updateProfileInformation');

        // Update profile photo if provided under either key (fallback to request()->file if absent in $input)
        if (!empty($input['photo']) && $input['photo'] instanceof \Illuminate\Http\UploadedFile) {
            try {
                $user->updateProfilePhoto($input['photo']);
                Log::info('[ProfileUpdate] Updated using key photo', [
                    'user_id' => $user->id,
                    'stored_path' => $user->profile_photo_path,
                ]);
            } catch (\Throwable $e) {
                Log::error('[ProfileUpdate] Error updating photo (photo key)', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);
            }
        } elseif (!empty($input['profile_photo']) && $input['profile_photo'] instanceof \Illuminate\Http\UploadedFile) {
            try {
                $user->updateProfilePhoto($input['profile_photo']);
                Log::info('[ProfileUpdate] Updated using key profile_photo', [
                    'user_id' => $user->id,
                    'stored_path' => $user->profile_photo_path,
                ]);
            } catch (\Throwable $e) {
                Log::error('[ProfileUpdate] Error updating photo (profile_photo key)', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);
            }
        } else {
            // Fallback: sometimes Livewire/Jetstream passes files only via the HTTP request, not the $input array
            $requestPhoto = request()->file('photo') ?? request()->file('profile_photo');
            if ($requestPhoto instanceof \Illuminate\Http\UploadedFile) {
                try {
                    $user->updateProfilePhoto($requestPhoto);
                    Log::info('[ProfileUpdate] Updated using request()->file fallback', [
                        'user_id' => $user->id,
                        'stored_path' => $user->profile_photo_path,
                    ]);
                } catch (\Throwable $e) {
                    Log::error('[ProfileUpdate] Error updating photo (request file fallback)', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            } else {
                Log::info('[ProfileUpdate] No uploaded file provided for photo', [
                    'user_id' => $user->id,
                ]);
            }
        }

        // Handle DiplômeOrCNOM upload for medecin
        if ($user->role === 'medecin' && isset($input['DiplômeOrCNOM'])) {
            try {
                $storedPath = Storage::disk('public')->putFile('diplomes', $input['DiplômeOrCNOM']);
                $user->DiplômeOrCNOM = $storedPath;
                $user->save();
            } catch (\Exception $e) {
                Log::error('Erreur lors du téléversement du DiplômeOrCNOM: ' . $e->getMessage());
            }
        }

        // Prepare attributes to update based on role
        $attributes = [
            'nom' => $input['nom'],
            'prenom' => $input['prenom'],
            'email' => $input['email'],
        ];

        if ($user->role === 'patient') {
            $attributes = array_merge($attributes, [
                'dateNaissance' => $input['dateNaissance'] ?? $user->dateNaissance,
                'tel' => $input['tel'] ?? $user->tel,
                'adresse' => $input['adresse'] ?? $user->adresse,
            ]);
        } elseif ($user->role === 'medecin') {
            $attributes = array_merge($attributes, [
                'specialite' => $input['specialite'] ?? $user->specialite,
                'adresse_cabinet' => $input['adresse_cabinet'] ?? $user->adresse_cabinet,
                'experience' => $input['experience'] ?? $user->experience,
                'formation' => $input['formation'] ?? $user->formation,
                'langues' => $input['langues'] ?? $user->langues,
                'prixConsultation' => $input['prixConsultation'] ?? $user->prixConsultation,
            ]);
        }

        if (($input['email'] ?? $user->email) !== $user->email && $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $attributes);
        } else {
            $user->forceFill($attributes)->save();
        }

        Log::info('[ProfileUpdate] Success', [
            'user_id' => $user->id,
            'role' => $user->role,
            'final_profile_photo_path' => $user->profile_photo_path,
        ]);
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'nom' => $input['nom'] ?? $user->nom,
            'prenom' => $input['prenom'] ?? $user->prenom,
            'email' => $input['email'] ?? $user->email,
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
