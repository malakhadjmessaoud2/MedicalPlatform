<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RendezVous;
use App\Services\ConsultationService;

class CorrigerLiensConsultation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consultation:corriger-liens {--force : Forcer la correction de tous les liens}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corrige les liens de consultation Google Meet avec le bon format';

    protected $consultationService;

    public function __construct(ConsultationService $consultationService)
    {
        parent::__construct();
        $this->consultationService = $consultationService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Vérification et correction des liens de consultation...');

        $rendezVous = RendezVous::whereNotNull('lien_en_ligne')->get();
        $corriges = 0;
        $valides = 0;

        foreach ($rendezVous as $rdv) {
            $ancienLien = $rdv->lien_en_ligne;

            // Extraire le code du lien
            $code = str_replace('https://meet.google.com/', '', $ancienLien);

            // Vérifier si le code est valide
            if (!$this->consultationService->validerCodeMeet($code)) {
                $this->warn("Lien invalide détecté pour RDV {$rdv->id}: {$ancienLien}");

                // Générer un nouveau lien
                $nouveauLien = $this->consultationService->genererLienConsultation($rdv);
                $rdv->update(['lien_en_ligne' => $nouveauLien]);

                $this->info("  → Corrigé: {$nouveauLien}");
                $corriges++;
            } else {
                $valides++;
            }
        }

        $this->info("\nRésumé:");
        $this->info("- Liens valides: {$valides}");
        $this->info("- Liens corrigés: {$corriges}");
        $this->info("- Total vérifiés: " . $rendezVous->count());

        if ($corriges > 0) {
            $this->info("\n✅ Les liens ont été corrigés avec succès!");
        } else {
            $this->info("\n✅ Tous les liens sont déjà valides!");
        }
    }
}
