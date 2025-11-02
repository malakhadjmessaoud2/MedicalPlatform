<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service ChatDoctor pour intégration avec le modèle Mistral-7B-Instruct-v0.2
 *
 * Utilise l'API OpenAI-compatible de Hugging Face Router (recommandé)
 * Documentation: https://huggingface.co/mistralai/Mistral-7B-Instruct-v0.2
 */
class ChatDoctorService
{
    /**
     * Vérifie si on doit utiliser l'API Router (OpenAI-compatible)
     */
    private function useRouter()
    {
        return config('services.huggingface.use_router', true);
    }

    /**
     * Construit l'URL de l'endpoint Router (OpenAI-compatible)
     */
    private function getRouterEndpoint()
    {
        return config('services.huggingface.router_url', 'https://router.huggingface.co/v1') . '/chat/completions';
    }

    /**
     * Construit l'URL de l'endpoint classique (fallback)
     */
    private function getClassicEndpoint()
    {
        $model = config('services.huggingface.model', 'mistralai/Mistral-7B-Instruct-v0.2');
        $baseUrl = config('services.huggingface.api_url', 'https://api-inference.huggingface.co/models/');
        return rtrim($baseUrl, '/') . '/' . $model;
    }

    /**
     * Construit le nom du modèle avec le provider pour le Router
     * Format: model:featherless-ai
     */
    private function getModelWithProvider()
    {
        $model = config('services.huggingface.model', 'mistralai/Mistral-7B-Instruct-v0.2');
        return $model . ':featherless-ai';
    }

    /**
     * Envoie une question au modèle Mistral-7B-Instruct-v0.2
     * Utilise l'API Router (OpenAI-compatible) par défaut
     *
     * @param string $message Question du patient
     * @return array Réponse du modèle ou erreur
     */
    public function ask($message)
    {
        // Récupérer le token depuis la configuration
        $token = config('services.huggingface.token') ?? config('services.huggingface.api_key');

        if (empty($token)) {
            Log::error('Token Hugging Face non configuré pour Mistral');
            return [
                'error' => 'Configuration manquante',
                'details' => 'Veuillez configurer HUGGINGFACE_API_KEY dans votre fichier .env'
            ];
        }

        // Utiliser l'API Router par défaut (recommandé)
        if ($this->useRouter()) {
            return $this->askViaRouter($message, $token);
        }

        // Fallback : utiliser l'API classique
        return $this->askViaClassic($message, $token);
    }

    /**
     * Utilise l'API Router (OpenAI-compatible) - RECOMMANDÉ
     * Format standard OpenAI avec messages et roles
     */
    private function askViaRouter($message, $token)
    {
        $endpoint = $this->getRouterEndpoint();
        $modelWithProvider = $this->getModelWithProvider();

        Log::info("Appel Mistral via Router API - Modèle: {$modelWithProvider}");
        Log::info("Message: " . substr($message, 0, 150));

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type'  => 'application/json',
            ])->timeout(90)->post($endpoint, [
                'model' => $modelWithProvider,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => trim($message)
                    ]
                ],
                'max_tokens' => 512,
                'temperature' => 0.7,
                'top_p' => 0.95
            ]);

            if ($response->failed()) {
                $statusCode = $response->status();
                $errorBody = $response->body();
                $errorData = $response->json();

                Log::error("Erreur API Mistral Router - Status: {$statusCode}, Message: " . substr($errorBody, 0, 200));

                return [
                    'error' => 'API error',
                    'status' => $statusCode,
                    'details' => $errorData ?? $errorBody
                ];
            }

            $data = $response->json();

            // Format OpenAI : extraction du message
            if (isset($data['choices'][0]['message']['content'])) {
                $generatedText = $data['choices'][0]['message']['content'];

                Log::info("Réponse Mistral Router reçue avec succès");

                // Retourner au format attendu (compatible avec l'ancien format)
                return [
                    [
                        'generated_text' => $generatedText
                    ]
                ];
            }

            // Retourner la réponse brute si format différent
            Log::info("Réponse Mistral Router reçue (format alternatif)");
            return $data;

        } catch (\Exception $e) {
            Log::error("Exception lors de l'appel Mistral Router: " . $e->getMessage());
            return [
                'error' => 'Exception',
                'details' => $e->getMessage()
            ];
        }
    }

    /**
     * Utilise l'API classique (fallback)
     */
    private function askViaClassic($message, $token)
    {
        // Formater selon le format Mistral: <s>[INST] question [/INST]
        $formattedPrompt = "<s>[INST] " . trim($message) . " [/INST]";
        $endpoint = $this->getClassicEndpoint();

        Log::info("Appel Mistral API classique - Modèle: " . config('services.huggingface.model'));
        Log::info("Prompt formaté: " . substr($formattedPrompt, 0, 150));

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type'  => 'application/json',
            ])->timeout(90)->post($endpoint, [
                'inputs' => $formattedPrompt,
                'parameters' => [
                    'max_new_tokens' => 512,
                    'temperature' => 0.7,
                    'top_p' => 0.95,
                    'return_full_text' => false
                ]
            ]);

            if ($response->failed()) {
                $statusCode = $response->status();
                $errorBody = $response->body();

                Log::error("Erreur API Mistral - Status: {$statusCode}, Message: " . substr($errorBody, 0, 200));

                return [
                    'error' => 'API error',
                    'status' => $statusCode,
                    'details' => $errorBody
                ];
            }

            $data = $response->json();

            // Vérifier si le modèle est en cours de chargement
            if (isset($data['error'])) {
                $errorMsg = is_string($data['error']) ? $data['error'] : json_encode($data['error']);
                if (stripos($errorMsg, 'loading') !== false) {
                    return [
                        'error' => 'Modèle en chargement',
                        'details' => 'Le modèle est en cours de chargement. Veuillez réessayer dans 30-60 secondes.'
                    ];
                }
                return [
                    'error' => 'Erreur du modèle',
                    'details' => $errorMsg
                ];
            }

            Log::info("Réponse Mistral reçue avec succès");
            return $data;

        } catch (\Exception $e) {
            Log::error("Exception lors de l'appel Mistral: " . $e->getMessage());
            return [
                'error' => 'Exception',
                'details' => $e->getMessage()
            ];
        }
    }
}

