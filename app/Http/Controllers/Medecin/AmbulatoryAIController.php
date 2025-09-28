<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Services\AmbulatoryMedicalAIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AmbulatoryAIController extends Controller
{
    private $aiService;

    public function __construct()
    {
        $this->aiService = new AmbulatoryMedicalAIService();
    }

    /**
     * Interface de génération IA pour médecine ambulatoire
     */
    public function showAmbulatoryInterface($consultationId)
    {
        $consultation = Consultation::with(['rendezVous.patient', 'rendezVous.medecin'])
            ->whereHas('rendezVous', function($query) {
                $query->where('medecin_id', Auth::id());
            })
            ->findOrFail($consultationId);

        return view('medecin.consultations.ambulatory-ai-generation', compact('consultation'));
    }

    /**
     * Récupère les données existantes de la consultation
     */
    public function getExistingData($consultationId)
    {
        try {
            $consultation = $this->getConsultation($consultationId);

            // Vérifier si des données IA existent
            $hasExistingData = $consultation->compte_rendu_ia || $consultation->teleconsultation_report_ia;

            if (!$hasExistingData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun compte-rendu IA trouvé pour cette consultation'
                ]);
            }

            // Construire le document existant
            $doc = [
                'consultation' => [
                    'patient' => $consultation->rendezVous->patient->prenom . ' ' . $consultation->rendezVous->patient->nom,
                    'medecin' => $consultation->rendezVous->medecin->prenom . ' ' . $consultation->rendezVous->medecin->nom,
                    'date' => $consultation->date->format('Y-m-d'),
                    'type' => $consultation->type,
                    'motif' => $consultation->motif,
                    'symptomes' => $consultation->symptomes,
                    'examen_physique' => $consultation->examen_physique,
                    'signes_vitaux' => [
                        'tension_arterielle' => $consultation->tension_arterielle,
                        'temperature_c' => $consultation->temperature,
                        'frequence_cardiaque_bpm' => $consultation->frequence_cardiaque,
                        'saturation_o2_pct' => $consultation->saturation_o2,
                    ],
                ],
                'diagnostic' => [
                    'summary' => $consultation->diagnostic_summary_ia,
                ],
                'treatment' => [
                    'plan' => $consultation->treatment_plan_ia,
                ],
                'recommendations' => [
                    'hygiene' => $consultation->hygiene_instructions_ia,
                    'follow_up' => $consultation->follow_up_plan_ia,
                    'specialist_referral' => $consultation->specialist_referral_ia,
                    'medication_instructions' => $consultation->medication_instructions_ia,
                ],
                'follow_up' => [
                    'plan' => $consultation->follow_up_plan_ia,
                    'monitoring_points' => $consultation->monitoring_points_ia,
                    'warning_signs' => $consultation->warning_signs_ia,
                    'appointment_schedule' => $consultation->appointment_schedule_ia,
                ],
                'summary' => [
                    'patient_friendly' => $consultation->online_summary_ia,
                    'key_points' => $consultation->key_points_ia,
                    'next_steps' => $consultation->next_steps_ia,
                ],
                'metadata' => [
                    'model' => $consultation->ai_service_used ?? 'google-gemini',
                    'generated_at' => $consultation->compte_rendu_generated_at?->format('Y-m-d H:i:s'),
                ],
            ];

            // Construire le texte lisible
            $reportText = $this->buildCompleteTeleconsultationText($doc);

            return response()->json([
                'success' => true,
                'message' => 'Données existantes récupérées avec succès',
                'data' => [
                    'teleconsultation_report' => $reportText,
                    'structured' => $doc,
                    'generated_at' => $consultation->compte_rendu_generated_at?->format('d/m/Y à H:i'),
                    'ai_service' => $consultation->ai_service_used
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des données: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Génère un compte-rendu de téléconsultation
     */
    public function generateTeleconsultationReport(Request $request, $consultationId)
    {
        try {
            $consultation = $this->getConsultation($consultationId);
            $consultationData = $this->prepareConsultationData($consultation);

            // Générer le compte-rendu de téléconsultation
            $teleconsultationResult = $this->aiService->generateTeleconsultationReport($consultationData);

            if (!$teleconsultationResult['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $teleconsultationResult['error']
                ], 500);
            }

            // Générer les recommandations
            $recommendationsResult = $this->aiService->generateWrittenRecommendations($consultationData);

            // Générer le plan de suivi
            $followUpResult = $this->aiService->generateFollowUpPlan($consultationData);

            // Construire le document complet
            $doc = [
                'consultation' => $teleconsultationResult['consultation'] ?? [],
                'diagnostic' => $teleconsultationResult['diagnostic'] ?? [],
                'treatment' => $teleconsultationResult['treatment'] ?? [],
                'recommendations' => $recommendationsResult['recommendations'] ?? [],
                'follow_up' => $followUpResult['follow_up'] ?? [],
                'summary' => $teleconsultationResult['summary'] ?? [],
                'metadata' => $teleconsultationResult['metadata'] ?? [],
            ];

            // Build a readable complete report text for UI
            $reportText = $this->buildCompleteTeleconsultationText($doc);

            // Mettre à jour la base de données avec toutes les informations
            $consultation->update([
                // Champs IA généraux (pour compatibilité)
                'compte_rendu_ia' => $reportText,
                'compte_rendu_generated_at' => now(),
                'ai_service_used' => 'google-gemini-2.0-flash',

                // Champs IA ambulatoires spécifiques
                'teleconsultation_report_ia' => $reportText,
                'diagnostic_summary_ia' => $doc['diagnostic']['summary'] ?? null,
                'treatment_plan_ia' => $doc['treatment']['plan'] ?? null,
                'hygiene_instructions_ia' => $doc['recommendations']['hygiene'] ?? null,
                'follow_up_plan_ia' => $doc['follow_up']['plan'] ?? null,
                'specialist_referral_ia' => $doc['recommendations']['specialist_referral'] ?? null,
                'medication_instructions_ia' => $doc['recommendations']['medication_instructions'] ?? null,
                'monitoring_points_ia' => $doc['follow_up']['monitoring_points'] ?? null,
                'warning_signs_ia' => $doc['follow_up']['warning_signs'] ?? null,
                'appointment_schedule_ia' => $doc['follow_up']['appointment_schedule'] ?? null,
                'teleconsultation_generated_at' => now(),
                'recommendations_generated_at' => now(),
                'followup_generated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Compte-rendu complet généré avec succès',
                'data' => [
                    // Text version for immediate display
                    'teleconsultation_report' => $reportText,
                    // Structured JSON for integrations/exports
                    'structured' => $doc
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    private function buildTeleconsultationText(array $doc): string
    {
        $c = $doc['consultation'] ?? [];
        $d = $doc['diagnostic'] ?? [];
        $t = $doc['treatment'] ?? [];
        $r = $doc['recommendations'] ?? [];
        $f = $doc['follow_up'] ?? [];

        $lines = [];
        $lines[] = 'COMPTE-RENDU DE TÉLÉCONSULTATION';

        // Informations de base (toujours affichées si présentes)
        if (!empty($c['patient'])) {
            $lines[] = 'Patient: ' . $c['patient'];
        }
        if (!empty($c['medecin'])) {
            $lines[] = 'Médecin: ' . $c['medecin'];
        }
        if (!empty($c['date'])) {
            $lines[] = 'Date: ' . $c['date'];
        }
        $lines[] = '';

        // Motif et symptômes (seulement si remplis)
        if (!empty($c['motif'])) {
            $lines[] = 'Motif: ' . $c['motif'];
        }
        if (!empty($c['symptomes'])) {
            $lines[] = 'Symptômes: ' . $c['symptomes'];
        }

        // Examen physique (seulement si rempli)
        if (!empty($c['examen_physique'])) {
            $lines[] = 'Examen physique: ' . $c['examen_physique'];
        }

        // Signes vitaux (seulement si au moins un est rempli)
        if (!empty($c['signes_vitaux'])) {
            $sv = $c['signes_vitaux'];
            $vitals = [];
            if (!empty($sv['tension_arterielle'])) {
                $vitals[] = 'TA=' . $sv['tension_arterielle'];
            }
            if (!empty($sv['temperature_c'])) {
                $vitals[] = 'T°C=' . $sv['temperature_c'];
            }
            if (!empty($sv['frequence_cardiaque_bpm'])) {
                $vitals[] = 'FC=' . $sv['frequence_cardiaque_bpm'];
            }
            if (!empty($sv['saturation_o2_pct'])) {
                $vitals[] = 'SpO2=' . $sv['saturation_o2_pct'];
            }
            if (!empty($vitals)) {
                $lines[] = 'Signes vitaux: ' . implode(', ', $vitals);
            }
        }

        // Diagnostic (seulement si rempli)
        if (!empty($d['summary'])) {
            $lines[] = '';
            $lines[] = 'Diagnostic - Synthèse:';
            $lines[] = $d['summary'];
        }

        // Plan de traitement (seulement si rempli)
        if (!empty($t['plan'])) {
            $lines[] = '';
            $lines[] = 'Plan de traitement:';
            $lines[] = $t['plan'];
        }

        // Recommandations (seulement les sections remplies)
        $recommendations = [];
        if (!empty($r['hygiene'])) {
            $recommendations[] = ' - Hygiène: ' . $r['hygiene'];
        }
        if (!empty($r['follow_up'])) {
            $recommendations[] = ' - Suivi: ' . $r['follow_up'];
        }
        if (!empty($r['specialist_referral'])) {
            $recommendations[] = ' - Orientation: ' . $r['specialist_referral'];
        }
        if (!empty($r['medication_instructions'])) {
            $recommendations[] = ' - Prise des médicaments: ' . $r['medication_instructions'];
        }

        if (!empty($recommendations)) {
            $lines[] = '';
            $lines[] = 'Recommandations:';
            $lines = array_merge($lines, $recommendations);
        }

        // Plan de suivi (seulement si rempli)
        if (!empty($f['plan'])) {
            $lines[] = '';
            $lines[] = 'Plan de suivi:';
            $lines[] = $f['plan'];
        }

        return implode("\n", array_filter($lines, fn($l) => $l !== null && $l !== ''));
    }

    private function buildCompleteTeleconsultationText(array $doc): string
    {
        $c = $doc['consultation'] ?? [];
        $d = $doc['diagnostic'] ?? [];
        $t = $doc['treatment'] ?? [];
        $r = $doc['recommendations'] ?? [];
        $f = $doc['follow_up'] ?? [];

        $lines = [];
        $lines[] = 'COMPTE-RENDU DE TÉLÉCONSULTATION';

        // Informations de base (toujours affichées si présentes)
        if (!empty($c['patient'])) {
            $lines[] = 'Patient: ' . $c['patient'];
        }
        if (!empty($c['medecin'])) {
            $lines[] = 'Médecin: ' . $c['medecin'];
        }
        if (!empty($c['date'])) {
            $lines[] = 'Date: ' . $c['date'];
        }
        $lines[] = '';

        // Motif et symptômes (seulement si remplis)
        if (!empty($c['motif'])) {
            $lines[] = 'Motif: ' . $c['motif'];
        }
        if (!empty($c['symptomes'])) {
            $lines[] = 'Symptômes: ' . $c['symptomes'];
        }

        // Examen physique (seulement si rempli)
        if (!empty($c['examen_physique'])) {
            $lines[] = 'Examen physique: ' . $c['examen_physique'];
        }

        // Signes vitaux (seulement si au moins un est rempli)
        if (!empty($c['signes_vitaux'])) {
            $sv = $c['signes_vitaux'];
            $vitals = [];
            if (!empty($sv['tension_arterielle'])) {
                $vitals[] = 'TA=' . $sv['tension_arterielle'];
            }
            if (!empty($sv['temperature_c'])) {
                $vitals[] = 'T°C=' . $sv['temperature_c'];
            }
            if (!empty($sv['frequence_cardiaque_bpm'])) {
                $vitals[] = 'FC=' . $sv['frequence_cardiaque_bpm'];
            }
            if (!empty($sv['saturation_o2_pct'])) {
                $vitals[] = 'SpO2=' . $sv['saturation_o2_pct'];
            }
            if (!empty($vitals)) {
                $lines[] = 'Signes vitaux: ' . implode(', ', $vitals);
            }
        }

        // Diagnostic (seulement si rempli)
        if (!empty($d['summary'])) {
            $lines[] = '';
            $lines[] = 'Diagnostic - Synthèse:';
            $lines[] = $d['summary'];
        }

        // Plan de traitement (seulement si rempli)
        if (!empty($t['plan'])) {
            $lines[] = '';
            $lines[] = 'Plan de traitement:';
            $lines[] = $t['plan'];
        }

        // Recommandations (seulement les sections remplies)
        $recommendations = [];
        if (!empty($r['hygiene'])) {
            $recommendations[] = ' - Hygiène: ' . $r['hygiene'];
        }
        if (!empty($r['follow_up'])) {
            $recommendations[] = ' - Suivi: ' . $r['follow_up'];
        }
        if (!empty($r['specialist_referral'])) {
            $recommendations[] = ' - Orientation: ' . $r['specialist_referral'];
        }
        if (!empty($r['medication_instructions'])) {
            $recommendations[] = ' - Prise des médicaments: ' . $r['medication_instructions'];
        }

        if (!empty($recommendations)) {
            $lines[] = '';
            $lines[] = 'Recommandations:';
            $lines = array_merge($lines, $recommendations);
        }

        // Plan de suivi (seulement si rempli)
        if (!empty($f['plan'])) {
            $lines[] = '';
            $lines[] = 'Plan de suivi:';
            $lines[] = $f['plan'];
        }

        return implode("\n", array_filter($lines, fn($l) => $l !== null && $l !== ''));
    }

    /**
     * Génère des recommandations écrites
     */
    public function generateWrittenRecommendations(Request $request, $consultationId)
    {
        try {
            $consultation = $this->getConsultation($consultationId);
            $consultationData = $this->prepareConsultationData($consultation);

            $result = $this->aiService->generateWrittenRecommendations($consultationData);

            if ($result['success']) {
                $consultation->update([
                    'hygiene_instructions_ia' => $result['hygiene_instructions'] ?? null,
                    'follow_up_plan_ia' => $result['follow_up_plan'] ?? null,
                    'specialist_referral_ia' => $result['specialist_referral'] ?? null,
                    'medication_instructions_ia' => $result['medication_instructions'] ?? null,
                    'recommendations_generated_at' => now()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Recommandations générées avec succès',
                    'data' => $result
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['error']
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
     * Génère un résumé de consultation en ligne
     */
    public function generateOnlineSummary(Request $request, $consultationId)
    {
        try {
            $consultation = $this->getConsultation($consultationId);
            $consultationData = $this->prepareConsultationData($consultation);

            $result = $this->aiService->generateOnlineConsultationSummary($consultationData);

            if ($result['success']) {
                $consultation->update([
                    'online_summary_ia' => $result['summary'],
                    'key_points_ia' => $result['key_points'],
                    'next_steps_ia' => $result['next_steps'],
                    'summary_generated_at' => now()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Résumé de consultation généré avec succès',
                    'data' => $result
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['error']
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
     * Génère un plan de suivi personnalisé
     */
    public function generateFollowUpPlan(Request $request, $consultationId)
    {
        try {
            $consultation = $this->getConsultation($consultationId);
            $consultationData = $this->prepareConsultationData($consultation);

            $result = $this->aiService->generateFollowUpPlan($consultationData);

            if ($result['success']) {
                $consultation->update([
                    'follow_up_plan_ia' => $result['follow_up_plan'] ?? null,
                    'monitoring_points_ia' => $result['monitoring_points'] ?? null,
                    'warning_signs_ia' => $result['warning_signs'] ?? null,
                    'appointment_schedule_ia' => $result['appointment_schedule'] ?? null,
                    'followup_generated_at' => now()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Plan de suivi généré avec succès',
                    'data' => $result
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['error']
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
     * Génère un document complet ambulatoire
     */
    public function generateCompleteDocument(Request $request, $consultationId)
    {
        try {
            $consultation = $this->getConsultation($consultationId);
            $consultationData = $this->prepareConsultationData($consultation);

            $result = $this->aiService->generateCompleteAmbulatoryDocument($consultationData);

            if ($result['success']) {
                $consultation->update([
                    'teleconsultation_report_ia' => $result['teleconsultation_report'],
                    'diagnostic_summary_ia' => $result['diagnostic_summary'],
                    'treatment_plan_ia' => $result['treatment_plan'],
                    'hygiene_instructions_ia' => $result['hygiene_instructions'],
                    'follow_up_plan_ia' => $result['follow_up_plan'],
                    'specialist_referral_ia' => $result['specialist_referral'],
                    'medication_instructions_ia' => $result['medication_instructions'],
                    'online_summary_ia' => $result['summary'],
                    'key_points_ia' => $result['key_points'],
                    'next_steps_ia' => $result['next_steps'],
                    'monitoring_points_ia' => $result['monitoring_points'],
                    'warning_signs_ia' => $result['warning_signs'],
                    'appointment_schedule_ia' => $result['appointment_schedule'],
                    'complete_document_generated_at' => now()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Document complet généré avec succès',
                    'data' => $result
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['error']
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
     * Génère un PDF du document ambulatoire
     */
    public function generatePDFDocument($consultationId)
    {
        try {
            $consultation = $this->getConsultation($consultationId);

            if (!$consultation->teleconsultation_report_ia) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun document généré pour cette consultation'
                ], 404);
            }

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('medecin.consultations.ambulatory-document-pdf', [
                'consultation' => $consultation,
                'teleconsultation_report' => $consultation->teleconsultation_report_ia,
                'diagnostic_summary' => $consultation->diagnostic_summary_ia,
                'treatment_plan' => $consultation->treatment_plan_ia,
                'hygiene_instructions' => $consultation->hygiene_instructions_ia,
                'follow_up_plan' => $consultation->follow_up_plan_ia,
                'specialist_referral' => $consultation->specialist_referral_ia,
                'medication_instructions' => $consultation->medication_instructions_ia,
                'online_summary' => $consultation->online_summary_ia,
                'key_points' => $consultation->key_points_ia,
                'next_steps' => $consultation->next_steps_ia,
                'monitoring_points' => $consultation->monitoring_points_ia,
                'warning_signs' => $consultation->warning_signs_ia,
                'appointment_schedule' => $consultation->appointment_schedule_ia
            ]);

            $filename = 'consultation-ambulatoire-' . $consultation->id . '-' . now()->format('Y-m-d') . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération du PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupère une consultation avec vérification des permissions
     */
    private function getConsultation($consultationId)
    {
        return Consultation::with(['rendezVous.patient', 'rendezVous.medecin'])
            ->whereHas('rendezVous', function($query) {
                $query->where('medecin_id', Auth::id());
            })
            ->findOrFail($consultationId);
    }

    /**
     * Prépare les données de consultation pour l'IA
     */
    private function prepareConsultationData($consultation)
    {
        return [
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
    }
}
