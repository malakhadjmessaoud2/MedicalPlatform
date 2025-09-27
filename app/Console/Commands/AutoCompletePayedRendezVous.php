<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RendezVous;

class AutoCompletePayedRendezVous extends Command
{
    protected $signature = 'rdv:auto-complete-payed';
    protected $description = 'Mark payed rendez-vous as completed when end time has passed';

    public function handle(): int
    {
        $count = 0;
        RendezVous::where('statut', RendezVous::STATUS_PAYED)
            ->whereNotNull('date_fin')
            ->chunkById(200, function ($rdvs) use (&$count) {
                foreach ($rdvs as $rdv) {
                    if ($rdv->autoCompleteIfNeeded()) {
                        $count++;
                    }
                }
            });

        $this->info("Rendez-vous auto-complétés: {$count}");
        return Command::SUCCESS;
    }
}





