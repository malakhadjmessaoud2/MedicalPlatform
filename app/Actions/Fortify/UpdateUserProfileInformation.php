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
        //  dd($input);
        // Base rules common to all roles
        $rules = [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'profile_photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
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
            ]);
        } elseif ($user->role === 'donateur') {
            // No extra mandatory fields currently
        } elseif ($user->role === 'operateurpharmacie') {
            // No extra mandatory fields currently
        }

        Validator::make($input, $rules)->validateWithBag('updateProfileInformation');

        if (isset($input['profile_photo'])) {
            $user->updateProfilePhoto($input['profile_photo']);
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
            ]);
        }

        if (($input['email'] ?? $user->email) !== $user->email && $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $attributes);
        } else {
            $user->forceFill($attributes)->save();
        }
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
