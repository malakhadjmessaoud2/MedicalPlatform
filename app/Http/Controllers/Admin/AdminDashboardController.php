<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\RendezVous;
use App\Models\Consultation;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Statistiques générales
        $stats = [
            'total_medecins' => User::where('role', 'medecin')->count(),
            'total_patients' => User::where('role', 'patient')->count(),
            'total_pharmacies' => User::where('role', 'operateurpharmacie')->count(),
            'total_donateurs' => User::where('role', 'donateur')->count(),
            'total_rendez_vous' => RendezVous::count(),
            'rendez_vous_aujourd_hui' => RendezVous::whereDate('date_debut', Carbon::today())->count(),
            'consultations_aujourd_hui' => Consultation::whereDate('created_at', Carbon::today())->count(),
            'revenus_aujourd_hui' => Paiement::whereDate('created_at', Carbon::today())->sum('amount'),
            'revenus_mois' => Paiement::whereMonth('created_at', Carbon::now()->month)->sum('amount'),
        ];

        // Rendez-vous récents
        $rendez_vous_recents = RendezVous::with(['medecin', 'patient'])
            ->orderBy('date_debut', 'desc')
            ->limit(10)
            ->get();

        // Médecins les plus actifs
        $medecins_actifs = User::where('role', 'medecin')
            ->withCount('rendezVous')
            ->orderBy('rendez_vous_count', 'desc')
            ->limit(5)
            ->get();

        // Évolution des rendez-vous sur 7 derniers jours
        $evolution_rendez_vous = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $evolution_rendez_vous[] = [
                'date' => $date->format('d/m'),
                'count' => RendezVous::whereDate('date_debut', $date)->count()
            ];
        }

        return view('dashAdmin.dashboard', compact('stats', 'rendez_vous_recents', 'medecins_actifs', 'evolution_rendez_vous'));
    }

    public function statistiques()
    {
        // Statistiques détaillées pour la page dédiée
        $stats_detaillees = [
            'medecins_par_specialite' => User::where('role', 'medecin')
                ->selectRaw('specialite, COUNT(*) as count')
                ->groupBy('specialite')
                ->get(),

            'rendez_vous_par_statut' => RendezVous::selectRaw('statut, COUNT(*) as count')
                ->groupBy('statut')
                ->get(),

            'revenus_par_mois' => Paiement::selectRaw('MONTH(created_at) as mois, SUM(amount) as total')
                ->whereYear('created_at', Carbon::now()->year)
                ->groupBy('mois')
                ->orderBy('mois')
                ->get(),
        ];

        return view('dashAdmin.statistiques', compact('stats_detaillees'));
    }

    public function utilisateurs()
    {
        $utilisateurs = User::withCount(['rendezVous', 'consultations'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('dashAdmin.utilisateurs', compact('utilisateurs'));
    }

    public function rapports()
    {
        return view('dashAdmin.rapports');
    }
}
