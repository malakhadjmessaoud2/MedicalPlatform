<?php

namespace App\Events;

use App\Models\RendezVous;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RendezVousModifie implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $rendezVous;
    public $action;

    public function __construct(RendezVous $rendezVous, $action)
    {
        $this->rendezVous = $rendezVous;
        $this->action = $action;
    }

    public function broadcastOn()
    {
        return new Channel('rendez-vous');
    }

    public function broadcastAs()
    {
        return 'RendezVousModifie';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->rendezVous->id,
            'action' => $this->action,
            'rendezVous' => [
                'id' => $this->rendezVous->id,
                'title' => $this->rendezVous->titre ?? ($this->rendezVous->patient ? $this->rendezVous->patient->nom : 'Sans titre'),
                'start' => $this->rendezVous->date_debut->toIso8601String(),
                'end' => $this->rendezVous->date_fin->toIso8601String(),
                'extendedProps' => [
                    'patient_id' => $this->rendezVous->patient_id,
                    'type' => $this->rendezVous->type,
                    'statut' => $this->rendezVous->statut,
                    'description' => $this->rendezVous->description,
                ]
            ]
        ];
    }
}
