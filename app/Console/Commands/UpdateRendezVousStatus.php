<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RendezVous;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class UpdateRendezVousStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rendez-vous:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Met à jour automatiquement les statuts des rendez-vous de "payed" vers "completed" quand la date de fin est dépassée';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Vérification des statuts des rendez-vous...');

        $maintenant = Carbon::now();
        $rendezVousAMettreAJour = RendezVous::where('statut', 'payed')
            ->whereNotNull('date_fin')
            ->where('date_fin', '<', $maintenant)
            ->get();

        if ($rendezVousAMettreAJour->isEmpty()) {
            $this->info('Aucun rendez-vous à mettre à jour.');
            return 0;
        }

        $this->info('Mise à jour de ' . $rendezVousAMettreAJour->count() . ' rendez-vous...');

        $updated = 0;
        foreach ($rendezVousAMettreAJour as $rdv) {
            $rdv->update(['statut' => 'completed']);
            $updated++;
            $this->line("Rendez-vous ID {$rdv->id} mis à jour vers 'completed'");
            Log::info("[CRON] Rendez-vous ID {$rdv->id} mis à jour automatiquement vers 'completed'");
        }

        $this->info("Mise à jour terminée: {$updated} rendez-vous mis à jour.");

        return 0;
    }
}
