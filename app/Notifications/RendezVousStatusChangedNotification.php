<?php

namespace App\Notifications;

use App\Models\RendezVous;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RendezVousStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $rendezVous;
    public $oldStatus;
    public $newStatus;

    /**
     * Create a new notification instance.
     */
    public function __construct(RendezVous $rendezVous, string $oldStatus, string $newStatus)
    {
        $this->rendezVous = $rendezVous;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
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
        $oldStatusLabel = $this->getStatusLabel($this->oldStatus);
        $newStatusLabel = $this->getStatusLabel($this->newStatus);

        // Déterminer le message et la couleur selon le statut
        $message = $this->getStatusMessage($newStatusLabel);
        $color = $this->getStatusColor($this->newStatus);
        $icon = $this->getStatusIcon($this->newStatus);

        return [
            'type' => 'rendez_vous_status_changed',
            'title' => 'Statut du rendez-vous modifié',
            'message' => $message,
            'rendezvous_id' => $this->rendezVous->id,
            'patient_id' => $this->rendezVous->patient_id,
            'medecin_id' => $this->rendezVous->medecin_id,
            'date_debut' => $this->rendezVous->date_debut,
            'date_fin' => $this->rendezVous->date_fin,
            'type_rendez_vous' => $this->rendezVous->type,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'old_status_label' => $oldStatusLabel,
            'new_status_label' => $newStatusLabel,
            'description' => $this->rendezVous->description,
            'icon' => $icon,
            'color' => $color,
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

    /**
     * Obtenir le libellé du statut
     */
    private function getStatusLabel($status): string
    {
        return match($status) {
            'pending' => 'En attente',
            'confirmed' => 'Confirmé',
            'cancelled' => 'Annulé',
            'rejected' => 'Rejeté',
            'completed' => 'Terminé',
            'payed' => 'Payé',
            'en_attente' => 'En attente',
            'confirmé' => 'Confirmé',
            'annulé' => 'Annulé',
            default => $status ?? 'Inconnu'
        };
    }

    /**
     * Obtenir le message selon le statut
     */
    private function getStatusMessage(string $newStatusLabel): string
    {
        return match($this->newStatus) {
            'confirmed', 'confirmé' => "Votre rendez-vous a été confirmé",
            'cancelled', 'annulé' => "Votre rendez-vous a été annulé",
            'rejected' => "Votre rendez-vous a été rejeté",
            'completed' => "Votre rendez-vous est terminé",
            'payed' => "Votre rendez-vous a été payé",
            default => "Le statut de votre rendez-vous est maintenant : {$newStatusLabel}"
        };
    }

    /**
     * Obtenir la couleur selon le statut
     */
    private function getStatusColor(string $status): string
    {
        return match($status) {
            'confirmed', 'confirmé' => 'green',
            'cancelled', 'annulé' => 'red',
            'rejected' => 'red',
            'completed' => 'blue',
            'payed' => 'green',
            'pending', 'en_attente' => 'yellow',
            default => 'gray'
        };
    }

    /**
     * Obtenir l'icône selon le statut
     */
    private function getStatusIcon(string $status): string
    {
        return match($status) {
            'confirmed', 'confirmé' => 'check-circle',
            'cancelled', 'annulé' => 'x-circle',
            'rejected' => 'x-circle',
            'completed' => 'check-circle',
            'payed' => 'credit-card',
            'pending', 'en_attente' => 'clock',
            default => 'calendar'
        };
    }
}
