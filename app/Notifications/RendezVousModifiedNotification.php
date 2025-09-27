<?php

namespace App\Notifications;

use App\Models\RendezVous;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RendezVousModifiedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $rendezVous;
    public $changes;

    /**
     * Create a new notification instance.
     */
    public function __construct(RendezVous $rendezVous, array $changes = [])
    {
        $this->rendezVous = $rendezVous;
        $this->changes = $changes;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $medecin = $this->rendezVous->medecin;
        $medecinName = $medecin ? 'Dr. ' . $medecin->nom . ' ' . $medecin->prenom : 'Médecin';

        // Construire le message selon les changements
        $changeMessages = $this->buildChangeMessages();
        $message = "Votre rendez-vous a été modifié par {$medecinName}";

        if (!empty($changeMessages)) {
            $message .= " : " . implode(', ', $changeMessages);
        }

        return [
            'type' => 'rendez_vous_modified',
            'title' => 'Rendez-vous modifié',
            'message' => $message,
            'rendezvous_id' => $this->rendezVous->id,
            'patient_id' => $this->rendezVous->patient_id,
            'medecin_id' => $this->rendezVous->medecin_id,
            'medecin_name' => $medecinName,
            'date_debut' => $this->rendezVous->date_debut,
            'date_fin' => $this->rendezVous->date_fin,
            'type_rendez_vous' => $this->rendezVous->type,
            'statut' => $this->rendezVous->statut,
            'description' => $this->rendezVous->description,
            'changes' => $this->changes,
            'change_messages' => $changeMessages,
            'icon' => 'calendar-edit',
            'color' => 'yellow',
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
     * Construire les messages de changement
     */
    private function buildChangeMessages(): array
    {
        $messages = [];

        foreach ($this->changes as $field => $change) {
            $oldValue = $change['old'] ?? null;
            $newValue = $change['new'] ?? null;

            if ($oldValue === $newValue) {
                continue;
            }

            switch ($field) {
                case 'date_debut':
                    $messages[] = 'date modifiée';
                    break;
                case 'date_fin':
                    $messages[] = 'heure de fin modifiée';
                    break;
                case 'statut':
                    $oldStatus = $this->getStatusLabel($oldValue);
                    $newStatus = $this->getStatusLabel($newValue);
                    $messages[] = "statut changé de {$oldStatus} à {$newStatus}";
                    break;
                case 'type':
                    $messages[] = 'type modifié';
                    break;
                case 'description':
                    $messages[] = 'description mise à jour';
                    break;
                case 'lien_en_ligne':
                    if ($newValue && !$oldValue) {
                        $messages[] = 'lien de consultation ajouté';
                    } elseif ($newValue && $oldValue) {
                        $messages[] = 'lien de consultation modifié';
                    }
                    break;
            }
        }

        return $messages;
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
}
