<?php

namespace App\Notifications;

use App\Models\RendezVous;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RendezVousCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $rendezVous;

    /**
     * Create a new notification instance.
     */
    public function __construct(RendezVous $rendezVous)
    {
        $this->rendezVous = $rendezVous;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $patient = $this->rendezVous->patient;
        $patientName = $patient ? $patient->nom . ' ' . $patient->prenom : 'Patient inconnu';

        return [
            'type' => 'rendez_vous_created',
            'title' => 'Nouveau rendez-vous demandé',
            'message' => "Un nouveau rendez-vous a été demandé par {$patientName}",
            'rendezvous_id' => $this->rendezVous->id,
            'patient_id' => $this->rendezVous->patient_id,
            'patient_name' => $patientName,
            'medecin_id' => $this->rendezVous->medecin_id,
            'date_debut' => $this->rendezVous->date_debut,
            'date_fin' => $this->rendezVous->date_fin,
            'type_rendez_vous' => $this->rendezVous->type,
            'statut' => $this->rendezVous->statut,
            'description' => $this->rendezVous->description,
            'icon' => 'calendar-plus',
            'color' => 'blue',
            'created_at' => now(),
        ];
    }

    /**
     * Get the database representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return $this->toArray($notifiable);
    }
}
