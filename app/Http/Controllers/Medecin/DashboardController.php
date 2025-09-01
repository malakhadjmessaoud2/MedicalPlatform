<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\RendezVous;
use App\Models\DossierMedical;
use App\Models\User;
use Carbon\Carbon;
use App\Services\ConsultationService;

class DashboardController extends Controller
{
    private ConsultationService $consultationService;

    public function __construct(ConsultationService $consultationService)
    {
        $this->consultationService = $consultationService;
    }

    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->isMedecin()) {
            return redirect()->route('dashboard')->with('error', 'Accès non autorisé. Vous devez être un médecin.');
        }

        // Statistiques principales
        $consultationsAujourdhui = RendezVous::where('medecin_id', $user->id)
            ->whereDate('date_debut', Carbon::today())
            ->count();
        $consultationsSemaine = RendezVous::where('medecin_id', $user->id)
            ->whereBetween('date_debut', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->count();

        $consultationsMois = RendezVous::where('medecin_id', $user->id)
            ->whereBetween('date_debut', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->count();

        // Get unique patients who have appointments with this doctor
        $totalPatients = RendezVous::where('medecin_id', $user->id)
            ->select('patient_id')
            ->distinct('patient_id')
            ->count('patient_id');

        $nouveauxPatients = RendezVous::where('medecin_id', $user->id)
            ->whereBetween('date_debut', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->select('patient_id')
            ->distinct('patient_id')
            ->count('patient_id');
            
        $rdvEnAttente = RendezVous::where('medecin_id', $user->id)
            ->where('statut', 'pending')
            ->count();

        // Récupérer tous les dossiers médicaux des patients du médecin connecté
        $dossiers = DossierMedical::with(['patient'])
                ->whereIn('patient_id', function($query) use ($user) {
                    $query->select('patient_id')
                          ->from('rendez_vous')
                          ->where('medecin_id', $user->id)
                          ->distinct();
                })
                ->paginate(10);

        // On ne passe plus la liste des patients du jour ici
        return view('dashMedecin.index', compact(
            'user',
            'consultationsAujourdhui',
            'consultationsSemaine',
            'consultationsMois',
            'totalPatients',
            'nouveauxPatients',
            'rdvEnAttente',
            'dossiers'
        ));
    }
    public function SuiviTraitements()
    {
        return view('dashMedecin.SuiviTraitements.index');
    }
    public function Prestations()
    {
        return view('dashMedecin.gestionPrestations.index');
    }
    public function communication()
    {
        return view('dashMedecin.communication.index');
    }
    // API : Récupérer tous les rendez-vous du jour pour le médecin connecté (avec infos patient)
    public function getRendezVousDuJour()
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->isMedecin()) {
            return response()->json(['error' => 'Accès non autorisé. Vous devez être un médecin.'], 403);
        }

        $rendezVousDuJour = $this->consultationService->getRendezVousDuJour($user->id);
        return response()->json($rendezVousDuJour);
    }
}
