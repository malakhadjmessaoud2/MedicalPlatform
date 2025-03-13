<?php

namespace App\Http\Controllers;

use App\Models\RendezVous;
use App\Events\RendezVousModifie;
use App\Models\Medecin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RendezVousController extends Controller
{
    /**
     * Affiche la page d'agenda avec les rendez-vous
     */
    public function index(Request $request)
    {
        try {
            // Récupérer l'utilisateur connecté et son médecin associé
            $user = Auth::user();
            $medecin = $user->medecin;

            if (!$medecin) {
                return redirect()->route('dashboard')->with('error', 'Profil médecin non trouvé');
            }

            // Récupérer la date sélectionnée ou utiliser aujourd'hui par défaut
            $selectedDate = $request->has('date')
                ? Carbon::parse($request->date)
                : Carbon::today();

            // Récupérer la vue sélectionnée (jour, semaine, mois)
            $view = $request->get('view', 'day');

            // Calculer les dates de début et de fin selon la vue
            $startDate = $selectedDate->copy();
            $endDate = $selectedDate->copy();

            if ($view == 'week') {
                $startDate = $selectedDate->copy()->startOfWeek(Carbon::MONDAY);
                $endDate = $startDate->copy()->addDays(6);
            } elseif ($view == 'month') {
                $startDate = $selectedDate->copy()->startOfMonth()->startOfWeek(Carbon::MONDAY);
                $endDate = $selectedDate->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);
            }

            // Récupérer tous les rendez-vous du médecin pour la période
            $rendezVous = RendezVous::where('medecin_id', $medecin->id)
                ->whereBetween('date_debut', [$startDate->startOfDay(), $endDate->endOfDay()])
                ->with('patient:id,nom,prenom')
                ->get();

            // Statistiques des rendez-vous
            $stats = [
                'total' => $rendezVous->count(),
                'confirmes' => $rendezVous->where('statut', 'confirmé')->count(),
                'en_attente' => $rendezVous->where('statut', 'en_attente')->count(),
                'annules' => $rendezVous->where('statut', 'annulé')->count(),
            ];

            // Heures de travail (pour les vues jour et semaine)
            $heuresTravail = [];
            for ($h = 8; $h <= 18; $h++) {
                $heuresTravail[] = sprintf('%02d:00', $h);
                if ($h < 18) {
                    $heuresTravail[] = sprintf('%02d:30', $h);
                }
            }

            // Préparer les données pour le mini-calendrier
            $moisActuel = $selectedDate->copy();
            $joursCalendrier = [];

            $debutMois = $moisActuel->copy()->startOfMonth()->startOfWeek(Carbon::MONDAY);
            $finMois = $moisActuel->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);

            $jourCourant = $debutMois->copy();

            while ($jourCourant <= $finMois) {
                $dateStr = $jourCourant->format('Y-m-d');
                $aRdv = $rendezVous->contains(function ($rdv) use ($jourCourant) {
                    return Carbon::parse($rdv->date_debut)->isSameDay($jourCourant);
                });

                $joursCalendrier[] = [
                    'jour' => $jourCourant->format('j'),
                    'date' => $dateStr,
                    'mois' => $jourCourant->month == $moisActuel->month ? 'actuel' : 'autre',
                    'aujourdhui' => $jourCourant->isToday(),
                    'a_rdv' => $aRdv
                ];

                $jourCourant->addDay();
            }

            // Récupérer la liste des patients pour le formulaire d'ajout de rendez-vous
            $patients = Patient::select('id', 'nom', 'prenom')->orderBy('nom')->get();

            return view('dashMedecin.AgendaRendezvous.index', compact(
                'rendezVous', 'selectedDate', 'view', 'startDate', 'endDate',
                'stats', 'heuresTravail', 'moisActuel', 'joursCalendrier', 'patients'
            ));
        } catch (\Exception $e) {
            Log::error('Erreur dans l\'affichage de l\'agenda: ' . $e->getMessage());
            return redirect()->route('dashboard.medecin')->with('error', 'Erreur lors du chargement de l\'agenda: ' . $e->getMessage());
        }
    }

    /**
     * Récupère les événements pour l'API (format JSON pour FullCalendar)
     */
    public function getEvenements()
    {
        try {
            // Récupérer l'utilisateur connecté
            $user = Auth::user();

            // Récupérer le médecin associé à l'utilisateur
            $medecin = $user->medecin;

            if (!$medecin) {
                return response()->json([
                    'error' => 'Profil médecin non trouvé pour cet utilisateur'
                ], 404);
            }

            // Récupérer tous les rendez-vous du médecin
            $rendezVous = RendezVous::where('medecin_id', $medecin->id)
                ->with('patient:id,nom,prenom')
                ->get();

            // Formater les rendez-vous pour FullCalendar
            $events = $rendezVous->map(function($rdv) {
                // Déterminer la couleur en fonction du type et du statut
                $backgroundColor = '#3788d8'; // Couleur par défaut (bleu)
                $borderColor = '#3788d8';
                $textColor = '#ffffff';

                // Couleurs selon le type
                if ($rdv->type === 'consultation') {
                    $backgroundColor = '#4299e1'; // Bleu
                    $borderColor = '#2b6cb0';
                } elseif ($rdv->type === 'suivi') {
                    $backgroundColor = '#48bb78'; // Vert
                    $borderColor = '#2f855a';
                } elseif ($rdv->type === 'urgence') {
                    $backgroundColor = '#f56565'; // Rouge
                    $borderColor = '#c53030';
                }

                // Modifier l'apparence selon le statut
                if ($rdv->statut === 'annulé') {
                    $backgroundColor = '#a0aec0'; // Gris
                    $borderColor = '#718096';
                } elseif ($rdv->statut === 'en_attente') {
                    $backgroundColor = '#ecc94b'; // Jaune
                    $borderColor = '#d69e2e';
                }

                // Construire le titre avec le nom du patient
                $patientNom = $rdv->patient ? $rdv->patient->nom . ' ' . $rdv->patient->prenom : 'Patient inconnu';
                $title = $rdv->titre . ' - ' . $patientNom;

                return [
                    'id' => $rdv->id,
                    'title' => $title,
                    'start' => $rdv->date_debut->toIso8601String(),
                    'end' => $rdv->date_fin->toIso8601String(),
                    'backgroundColor' => $backgroundColor,
                    'borderColor' => $borderColor,
                    'textColor' => $textColor,
                    'extendedProps' => [
                        'patient_id' => $rdv->patient_id,
                        'patient_nom' => $patientNom,
                        'type' => $rdv->type,
                        'statut' => $rdv->statut,
                        'description' => $rdv->description
                    ],
                    'editable' => $rdv->statut !== 'annulé', // Rendre non modifiable les RDV annulés
                    'durationEditable' => $rdv->statut !== 'annulé'
                ];
            });

            return response()->json($events);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des événements: ' . $e->getMessage());
            return response()->json([
                'error' => 'Erreur lors de la récupération des événements: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Enregistre un nouveau rendez-vous
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'titre' => 'required|string|max:255',
            'date_debut' => 'required|date',
            'heure_debut' => 'required|string',
            'duree' => 'required|integer|min:15',
            'type' => 'required|in:consultation,suivi,urgence',
            'statut' => 'required|in:confirmé,en_attente,annulé',
            'description' => 'nullable|string'
        ]);

        try {
            // Récupérer l'utilisateur connecté
            $user = Auth::user();

            // Récupérer le médecin associé à l'utilisateur
            $medecin = $user->medecin;

            if (!$medecin) {
                return redirect()->back()
                    ->with('error', 'Profil médecin non trouvé pour cet utilisateur')
                    ->withInput();
            }

            // Convertir la date et l'heure en objet Carbon
            $dateDebut = Carbon::parse($validated['date_debut'] . ' ' . $validated['heure_debut']);

            // Calculer la date de fin en ajoutant la durée (convertie en minutes)
            $dateFin = $dateDebut->copy()->addMinutes((int) $validated['duree']);

            // Créer le rendez-vous
            $rendezVous = RendezVous::create([
                'medecin_id' => $medecin->id, // Utiliser l'ID du modèle Medecin
                'patient_id' => $validated['patient_id'],
                'titre' => $validated['titre'],
                'date_debut' => $dateDebut,
                'date_fin' => $dateFin,
                'type' => $validated['type'],
                'statut' => $validated['statut'],
                'description' => $validated['description'] ?? null
            ]);

            // Charger la relation patient pour l'événement
            $rendezVous->load('patient:id,nom,prenom');

            // Diffuser l'événement
            broadcast(new RendezVousModifie($rendezVous, 'created'))->toOthers();

            return redirect()->route('medecin.agenda', [
                'date' => $dateDebut->format('Y-m-d'),
                'view' => 'day'
            ])->with('success', 'Rendez-vous créé avec succès');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du rendez-vous: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Erreur lors de la création du rendez-vous: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Affiche les détails d'un rendez-vous
     */
    public function show(RendezVous $rendezVous)
    {
        return response()->json($rendezVous->load('patient:id,nom'));
    }

    /**
     * Met à jour un rendez-vous existant
     */
    public function update(Request $request, RendezVous $rendezVous)
    {
        try {
            // Vérifier si le rendez-vous existe
            if (!$rendezVous) {
                return response()->json([
                    'message' => 'Rendez-vous non trouvé'
                ], 404);
            }

            // Vérifier si l'utilisateur est autorisé à modifier ce rendez-vous
            $user = Auth::user();
            $medecin = $user->medecin;

            if (!$medecin || $rendezVous->medecin_id !== $medecin->id) {
                return response()->json([
                    'message' => 'Vous n\'êtes pas autorisé à modifier ce rendez-vous'
                ], 403);
            }

            // Si la requête vient d'un drag & drop ou resize dans le calendrier
            if ($request->has('start') && $request->has('end')) {
                $start = Carbon::parse($request->input('start'));
                $end = Carbon::parse($request->input('end'));

                $rendezVous->date_debut = $start;
                $rendezVous->date_fin = $end;
                $rendezVous->save();

                // Diffuser l'événement
                broadcast(new RendezVousModifie($rendezVous, 'updated'))->toOthers();

                return response()->json([
                    'success' => true,
                    'message' => 'Rendez-vous mis à jour avec succès'
                ]);
            } else {
                // Sinon, c'est une mise à jour complète du formulaire
                $validated = $request->validate([
                    'patient_id' => 'required|exists:patients,id',
                    'date_debut' => 'required|date',
                    'heure_debut' => 'required|string',
                    'duree' => 'required|integer|min:15',
                    'type' => 'required|in:consultation,suivi,urgence',
                    'description' => 'nullable|string',
                    'titre' => 'required|string',
                    'statut' => 'required|in:confirmé,en_attente,annulé'
                ]);

                // Convertir la date et l'heure en objet Carbon
                $dateDebut = Carbon::parse($validated['date_debut'] . ' ' . $validated['heure_debut']);

                // Calculer la date de fin en ajoutant la durée (convertie en minutes)
                $dateFin = $dateDebut->copy()->addMinutes((int) $validated['duree']);

                // Mettre à jour le rendez-vous
                $rendezVous->update([
                    'patient_id' => $validated['patient_id'],
                    'titre' => $validated['titre'],
                    'date_debut' => $dateDebut,
                    'date_fin' => $dateFin,
                    'type' => $validated['type'],
                    'statut' => $validated['statut'],
                    'description' => $validated['description'] ?? null
                ]);

                // Diffuser l'événement
                broadcast(new RendezVousModifie($rendezVous, 'updated'))->toOthers();

                // Rediriger vers la page d'agenda avec la date du rendez-vous
                return redirect()->route('medecin.agenda', [
                    'date' => $dateDebut->format('Y-m-d'),
                    'view' => 'day'
                ])->with('success', 'Rendez-vous mis à jour avec succès');
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du rendez-vous: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Erreur lors de la mise à jour du rendez-vous: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour du rendez-vous: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Supprime un rendez-vous
     */
    public function destroy(RendezVous $rendezVous)
    {
        try {
            // Vérifier si l'utilisateur est autorisé à supprimer ce rendez-vous
            $user = Auth::user();
            $medecin = $user->medecin;

            if (!$medecin || $rendezVous->medecin_id !== $medecin->id) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'message' => 'Vous n\'êtes pas autorisé à supprimer ce rendez-vous'
                    ], 403);
                }

                return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à supprimer ce rendez-vous');
            }

            // Stocker la date avant suppression pour la redirection
            $dateRdv = Carbon::parse($rendezVous->date_debut)->format('Y-m-d');

            // Diffuser l'événement avant de supprimer
            broadcast(new RendezVousModifie($rendezVous, 'deleted'))->toOthers();

            // Supprimer le rendez-vous
            $rendezVous->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Rendez-vous supprimé avec succès'
                ]);
            }

            // Rediriger vers la page d'agenda avec la date du rendez-vous supprimé
            return redirect()->route('medecin.agenda', [
                'date' => $dateRdv,
                'view' => 'day'
            ])->with('success', 'Rendez-vous supprimé avec succès');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du rendez-vous: ' . $e->getMessage());

            if (request()->expectsJson()) {
                return response()->json([
                    'error' => 'Erreur lors de la suppression du rendez-vous: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Erreur lors de la suppression du rendez-vous: ' . $e->getMessage());
        }
    }

    /**
     * Récupère la liste des patients pour le select
     */
    public function getPatients(Request $request)
    {
        $search = $request->query('search', '');

        $patients = Patient::where('nom', 'LIKE', "%{$search}%")
            ->orWhere('prenom', 'LIKE', "%{$search}%")
            ->select('id', 'nom', 'prenom')
            ->limit(10)
            ->get()
            ->map(function($patient) {
                return [
                    'id' => $patient->id,
                    'text' => $patient->nom . ' ' . $patient->prenom
                ];
            });

        return response()->json($patients);
    }

    /**
     * Met à jour le statut d'un rendez-vous
     */
    public function updateStatus(Request $request, RendezVous $rendezVous)
    {
        try {
            $user = Auth::user();
            $medecin = $user->medecin;

            if (!$medecin || $rendezVous->medecin_id !== $medecin->id) {
                return response()->json([
                    'message' => 'Vous n\'êtes pas autorisé à modifier ce rendez-vous'
                ], 403);
            }

            $validated = $request->validate([
                'statut' => 'required|in:confirmé,en_attente,annulé'
            ]);

            $rendezVous->update([
                'statut' => $validated['statut']
            ]);

            broadcast(new RendezVousModifie($rendezVous, 'updated'))->toOthers();

            return response()->json([
                'success' => true,
                'message' => 'Statut du rendez-vous mis à jour avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du statut: ' . $e->getMessage());
            return response()->json([
                'error' => 'Erreur lors de la mise à jour du statut: ' . $e->getMessage()
            ], 500);
        }
    }
}
