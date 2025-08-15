<?php

namespace App\Events;

use App\Models\RendezVous;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class RendezVousModifie implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $rendezVous;
    public $action;

    /**
     * Create a new event instance.
     */
    public function __construct(RendezVous $rendezVous, string $action)
    {
        $this->rendezVous = $rendezVous;
        $this->action = $action;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('rendez-vous'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'RendezVousModifie';
    }

    /**
     * Détermine la couleur de fond selon le type et le statut
     */
    protected function getBackgroundColor(): string
    {
        $type = $this->rendezVous->type;
        $statut = $this->rendezVous->statut;

        $backgroundColor = match($type) {
            'consultation' => '#4299e1',
            'suivi' => '#48bb78',
            'urgence' => '#f56565',
            default => '#3788d8'
        };

        if ($statut === 'annulé') {
            $backgroundColor = '#a0aec0';
        } elseif ($statut === 'en_attente') {
            $backgroundColor = '#ecc94b';
        }

        return $backgroundColor;
    }

    /**
     * Détermine la couleur de bordure selon le type et le statut
     */
    protected function getBorderColor(): string
    {
        $type = $this->rendezVous->type;
        $statut = $this->rendezVous->statut;

        $borderColor = match($type) {
            'consultation' => '#4299e1',
            'suivi' => '#48bb78',
            'urgence' => '#f56565',
            default => '#3788d8'
        };

        if ($statut === 'annulé') {
            $borderColor = '#718096';
        } elseif ($statut === 'en_attente') {
            $borderColor = '#d69e2e';
        }

        return $borderColor;
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        // Charger la relation patient
        $this->rendezVous->load('patient:id,nom,prenom');

        // Formater les dates avec le fuseau horaire de Tunis
        $dateDebut = Carbon::parse($this->rendezVous->date_debut)->timezone('Africa/Tunis');
        $dateFin = Carbon::parse($this->rendezVous->date_fin)->timezone('Africa/Tunis');

        // Obtenir le nom du patient
        $patientNom = $this->rendezVous->patient
            ? $this->rendezVous->patient->nom . ' ' . $this->rendezVous->patient->prenom
            : 'Patient inconnu';

        // Construire le titre
        $title = $this->rendezVous->titre . ' - ' . $patientNom;

        // Déterminer les couleurs
        $backgroundColor = $this->getBackgroundColor();
        $borderColor = $this->getBorderColor();

        return [
            'action' => $this->action,
            'rendezVous' => [
                'id' => $this->rendezVous->id,
                'title' => $title,
                'start' => $dateDebut->format('Y-m-d\TH:i:s'),
                'end' => $dateFin->format('Y-m-d\TH:i:s'),
                'backgroundColor' => $backgroundColor,
                'borderColor' => $borderColor,
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'patient_id' => $this->rendezVous->patient_id,
                    'patient_nom' => $patientNom,
                    'type' => $this->rendezVous->type,
                    'statut' => $this->rendezVous->statut,
                    'description' => $this->rendezVous->description,
                    'heure_debut' => $dateDebut->format('H:i'),
                    'heure_fin' => $dateFin->format('H:i'),
                    'medecin_id' => $this->rendezVous->medecin_id
                ]
            ]
        ];
    }
}
