<?php

namespace App\Http\Controllers\Patient;

use App\Events\RendezVousCreate;
use App\Models\RendezVous;
use App\Events\RendezVousModifie;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use App\Services\ConsultationService;
use App\Notifications\RendezVousCreatedNotification;
use App\Notifications\RendezVousModifiedNotification;
use App\Notifications\RendezVousStatusChangedNotification;

class RendezVousController extends Controller
{
    protected $consultationService;

    public function __construct(ConsultationService $consultationService)
    {
        $this->consultationService = $consultationService;
    }

    /**
     * Affiche la page d'agenda avec les rendez-vous
     */
    public function index(Request $request)
    {
        try {
            /** @var User $user */
            $user = Auth::user();
            $medecin = $user->isMedecin();

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
            $rendezVous = RendezVous::where('medecin_id', $user->id)
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
            $patients = User::where('role', 'patient')->select('id', 'nom', 'prenom')->orderBy('nom')->get();

            return view('dashMedecin.AgendaRendezvous.index', compact(
                'rendezVous',
                'selectedDate',
                'view',
                'startDate',
                'endDate',
                'stats',
                'heuresTravail',
                'moisActuel',
                'joursCalendrier',
                'patients'
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
            /** @var User $user */
            $user = Auth::user();
            $medecin = $user->isMedecin();

            if (!$medecin) {
                return response()->json([
                    'error' => 'Profil médecin non trouvé pour cet utilisateur'
                ], 404);
            }

            $rendezVous = RendezVous::where('medecin_id', $user->id)
                ->with('patient:id,nom,prenom')
                ->get();

            $events = $rendezVous->map(function ($rdv) {
                // Configurer les dates avec le fuseau horaire de Tunis
                $dateDebut = Carbon::parse($rdv->date_debut)->timezone('Africa/Tunis');
                $dateFin = Carbon::parse($rdv->date_fin)->timezone('Africa/Tunis');

                $patientNom = $rdv->patient ? $rdv->patient->nom . ' ' . $rdv->patient->prenom : 'Patient inconnu';
                $title = $rdv->titre . ' - ' . $patientNom;

                // Déterminer les couleurs selon le type et le statut
                $backgroundColor = match ($rdv->type) {
                    'consultation' => '#4299e1',
                    'suivi' => '#48bb78',
                    'urgence' => '#f56565',
                    default => '#3788d8'
                };

                $borderColor = $backgroundColor;

                if ($rdv->statut === 'annulé') {
                    $backgroundColor = '#a0aec0';
                    $borderColor = '#718096';
                } elseif ($rdv->statut === 'en_attente') {
                    $backgroundColor = '#ecc94b';
                    $borderColor = '#d69e2e';
                }

                return [
                    'id' => $rdv->id,
                    'title' => $title,
                    'start' => $dateDebut->format('Y-m-d\TH:i:s'),
                    'end' => $dateFin->format('Y-m-d\TH:i:s'),
                    'backgroundColor' => $backgroundColor,
                    'borderColor' => $borderColor,
                    'textColor' => '#ffffff',
                    'extendedProps' => [
                        'patient_id' => $rdv->patient_id,
                        'patient_nom' => $patientNom,
                        'type' => $rdv->type,
                        'statut' => $rdv->statut,
                        'description' => $rdv->description,
                        'heure_debut' => $dateDebut->format('H:i'),
                        'heure_fin' => $dateFin->format('H:i')
                    ],
                    'editable' => $rdv->statut !== 'annulé',
                    'durationEditable' => $rdv->statut !== 'annulé'
                ];
            });

            return response()->json($events);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des événements: ' . $e->getMessage());
            return response()->json([
                'error' => 'Erreur lors de la récupération des événements'
            ], 500);
        }
    }
    /**
     * Enregistre un nouveau rendez-vous demandé par un patient.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function patientRendezVousStore(Request $request)
    {
        // Log pour le débogage
        Log::info('PATIENT_CREATE_RDV: payload reçu', $request->all());

        try {
            /** @var User $user */
            $user = Auth::user();
            $patient = $user->isPatient();

            if (!$patient) {
                return redirect()->route('dashboard')->with('error', 'Profil patient non trouvé');
            }

            // Validation robuste des données du formulaire
            Log::info('PATIENT_CREATE_RDV: début validation');
            $validated = $request->validate([
                'medecin_id' => 'required|exists:users,id',
                'date_rdv' => 'required|date|after_or_equal:today',
                'heure_debut' => 'required',
                'type' => 'required|in:consultation,suivi,urgent',
                'description' => 'required|string|min:10',
            ]);
            Log::info('PATIENT_CREATE_RDV: validation OK', $validated);

            // Construction des dates avec le bon fuseau horaire
            $dateDebut = Carbon::createFromFormat('Y-m-d H:i', $validated['date_rdv'] . ' ' . $validated['heure_debut'], 'Africa/Tunis');
            $dateFin = (clone $dateDebut)->addMinutes(30);
            Log::info('PATIENT_CREATE_RDV: dates construites', [
                'date_debut_tunis' => $dateDebut->format('Y-m-d H:i:s'),
                'date_fin_tunis' => $dateFin->format('Y-m-d H:i:s')
            ]);

            // Création du rendez-vous en utilisant la méthode `create`
            $rdv = RendezVous::create([
                'patient_id' => $user->id,
                'medecin_id' => $validated['medecin_id'],
                'date_debut' => $dateDebut,
                'date_fin' => $dateFin,
                'type' => $validated['type'],
                'description' => $validated['description'],
                // DB enum utilise l'anglais; 'pending' ~ 'en_attente'
                'statut' => 'pending',
            ]);
            Log::info('PATIENT_CREATE_RDV: RDV créé', $rdv->only(['id', 'patient_id', 'medecin_id', 'date_debut', 'date_fin', 'type', 'statut']));

            // Générer le lien de visioconférence Jitsi et le sauvegarder
            $lien = $this->consultationService->creerOuRecupererLien($rdv);
            Log::info('PATIENT_CREATE_RDV: lien visioconférence', ['rdv_id' => $rdv->id, 'lien' => $lien]);

            Log::info('Rendez-vous créé avec succès.', ['id' => $rdv->id]);

            try {
                $rdv->load('patient:id,nom,prenom', 'medecin:id,nom,prenom');
                event(new RendezVousCreate($rdv));
                Log::info("✅ Event RendezVousCreate lancé avec ID " . $rdv->id);

                // Envoyer une notification au médecin
                $medecin = User::find($rdv->medecin_id);
                if ($medecin) {
                    $medecin->notify(new RendezVousCreatedNotification($rdv));
                    Log::info("✅ Notification RendezVousCreatedNotification envoyée au médecin ID " . $medecin->id);
                }
            } catch (\Exception $e) {
                Log::error("❌ Erreur lors de la publication de l'événement RendezVousCreate: " . $e->getMessage());
            }
            // Redirection vers la liste des rendez-vous avec un message de succès
            Log::info('PATIENT_CREATE_RDV: redirection vers patient.rendez-vous.index');
            return redirect()->route('patient.rendez-vous.index')
                ->with('success', 'Votre demande de rendez-vous a été envoyée avec succès !');
        } catch (ValidationException $e) {
            // En cas d'erreur de validation, on logue et on redirige avec les erreurs
            Log::error('PATIENT_CREATE_RDV: erreur de validation', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            // Pour toute autre erreur, on logue et on affiche un message générique
            Log::error('PATIENT_CREATE_RDV: exception générale', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur technique est survenue. Veuillez réessayer plus tard.');
        }
    }
    /**
     * Enregistre un nouveau rendez-vous
     */
    public function store(Request $request)
    {
        // Logging pour le débogage
        Log::info('Données de la requête pour la création de rendez-vous', $request->all());

        try {
            // Vérifier si la requête vient du dashboard médecin ou patient
            /** @var User $user */
            $user = Auth::user();
            $isPatient = $user->isPatient();
            $isMedecin = $user->isMedecin();

            if ($isPatient) {
                // Validation pour le dashboard patient
                $validated = $request->validate([
                    'medecin_id' => 'required|exists:users,id',
                    'date_rdv' => 'required|date|after_or_equal:today',
                    'heure_debut' => 'required',
                    'description' => 'required|string|min:10',
                    'type' => 'required|in:consultation,suivi,urgent',
                ]);

                // Récupérer le patient connecté
                $patient = $user->isPatient();
                if (!$patient) {
                    throw new \Exception('Profil patient non trouvé');
                }

                // Créer la date de début avec le fuseau horaire de Tunis
                $dateDebut = Carbon::createFromFormat(
                    'Y-m-d H:i',
                    $validated['date_rdv'] . ' ' . $validated['heure_debut'],
                    'Africa/Tunis'
                );

                // Calculer la date de fin (ajouter 30 minutes par défaut)
                $dateFin = (clone $dateDebut)->addMinutes(30);

                // Créer le rendez-vous
                $rendezVous = RendezVous::create([
                    'medecin_id' => $validated['medecin_id'],
                    'patient_id' => $user->id,
                    'date_debut' => $dateDebut,
                    'date_fin' => $dateFin,
                    'description' => $validated['description'],
                    'type' => $validated['type'],
                    'statut' => 'en_attente'
                ]);

                // Générer le lien de consultation après la création
                $lienConsultation = $this->consultationService->creerOuRecupererLien($rendezVous);
            } else if ($isMedecin) {
                // Validation pour le dashboard médecin
                $validated = $request->validate([
                    'patient_id' => 'required|exists:users,id',
                    'titre' => 'required|string|max:255',
                    'date_debut' => 'required|date',
                    'heure_debut' => 'required',
                    'duree' => 'required|integer|min:15',
                    'type' => 'required|string',
                    'statut' => 'required|string',
                    'description' => 'nullable|string',
                ]);

                // Convertir explicitement la durée en entier
                $duree = (int) $validated['duree'];
                // Récupérer l'ID du médecin connecté
                $medecin = $user->isMedecin();
                if (!$medecin) {
                    throw new \Exception('Profil médecin non trouvé pour cet utilisateur.');
                }

                // Création de l'objet DateTime pour la date de début
                $dateDebut = Carbon::createFromFormat(
                    'Y-m-d H:i',
                    $validated['date_debut'] . ' ' . $validated['heure_debut'],
                    'Africa/Tunis'
                );

                // Calcul de la date de fin avec la durée explicitement convertie en entier
                $dateFin = (clone $dateDebut)->addMinutes($duree);

                // Création du rendez-vous
                $rendezVous = RendezVous::create([
                    'medecin_id' => $user->id,
                    'patient_id' => $validated['patient_id'],
                    'titre' => $validated['titre'],
                    'date_debut' => $dateDebut,
                    'date_fin' => $dateFin,
                    'type' => $validated['type'],
                    'statut' => $validated['statut'],
                    'description' => $validated['description'] ?? null,
                ]);
            } else {
                throw new \Exception('Profil utilisateur non autorisé');
            }

            // Diffuser l'événement pour les mises à jour en temps réel
            broadcast(new RendezVousModifie($rendezVous, 'created'));

            // Réponse JSON si la requête attend du JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Rendez-vous créé avec succès',
                    'rendezvous' => $rendezVous
                ]);
            }

            // Redirection en fonction du type d'utilisateur
            if ($isPatient) {
                return redirect()->route('patient.rendez-vous.index')
                    ->with('success', 'Votre rendez-vous a été créé avec succès et est en attente de confirmation par le médecin.');
            } else {
                return redirect()->route('medecin.agenda')
                    ->with('success', 'Le rendez-vous a été créé avec succès');
            }
        } catch (ValidationException $e) {
            Log::error('Erreur de validation lors de la création de rendez-vous', [
                'errors' => $e->errors()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Erreur de validation',
                    'messages' => $e->errors()
                ], 422);
            }

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Exception lors de la création de rendez-vous', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Une erreur est survenue lors de la création du rendez-vous',
                    'message' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue lors de la création du rendez-vous: ' . $e->getMessage()]);
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
            /** @var User $user */
            $user = Auth::user();
            $medecin = $user->isMedecin();

