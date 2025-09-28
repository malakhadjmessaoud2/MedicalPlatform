<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MedicalAIService
{
    private $openaiApiKey;
    private $anthropicApiKey;
    private $googleApiKey;

    public function __construct()
    {
        $this->openaiApiKey = config('services.openai.api_key');
        $this->anthropicApiKey = config('services.anthropic.api_key');
        $this->googleApiKey = config('services.google.api_key');
    }

    /**
     * Génère un compte-rendu complet de consultation
     */
    public function generateCompteRendu(array $consultationData): array
    {
        try {
            $prompt = $this->buildMedicalPrompt($consultationData);

            // Utiliser OpenAI GPT-4 pour la génération
            $response = $this->callOpenAI($prompt);

            return [
                'success' => true,
                'compte_rendu' => $response['compte_rendu'],
                'resume' => $response['resume'],
                'recommandations' => $response['recommandations']
            ];
        } catch (\Exception $e) {
            Log::error('Erreur génération compte-rendu IA: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Erreur lors de la génération du compte-rendu'
            ];
        }
    }

    /**
     * Génère un résumé court de consultation
     */
    public function generateResume(array $consultationData): array
    {
        try {
            $prompt = $this->buildResumePrompt($consultationData);

            // Utiliser Claude pour les résumés
            $response = $this->callClaude($prompt);

            return [
                'success' => true,
                'resume' => $response
            ];
        } catch (\Exception $e) {
            Log::error('Erreur génération résumé IA: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Erreur lors de la génération du résumé'
            ];
        }
    }

    /**
     * Construit le prompt pour le compte-rendu complet
     */
    private function buildMedicalPrompt(array $data): string
    {
        $patientInfo = $this->getPatientInfo($data);
        $clinicalData = $this->formatClinicalData($data);

        return "
        En tant qu'assistant IA médical, génère un compte-rendu professionnel de consultation basé sur les données suivantes :

        INFORMATIONS PATIENT :
        {$patientInfo}

        DONNÉES CLINIQUES :
        {$clinicalData}

        Veuillez générer :
        1. Un COMPTE-RENDU COMPLET structuré avec :
           - Motif de consultation
           - Anamnèse
           - Examen clinique
           - Diagnostic
           - Traitement prescrit
           - Suivi recommandé

        2. Un RÉSUMÉ EXÉCUTIF (2-3 phrases)

        3. Des RECOMMANDATIONS SPÉCIFIQUES

        Format de réponse en JSON :
        {
            \"compte_rendu\": \"...\",
            \"resume\": \"...\",
            \"recommandations\": \"...\"
        }
        ";
    }

    /**
     * Construit le prompt pour le résumé court
     */
    private function buildResumePrompt(array $data): string
    {
        return "
        Génère un résumé médical concis (maximum 150 mots) de cette consultation :

        Type: {$data['type']}
        Motif: {$data['motif']}
        Symptômes: {$data['symptomes']}
        Diagnostic: {$data['diagnostic_presume']}
        Traitement: {$data['medicaments_prescrits']}

        Le résumé doit être professionnel et compréhensible par le patient.
        ";
    }

    /**
     * Appel à l'API OpenAI
     */
    private function callOpenAI(string $prompt): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->openaiApiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Tu es un assistant IA médical spécialisé dans la rédaction de comptes-rendus de consultation. Tu dois générer des documents professionnels, précis et structurés.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'temperature' => 0.3,
            'max_tokens' => 2000
        ]);

        if ($response->successful()) {
            $content = $response->json()['choices'][0]['message']['content'];
            return json_decode($content, true);
        }

        throw new \Exception('Erreur API OpenAI: ' . $response->body());
    }

    /**
     * Appel à l'API Claude
     */
    private function callClaude(string $prompt): string
    {
        $response = Http::withHeaders([
            'x-api-key' => $this->anthropicApiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.anthropic.com/v1/messages', [
            'model' => 'claude-3-5-sonnet-20241022',
            'max_tokens' => 1000,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ]
        ]);

        if ($response->successful()) {
            return $response->json()['content'][0]['text'];
        }

        throw new \Exception('Erreur API Claude: ' . $response->body());
    }

    /**
     * Formate les informations patient
     */
    private function getPatientInfo(array $data): string
    {
        return "
        - Type de consultation: {$data['type']}
        - Date: {$data['date']}
        - Motif: {$data['motif']}
        - Gravité: {$data['gravite']}
        ";
    }

    /**
     * Formate les données cliniques
     */
    private function formatClinicalData(array $data): string
    {
        $clinical = [];

        if (!empty($data['symptomes'])) {
            $clinical[] = "Symptômes: {$data['symptomes']}";
        }

        if (!empty($data['tension_arterielle'])) {
            $clinical[] = "Tension artérielle: {$data['tension_arterielle']}";
        }

        if (!empty($data['temperature'])) {
            $clinical[] = "Température: {$data['temperature']}°C";
        }

        if (!empty($data['examen_physique'])) {
            $clinical[] = "Examen physique: {$data['examen_physique']}";
        }

        if (!empty($data['diagnostic_presume'])) {
            $clinical[] = "Diagnostic présumé: {$data['diagnostic_presume']}";
        }

        if (!empty($data['medicaments_prescrits'])) {
            $clinical[] = "Traitement prescrit: {$data['medicaments_prescrits']}";
        }

        return implode("\n", $clinical);
    }

    /**
     * Génère une lettre de sortie
     */
    public function generateLettreSortie(array $consultationData): array
    {
        try {
            $prompt = $this->buildLettreSortiePrompt($consultationData);
            $response = $this->callOpenAI($prompt);

            return [
                'success' => true,
                'lettre_sortie' => $response
            ];
        } catch (\Exception $e) {
            Log::error('Erreur génération lettre de sortie: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Erreur lors de la génération de la lettre de sortie'
            ];
        }
    }

    /**
     * Construit le prompt pour la lettre de sortie
     */
    private function buildLettreSortiePrompt(array $data): string
    {
        return "
        Génère une lettre de sortie médicale professionnelle basée sur cette consultation :

        Patient: [Nom du patient]
        Date: {$data['date']}
        Motif: {$data['motif']}
        Diagnostic: {$data['diagnostic_presume']}
        Traitement: {$data['medicaments_prescrits']}
        Suivi: {$data['propositions_suivi']}

        La lettre doit être adressée au médecin traitant et inclure :
        - Résumé de la consultation
        - Diagnostic établi
        - Traitement prescrit
        - Recommandations de suivi
        - Coordonnées du médecin prescripteur
        ";
    }
}
