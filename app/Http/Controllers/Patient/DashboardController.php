<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\RendezVous;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $patient = Auth::user();

        // Statistiques du patient
        $stats = [
            'total_rendez_vous' => RendezVous::where('patient_id', $patient->id)->count(),
            'rendez_vous_confirmes' => RendezVous::where('patient_id', $patient->id)
                ->where('statut', RendezVous::STATUS_CONFIRMED)->count(),
            'rendez_vous_payes' => RendezVous::where('patient_id', $patient->id)
                ->where('statut', RendezVous::STATUS_PAYED)->count(),
            'consultations_terminees' => RendezVous::where('patient_id', $patient->id)
                ->where('statut', RendezVous::STATUS_COMPLETED)->count(),
        ];

        // Rendez-vous récents du patient
        $rendez_vous_recents = RendezVous::where('patient_id', $patient->id)
            ->with(['medecin'])
            ->orderBy('date_debut', 'desc')
            ->limit(5)
            ->get();

        // Médecins disponibles (tous les médecins actifs pour filtrage côté client)
        $medecins = User::where('role', 'medecin')
            ->where('isActive', true)
            ->with(['rendezVousCommeMedecin' => function($query) {
                $query->where('statut', RendezVous::STATUS_CONFIRMED);
            }])
            ->orderBy('score', 'desc')
            ->get();

        // Prochains rendez-vous
        $prochains_rendez_vous = RendezVous::where('patient_id', $patient->id)
            ->where('date_debut', '>=', now())
            ->whereIn('statut', [RendezVous::STATUS_CONFIRMED, RendezVous::STATUS_PAYED])
            ->with(['medecin'])
            ->orderBy('date_debut', 'asc')
            ->limit(3)
            ->get();

        // Spécialités disponibles pour le filtre
        $specialites = User::where('role', 'medecin')
            ->where('isActive', true)
            ->whereNotNull('specialite')
            ->distinct()
            ->pluck('specialite')
            ->filter()
            ->sort()
            ->values();

        return view('dashPatient.index', compact(
            'stats',
            'rendez_vous_recents',
            'medecins',
            'prochains_rendez_vous',
            'specialites'
        ));
    }
    public function createRendezVous()
    {
        return view('dashPatient.RendezVous.create');
    }

    public function dossierPatient()
    {
        return view('dashPatient.dossier.index');
    }

    public function messages()
    {
        return view('dashPatient.messages.index');
    }

    public function achatMedicament()
    {
        return view('dashPatient.achatMedicament.index');
    }
    public function commandes()
    {
        return view('dashPatient.commandes.index');
    }

    public function dons()
    {
        return view('dashPatient.dons.index');
    }

}
