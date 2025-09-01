<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RendezVous;
use App\Models\Consultation;
use App\Models\DossierMedical;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PatientController extends Controller
{
    /**
     * Affiche la liste des patients pour le médecin connecté.
     */
    public function index()
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            if (!$user->isMedecin()) {
                return redirect()->route('medecin.dashboard')->with('error', 'Profil médecin non trouvé.');
            }

            // Récupérer les IDs des patients uniques qui ont un rendez-vous avec ce médecin
            $patientIds = RendezVous::where('medecin_id', $user->id)
                ->select('patient_id')
                ->distinct()
                ->pluck('patient_id');

            // Récupérer les patients avec leurs informations
            $patients = User::whereIn('id', $patientIds)
                ->with(['rendezVousCommePatient' => function ($query) use ($user) {
                    $query->where('medecin_id', $user->id)->latest('date_debut');
                }])
                ->paginate(10);

            // Calculer l'âge et formater la dernière visite
            $patients->getCollection()->transform(function ($patient) use ($user) {
                $patient->age = $patient->dateNaissance ? Carbon::parse($patient->dateNaissance)->age : 'N/A';
                $patient->derniere_visite = $patient->rendezVousCommePatient->first()
                    ? Carbon::parse($patient->rendezVousCommePatient->first()->date_debut)->translatedFormat('d F Y')
                    : 'Aucune visite';
                return $patient;
            });

            // Récupérer tous les rendez-vous des patients suivis par ce médecin
            $rendezVous = RendezVous::with(['patient'])
                ->where('medecin_id', $user->id)
                ->orderBy('date_debut', 'desc')
                ->get();

            // Statistiques dynamiques
            $consultationsMois = Consultation::whereHas('rendezVous', function ($query) use ($user) {
                $query->where('medecin_id', $user->id);
            })
                ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                ->count();

            $stats = [
                'total_patients' => $patientIds->count(),
                'consultations_mois' => $consultationsMois,
                'nouveaux_patients' => User::whereIn('id', $patientIds)
                    ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                    ->count(),
            ];

            return view('dashMedecin.gestionPatient.index', compact('patients', 'rendezVous', 'stats'));
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement de la liste des patients: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('medecin.dashboard')->with('error', 'Une erreur est survenue lors du chargement des patients: ' . $e->getMessage());
        }
    }

    public function dossiersMedicaux()
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            if (!$user->isMedecin()) {
                return redirect()->route('medecin.dashboard')->with('error', 'Profil médecin non trouvé.');
            }

            // Récupérer les IDs des patients suivis par ce médecin
            $patientIds = RendezVous::where('medecin_id', $user->id)
                ->select('patient_id')
                ->distinct()
                ->pluck('patient_id');

            // Récupérer les dossiers médicaux de ces patients
            $dossiers = DossierMedical::with('patient')
                ->whereIn('patient_id', $patientIds)
                ->paginate(10);

            // Récupérer les patients avec leur dernier rendez-vous avec ce médecin
            $patients = User::whereIn('id', $patientIds)
                ->with(['rendezVousCommePatient' => function ($query) use ($user) {
                    $query->where('medecin_id', $user->id)->latest('date_debut');
                }])
                ->paginate(10);

            // Formater quelques champs utiles
            $patients->getCollection()->transform(function ($patient) use ($user) {
                $patient->age = $patient->dateNaissance ? Carbon::parse($patient->dateNaissance)->age : 'N/A';
                $patient->derniere_visite = $patient->rendezVousCommePatient->first()
                    ? Carbon::parse($patient->rendezVousCommePatient->first()->date_debut)->translatedFormat('d F Y')
                    : 'Aucune visite';
                return $patient;
            });

            // Récupérer la liste des rendez-vous de ces patients pour ce médecin
            $rendezVous = RendezVous::with(['patient'])
                ->where('medecin_id', $user->id)
                ->orderBy('date_debut', 'desc')
                ->get();

            return view('dashMedecin.gestionPatient.index', [
                'patients' => $patients,
                'dossiers' => $dossiers ?? collect([]),
                'rendezVous' => $rendezVous ?? collect([]),
            ]);
        } catch (\Exception $e) {
            return redirect()->route('medecin.dashboard')->with('error', 'Erreur lors du chargement des dossiers médicaux.');
        }
    }

    public function showDossierMedical(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user->isMedecin()) {
            return redirect()->route('medecin.dashboard')->with('error', 'Profil médecin non trouvé.');
        }

        $patientId = $request->query('patient_id');
        if (!$patientId) {
            return redirect()->back()->with('error', 'Aucun patient sélectionné.');
        }

        // Vérifier que ce patient est bien suivi par ce médecin
        $isSuivi = RendezVous::where('medecin_id', $user->id)
            ->where('patient_id', $patientId)
            ->exists();
        if (!$isSuivi) {
            return redirect()->back()->with('error', 'Ce patient ne fait pas partie de vos suivis.');
        }

        $patient = User::findOrFail($patientId);
        $dossier = DossierMedical::where('patient_id', $patientId)->first();

        // Récupérer les rendez-vous du jour pour ce patient et ce médecin
        $todayStart = Carbon::today()->startOfDay();
        $todayEnd = Carbon::today()->endOfDay();
        $rendezVousDuJour = RendezVous::where('patient_id', $patientId)
            ->where('medecin_id', $user->id)
            ->whereBetween('date_debut', [$todayStart, $todayEnd])
            ->orderBy('date_debut')
            ->get();

        return view('dashMedecin.gestionPatient.dossierMedical', compact('patient', 'dossier', 'rendezVousDuJour'));
    }

    /**
     * Affiche les détails d'un dossier médical en format JSON pour le modal
     */
    public function showDossierMedicalJson($dossierId)
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            if (!$user->isMedecin()) {
                return response()->json(['error' => 'Profil médecin non trouvé.'], 403);
            }

            $dossier = DossierMedical::with(['patient'])
                ->whereIn('patient_id', function($query) use ($user) {
                    $query->select('patient_id')
                          ->from('rendez_vous')
                          ->where('medecin_id', $user->id)
                          ->distinct();
                })
                ->findOrFail($dossierId);

            // Générer le HTML pour le modal
            $html = view('dashMedecin.gestionPatient.dossierMedicalModal', compact('dossier'))->render();

            return response()->json(['html' => $html]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors du chargement du dossier: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Met à jour un champ spécifique du dossier médical
     */
    public function updateDossierMedical(Request $request)
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            if (!$user->isMedecin()) {
                return response()->json(['success' => false, 'message' => 'Profil médecin non trouvé.'], 403);
            }

            $request->validate([
                'field' => 'required|string',
                'value' => 'required|string',
                'patient_id' => 'required|exists:users,id'
            ]);

            $patientId = $request->input('patient_id');
            $field = $request->input('field');
            $value = $request->input('value');

            // Vérifier que ce patient est bien suivi par ce médecin
            $isSuivi = RendezVous::where('medecin_id', $user->id)
                ->where('patient_id', $patientId)
                ->exists();
            if (!$isSuivi) {
                return response()->json(['success' => false, 'message' => 'Ce patient ne fait pas partie de vos suivis.'], 403);
            }

            // Récupérer ou créer le dossier médical
            $dossier = DossierMedical::firstOrCreate(
                ['patient_id' => $patientId],
                [
                    'patient_id' => $patientId,

                    'groupe_sanguin' => '',
                    'antecedents_medicaux' => '',
                    'allergies' => ''
                ]
            );

            // Mettre à jour le champ approprié
            switch ($field) {
                case 'groupe_sanguin':
                    $dossier->update(['groupe_sanguin' => $value]);
                    break;
                case 'antecedents_medicaux':
                    $dossier->update(['antecedents_medicaux' => $value]);
                    break;
                case 'allergies':
                    $dossier->update(['allergies' => $value]);
                    break;
                default:
                    return response()->json(['success' => false, 'message' => 'Champ non reconnu.'], 400);
            }

            return response()->json(['success' => true, 'message' => 'Champ mis à jour avec succès.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Affiche le formulaire de création d'un nouveau patient
     */
    public function create()
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            if (!$user->isMedecin()) {
                return redirect()->route('medecin.dashboard')->with('error', 'Profil médecin non trouvé.');
            }

            return view('dashMedecin.gestionPatient.create');
        } catch (\Exception $e) {
            return redirect()->route('medecin.dashboard')->with('error', 'Erreur lors du chargement du formulaire.');
        }
    }

    /**
     * Enregistre un nouveau patient
     */
    public function store(Request $request)
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            if (!$user->isMedecin()) {
                return redirect()->route('medecin.dashboard')->with('error', 'Profil médecin non trouvé.');
            }

            $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'dateNaissance' => 'required|date|before:today',
                'tel' => 'required|string|max:20',
                'adresse' => 'required|string|max:500',
            ]);

            // Créer le patient
            $patient = User::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'email' => $request->email,
                'dateNaissance' => $request->dateNaissance,
                'tel' => $request->tel,
                'adresse' => $request->adresse,
                'role' => 'patient',
                'password' => bcrypt('password123'), // Mot de passe temporaire
            ]);

            // Créer le dossier médical
            DossierMedical::create([
                'patient_id' => $patient->id,
                'allergies' => '',
                'groupe_sanguin' => '',
                'antecedents_medicaux' => '',
            ]);

            return redirect()->route('medecin.patients')->with('success', 'Patient créé avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du patient: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de la création du patient: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Affiche les détails d'un patient
     */
    public function show($patientId)
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            if (!$user->isMedecin()) {
                return redirect()->route('medecin.dashboard')->with('error', 'Profil médecin non trouvé.');
            }

            // Vérifier que ce patient est bien suivi par ce médecin
            $isSuivi = RendezVous::where('medecin_id', $user->id)
                ->where('patient_id', $patientId)
                ->exists();
            if (!$isSuivi) {
                return redirect()->back()->with('error', 'Ce patient ne fait pas partie de vos suivis.');
            }

            $patient = User::with(['rendezVousCommePatient' => function ($query) use ($user) {
                $query->where('medecin_id', $user->id)->latest('date_debut');
            }])->findOrFail($patientId);

            $dossier = DossierMedical::where('patient_id', $patientId)->first();
            $consultations = Consultation::whereHas('rendezVous', function ($query) use ($user, $patientId) {
                $query->where('medecin_id', $user->id)->where('patient_id', $patientId);
            })->with('rendezVous')->latest()->get();

            return view('dashMedecin.gestionPatient.show', compact('patient', 'dossier', 'consultations'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors du chargement du patient.');
        }
    }

    /**
     * Affiche le formulaire d'édition d'un patient
     */
    public function edit($patientId)
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            if (!$user->isMedecin()) {
                return redirect()->route('medecin.dashboard')->with('error', 'Profil médecin non trouvé.');
            }

            // Vérifier que ce patient est bien suivi par ce médecin
            $isSuivi = RendezVous::where('medecin_id', $user->id)
                ->where('patient_id', $patientId)
                ->exists();
            if (!$isSuivi) {
                return redirect()->back()->with('error', 'Ce patient ne fait pas partie de vos suivis.');
            }

            $patient = User::findOrFail($patientId);
            $dossier = DossierMedical::where('patient_id', $patientId)->first();

            return view('dashMedecin.gestionPatient.edit', compact('patient', 'dossier'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors du chargement du patient.');
        }
    }

    /**
     * Met à jour un patient
     */
    public function update(Request $request, $patientId)
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            if (!$user->isMedecin()) {
                return redirect()->route('medecin.dashboard')->with('error', 'Profil médecin non trouvé.');
            }

            // Vérifier que ce patient est bien suivi par ce médecin
            $isSuivi = RendezVous::where('medecin_id', $user->id)
                ->where('patient_id', $patientId)
                ->exists();
            if (!$isSuivi) {
                return redirect()->back()->with('error', 'Ce patient ne fait pas partie de vos suivis.');
            }

            $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $patientId,
                'dateNaissance' => 'required|date|before:today',
                'tel' => 'required|string|max:20',
                'adresse' => 'required|string|max:500',
            ]);

            $patient = User::findOrFail($patientId);
            $patient->update($request->only(['nom', 'prenom', 'email', 'dateNaissance', 'tel', 'adresse']));

            return redirect()->route('medecin.patients.show', $patientId)->with('success', 'Patient mis à jour avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du patient: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour du patient: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Supprime un patient (soft delete)
     */
    public function destroy($patientId)
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            if (!$user->isMedecin()) {
                return response()->json(['success' => false, 'message' => 'Profil médecin non trouvé.'], 403);
            }

            // Vérifier que ce patient est bien suivi par ce médecin
            $isSuivi = RendezVous::where('medecin_id', $user->id)
                ->where('patient_id', $patientId)
                ->exists();
            if (!$isSuivi) {
                return response()->json(['success' => false, 'message' => 'Ce patient ne fait pas partie de vos suivis.'], 403);
            }

            $patient = User::findOrFail($patientId);
            $patient->delete(); // Soft delete si configuré

            return response()->json(['success' => true, 'message' => 'Patient supprimé avec succès.']);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du patient: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erreur lors de la suppression du patient.'], 500);
        }
    }
}
