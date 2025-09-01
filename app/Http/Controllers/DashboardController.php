<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use App\Models\Medecin;
use App\Models\DossierMedical;
use App\Models\RendezVous;
use App\Models\User;
use App\Services\ConsultationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    protected $consultationService;

    public function __construct(ConsultationService $consultationService)
    {
        $this->consultationService = $consultationService;
    }

    // Affiche le dashboard principal du médecin
    public function index()
    {
        $user = Auth::user();
        if (!$user->medecin) {
            return redirect()->route('dashboard')->with('error', 'Accès non autorisé. Vous devez être un médecin.');
        }
        $medecin = $user->medecin;

        // Statistiques principales
        $consultationsAujourdhui = RendezVous::where('medecin_id', $medecin->id)
            ->whereDate('date_debut', Carbon::today())
            ->count();
        $consultationsSemaine = RendezVous::where('medecin_id', $medecin->id)
            ->whereBetween('date_debut', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->count();
        $consultationsMois = RendezVous::where('medecin_id', $medecin->id)
            ->whereBetween('date_debut', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->count();
        $totalPatients = User::whereHas('rendezVous', function($query) use ($medecin) {
            $query->where('medecin_id', $medecin->id);
        })->count();
        $nouveauxPatients = User::whereHas('rendezVous', function($query) use ($medecin) {
            $query->where('medecin_id', $medecin->id)
                ->whereMonth('date_debut', Carbon::now()->month);
        })->count();
        $rdvEnAttente = RendezVous::where('medecin_id', $medecin->id)
            ->where('statut', 'en_attente')
            ->count();

        // Récupérer tous les dossiers médicaux des patients du médecin connecté
        $dossiers = DossierMedical::with(['patient.user', 'patient'])
            ->whereHas('patient.rendezVous', function($query) use ($medecin) {
                $query->where('medecin_id', $medecin->id);
            })
            ->paginate(10);

        // On ne passe plus la liste des patients du jour ici
        return view('dashMedecin.index', compact(
            'medecin',
            'consultationsAujourdhui',
            'consultationsSemaine',
            'consultationsMois',
            'totalPatients',
            'nouveauxPatients',
            'rdvEnAttente',
            'dossiers'
        ));
    }

    // API : Récupérer tous les rendez-vous du jour pour le médecin connecté (avec infos patient)
    public function getRendezVousDuJour()
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->isMedecin()) {
            return response()->json(['error' => 'Accès non autorisé. Vous devez être un médecin.'], 403);
        }

     //   dd($user->id);

        $rendezVousDuJour = $this->consultationService->getRendezVousDuJour($user->id);
        return response()->json($rendezVousDuJour);
    }
}
