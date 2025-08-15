<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use App\Models\Medecin;
use Carbon\Carbon;

class GestionPatientDashMedecinController extends Controller
{
    /**
     * Affiche la liste des patients pour le médecin connecté.
     */
    public function index()
    {
        try {
            // Récupérer le médecin connecté
            $user = Auth::user();
            $medecin = $user->medecin;

            if (!$medecin) {
                return redirect()->route('dashboard.medecin')->with('error', 'Profil médecin non trouvé.');
            }

            // Récupérer les IDs des patients uniques qui ont un rendez-vous avec ce médecin
            $patientIds = $medecin->rendezVous()->select('patient_id')->distinct()->pluck('patient_id');

            // Récupérer les patients avec leurs informations et la dernière date de visite
            $patients = Patient::with(['user', 'rendezVous' => function ($query) use ($medecin) {
                $query->where('medecin_id', $medecin->id)->latest('date_debut');
            }])
            ->whereIn('id', $patientIds)
            ->paginate(10); // Paginer les résultats

            // Calculer l'âge et formater la dernière visite
            $patients->getCollection()->transform(function ($patient) {
                $patient->age = $patient->date_naissance ? Carbon::parse($patient->date_naissance)->age : 'N/A';
                $patient->derniere_visite = $patient->rendezVous->first() ? Carbon::parse($patient->rendezVous->first()->date_debut)->translatedFormat('d F Y') : 'Aucune visite';
                return $patient;
            });

            return view('dashMedecin.gestionPatient.index', compact('patients'));

        } catch (\Exception $e) {
            // Log::error('Erreur lors du chargement de la liste des patients: ' . $e->getMessage());
            return redirect()->route('dashboard.medecin')->with('error', 'Une erreur est survenue lors du chargement des patients.');
        }
    }

    public function dossiersMedicaux()
    {
        try {
            $user = Auth::user();
            $medecin = $user->medecin;

            if (!$medecin) {
                return redirect()->route('dashboard.medecin')->with('error', 'Profil médecin non trouvé.');
            }

            // Récupérer les IDs des patients suivis par ce médecin
            $patientIds = $medecin->rendezVous()->select('patient_id')->distinct()->pluck('patient_id');

            // Récupérer les dossiers médicaux de ces patients
            $dossiers = \App\Models\DossierMedical::with('patient.user')
                ->whereIn('patient_id', $patientIds)
                ->paginate(10);

            return view('dashMedecin.gestionPatient.index', [
                'patients' => null,
                'dossiers' => $dossiers ?? collect([])
            ]);
        } catch (\Exception $e) {
            return redirect()->route('dashboard.medecin')->with('error', 'Erreur lors du chargement des dossiers médicaux.');
        }
    }

    public function showDossierMedical(Request $request)
    {
        $user = Auth::user();
        $medecin = $user->medecin;

        if (!$medecin) {
            return redirect()->route('dashboard.medecin')->with('error', 'Profil médecin non trouvé.');
        }

        $patientId = $request->query('patient_id');
        if (!$patientId) {
            return redirect()->back()->with('error', 'Aucun patient sélectionné.');
        }

        // Vérifier que ce patient est bien suivi par ce médecin
        $isSuivi = $medecin->rendezVous()->where('patient_id', $patientId)->exists();
        if (!$isSuivi) {
            return redirect()->back()->with('error', 'Ce patient ne fait pas partie de vos suivis.');
        }

        $patient = \App\Models\Patient::with(['user'])->findOrFail($patientId);
        $dossier = \App\Models\DossierMedical::where('patient_id', $patientId)->first();

        return view('dashMedecin.gestionPatient.dossierMedical', compact('patient', 'dossier'));
    }

    /**
     * Affiche les détails d'un dossier médical en format JSON pour le modal
     */
    public function showDossierMedicalJson($dossierId)
    {
        try {
            $user = Auth::user();
            $medecin = $user->medecin;

            if (!$medecin) {
                return response()->json(['error' => 'Profil médecin non trouvé.'], 403);
            }

            $dossier = \App\Models\DossierMedical::with(['patient.user'])
                ->whereHas('patient.rendezVous', function($query) use ($medecin) {
                    $query->where('medecin_id', $medecin->id);
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
            $user = Auth::user();
            $medecin = $user->medecin;

            if (!$medecin) {
                return response()->json(['success' => false, 'message' => 'Profil médecin non trouvé.'], 403);
            }

            $request->validate([
                'field' => 'required|string',
                'value' => 'required|string',
                'patient_id' => 'required|exists:patients,id'
            ]);

            $patientId = $request->input('patient_id');
            $field = $request->input('field');
            $value = $request->input('value');

            // Vérifier que ce patient est bien suivi par ce médecin
            $isSuivi = $medecin->rendezVous()->where('patient_id', $patientId)->exists();
            if (!$isSuivi) {
                return response()->json(['success' => false, 'message' => 'Ce patient ne fait pas partie de vos suivis.'], 403);
            }

            // Récupérer ou créer le dossier médical
            $dossier = \App\Models\DossierMedical::firstOrCreate(
                ['patient_id' => $patientId],
                [
                    'patient_id' => $patientId,
                    'tel' => '',
                    'adresse' => '',
                    'groupe_sanguin' => '',
                    'antecedents_medicaux' => '',
                    'allergies' => ''
                ]
            );

            // Mettre à jour le champ approprié
            switch ($field) {
                case 'date_naissance':
                    // Mettre à jour la date de naissance du patient
                    $patient = Patient::find($patientId);
                    $patient->update(['dateNaissance' => $value]);
                    break;
                case 'groupe_sanguin':
                    $dossier->update(['groupe_sanguin' => $value]);
                    break;
                case 'tel':
                    $dossier->update(['tel' => $value]);
                    break;
                case 'adresse':
                    $dossier->update(['adresse' => $value]);
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
}
