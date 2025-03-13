<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Medecin;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RegisteredUserController extends Controller
{
    /**
     * Affiche le formulaire d'enregistrement.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Gère l'enregistrement d'un nouvel utilisateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validation de base pour tous les utilisateurs
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'in:medecin,patient'],
            'prenom' => ['required', 'string', 'max:255'],
            'profile_photo' => ['nullable', 'image', 'max:1024'], // Max 1MB
        ]);

        // Validation conditionnelle selon le rôle
        if ($request->role === 'medecin') {
            $request->validate([
                'specialite' => ['required', 'string', 'max:255'],
            ]);
        } elseif ($request->role === 'patient') {
            $request->validate([
                'dateNaissance' => ['required', 'date'],
            ]);
        }

        // Créer l'utilisateur
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Traiter la photo de profil si elle est fournie
        if ($request->hasFile('profile_photo')) {
            // Vérifier que le dossier de stockage existe et est accessible en écriture
            $storagePath = storage_path('app/public/profile-photos');
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0755, true);
            }

            // Utiliser la méthode de Jetstream pour mettre à jour la photo de profil
            $user->updateProfilePhoto($request->file('profile_photo'));
        }

        // Créer l'enregistrement correspondant selon le rôle
        if ($request->role === 'medecin') {
            Medecin::create([
                'user_id' => $user->id,
                'nom' => $request->name,
                'prenom' => $request->prenom,
                'specialite' => $request->specialite,
            ]);
        } elseif ($request->role === 'patient') {
            Patient::create([
                'user_id' => $user->id,
                'nom' => $request->name,
                'prenom' => $request->prenom,
                'dateNaissance' => $request->dateNaissance,
            ]);
        }

        // Authentifier l'utilisateur après l'enregistrement
        Auth::login($user);

        // Rediriger en fonction du rôle
        if ($user->role === 'medecin') {
            return redirect()->route('dashboard.medecin');
        } elseif ($user->role === 'patient') {
            return redirect()->route('dashboard.patient');
        }

        // Redirection par défaut si aucun des rôles spécifiques n'est trouvé
        return redirect()->route('dashboard');
    }

    /**
     * Affiche le formulaire d'inscription pour les médecins.
     *
     * @return \Illuminate\View\View
     */
    public function createMedecin()
    {
        return view('auth.register-medecin');
    }

    /**
     * Affiche le formulaire d'inscription pour les patients.
     *
     * @return \Illuminate\View\View
     */
    public function createPatient()
    {
        return view('auth.register-patient');
    }

    /**
     * Affiche le formulaire d'inscription pour les pharmacies.
     *
     * @return \Illuminate\View\View
     */
    public function createPharmacie()
    {
        return view('auth.register-pharmacie');
    }

    /**
     * Affiche le formulaire d'inscription pour les donateurs.
     *
     * @return \Illuminate\View\View
     */
    public function createDonateur()
    {
        return view('auth.register-donateur');
    }
}
