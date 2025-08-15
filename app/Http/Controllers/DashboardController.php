<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use App\Models\Medecin;
use App\Models\DossierMedical;
use App\Models\RendezVous;
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
        $totalPatients = Patient::whereHas('rendezVous', function($query) use ($medecin) {
            $query->where('medecin_id', $medecin->id);
        })->count();
        $nouveauxPatients = Patient::whereHas('rendezVous', function($query) use ($medecin) {
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
        $user = Auth::user();
        if (!$user->medecin) {
            return response()->json(['error' => 'Accès non autorisé. Vous devez être un médecin.'], 403);
        }
        $medecin = $user->medecin;

        $rendezVousDuJour = RendezVous::with(['patient.user'])
            ->where('medecin_id', $medecin->id)
            ->whereDate('date_debut', Carbon::today())
            ->orderBy('date_debut')
            ->get()
            ->map(function($rdv) {
                // Déterminer la photo de profil du patient
                $photo = 'https://randomuser.me/api/portraits/women/68.jpg';
                if ($rdv->patient) {
                    if ($rdv->patient->user && $rdv->patient->user->profile_photo_path) {
                        $photo = Storage::url($rdv->patient->user->profile_photo_path);
                    } elseif ($rdv->patient->photo) {
                        // Si le champ photo est déjà une URL absolue, l'utiliser, sinon le passer par Storage::url
                        $photo = filter_var($rdv->patient->photo, FILTER_VALIDATE_URL)
                            ? $rdv->patient->photo
                            : Storage::url($rdv->patient->photo);
                    }
                }

                // Vérifier si la consultation peut commencer (15 minutes avant l'heure prévue)
                $heureDebut = Carbon::parse($rdv->date_debut);
                $heureFin = Carbon::parse($rdv->date_fin ?? $rdv->date_debut->addMinutes(30));
                $maintenant = Carbon::now();

                $consultationActive = $this->consultationService->consultationPeutCommencer($rdv);
                $consultationEnCours = $this->consultationService->consultationEnCours($rdv);

                // Utiliser le service pour créer ou récupérer le lien
                $lienMeet = $this->consultationService->creerOuRecupererLien($rdv);

                return [
                    'id' => $rdv->id,
                    'nom' => $rdv->patient->nom ?? '',
                    'prenom' => $rdv->patient->prenom ?? '',
                    'photo' => $photo,
                    'heure' => Carbon::parse($rdv->date_debut)->format('H:i'),
                    'heure_fin' => $heureFin->format('H:i'),
                    'type' => $rdv->type ?? '',
                    'statut' => $rdv->statut ?? '',
                    'consultation_active' => $consultationActive,
                    'consultation_en_cours' => $consultationEnCours,
                    'lien_meet' => $lienMeet,
                    'date_debut' => $rdv->date_debut,
                    'date_fin' => $rdv->date_fin
                ];
            })->toArray();

        return response()->json($rendezVousDuJour);
    }
}
