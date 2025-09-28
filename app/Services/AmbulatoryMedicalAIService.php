<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AmbulatoryMedicalAIService
{
    private $googleApiKey;
    private $huggingFaceApiKey;

    public function __construct()
    {
        $this->googleApiKey = config('services.google.api_key');
        $this->huggingFaceApiKey = config('services.huggingface.api_key');
    }

    /**
     * Génère un compte-rendu de téléconsultation
     */
    public function generateTeleconsultationReport(array $consultationData): array
    {
        try {
            $prompt = $this->buildTeleconsultationPrompt($consultationData);
            $response = $this->callGoogleGemini($prompt);

            $normalized = $this->normalizeSchema($response, $consultationData);

            return array_merge(['success' => true], $normalized);
        } catch (\Exception $e) {
            Log::error('Erreur génération téléconsultation: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Erreur lors de la génération du compte-rendu de téléconsultation'
            ];
        }
    }

    /**
     * Génère des recommandations écrites personnalisées
     */
    public function generateWrittenRecommendations(array $consultationData): array
    {
        try {
            $prompt = $this->buildRecommendationsPrompt($consultationData);
            $response = $this->callGoogleGemini($prompt);

            $normalized = $this->normalizeSchema($response, $consultationData);

            return array_merge(['success' => true], $normalized);
        } catch (\Exception $e) {
            Log::error('Erreur génération recommandations: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Erreur lors de la génération des recommandations'
            ];
        }
    }

    /**
     * Génère un résumé de consultation en ligne
     */
    public function generateOnlineConsultationSummary(array $consultationData): array
    {
        try {
            $prompt = $this->buildOnlineSummaryPrompt($consultationData);
            $response = $this->callGoogleGemini($prompt);

            $normalized = $this->normalizeSchema($response, $consultationData);

            return array_merge(['success' => true], $normalized);
        } catch (\Exception $e) {
            Log::error('Erreur génération résumé en ligne: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Erreur lors de la génération du résumé'
            ];
        }
    }

    /**
     * Génère un plan de suivi personnalisé
     */
    public function generateFollowUpPlan(array $consultationData): array
    {
        try {
            $prompt = $this->buildFollowUpPrompt($consultationData);
            $response = $this->callGoogleGemini($prompt);

            $normalized = $this->normalizeSchema($response, $consultationData);

            return array_merge(['success' => true], $normalized);
        } catch (\Exception $e) {
            Log::error('Erreur génération plan de suivi: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Erreur lors de la génération du plan de suivi'
            ];
        }
    }

    /**
     * Construit le prompt pour la téléconsultation
     */
    private function buildTeleconsultationPrompt(array $data): string
    {
        $schema = $this->jsonSchemaString();
        $filledFields = $this->getFilledFields($data);

        return "
Tu es un assistant IA médical pour médecine de famille. Réponds STRICTEMENT en JSON valide conforme au schéma ci-dessous, sans markdown ni commentaires.

DONNÉES CONSULTATION (champs remplis uniquement):
{$filledFields}

SCHÉMA JSON:
{$schema}
";
    }

    /**
     * Construit le prompt pour les recommandations
     */
    private function buildRecommendationsPrompt(array $data): string
    {
        $schema = $this->jsonSchemaString();
        $filledFields = $this->getFilledFields($data);

        return "
Tu es un assistant IA médical. Génère des recommandations pour médecine ambulatoire. Réponds STRICTEMENT en JSON conforme au schéma.

DONNÉES CONSULTATION (champs remplis uniquement):
{$filledFields}

SCHÉMA JSON:
{$schema}
";
    }

    /**
     * Construit le prompt pour le résumé en ligne
     */
    private function buildOnlineSummaryPrompt(array $data): string
    {
        $schema = $this->jsonSchemaString();
        $filledFields = $this->getFilledFields($data);

        return "
Génère un résumé patient (max 200 mots) pour téléconsultation. Réponds STRICTEMENT en JSON selon le schéma.

DONNÉES CONSULTATION (champs remplis uniquement):
{$filledFields}

SCHÉMA JSON:
{$schema}
";
    }

    /**
     * Construit le prompt pour le plan de suivi
     */
    private function buildFollowUpPrompt(array $data): string
    {
        $schema = $this->jsonSchemaString();
        $filledFields = $this->getFilledFields($data);

        return "
Génère un plan de suivi ambulatoire. Réponds STRICTEMENT en JSON selon le schéma.

DONNÉES CONSULTATION (champs remplis uniquement):
{$filledFields}

SCHÉMA JSON:
{$schema}
";
    }

    /**
     * Appel à l'API Google Gemini
     */
    private function callGoogleGemini(string $prompt): array
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . $this->googleApiKey, [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.3,
                'maxOutputTokens' => 2000
            ]
        ]);

        if ($response->successful()) {
            $content = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? '';
            $content = $this->stripCodeFences($content);
            $decoded = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
                // Retour contrôlé si pas JSON strict
                return ['unstructured' => $content];
            }

            return $decoded;
        }

        throw new \Exception('Erreur API Gemini: ' . $response->body());
    }

    /**
     * Normalise la sortie du modèle vers un schéma stable
     */
    private function normalizeSchema(array $modelOutput, array $ctx): array
    {
        // Si le modèle a renvoyé déjà les sections, les mapper; sinon, insérer valeurs par défaut
        $consultation = [
            'patient' => $ctx['patient_name'] ?? null,
            'medecin' => $ctx['medecin_name'] ?? null,
            'date' => $ctx['date'] ?? null,
            'type' => $ctx['type'] ?? null,
            'motif' => $ctx['motif'] ?? null,
            'symptomes' => $ctx['symptomes'] ?? null,
            'examen_physique' => $ctx['examen_physique'] ?? null,
            'signes_vitaux' => [
                'tension_arterielle' => $ctx['tension_arterielle'] ?? null,
                'temperature_c' => $ctx['temperature'] ?? null,
                'frequence_cardiaque_bpm' => $ctx['frequence_cardiaque'] ?? null,
                'saturation_o2_pct' => $ctx['saturation_o2'] ?? null,
            ],
        ];

        // Si retour non structuré
        if (isset($modelOutput['unstructured'])) {
            return [
                'consultation' => $consultation,
                'diagnostic' => [
                    'summary' => $ctx['diagnostic_presume'] ?? null,
                    'notes' => null,
                ],
                'treatment' => [
                    'plan' => $ctx['medicaments_prescrits'] ?? null,
                    'medications' => [],
                ],
                'recommendations' => [
                    'hygiene' => null,
                    'follow_up' => $ctx['propositions_suivi'] ?? null,
                    'specialist_referral' => null,
                    'medication_instructions' => null,
                ],
                'follow_up' => [
                    'plan' => $ctx['propositions_suivi'] ?? null,
                    'monitoring_points' => null,
                    'warning_signs' => null,
                    'appointment_schedule' => null,
                ],
                'summary' => [
                    'patient_friendly' => $modelOutput['unstructured'],
                    'key_points' => [],
                    'next_steps' => null,
                ],
                'metadata' => [
                    'model' => 'gemini-2.0-flash',
                    'generated_at' => now()->toISOString(),
                ],
            ];
        }

        // Sinon, tenter d'extraire selon clés attendues
        return [
            'consultation' => $modelOutput['consultation'] ?? $consultation,
            'diagnostic' => $modelOutput['diagnostic'] ?? [
                'summary' => $modelOutput['diagnostic_summary'] ?? ($ctx['diagnostic_presume'] ?? null),
                'notes' => $modelOutput['diagnostic_notes'] ?? null,
            ],
            'treatment' => $modelOutput['treatment'] ?? [
                'plan' => $modelOutput['treatment_plan'] ?? ($ctx['medicaments_prescrits'] ?? null),
                'medications' => $modelOutput['medications'] ?? [],
            ],
            'recommendations' => $modelOutput['recommendations'] ?? [
                'hygiene' => $modelOutput['hygiene_instructions'] ?? null,
                'follow_up' => $modelOutput['follow_up_plan'] ?? ($ctx['propositions_suivi'] ?? null),
                'specialist_referral' => $modelOutput['specialist_referral'] ?? null,
                'medication_instructions' => $modelOutput['medication_instructions'] ?? null,
            ],
            'follow_up' => $modelOutput['follow_up'] ?? [
                'plan' => $modelOutput['follow_up_plan'] ?? ($ctx['propositions_suivi'] ?? null),
                'monitoring_points' => $modelOutput['monitoring_points'] ?? null,
                'warning_signs' => $modelOutput['warning_signs'] ?? null,
                'appointment_schedule' => $modelOutput['appointment_schedule'] ?? null,
            ],
            'summary' => $modelOutput['summary'] ?? [
                'patient_friendly' => $modelOutput['summary'] ?? null,
                'key_points' => $modelOutput['key_points'] ?? [],
                'next_steps' => $modelOutput['next_steps'] ?? null,
            ],
            'metadata' => $modelOutput['metadata'] ?? [
                'model' => 'gemini-2.0-flash',
                'generated_at' => now()->toISOString(),
            ],
        ];
    }

    private function stripCodeFences(string $text): string
    {
        $t = trim($text);
        // Remove ```json ... ``` or ``` ... ``` fences
        if (preg_match('/^```[a-zA-Z]*\n([\s\S]*?)```$/', $t, $m)) {
            return trim($m[1]);
        }
        return $t;
    }

    private function jsonSchemaString(): string
    {
        // Schéma minimal, lisible et stable pour humains et logiciels
        return json_encode([
            'type' => 'object',
            'required' => ['consultation','diagnostic','treatment','recommendations','follow_up','summary','metadata'],
            'properties' => [
                'consultation' => [
                    'type' => 'object',
                    'properties' => [
                        'patient' => ['type' => ['string','null']],
                        'medecin' => ['type' => ['string','null']],
                        'date' => ['type' => ['string','null']],
                        'type' => ['type' => ['string','null']],
                        'motif' => ['type' => ['string','null']],
                        'symptomes' => ['type' => ['string','null']],
                        'examen_physique' => ['type' => ['string','null']],
                        'signes_vitaux' => [
                            'type' => 'object',
                            'properties' => [
                                'tension_arterielle' => ['type' => ['string','null']],
                                'temperature_c' => ['type' => ['string','null','number']],
                                'frequence_cardiaque_bpm' => ['type' => ['string','null','number']],
                                'saturation_o2_pct' => ['type' => ['string','null','number']],
                            ]
                        ],
                    ]
                ],
                'diagnostic' => [
                    'type' => 'object',
                    'properties' => [
                        'summary' => ['type' => ['string','null']],
                        'notes' => ['type' => ['string','null']],
                        'codes' => ['type' => 'array', 'items' => ['type' => 'string']]
                    ]
                ],
                'treatment' => [
                    'type' => 'object',
                    'properties' => [
                        'plan' => ['type' => ['string','null']],
                        'medications' => ['type' => 'array', 'items' => [
                            'type' => 'object',
                            'properties' => [
                                'name' => ['type' => 'string'],
                                'dose' => ['type' => 'string'],
                                'frequency' => ['type' => 'string'],
                                'duration' => ['type' => 'string']
                            ]
                        ]]
                    ]
                ],
                'recommendations' => [
                    'type' => 'object',
                    'properties' => [
                        'hygiene' => ['type' => ['string','null']],
                        'follow_up' => ['type' => ['string','null']],
                        'specialist_referral' => ['type' => ['string','null']],
                        'medication_instructions' => ['type' => ['string','null']]
                    ]
                ],
                'follow_up' => [
                    'type' => 'object',
                    'properties' => [
                        'plan' => ['type' => ['string','null']],
                        'monitoring_points' => ['type' => ['string','null']],
                        'warning_signs' => ['type' => ['string','null']],
                        'appointment_schedule' => ['type' => ['string','null']]
                    ]
                ],
                'summary' => [
                    'type' => 'object',
                    'properties' => [
                        'patient_friendly' => ['type' => ['string','null']],
                        'key_points' => ['type' => 'array', 'items' => ['type' => 'string']],
                        'next_steps' => ['type' => ['string','null']]
                    ]
                ],
                'metadata' => [
                    'type' => 'object',
                    'properties' => [
                        'model' => ['type' => 'string'],
                        'generated_at' => ['type' => 'string']
                    ]
                ]
            ]
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Génère un document complet de consultation ambulatoire
     */
    public function generateCompleteAmbulatoryDocument(array $consultationData): array
    {
        try {
            $teleconsultation = $this->generateTeleconsultationReport($consultationData);
            $recommendations = $this->generateWrittenRecommendations($consultationData);
            $summary = $this->generateOnlineConsultationSummary($consultationData);
            $followUp = $this->generateFollowUpPlan($consultationData);

            return [
                'success' => true,
                'teleconsultation_report' => $teleconsultation['teleconsultation_report'] ?? '',
                'diagnostic_summary' => $teleconsultation['diagnostic_summary'] ?? '',
                'treatment_plan' => $teleconsultation['treatment_plan'] ?? '',
                'hygiene_instructions' => $recommendations['hygiene_instructions'] ?? '',
                'follow_up_plan' => $recommendations['follow_up_plan'] ?? '',
                'specialist_referral' => $recommendations['specialist_referral'] ?? '',
                'medication_instructions' => $recommendations['medication_instructions'] ?? '',
                'summary' => $summary['summary'] ?? '',
                'key_points' => $summary['key_points'] ?? '',
                'next_steps' => $summary['next_steps'] ?? '',
                'monitoring_points' => $followUp['monitoring_points'] ?? '',
                'warning_signs' => $followUp['warning_signs'] ?? '',
                'appointment_schedule' => $followUp['appointment_schedule'] ?? ''
            ];
        } catch (\Exception $e) {
            Log::error('Erreur génération document ambulatoire: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Erreur lors de la génération du document complet'
            ];
        }
    }

    /**
     * Filtre les champs remplis et les formate pour le prompt
     */
    private function getFilledFields(array $data): string
    {
        $fields = [];

        // Champs de base toujours présents
        if (!empty($data['patient_name'])) {
            $fields[] = "- Patient: {$data['patient_name']}";
        }
        if (!empty($data['medecin_name'])) {
            $fields[] = "- Médecin: {$data['medecin_name']}";
        }
        if (!empty($data['date'])) {
            $fields[] = "- Date: {$data['date']}";
        }
        if (!empty($data['type'])) {
            $fields[] = "- Type: {$data['type']}";
        }

        // Champs de consultation
        if (!empty($data['motif'])) {
            $fields[] = "- Motif: {$data['motif']}";
        }
        if (!empty($data['symptomes'])) {
            $fields[] = "- Symptômes: {$data['symptomes']}";
        }
        if (!empty($data['examen_physique'])) {
            $fields[] = "- Examen physique: {$data['examen_physique']}";
        }

        // Signes vitaux
        $vitals = [];
        if (!empty($data['tension_arterielle'])) {
            $vitals[] = "TA: {$data['tension_arterielle']}";
        }
        if (!empty($data['temperature'])) {
            $vitals[] = "Température: {$data['temperature']}";
        }
        if (!empty($data['frequence_cardiaque'])) {
            $vitals[] = "FC: {$data['frequence_cardiaque']}";
        }
        if (!empty($data['saturation_o2'])) {
            $vitals[] = "Saturation O2: {$data['saturation_o2']}";
        }
        if (!empty($vitals)) {
            $fields[] = "- Signes vitaux: " . implode(', ', $vitals);
        }

        // Diagnostic et traitement
        if (!empty($data['diagnostic_presume'])) {
            $fields[] = "- Diagnostic présumé: {$data['diagnostic_presume']}";
        }
        if (!empty($data['medicaments_prescrits'])) {
            $fields[] = "- Traitement prescrit: {$data['medicaments_prescrits']}";
        }
        if (!empty($data['propositions_suivi'])) {
            $fields[] = "- Suivi proposé: {$data['propositions_suivi']}";
        }
        if (!empty($data['gravite'])) {
            $fields[] = "- Gravité: {$data['gravite']}";
        }

        return implode("\n", $fields);
    }
}