            if (!$medecin || $rendezVous->medecin_id !== $user->id) {
                return response()->json([
                    'message' => 'Vous n\'êtes pas autorisé à modifier ce rendez-vous'
                ], 403);
            }

            // Si la requête vient d'un drag & drop ou resize dans le calendrier
            if ($request->has('start') && $request->has('end')) {
                // Corriger le décalage horaire en utilisant le fuseau horaire de Tunis explicitement
                // Lors du drag & drop, FullCalendar envoie des dates UTC ou dans le fuseau horaire local du navigateur
                $start = Carbon::parse($request->input('start'))->timezone('Africa/Tunis');
                $end = Carbon::parse($request->input('end'))->timezone('Africa/Tunis');

                // Convertir en UTC pour le stockage en base de données
                $rendezVous->date_debut = $start->setTimezone('UTC');
                $rendezVous->date_fin = $end->setTimezone('UTC');

                Log::info('Mise à jour de rendez-vous par drag & drop', [
                    'id' => $rendezVous->id,
                    'date_debut_reçue' => $request->input('start'),
                    'date_fin_reçue' => $request->input('end'),
                    'date_debut_parsée' => $start->format('Y-m-d H:i:s'),
                    'date_fin_parsée' => $end->format('Y-m-d H:i:s'),
                    'date_debut_UTC' => $rendezVous->date_debut,
                    'date_fin_UTC' => $rendezVous->date_fin
                ]);

                // Préparer les changements pour notification (drag & drop)
                $changes = [
                    'date_debut' => [
                        'old' => $rendezVous->getOriginal('date_debut'),
                        'new' => $rendezVous->date_debut,
                    ],
                    'date_fin' => [
                        'old' => $rendezVous->getOriginal('date_fin'),
                        'new' => $rendezVous->date_fin,
                    ],
                ];

                $rendezVous->save();

                // Diffuser l'événement
                broadcast(new RendezVousModifie($rendezVous, 'updated', $changes))->toOthers();

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

                // Calculer les changements
                $changes = [
                    'patient_id' => [ 'old' => $rendezVous->patient_id, 'new' => $validated['patient_id'] ],
                    'titre' => [ 'old' => $rendezVous->titre ?? null, 'new' => $validated['titre'] ],
                    'date_debut' => [ 'old' => $rendezVous->date_debut, 'new' => $dateDebut ],
                    'date_fin' => [ 'old' => $rendezVous->date_fin, 'new' => $dateFin ],
                    'type' => [ 'old' => $rendezVous->type, 'new' => $validated['type'] ],
                    'statut' => [ 'old' => $rendezVous->statut, 'new' => $validated['statut'] ],
                    'description' => [ 'old' => $rendezVous->description ?? null, 'new' => $validated['description'] ?? null ],
                ];

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
                broadcast(new RendezVousModifie($rendezVous, 'updated', $changes))->toOthers();

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
            /** @var User $user */
            $user = Auth::user();
            $medecin = $user->isMedecin();

            if (!$medecin || $rendezVous->medecin_id !== $user->id) {
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

        $patients = User::where('role', 'patient')->where('nom', 'LIKE', "%{$search}%")
            ->orWhere('prenom', 'LIKE', "%{$search}%")
            ->select('id', 'nom', 'prenom')
            ->limit(10)
            ->get()
            ->map(function ($patient) {
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
            /** @var User $user */
            $user = Auth::user();
            $medecin = $user->isMedecin();

            if (!$medecin || $rendezVous->medecin_id !== $user->id) {
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

    /**
     * Récupère les médecins par spécialité
     */
    public function getMedecinsBySpecialite(Request $request)
    {
        try {
            $specialite = $request->query('specialite');

            if (!$specialite) {
                return response()->json([
                    'error' => 'La spécialité est requise'
                ], 400);
            }

            $medecins = User::where('role', 'medecin')
                ->where('specialite', $specialite)
                // Priorité 1: médecins avec des avis en premier, ceux avec 0 avis en bas
                ->orderByRaw('CASE WHEN COALESCE(nbrAvis, 0) = 0 THEN 1 ELSE 0 END ASC')
                // Priorité 2: score décroissant (null traité comme 0)
                ->orderByRaw('COALESCE(score, 0) DESC')
                // Priorité 3: nombre d'avis décroissant pour départager les égalités de score
                ->orderBy('nbrAvis', 'DESC')
                ->get()
                ->map(function ($medecin) {
                    return [
                        'id' => $medecin->id,
                        'nom' => $medecin->nom,
                        'prenom' => $medecin->prenom,
                        'specialite' => $medecin->specialite,
                        'adresse_cabinet' => $medecin->adresse_cabinet,
                        'experience' => $medecin->experience,
                        'langues' => $medecin->langues_array,
                        'score' => $medecin->score ?? 0,
                        'nbrAvis' => $medecin->nbrAvis ?? 0,
                        'profile_photo_url' => $medecin->user ? $medecin->profile_photo_url : null,
                        'formation' => $medecin->formation,
                    ];
                });

            return response()->json([
                'medecins' => $medecins,
                'count' => $medecins->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des médecins: ' . $e->getMessage());
            return response()->json([
                'error' => 'Erreur lors de la récupération des médecins'
            ], 500);
        }
    }

    public function getCreneauxDisponibles(Request $request, User $medecin)
    {
        try {
            // Valider la date
            $request->validate([
                'date' => 'required|date|after_or_equal:today',
            ]);

            $selectedDate = Carbon::parse($request->date);

            // Vérifier si c'est un jour de travail (lundi-vendredi)
            if ($selectedDate->isWeekend()) {
                return response()->json([
                    'error' => 'Les rendez-vous ne sont pas disponibles le weekend'
                ], 400);
            }

            // Définir les heures de travail (8h-18h)
            $startWorkHour = 8;
            $endWorkHour = 18;
            $creneauDuration = 30; // durée en minutes

            // Récupérer les rendez-vous existants pour cette date
            $rendezVousExistants = RendezVous::where('medecin_id', $medecin->id)
                ->whereDate('date_debut', $selectedDate)
                ->where('statut', '!=', 'annulé')
                ->get();

            // Générer tous les créneaux possibles
            $creneauxDisponibles = [];
            $currentTime = $selectedDate->copy()->setHour($startWorkHour)->setMinute(0);
            $endTime = $selectedDate->copy()->setHour($endWorkHour)->setMinute(0);

            while ($currentTime < $endTime) {
                $creneauEnd = $currentTime->copy()->addMinutes($creneauDuration);

                // Vérifier si le créneau est déjà pris
                $estDisponible = true;
                foreach ($rendezVousExistants as $rdv) {
                    $rdvDebut = Carbon::parse($rdv->date_debut);
                    $rdvFin = Carbon::parse($rdv->date_fin);

                    if (
                        $currentTime->between($rdvDebut, $rdvFin) ||
                        $creneauEnd->between($rdvDebut, $rdvFin) ||
                        ($currentTime <= $rdvDebut && $creneauEnd >= $rdvFin)
                    ) {
                        $estDisponible = false;
                        break;
                    }
                }

                // Si le créneau est dans le futur et disponible
                if ($estDisponible && $currentTime > now()) {
                    $creneauxDisponibles[] = [
                        'heure_debut' => $currentTime->format('H:i'),
                        'heure_fin' => $creneauEnd->format('H:i'),
                        'timestamp_debut' => $currentTime->timestamp,
                        'timestamp_fin' => $creneauEnd->timestamp
                    ];
                }

                $currentTime->addMinutes($creneauDuration);
            }

            return response()->json([
                'date' => $selectedDate->format('Y-m-d'),
                'creneaux_disponibles' => $creneauxDisponibles
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des créneaux disponibles: ' . $e->getMessage());
            return response()->json([
                'error' => 'Erreur lors de la récupération des créneaux disponibles'
            ], 500);
        }
    }

    public function indexPatient()
    {
        try {
            /** @var User $user */
            $user = Auth::user();
            $patient = $user->isPatient();
            //       dd($patient);

            if (!$patient) {
                return redirect()->route('dashboard')->with('error', 'Profil patient non trouvé');
            }

            // Créer le service de consultation
            $consultationService = app(\App\Services\ConsultationService::class);

            // Récupérer les prochains rendez-vous (confirmed et payed)
            $prochainsRendezVous = RendezVous::with('medecin')
                ->where('patient_id', $user->id)
                ->whereIn('statut', ['confirmed', 'payed'])
                ->orderBy('date_debut', 'asc')
                ->get();

            // DEBUG: Log des rendez-vous récupérés
            Log::info('=== RENDEZ-VOUS RÉCUPÉRÉS DANS LE CONTRÔLEUR ===');
            Log::info('Nombre total de rendez-vous récupérés: ' . $prochainsRendezVous->count());
            foreach($prochainsRendezVous as $rdv) {
                Log::info("RDV ID: {$rdv->id} | Statut: '{$rdv->statut}' | Date début: {$rdv->date_debut} | Date fin: {$rdv->date_fin}");
            }

            // Générer les liens de consultation pour chaque rendez-vous
            foreach ($prochainsRendezVous as $rdv) {
                // Générer le lien s'il n'existe pas déjà
                if (!$rdv->lien_en_ligne) {
                    $lien = $consultationService->creerOuRecupererLien($rdv);
                    $rdv->lien_en_ligne = $lien;
                }
            }

            // Récupérer les rendez-vous en attente
            $rendezVousEnAttente = RendezVous::with('medecin')
                ->where('patient_id', $user->id)
                ->where('date_debut', '>=', now())
                ->where('statut', 'pending')
                ->orderBy('date_debut', 'asc')
                ->get();

            // Récupérer l'historique des rendez-vous
            // L'historique contient tous les rendez-vous dont la date de fin a dépassé la date système
            $historiqueRendezVous = RendezVous::with('medecin')
                ->where('patient_id', $user->id)
                ->where(function ($query) {
                    $query->whereRaw('COALESCE(date_fin, DATE_ADD(date_debut, INTERVAL 30 MINUTE)) < ?', [now()])
                        ->orWhere('statut', 'cancelled');
                })
                ->orderBy('date_debut', 'desc')
                ->paginate(5);

            return view('dashPatient.RendezVous.index', compact(
                'prochainsRendezVous',
                'rendezVousEnAttente',
                'historiqueRendezVous'
            ));
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des rendez-vous: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de la récupération des rendez-vous');
        }
    }

    public function cancelRendezVous(RendezVous $rendezVous)
    {
        try {
            /** @var User $user */
            $user = Auth::user();
            $patient = $user->isPatient();

            if (!$patient || $rendezVous->patient_id !== $user->id) {
                return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à annuler ce rendez-vous');
            }

            // Patient peut annuler seulement si pending ou confirmed
            if (!in_array($rendezVous->statut, ['pending','confirmed'], true)) {
                return redirect()->back()->with('error', 'Ce rendez-vous ne peut plus être annulé.');
            }
            $old = $rendezVous->statut;
            $rendezVous->transitionTo('cancelled');

            // Notifier le médecin de l'annulation
            broadcast(new RendezVousModifie($rendezVous, 'updated'))->toOthers();

            // Notifier le médecin via système de notifications persistantes
            try {
                $medecin = $rendezVous->medecin;
                if ($medecin) {
                    $medecin->notify(new \App\Notifications\RendezVousStatusChangedNotification($rendezVous, $old, 'cancelled'));
                }
            } catch (\Throwable $e) {
                Log::warning('Notification annulation médecin échouée', ['error' => $e->getMessage()]);
            }

            return redirect()->route('patient.rendez-vous.index')
                ->with('success', 'Le rendez-vous a été annulé avec succès');
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'annulation du rendez-vous: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de l\'annulation du rendez-vous');
        }
    }

    public function getMedecinDetails(User $medecin)
    {
        try {
            return response()->json([
                'id' => $medecin->id,
                'nom' => $medecin->nom,
                'prenom' => $medecin->prenom,
                'specialite' => $medecin->specialite,
                'adresse_cabinet' => $medecin->adresse_cabinet,
                'experience' => $medecin->experience,
                'langues' => $medecin->langues_array,
                'score' => $medecin->score ?? 0,
                'nbrAvis' => $medecin->nbrAvis ?? 0,
                'user' => $medecin->user ? [
                    'profile_photo_path' => $medecin->user->profile_photo_path,
                ] : null,
                'formation' => $medecin->formation
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des détails du médecin: ' . $e->getMessage());
            return response()->json([
                'error' => 'Erreur lors de la récupération des détails du médecin'
            ], 500);
        }
    }

    /**
     * Récupère les sections HTML du dashboard patient pour les mises à jour AJAX
     */
    public function getPatientDashboardSections()
    {
        try {
            /** @var User $user */
            $user = Auth::user();
            $patient = $user->isPatient();

            if (!$patient) {
                return response()->json([
                    'error' => 'Profil patient non trouvé'
                ], 404);
            }

            // Récupérer le prochain rendez-vous
            $prochainRendezVous = RendezVous::with('medecin')
                ->where('patient_id', $user->id)
                ->where('date_debut', '>=', now())
                ->where('statut', '!=', 'annulé')
                ->orderBy('date_debut', 'asc')
                ->first();

            // Récupérer les rendez-vous en attente
            $rendezVousEnAttente = RendezVous::with('medecin')
                ->where('patient_id', $user->id)
                ->where('date_debut', '>=', now())
                ->where('statut', 'en_attente')
                ->orderBy('date_debut', 'asc')
                ->get();

            // Récupérer l'historique des rendez-vous
            $historiqueRendezVous = RendezVous::with('medecin')
                ->where('patient_id', $user->id)
                ->where(function ($query) {
                    $query->where('date_debut', '<', now())
                        ->orWhere('statut', 'annulé');
                })
                ->orderBy('date_debut', 'desc')
                ->paginate(5);

            // Générer le HTML pour chaque section
            $prochainRdvHtml = view('dashPatient.RendezVous._prochain_rdv', compact('prochainRendezVous'))->render();
            $rdvEnAttenteHtml = view('dashPatient.RendezVous._rdv_en_attente', compact('rendezVousEnAttente'))->render();
            $historiqueRdvHtml = view('dashPatient.RendezVous._historique_rdv', compact('historiqueRendezVous'))->render();

            return response()->json([
                'prochainRdv' => '<div class="mb-8" id="prochain-rdv">' . $prochainRdvHtml . '</div>',
                'rdvEnAttente' => '<div class="mb-8" id="rdv-en-attente">' . $rdvEnAttenteHtml . '</div>',
                'historiqueRdv' => '<div id="historique-rdv">' . $historiqueRdvHtml . '</div>'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des sections: ' . $e->getMessage());
            return response()->json([
                'error' => 'Erreur lors de la récupération des sections de rendez-vous: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Affiche le formulaire de création de rendez-vous pour le patient
     */
    public function patientRendezVousCreate()
    {
        try {
            /** @var User $user */
            $user = Auth::user();
            $patient = $user->isPatient();

            if (!$patient) {
                return redirect()->route('dashboard')->with('error', 'Profil patient non trouvé');
            }

            return view('dashPatient.RendezVous.create');
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'affichage du formulaire de création: ' . $e->getMessage());
            return redirect()->route('patient.rendez-vous.index')->with('error', 'Erreur lors du chargement du formulaire');
        }
    }

    /**
     * Crée le lien de consultation pour un rendez-vous
     */
    public function creerLienConsultation(RendezVous $rendezVous)
    {
        try {
            // Vérifier que l'utilisateur est autorisé
            /** @var User $user */
            $user = Auth::user();
            $medecin = $user->isMedecin();

            if (!$medecin || $rendezVous->medecin_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'êtes pas autorisé à créer un lien pour ce rendez-vous'
                ], 403);
            }

            // Vérifier que le rendez-vous est payé
            if ($rendezVous->statut !== 'payed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Le rendez-vous doit être payé pour créer un lien de consultation'
                ], 400);
            }

            // Vérifier que la consultation peut commencer
            if (!$this->consultationService->consultationPeutCommencer($rendezVous)) {
                return response()->json([
                    'success' => false,
                    'message' => 'La consultation ne peut pas encore commencer'
                ], 400);
            }

            // Créer ou récupérer le lien de consultation
            $lienConsultation = $this->consultationService->creerOuRecupererLien($rendezVous);

            return response()->json([
                'success' => true,
                'message' => 'Lien de consultation créé avec succès',
                'lien_consultation' => $lienConsultation,
                'consultation_en_cours' => $this->consultationService->consultationEnCours($rendezVous)
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du lien de consultation: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du lien de consultation'
            ], 500);
        }
    }

    public function confirmationPayment(RendezVous $rendezVous)
    {
        return view('dashPatient.RendezVous.confirmationPayment', compact('rendezVous'));
    }
}
