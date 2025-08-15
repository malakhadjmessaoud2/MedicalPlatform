<?php

namespace App\Services;

use App\Models\RendezVous;
use Carbon\Carbon;

class ConsultationService
{
    public function __construct(private ?GoogleMeetService $googleMeetService = null)
    {
        // L'injection est optionnelle; si le container n'a pas GoogleMeetService, on reste à null
    }

    /**
     * Retourne un lien de visioconférence pour un rendez-vous en utilisant le provider configuré.
     * - GOOGLE: via Google Calendar API (si configurée) → lien Meet réel
     * - JITSI (défaut): lien Jitsi fonctionnel et accessible
     */
    public function genererLienConsultation(RendezVous $rendezVous): string
    {
        $provider = strtoupper(env('ONLINE_MEET_PROVIDER', 'JITSI'));

        if ($provider === 'GOOGLE' && $this->googleMeetService) {
            $meetLink = $this->googleMeetService->createMeetLink($rendezVous);
            if ($meetLink) {
                return $meetLink;
            }
            // Si l'API Google n'est pas disponible ou échoue, on bascule sur Jitsi
        }

        // Fallback Jitsi: lien réellement ouvrable, pas besoin de compte
        return $this->genererLienJitsi($rendezVous);
    }

    /**
     * Génère un lien Jitsi fonctionnel basé sur l'ID du rendez-vous
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
        // Vérifier d'abord que le statut est confirmé
        if ($rendezVous->statut !== 'confirmé') {
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
        // Vérifier d'abord que le statut est confirmé
        if ($rendezVous->statut !== 'confirmé') {
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
        // Vérifier d'abord que le statut est confirmé
        if ($rendezVous->statut !== 'confirmé') {
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
        // Si un lien est déjà là, le réutiliser tel quel (Google Meet réel ou Jitsi)
        if ($rendezVous->lien_en_ligne) {
            return $rendezVous->lien_en_ligne;
        }

        // Sinon, générer selon le provider
        $lien = $this->genererLienConsultation($rendezVous);
        $rendezVous->update(['lien_en_ligne' => $lien]);
        return $lien;
    }
}
