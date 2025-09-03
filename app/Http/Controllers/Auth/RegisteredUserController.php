<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
            'nom' => ['required', 'string', 'max:255'],
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
                'adresse_cabinet' => ['required', 'string', 'max:255'],
                'experience' => ['nullable', 'integer', 'min:0'],
                'formation' => ['nullable', 'string'],
                'prixConsultation'=>['nullable', 'integer', 'min:60'],
                'DiplômeOrCNOM' => ['nullable', 'mimes:jpg,jpeg,png,pdf', 'max:1024'], // Max 1MB
            ]);
        } elseif ($request->role === 'patient') {
            $request->validate([
                'dateNaissance' => ['required', 'date'],
            ]);
        }

        // Créer l'utilisateur
        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Traiter la photo de profil si elle est fournie
        if ($request->hasFile('profile_photo')) {
            try {
                // Stocker le fichier dans le disque 'public' dans le dossier 'profile-photos'
                $filename = Storage::disk('public')->putFile('profile-photos', $request->file('profile_photo'));

                // Mettre à jour le chemin dans la base de données
                $user->profile_photo_path = $filename;
                $user->save();

                // Log pour débogage
                Log::info('Photo de profil enregistrée: ' . $filename);
            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'enregistrement de la photo: ' . $e->getMessage());
            }
        }

        // Créer l'enregistrement correspondant selon le rôle
        if ($request->role === 'medecin') {
            // Traiter le fichier DiplômeOrCNOM si fourni
            if ($request->hasFile('DiplômeOrCNOM')) {
                try {
                    // Stocker le fichier dans le disque 'public' dans le dossier 'diplomes'
                    $diplomeFilename = Storage::disk('public')->putFile('diplomes', $request->file('DiplômeOrCNOM'));

                    // Mettre à jour le chemin dans la base de données
                    $user->DiplômeOrCNOM = $diplomeFilename;
                    $user->save();

                    Log::info('Diplôme ou CNOM enregistré: ' . $diplomeFilename);
                } catch (\Exception $e) {
                    Log::error('Erreur lors de l\'enregistrement du DiplômeOrCNOM: ' . $e->getMessage());
                }
            }
            // Traitement des langues
            $languesArray = [];
            if ($request->has('langues') && is_array($request->langues)) {
                $languesArray = $request->langues;
            }

            // Ajouter la langue "autre" si elle est cochée et remplie
            if ($request->has('langues_autre') && $request->has('langues_autre_texte') && !empty($request->langues_autre_texte)) {
                $languesArray[] = $request->langues_autre_texte;
            }

            // Convertir le tableau de langues en chaîne de caractères
            $langues = !empty($languesArray) ? implode(', ', $languesArray) : '';

            $user->update([
                'specialite' => $request->specialite,
                'adresse_cabinet' => $request->adresse_cabinet,
                'experience' => $request->experience,
                'formation' => $request->formation,
                'langues' => $langues,
                'score' => 0,
                'prixConsultation' => $request->prixConsultation ?? 60,
            ]);
        } elseif ($request->role === 'patient') {
            $user->update([
                'dateNaissance' => $request->dateNaissance,
            ]);
        }

        // Authentifier l'utilisateur après l'enregistrement
        Auth::login($user);
        //    dd($user->isMedecin());

        return $user->isMedecin() ? redirect()->route('medecin.dashboard') : redirect()->route('patient.dashboard');
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
