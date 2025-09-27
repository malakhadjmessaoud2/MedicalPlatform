<?php

namespace App\Services;

use App\Models\RendezVous;
use App\Models\Consultation;
use App\Models\DossierMedical;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ConsultationService
{
    /**
     * Retourne un lien de visioconférence pour un rendez-vous en utilisant Jitsi Meet.
     */
    public function genererLienConsultation(RendezVous $rendezVous): string
    {
        return $this->genererLienJitsi($rendezVous);
    }

    /**
     * Génère un lien Jitsi fonctionnel basé sur l'ID du rendez-vous
     * Format: https://meet.jit.si/rdv-{id}-{hash}
     */
    private function genererLienJitsi(RendezVous $rendezVous): string
    {
        $base = rtrim(env('JITSI_BASE_URL', 'https://meet.jit.si'), '/');
        $room = 'rdv-' . $rendezVous->id . '-' . substr(hash('sha256', $rendezVous->id . '|' . $rendezVous->patient_id . '|' . $rendezVous->medecin_id), 0, 10);
        return $base . '/' . $room;
    }

    /**
     * Vérifie si une consultation peut commencer (T-5 min à fin, seulement si statut confirmé)
     */
    public function consultationPeutCommencer(RendezVous $rendezVous): bool
    {
        // Vérifier d'abord que le statut est confirmé (gérer FR et EN)
        if (!in_array($rendezVous->statut, ['confirmé', 'confirmed'], true)) {
            return false;
        }

        $heureDebut = Carbon::parse($rendezVous->date_debut);
        $heureFin = Carbon::parse($rendezVous->date_fin ?? Carbon::parse($rendezVous->date_debut)->addMinutes(30));
        $maintenant = Carbon::now();

        // Le lien est actif 5 minutes avant le début jusqu'à la fin du rendez-vous
        return $maintenant->between($heureDebut->copy()->subMinutes(5), $heureFin);
    }

    /**
     * Vérifie si une consultation est en cours (de début à fin, seulement si statut confirmé)
     */
    public function consultationEnCours(RendezVous $rendezVous): bool
    {
        // Vérifier d'abord que le statut est confirmé (gérer FR et EN)
        if (!in_array($rendezVous->statut, ['confirmé', 'confirmed'], true)) {
            return false;
        }

        $heureDebut = Carbon::parse($rendezVous->date_debut);
        $heureFin = Carbon::parse($rendezVous->date_fin ?? Carbon::parse($rendezVous->date_debut)->addMinutes(30));
        $maintenant = Carbon::now();
        return $maintenant->between($heureDebut, $heureFin);
    }

    /**
     * Vérifie si un rendez-vous doit être affiché dans la section "Consultations en ligne"
     * (5 minutes avant le début jusqu'à la fin, seulement si statut confirmé)
     */
    public function doitAfficherDansConsultationsEnLigne(RendezVous $rendezVous): bool
    {
        // Vérifier d'abord que le statut est confirmé (gérer FR et EN)
        if (!in_array($rendezVous->statut, ['confirmé', 'confirmed'], true)) {
            return false;
        }

        $heureDebut = Carbon::parse($rendezVous->date_debut);
        $heureFin = Carbon::parse($rendezVous->date_fin ?? Carbon::parse($rendezVous->date_debut)->addMinutes(30));
        $maintenant = Carbon::now();

        // Afficher 5 minutes avant le début jusqu'à la fin
        return $maintenant->between($heureDebut->copy()->subMinutes(5), $heureFin);
    }

    /**
     * Vérifie si un rendez-vous doit être affiché dans "Prochains rendez-vous"
     * (jusqu'à la date de début, seulement si statut confirmé)
     */
    public function doitAfficherDansProchainsRendezVous(RendezVous $rendezVous): bool
    {
        // Vérifier d'abord que le statut est confirmé
        if ($rendezVous->statut !== 'confirmé') {
            return false;
        }

        $heureDebut = Carbon::parse($rendezVous->date_debut);
        $maintenant = Carbon::now();

        // Afficher jusqu'à la date de début
        return $maintenant <= $heureDebut;
    }

    /**
     * Crée ou récupère le lien de consultation pour un rendez-vous
     */
    public function creerOuRecupererLien(RendezVous $rendezVous): string
    {
        // Si un lien est déjà là, le réutiliser
        if ($rendezVous->lien_en_ligne) {
            return $rendezVous->lien_en_ligne;
        }

        // Sinon, générer un lien Jitsi
        $lien = $this->genererLienJitsi($rendezVous);
        $rendezVous->update(['lien_en_ligne' => $lien]);
        return $lien;
    }

    /**
     * Récupère les statistiques des consultations pour un médecin
     */
    public function getStatistiquesConsultations(int $medecinId): array
    {
        $aujourdhui = Carbon::today();
        $debutSemaine = Carbon::now()->startOfWeek();
        $debutMois = Carbon::now()->startOfMonth();

        $consultationsAujourdhui = Consultation::whereHas('rendezVous', function ($query) use ($medecinId) {
            $query->where('medecin_id', $medecinId);
        })
            ->whereDate('date', $aujourdhui)
            ->count();

        $consultationsSemaine = Consultation::whereHas('rendezVous', function ($query) use ($medecinId) {
            $query->where('medecin_id', $medecinId);
        })
            ->where('date', '>=', $debutSemaine)
            ->count();

        $consultationsMois = Consultation::whereHas('rendezVous', function ($query) use ($medecinId) {
            $query->where('medecin_id', $medecinId);
        })
            ->where('date', '>=', $debutMois)
            ->count();

        return [
            'aujourdhui' => $consultationsAujourdhui,
            'semaine' => $consultationsSemaine,
            'mois' => $consultationsMois
        ];
    }

    /**
     * Récupère les rendez-vous du jour pour un médecin
     */
    public function getRendezVousDuJour(int $medecinId): array
    {
        $aujourdhui = Carbon::today();

        $rendezVous = RendezVous::with(['patient'])
            ->where('medecin_id', $medecinId)
            ->whereDate('date_debut', $aujourdhui)
            ->orderBy('date_debut')
            ->get();

        return $rendezVous->map(function ($rdv) {
            $consultationService = app(ConsultationService::class);

            return [
                'id' => $rdv->id,
                'prenom' => $rdv->patient->prenom,
                'nom' => $rdv->patient->nom,
                'photo' => $rdv->patient->profile_photo_path
                    ? asset('storage/' . $rdv->patient->profile_photo_path)
                    : 'https://ui-avatars.com/api/?name=' . urlencode($rdv->patient->prenom . ' ' . $rdv->patient->nom),
                'heure' => Carbon::parse($rdv->date_debut)->format('H:i'),
                'heure_fin' => Carbon::parse($rdv->date_fin ?? Carbon::parse($rdv->date_debut)->addMinutes(30))->format('H:i'),
                'type' => $rdv->type ?? 'consultation',
                'statut' => $rdv->statut,
                'lien_meet' => $consultationService->creerOuRecupererLien($rdv),
                'consultation_active' => $consultationService->consultationPeutCommencer($rdv),
                'consultation_en_cours' => $consultationService->consultationEnCours($rdv),
                'consultation_terminee' => Carbon::now() > Carbon::parse($rdv->date_fin ?? Carbon::parse($rdv->date_debut)->addMinutes(30)),
                'date_debut' => $rdv->date_debut,
                'date_fin' => $rdv->date_fin
            ];
        })->toArray();
    }

    /**
     * Récupère les consultations avec filtres pour un médecin
     */
    public function getConsultationsAvecFiltres(int $medecinId, string $filtre = 'aujourdhui', ?string $date = null): array
    {
        $query = RendezVous::with(['patient'])
            ->where('medecin_id', $medecinId);

        switch ($filtre) {
            case 'aujourdhui':
                $query->whereDate('date_debut', Carbon::today());
                break;

            case 'demain':
                $query->whereDate('date_debut', Carbon::tomorrow());
                break;

            case 'semaine':
                $query->whereBetween('date_debut', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ]);
                break;

            case 'semaine_prochaine':
                $query->whereBetween('date_debut', [
                    Carbon::now()->addWeek()->startOfWeek(),
                    Carbon::now()->addWeek()->endOfWeek()
                ]);
                break;

            case 'mois':
                $query->whereBetween('date_debut', [
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth()
                ]);
                break;

            case 'date':
                if ($date) {
                    $query->whereDate('date_debut', Carbon::parse($date));
                }
                break;

            case 'periode':
                if ($date) {
                    $dates = explode(' - ', $date);
                    if (count($dates) === 2) {
                        $query->whereBetween('date_debut', [
                            Carbon::parse($dates[0])->startOfDay(),
                            Carbon::parse($dates[1])->endOfDay()
                        ]);
                    }
                }
                break;
        }

        $rendezVous = $query->orderBy('date_debut')->get();

        // Vérifier et mettre à jour automatiquement les statuts "payed" vers "completed"
        $this->verifierEtMettreAJourStatutsAutomatiquement($rendezVous);

        // Recharger les rendez-vous pour avoir les statuts mis à jour
        $rendezVous = $rendezVous->fresh();

        return $rendezVous->map(function ($rdv) {
            $consultationService = app(ConsultationService::class);
            $maintenant = Carbon::now();
            $dateDebut = Carbon::parse($rdv->date_debut);
            $dateFin = Carbon::parse($rdv->date_fin ?? $dateDebut->copy()->addMinutes(30));

            // Déterminer l'état de la consultation basé sur le statut de la DB et l'heure
            $consultationActive = false;
            $consultationEnCours = false;
            $consultationTerminee = false;

            if ($rdv->statut === 'confirmed' || $rdv->statut === 'confirmé') {
                $cinqMinutesAvant = $dateDebut->copy()->subMinutes(5);
                $consultationActive = $maintenant->between($cinqMinutesAvant, $dateFin);
                $consultationEnCours = $maintenant->between($dateDebut, $dateFin);
                $consultationTerminee = $maintenant->gt($dateFin);
            } elseif ($rdv->statut === 'completed') {
                $consultationTerminee = true;
            }

            return [
                'id' => $rdv->id,
                'prenom' => $rdv->patient->prenom,
                'nom' => $rdv->patient->nom,
                'photo' => $rdv->patient->profile_photo_path
                    ? asset('storage/' . $rdv->patient->profile_photo_path)
                    : 'https://ui-avatars.com/api/?name=' . urlencode($rdv->patient->prenom . ' ' . $rdv->patient->nom),
                'heure' => $dateDebut->format('H:i'),
                'heure_fin' => $dateFin->format('H:i'),
                'date' => $dateDebut->format('d/m/Y'),
                'type' => $rdv->type ?? 'consultation',
                'statut' => $rdv->statut, // Statut exact de la base de données
                'lien_meet' => $consultationService->creerOuRecupererLien($rdv),
                'consultation_active' => $consultationActive,
                'consultation_en_cours' => $consultationEnCours,
                'consultation_terminee' => $consultationTerminee,
                'date_debut' => $rdv->date_debut,
                'date_fin' => $rdv->date_fin,
                'patient_id' => $rdv->patient_id,
                'medecin_id' => $rdv->medecin_id
            ];
        })->toArray();
    }

    /**
     * Crée une nouvelle consultation
     */
    public function createConsultation(array $data, int $medecinId): Consultation
    {
        try {
            DB::beginTransaction();

            // Vérifier que le rendez-vous appartient au médecin
            $rendezVous = RendezVous::where('id', $data['rendezvous_id'])
                ->where('medecin_id', $medecinId)
                ->firstOrFail();

            // Vérifier que le patient a un dossier médical
            $dossierMedical = DossierMedical::firstOrCreate(
                ['patient_id' => $rendezVous->patient_id],
                [
                    'allergies' => '',
                    'groupe_sanguin' => '',
                    'antecedents_medicaux' => '',
                ]
            );

            // Créer la consultation
            $consultation = Consultation::create([
                'dossier_medical_id' => $dossierMedical->id,
                'rendezvous_id' => $data['rendezvous_id'],
                'date' => $data['date'] ?? now(),
                'type' => $data['type'] ?? 'consultation',
                'motif' => $data['motif'] ?? '',
                'symptomes' => $data['symptomes'] ?? '',
                'tension_arterielle' => $data['tension_arterielle'] ?? '',
                'frequence_cardiaque' => $data['frequence_cardiaque'] ?? '',
                'temperature' => $data['temperature'] ?? '',
                'saturation_o2' => $data['saturation_o2'] ?? '',
                'score_glasgow' => $data['score_glasgow'] ?? '',
                'examen_physique' => $data['examen_physique'] ?? '',
                'diagnostic_presume' => $data['diagnostic_presume'] ?? '',
                'medicaments_prescrits' => $data['medicaments_prescrits'] ?? '',
                'propositions_suivi' => $data['propositions_suivi'] ?? '',
                'instructions_particulieres' => $data['instructions_particulieres'] ?? '',
                'poids' => $data['poids'] ?? '',
                'taille' => $data['taille'] ?? '',
                'imc' => $this->calculerIMC($data['poids'] ?? null, $data['taille'] ?? null),
                'habitudes_vie' => $data['habitudes_vie'] ?? '',
                'traitement_actuel' => $data['traitement_actuel'] ?? '',
                'evolution_symptomes' => $data['evolution_symptomes'] ?? '',
                'effets_secondaires' => $data['effets_secondaires'] ?? '',
                'examens_controle' => $data['examens_controle'] ?? '',
                'symptomes_aigus' => $data['symptomes_aigus'] ?? '',
                'debut_symptomes' => $data['debut_symptomes'] ?? '',
                'gravite' => $data['gravite'] ?? '',
                'orientation_patient' => $data['orientation_patient'] ?? '',
            ]);

            // Mettre à jour le statut du rendez-vous
            $rendezVous->update(['statut' => 'completed']);

            DB::commit();
            return $consultation;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la création de la consultation: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Met à jour une consultation
     */
    public function updateConsultation(int $consultationId, array $data, int $medecinId): Consultation
    {
        try {
            $consultation = Consultation::whereHas('rendezVous', function ($query) use ($medecinId) {
                $query->where('medecin_id', $medecinId);
            })->findOrFail($consultationId);

            // Calculer l'IMC si poids et taille sont fournis
            if (isset($data['poids']) || isset($data['taille'])) {
                $poids = $data['poids'] ?? $consultation->poids;
                $taille = $data['taille'] ?? $consultation->taille;
                $data['imc'] = $this->calculerIMC($poids, $taille);
            }

            $consultation->update($data);

            return $consultation;

        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de la consultation: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Supprime une consultation
     */
    public function deleteConsultation(int $consultationId, int $medecinId): bool
    {
        try {
            $consultation = Consultation::whereHas('rendezVous', function ($query) use ($medecinId) {
                $query->where('medecin_id', $medecinId);
            })->findOrFail($consultationId);

            // Remettre le rendez-vous en statut "confirmed"
            $consultation->rendezVous->update(['statut' => 'confirmed']);

            $consultation->delete();
            return true;

        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de la consultation: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Récupère les consultations d'un médecin avec filtres
     */
    public function getConsultationsMedecin(int $medecinId, array $filters = []): \Illuminate\Database\Eloquent\Collection
    {
        $query = Consultation::whereHas('rendezVous', function ($query) use ($medecinId) {
            $query->where('medecin_id', $medecinId);
        })->with(['rendezVous.patient', 'rendezVous.medecin']);

        // Appliquer les filtres
        if (isset($filters['patient_id'])) {
            $query->whereHas('rendezVous', function ($q) use ($filters) {
                $q->where('patient_id', $filters['patient_id']);
            });
        }

        if (isset($filters['date_debut'])) {
            $query->where('date', '>=', $filters['date_debut']);
        }

        if (isset($filters['date_fin'])) {
            $query->where('date', '<=', $filters['date_fin']);
        }

        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        return $query->orderBy('date', 'desc')->get();
    }

    /**
     * Vérifie et met à jour automatiquement les statuts "payed" vers "completed"
     * lorsque la date système dépasse la date de fin du rendez-vous
     */
    private function verifierEtMettreAJourStatutsAutomatiquement($rendezVous)
    {
        $maintenant = Carbon::now();
        $rendezVousAMettreAJour = [];

        foreach ($rendezVous as $rdv) {
            // Vérifier si le statut est "payed" et si la date de fin est dépassée
            if ($rdv->statut === 'payed' && $rdv->date_fin) {
                $dateFin = Carbon::parse($rdv->date_fin);

                if ($maintenant->gt($dateFin)) {
                    $rendezVousAMettreAJour[] = $rdv;
                }
            }
        }

        // Mettre à jour les statuts en lot
        if (!empty($rendezVousAMettreAJour)) {
            Log::info('[AUTO-UPDATE] Mise à jour automatique de ' . count($rendezVousAMettreAJour) . ' rendez-vous de "payed" vers "completed"');

            foreach ($rendezVousAMettreAJour as $rdv) {
                $rdv->update(['statut' => 'completed']);
                Log::info('[AUTO-UPDATE] Rendez-vous ID ' . $rdv->id . ' mis à jour vers "completed"');
            }
        }
    }

    /**
     * Calcule l'IMC
     */
    private function calculerIMC(?float $poids, ?float $taille): ?float
    {
        if (!$poids || !$taille || $taille <= 0) {
            return null;
        }

        return round($poids / pow($taille / 100, 2), 2);
    }
}
