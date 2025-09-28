<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Services\FreeMedicalAIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FreeAIConsultationController extends Controller
{
    private $aiService;

    public function __construct()
    {
        $this->aiService = new FreeMedicalAIService();
    }

    /**
     * Génère un compte-rendu avec IA gratuite
     */
    public function generateCompteRenduGratuit(Request $request, $consultationId)
    {
        try {
            $consultation = Consultation::with(['rendezVous.patient', 'rendezVous.medecin'])
                ->whereHas('rendezVous', function($query) {
                    $query->where('medecin_id', Auth::id());
                })
                ->findOrFail($consultationId);

            // Préparer les données
            $consultationData = [
                'type' => $consultation->type,
                'date' => $consultation->date,
                'motif' => $consultation->motif,
                'symptomes' => $consultation->symptomes,
                'tension_arterielle' => $consultation->tension_arterielle,
                'temperature' => $consultation->temperature,
                'frequence_cardiaque' => $consultation->frequence_cardiaque,
                'saturation_o2' => $consultation->saturation_o2,
                'examen_physique' => $consultation->examen_physique,
                'diagnostic_presume' => $consultation->diagnostic_presume,
                'medicaments_prescrits' => $consultation->medicaments_prescrits,
                'propositions_suivi' => $consultation->propositions_suivi,
                'examens_controle' => $consultation->examens_controle,
                'instructions_particulieres' => $consultation->instructions_particulieres,
                'gravite' => $consultation->gravite,
                'patient_name' => $consultation->rendezVous->patient->prenom . ' ' . $consultation->rendezVous->patient->nom,
                'medecin_name' => $consultation->rendezVous->medecin->prenom . ' ' . $consultation->rendezVous->medecin->nom
            ];

            // Essayer Gemini d'abord (gratuit et rapide)
            $result = $this->aiService->generateCompteRenduGemini($consultationData);

            // Si Gemini échoue, essayer Hugging Face
            if (!$result['success']) {
                $result = $this->aiService->generateCompteRenduHuggingFace($consultationData);
            }

            if ($result['success']) {
                // Sauvegarder dans la base de données
                $consultation->update([
                    'compte_rendu_ia' => $result['compte_rendu'],
                    'resume_ia' => $result['resume'],
                    'recommandations_ia' => $result['recommandations'],
                    'compte_rendu_generated_at' => now(),
                    'ai_service_used' => $result['generated_by'] ?? 'gemini'
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Compte-rendu généré avec succès',
                    'data' => [
                        'compte_rendu' => $result['compte_rendu'],
                        'resume' => $result['resume'],
                        'recommandations' => $result['recommandations'],
                        'service_used' => $result['generated_by'] ?? 'gemini'
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la génération du compte-rendu'
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Génère un résumé court gratuit
     */
    public function generateResumeGratuit(Request $request, $consultationId)
    {
        try {
            $consultation = Consultation::with(['rendezVous.patient'])
                ->whereHas('rendezVous', function($query) {
                    $query->where('medecin_id', Auth::id());
                })
                ->findOrFail($consultationId);

            $consultationData = [
                'type' => $consultation->type,
                'motif' => $consultation->motif,
                'symptomes' => $consultation->symptomes,
                'diagnostic_presume' => $consultation->diagnostic_presume,
                'medicaments_prescrits' => $consultation->medicaments_prescrits
            ];

            $result = $this->aiService->generateResumeCourt($consultationData);

            if ($result['success']) {
                $consultation->update([
                    'resume_ia' => $result['resume'],
                    'resume_generated_at' => now()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Résumé généré avec succès',
                    'resume' => $result['resume']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la génération du résumé'
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Génère une lettre de sortie simple
     */
    public function generateLettreSortieGratuit(Request $request, $consultationId)
    {
        try {
            $consultation = Consultation::with(['rendezVous.patient', 'rendezVous.medecin'])
                ->whereHas('rendezVous', function($query) {
                    $query->where('medecin_id', Auth::id());
                })
                ->findOrFail($consultationId);

            $consultationData = [
                'date' => $consultation->date,
                'motif' => $consultation->motif,
                'diagnostic_presume' => $consultation->diagnostic_presume,
                'medicaments_prescrits' => $consultation->medicaments_prescrits,
                'propositions_suivi' => $consultation->propositions_suivi,
                'patient_name' => $consultation->rendezVous->patient->prenom . ' ' . $consultation->rendezVous->patient->nom,
                'medecin_name' => $consultation->rendezVous->medecin->prenom . ' ' . $consultation->rendezVous->medecin->nom
            ];

            $result = $this->aiService->generateLettreSortieSimple($consultationData);

            if ($result['success']) {
                $consultation->update([
                    'lettre_sortie_ia' => $result['lettre_sortie'],
                    'lettre_sortie_generated_at' => now()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Lettre de sortie générée avec succès',
                    'lettre_sortie' => $result['lettre_sortie']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la génération de la lettre de sortie'
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Affiche le compte-rendu généré
     */
    public function showCompteRenduGratuit($consultationId)
    {
        try {
            $consultation = Consultation::with(['rendezVous.patient', 'rendezVous.medecin'])
                ->whereHas('rendezVous', function($query) {
                    $query->where('medecin_id', Auth::id());
                })
                ->findOrFail($consultationId);

            if (!$consultation->compte_rendu_ia) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun compte-rendu généré pour cette consultation'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'compte_rendu' => $consultation->compte_rendu_ia,
                    'resume' => $consultation->resume_ia,
                    'recommandations' => $consultation->recommandations_ia,
                    'generated_at' => $consultation->compte_rendu_generated_at,
                    'ai_service_used' => $consultation->ai_service_used ?? 'template'
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Génère un PDF du compte-rendu
     */
    public function generatePDFCompteRendu($consultationId)
    {
        try {
            $consultation = Consultation::with(['rendezVous.patient', 'rendezVous.medecin'])
                ->whereHas('rendezVous', function($query) {
                    $query->where('medecin_id', Auth::id());
                })
                ->findOrFail($consultationId);

            if (!$consultation->compte_rendu_ia) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun compte-rendu généré pour cette consultation'
                ], 404);
            }

            // Générer le PDF
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('medecin.consultations.compte-rendu-pdf', [
                'consultation' => $consultation,
                'compte_rendu' => $consultation->compte_rendu_ia,
                'resume' => $consultation->resume_ia,
                'recommandations' => $consultation->recommandations_ia
            ]);

            $filename = 'compte-rendu-' . $consultation->id . '-' . now()->format('Y-m-d') . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération du PDF: ' . $e->getMessage()
            ], 500);
        }
    }
}
