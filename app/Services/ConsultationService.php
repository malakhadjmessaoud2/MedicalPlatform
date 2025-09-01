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
