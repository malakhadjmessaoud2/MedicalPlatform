<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FreeMedicalAIService
{
    private $huggingFaceApiKey;
    private $geminiApiKey;

    public function __construct()
    {
        $this->huggingFaceApiKey = config('services.huggingface.api_key');
        $this->geminiApiKey = config('services.google.api_key');
    }

    /**
     * Génère un compte-rendu avec Hugging Face (Gratuit)
     */
    public function generateCompteRenduHuggingFace(array $consultationData): array
    {
        try {
            $prompt = $this->buildMedicalPrompt($consultationData);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->huggingFaceApiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api-inference.huggingface.co/models/microsoft/DialoGPT-medium', [
                'inputs' => $prompt,
                'parameters' => [
                    'max_length' => 1000,
                    'temperature' => 0.7,
                    'do_sample' => true
                ]
            ]);

            if ($response->successful()) {
                $generatedText = $response->json()[0]['generated_text'];

                return [
                    'success' => true,
                    'compte_rendu' => $this->formatCompteRendu($generatedText, $consultationData),
                    'resume' => $this->generateResume($generatedText),
                    'recommandations' => $this->generateRecommandations($consultationData)
                ];
            }

            throw new \Exception('Erreur API Hugging Face: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('Erreur Hugging Face: ' . $e->getMessage());

            // Fallback: génération basique sans IA
            return $this->generateBasicCompteRendu($consultationData);
        }
    }

    /**
     * Génère un compte-rendu avec Google Gemini (Gratuit)
     */
    public function generateCompteRenduGemini(array $consultationData): array
    {
        try {
            $prompt = $this->buildMedicalPrompt($consultationData);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . $this->geminiApiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.3,
                    'maxOutputTokens' => 1000
                ]
            ]);

            if ($response->successful()) {
                $generatedText = $response->json()['candidates'][0]['content']['parts'][0]['text'];

                return [
                    'success' => true,
                    'compte_rendu' => $this->formatCompteRendu($generatedText, $consultationData),
                    'resume' => $this->generateResume($generatedText),
                    'recommandations' => $this->generateRecommandations($consultationData)
                ];
            }

            throw new \Exception('Erreur API Gemini: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('Erreur Gemini: ' . $e->getMessage());
            return $this->generateBasicCompteRendu($consultationData);
        }
    }

    /**
     * Génération basique sans IA (Fallback)
     */
    private function generateBasicCompteRendu(array $data): array
    {
        $compteRendu = $this->buildBasicCompteRendu($data);
        $resume = $this->buildBasicResume($data);
        $recommandations = $this->buildBasicRecommandations($data);

        return [
            'success' => true,
            'compte_rendu' => $compteRendu,
            'resume' => $resume,
            'recommandations' => $recommandations,
            'generated_by' => 'template_basic'
        ];
    }

    /**
     * Construit le prompt médical
     */
    private function buildMedicalPrompt(array $data): string
    {
        return "
        CONSULTATION MÉDICALE - GÉNÉRATION DE COMPTE-RENDU

        PATIENT: {$data['patient_name']}
        MÉDECIN: {$data['medecin_name']}
        DATE: {$data['date']}
        TYPE: {$data['type']}

        MOTIF DE CONSULTATION:
        {$data['motif']}

        SYMPTÔMES:
        {$data['symptomes']}

        EXAMEN CLINIQUE:
        {$data['examen_physique']}

        PARAMÈTRES VITAUX:
        - Tension artérielle: {$data['tension_arterielle']}
        - Température: {$data['temperature']}°C
        - Fréquence cardiaque: {$data['frequence_cardiaque']} bpm

        DIAGNOSTIC PRÉSUMÉ:
        {$data['diagnostic_presume']}

        TRAITEMENT PRESCRIT:
        {$data['medicaments_prescrits']}

        Veuillez générer un compte-rendu médical professionnel structuré.
        ";
    }

    /**
     * Formate le compte-rendu généré
     */
    private function formatCompteRendu(string $generatedText, array $data): string
    {
        $template = "
        ============================================
        COMPTE-RENDU DE CONSULTATION MÉDICALE
        ============================================

        Patient: {$data['patient_name']}
        Médecin: {$data['medecin_name']}
        Date: {$data['date']}
        Type de consultation: {$data['type']}

        I. MOTIF DE CONSULTATION
        {$data['motif']}

        II. ANAMNÈSE
        {$data['symptomes']}

        III. EXAMEN CLINIQUE
        {$data['examen_physique']}

        IV. PARAMÈTRES VITAUX
        - Tension artérielle: {$data['tension_arterielle']}
        - Température: {$data['temperature']}°C
        - Fréquence cardiaque: {$data['frequence_cardiaque']} bpm
        - Saturation O2: {$data['saturation_o2']}%

        V. DIAGNOSTIC
        {$data['diagnostic_presume']}

        VI. TRAITEMENT PRESCRIT
        {$data['medicaments_prescrits']}

        VII. RECOMMANDATIONS
        {$data['propositions_suivi']}

        ============================================
        ";

        return $template;
    }

    /**
     * Génère un résumé court
     */
    private function generateResume(string $text): string
    {
        // Extraction des points clés
        $lines = explode("\n", $text);
        $keyPoints = array_slice($lines, 0, 3);

        return "Résumé: " . implode(" ", $keyPoints);
    }

    /**
     * Génère des recommandations
     */
    private function generateRecommandations(array $data): string
    {
        $recommandations = [];

        if (!empty($data['propositions_suivi'])) {
            $recommandations[] = "Suivi: " . $data['propositions_suivi'];
        }

        if (!empty($data['examens_controle'])) {
            $recommandations[] = "Examens de contrôle: " . $data['examens_controle'];
        }

        if (!empty($data['instructions_particulieres'])) {
            $recommandations[] = "Instructions particulières: " . $data['instructions_particulieres'];
        }

        return implode("\n", $recommandations);
    }

    /**
     * Compte-rendu basique (template)
     */
    private function buildBasicCompteRendu(array $data): string
    {
        return "
        ============================================
        COMPTE-RENDU DE CONSULTATION MÉDICALE
        ============================================

        Patient: {$data['patient_name']}
        Médecin: {$data['medecin_name']}
        Date: {$data['date']}
        Type: {$data['type']}

        MOTIF: {$data['motif']}
        SYMPTÔMES: {$data['symptomes']}
        DIAGNOSTIC: {$data['diagnostic_presume']}
        TRAITEMENT: {$data['medicaments_prescrits']}
        SUIVI: {$data['propositions_suivi']}

        ============================================
        ";
    }

    /**
     * Résumé basique
     */
    private function buildBasicResume(array $data): string
    {
        return "Consultation {$data['type']} - {$data['motif']}. Diagnostic: {$data['diagnostic_presume']}. Traitement prescrit.";
    }

    /**
     * Recommandations basiques
     */
    private function buildBasicRecommandations(array $data): string
    {
        return "Suivi recommandé: {$data['propositions_suivi']}";
    }

    /**
     * Génère un résumé court optimisé
     */
    public function generateResumeCourt(array $consultationData): array
    {
        try {
            $prompt = "Résumé médical court (max 100 mots): {$consultationData['motif']} - {$consultationData['diagnostic_presume']} - {$consultationData['medicaments_prescrits']}";

            // Essayer Gemini d'abord (plus rapide)
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . $this->geminiApiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.3,
                    'maxOutputTokens' => 200
                ]
            ]);

            if ($response->successful()) {
                $resume = $response->json()['candidates'][0]['content']['parts'][0]['text'];

                return [
                    'success' => true,
                    'resume' => $resume
                ];
            }

            // Fallback: résumé basique
            return [
                'success' => true,
                'resume' => $this->buildBasicResume($consultationData)
            ];

        } catch (\Exception $e) {
            Log::error('Erreur génération résumé court: ' . $e->getMessage());
            return [
                'success' => true,
                'resume' => $this->buildBasicResume($consultationData)
            ];
        }
    }

    /**
     * Génère une lettre de sortie simple
     */
    public function generateLettreSortieSimple(array $consultationData): array
    {
        $lettre = "
        À l'attention du médecin traitant,

        Objet: Lettre de sortie - {$consultationData['patient_name']}

        Nous avons reçu en consultation le patient {$consultationData['patient_name']} le {$consultationData['date']}.

        Motif de consultation: {$consultationData['motif']}
        Diagnostic établi: {$consultationData['diagnostic_presume']}
        Traitement prescrit: {$consultationData['medicaments_prescrits']}

        Recommandations de suivi: {$consultationData['propositions_suivi']}

        Cordialement,
        Dr. {$consultationData['medecin_name']}
        ";

        return [
            'success' => true,
            'lettre_sortie' => $lettre
        ];
    }
}
