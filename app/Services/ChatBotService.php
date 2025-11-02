<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

/**
 * Service de chatbot médical conversationnel
 *
 * Gère les interactions avec les API d'IA pour fournir des conseils généraux de santé
 * sans poser de diagnostic ni recommander de médicaments spécifiques.
 */
class ChatBotService
{
    private $openaiApiKey;
    private $anthropicApiKey;
    private $huggingfaceApiKey;
    private $googleApiKey;
    private $provider;

    public function __construct()
    {
        $this->openaiApiKey = config('services.openai.api_key');
        $this->anthropicApiKey = config('services.anthropic.api_key');
        $this->huggingfaceApiKey = config('services.huggingface.api_key');
        $this->googleApiKey = config('services.google.api_key');

        // Hugging Face est la priorité absolue pour le chatbot médical
        // Utilisation exclusive de modèles médicaux Hugging Face
        if (!empty($this->huggingfaceApiKey)) {
            $this->provider = 'huggingface';
        } else {
            $this->provider = null;
        }
    }

    /**
     * Génère une réponse du chatbot basée sur la question et l'historique
     *
     * @param string $message Message de l'utilisateur
     * @param array $conversationHistory Historique de la conversation
     * @return array Réponse structurée avec analyse, conseils et recommandations
     */
    public function generateResponse(string $message, array $conversationHistory = []): array
    {
        // Vérifier que la clé API Hugging Face est configurée
        if (empty($this->huggingfaceApiKey)) {
            Log::error('Clé API Hugging Face non configurée');
            return [
                'success' => false,
                'error' => 'Configuration manquante. Veuillez configurer HUGGINGFACE_API_KEY dans votre fichier .env'
            ];
        }

        // Vérifier que le token commence par 'hf_'
        if (!str_starts_with($this->huggingfaceApiKey, 'hf_')) {
            Log::error('Format de token Hugging Face invalide (doit commencer par hf_)');
            return [
                'success' => false,
                'error' => 'Format de token invalide. Le token Hugging Face doit commencer par "hf_". Veuillez vérifier HUGGINGFACE_API_KEY dans votre fichier .env'
            ];
        }

        // Construire le prompt avec le contexte médical
        $userPrompt = $this->buildUserPrompt($message, $conversationHistory);

        // UTILISATION UNIQUE DE Mistral-7B-Instruct-v0.2
        try {
            $model = config('services.huggingface.model', 'mistralai/Mistral-7B-Instruct-v0.2');
            Log::info("Génération de réponse via Hugging Face avec le modèle {$model}");
            $response = $this->callHuggingFace($userPrompt, $conversationHistory);

            // Si on arrive ici, Hugging Face a fonctionné
            Log::info("Réponse générée avec succès via l'API Hugging Face ({$model})");
            return $this->formatResponse($response);

        } catch (\Exception $e) {
            Log::error("Échec de l'API Hugging Face: " . $e->getMessage());

            // Retourner une erreur explicite au lieu d'utiliser le fallback
            // L'utilisateur doit savoir que l'API a échoué
            return [
                'success' => false,
                'error' => 'Impossible de contacter l\'API Hugging Face. Erreur: ' . $e->getMessage(),
                'error_details' => config('app.debug') ? $e->getTraceAsString() : null
            ];
        }
    }

    /**
     * Construit le prompt système pour le chatbot médical
     * Format conversationnel: Human (patient) / Assistant
     */
    private function buildSystemPrompt(): string
    {
        return "Tu es un assistant médical virtuel empathique et professionnel. Tu réponds aux questions de santé en français de manière claire, bienveillante et informative.

FORMAT DE CONVERSATION :
- Utilise le format \"Human:\" pour les questions du patient
- Utilise \"Assistant:\" pour tes réponses
- Sois naturel et conversationnel

RÈGLES ABSOLUES :
- Tu ne poses JAMAIS de diagnostic médical
- Tu ne recommandes JAMAIS de médicaments spécifiques (noms de médicaments, dosages)
- Tu fournis uniquement des conseils généraux et des informations éducatives
- Tu es toujours empathique, rassurant et professionnel
- Tu encourages systématiquement la consultation d'un professionnel de santé

TON STYLE :
- Réponds de manière naturelle et conversationnelle
- Adapte ta réponse au contenu spécifique de la question posée (analyse la question du patient)
- Sois précis et pertinent, évite les réponses génériques répétées
- Utilise un langage accessible et compréhensible
- Personnalise tes réponses selon le contexte de la question

IMPORTANT : Chaque réponse doit se terminer par :
\"⚠️ Je ne remplace pas un médecin. Pour un avis personnalisé, consultez un professionnel de santé.\"";
    }

    /**
     * Construit le prompt utilisateur avec l'historique de conversation
     * Format simple pour les modèles médicaux Hugging Face
     */
    private function buildUserPrompt(string $message, array $conversationHistory): string
    {
        // Pour les modèles médicaux, utiliser directement la question du patient
        // Format simple comme dans l'exemple : "Quels sont les symptômes de la grippe ?"
        if (empty($conversationHistory)) {
            return $message;
        }

        // Si on a un historique, construire un prompt conversationnel simple
        $prompt = $message;

        // Ajouter le contexte récent (limité aux 3 derniers échanges)
        $recentHistory = array_slice($conversationHistory, -3);
        if (!empty($recentHistory)) {
            $context = "\n\nContexte précédent:\n";
            foreach ($recentHistory as $entry) {
                if (isset($entry['role']) && isset($entry['content'])) {
                    $role = $entry['role'] === 'user' ? 'Patient' : 'Assistant';
                    $context .= "{$role}: {$entry['content']}\n";
                }
            }
            $prompt = $context . "Patient: {$message}";
        }

        return $prompt;
    }

    /**
     * Appel à l'API OpenAI
     */
    private function callOpenAI(string $systemPrompt, string $userPrompt, array $conversationHistory): string
    {
        // Construire les messages avec l'historique
        $messages = [
            [
                'role' => 'system',
                'content' => $systemPrompt
            ]
        ];

        // Ajouter l'historique (limité aux 10 derniers messages pour éviter les coûts)
        foreach (array_slice($conversationHistory, -10) as $entry) {
            if (isset($entry['role']) && isset($entry['content'])) {
                $messages[] = [
                    'role' => $entry['role'],
                    'content' => $entry['content']
                ];
            }
        }

        // Ajouter le message actuel
        $messages[] = [
            'role' => 'user',
            'content' => $userPrompt
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->openaiApiKey,
            'Content-Type' => 'application/json',
        ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
            'model' => config('services.openai.model', 'gpt-4'),
            'messages' => $messages,
            'temperature' => 0.7, // Plus créatif et empathique
            'max_tokens' => 1000
        ]);

        if ($response->successful()) {
            return $response->json()['choices'][0]['message']['content'];
        }

        throw new \Exception('Erreur API OpenAI: ' . $response->body());
    }

    /**
     * Appel à l'API Anthropic (Claude)
     */
    private function callAnthropic(string $systemPrompt, string $userPrompt, array $conversationHistory): string
    {
        // Construire les messages avec l'historique
        $messages = [];

        // Ajouter l'historique
        foreach (array_slice($conversationHistory, -10) as $entry) {
            if (isset($entry['role']) && isset($entry['content'])) {
                $messages[] = [
                    'role' => $entry['role'] === 'user' ? 'user' : 'assistant',
                    'content' => $entry['content']
                ];
            }
        }

        // Ajouter le message actuel
        $messages[] = [
            'role' => 'user',
            'content' => $userPrompt
        ];

        $response = Http::withHeaders([
            'x-api-key' => $this->anthropicApiKey,
            'anthropic-version' => '2023-06-01',
            'Content-Type' => 'application/json',
        ])->timeout(30)->post('https://api.anthropic.com/v1/messages', [
            'model' => config('services.anthropic.model', 'claude-3-5-sonnet-20241022'),
            'max_tokens' => 1000,
            'system' => $systemPrompt,
            'messages' => $messages
        ]);

        if ($response->successful()) {
            return $response->json()['content'][0]['text'];
        }

        throw new \Exception('Erreur API Anthropic: ' . $response->body());
    }

    /**
     * Appel à l'API Google Gemini (gratuit et fiable)
     * Gemini est gratuit jusqu'à 60 requêtes par minute
     */
    private function callGoogleGemini(string $systemPrompt, string $userPrompt, array $conversationHistory): string
    {
        // Construire les messages pour Gemini
        $messages = [];

        // Ajouter l'historique (limité aux 10 derniers messages)
        foreach (array_slice($conversationHistory, -10) as $entry) {
            if (isset($entry['role']) && isset($entry['content'])) {
                $messages[] = [
                    'role' => $entry['role'] === 'user' ? 'user' : 'model',
                    'parts' => [['text' => $entry['content']]]
                ];
            }
        }

        // Ajouter le message actuel avec le contexte système intégré
        // Pour Gemini, on peut intégrer le système prompt dans le premier message utilisateur
        $fullUserMessage = $userPrompt;
        if (empty($conversationHistory)) {
            // Pour la première question, inclure le contexte système
            $fullUserMessage = $systemPrompt . "\n\nQuestion du patient : " . $userPrompt;
        }

        $messages[] = [
            'role' => 'user',
            'parts' => [['text' => $fullUserMessage]]
        ];

        // Utiliser Gemini 1.5 Flash (gratuit et fiable) ou gemini-pro selon configuration
        $model = config('services.google.model', 'gemini-1.5-flash');

        // Déterminer la version de l'API selon le modèle
        // gemini-1.5-flash et gemini-1.5-pro utilisent v1beta
        // gemini-pro utilise v1
        if (strpos($model, '1.5') !== false) {
            $apiVersion = 'v1beta';
        } else {
            $apiVersion = 'v1';
        }

        $url = "https://generativelanguage.googleapis.com/{$apiVersion}/models/{$model}:generateContent?key=" . $this->googleApiKey;

        $requestData = [
            'contents' => $messages,
            'generationConfig' => [
                'temperature' => 0.7,
                'topK' => 40,
                'topP' => 0.95,
                'maxOutputTokens' => 1024,
            ],
            'safetySettings' => [
                [
                    'category' => 'HARM_CATEGORY_HARASSMENT',
                    'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                ],
                [
                    'category' => 'HARM_CATEGORY_HATE_SPEECH',
                    'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                ],
                [
                    'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
                    'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                ],
                [
                    'category' => 'HARM_CATEGORY_DANGEROUS_CONTENT',
                    'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                ]
            ]
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->timeout(30)->post($url, $requestData);

        if ($response->successful()) {
            $data = $response->json();

            // Gérer différents formats de réponse
            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                return $data['candidates'][0]['content']['parts'][0]['text'];
            }

            // Vérifier si la réponse a été bloquée par les safety settings
            if (isset($data['promptFeedback']['blockReason'])) {
                throw new \Exception('Réponse bloquée par les paramètres de sécurité Gemini: ' . $data['promptFeedback']['blockReason']);
            }

            throw new \Exception('Format de réponse Gemini inattendu: ' . json_encode($data));
        }

        $errorBody = $response->body();
        Log::error('Erreur API Google Gemini: ' . $errorBody);
        throw new \Exception('Erreur API Google Gemini: ' . $errorBody);
    }

    /**
     * Appel à l'API Hugging Face avec le modèle Mistral-7B-Instruct-v0.2
     *
     * Utilise l'API Router (OpenAI-compatible) par défaut - plus simple et fiable
     * Documentation: https://huggingface.co/mistralai/Mistral-7B-Instruct-v0.2
     */
    private function callHuggingFace(string $userPrompt, array $conversationHistory = []): string
    {
        // Utiliser le modèle Mistral depuis la configuration
        $model = config('services.huggingface.model', 'mistralai/Mistral-7B-Instruct-v0.2');

        // Utiliser l'API Router par défaut (recommandé)
        $useRouter = config('services.huggingface.use_router', true);

        if ($useRouter) {
            return $this->callHuggingFaceViaRouter($userPrompt, $conversationHistory, $model);
        }

        // Fallback : utiliser l'API classique
        return $this->callHuggingFaceClassic($userPrompt, $conversationHistory, $model);
    }

    /**
     * Utilise l'API Router (OpenAI-compatible) - RECOMMANDÉ
     */
    private function callHuggingFaceViaRouter(string $userPrompt, array $conversationHistory, string $model): string
    {
        $routerUrl = config('services.huggingface.router_url', 'https://router.huggingface.co/v1');
        $endpoint = $routerUrl . '/chat/completions';
        $modelWithProvider = $model . ':featherless-ai';

        // Construire les messages au format OpenAI
        $messages = [];

        // Ajouter l'historique de conversation
        foreach ($conversationHistory as $entry) {
            if (isset($entry['role']) && isset($entry['content'])) {
                $messages[] = [
                    'role' => $entry['role'] === 'user' ? 'user' : 'assistant',
                    'content' => $this->cleanMessageForHistory($entry['content'])
                ];
            }
        }

        // Ajouter le message actuel
        $messages[] = [
            'role' => 'user',
            'content' => trim($userPrompt)
        ];

        $tokenPreview = substr($this->huggingfaceApiKey, 0, 7) . '...';
        Log::info("Appel Mistral via Router API - Modèle: {$modelWithProvider}");
        Log::info("Token Hugging Face: {$tokenPreview}");
        Log::info("Nombre de messages: " . count($messages));

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->huggingfaceApiKey,
                'Content-Type' => 'application/json',
            ])->timeout(90)->post($endpoint, [
                'model' => $modelWithProvider,
                'messages' => $messages,
                'max_tokens' => 512,
                'temperature' => 0.7,
                'top_p' => 0.95
            ]);

            Log::info("Réponse HTTP Router - Status: " . $response->status());

            if (!$response->successful()) {
                $statusCode = $response->status();
                $errorBody = $response->body();
                $errorData = $response->json();
                $errorMessage = isset($errorData['error']['message']) ? $errorData['error']['message'] :
                               (isset($errorData['error']) ? (is_string($errorData['error']) ? $errorData['error'] : json_encode($errorData['error'])) : $errorBody);

                Log::error("Erreur API Hugging Face Router - Status: {$statusCode}");
                Log::error("Message d'erreur: " . substr($errorMessage, 0, 500));

                if ($statusCode === 401 || $statusCode === 403) {
                    throw new \Exception("Votre token Hugging Face est INVALIDE ({$statusCode}). Créez un nouveau token sur https://huggingface.co/settings/tokens. Erreur: {$errorMessage}");
                } elseif ($statusCode === 404) {
                    throw new \Exception("Le modèle '{$model}' n'est pas accessible via le Router (404). Erreur: {$errorMessage}");
                } else {
                    throw new \Exception("Erreur API Hugging Face Router (Status {$statusCode}): {$errorMessage}");
                }
            }

            $data = $response->json();

            Log::info("Réponse JSON Router reçue");

            // Format OpenAI : extraction du message
            if (isset($data['choices'][0]['message']['content'])) {
                $generatedText = $data['choices'][0]['message']['content'];

                Log::info("Réponse générée avec succès via Router API");
                Log::info("Réponse brute (premiers 200 caractères): " . substr($generatedText, 0, 200));

                return $this->cleanHuggingFaceResponse($generatedText, $userPrompt);
            }

            throw new \Exception("Format de réponse Router inattendu: " . json_encode($data));

        } catch (\Exception $e) {
            Log::error("Exception lors de l'appel Router API: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Utilise l'API classique (fallback)
     */
    private function callHuggingFaceClassic(string $userPrompt, array $conversationHistory, string $model): string
    {
        // Construire le prompt selon le format Mistral
        // Format Mistral: <s>[INST] question [/INST]
        if (empty($conversationHistory)) {
            // Première question : format Mistral standard
            $fullPrompt = "<s>[INST] " . trim($userPrompt) . " [/INST]";
        } else {
            // Avec historique : construire un prompt conversationnel
            $fullPrompt = "<s>";
            $recentHistory = array_slice($conversationHistory, -3);

            foreach ($recentHistory as $entry) {
                if (isset($entry['role']) && isset($entry['content'])) {
                    if ($entry['role'] === 'user') {
                        $content = $this->cleanMessageForHistory($entry['content']);
                        $fullPrompt .= "[INST] " . $content . " [/INST] ";
                    } else {
                        $content = $this->cleanMessageForHistory($entry['content']);
                        $fullPrompt .= $content . " </s>";
                    }
                }
            }

            // Ajouter la question actuelle
            $fullPrompt .= "<s>[INST] " . trim($userPrompt) . " [/INST]";
        }

        $baseUrl = rtrim(config('services.huggingface.api_url'), '/');
        $modelPath = ltrim($model, '/');
        $apiUrl = $baseUrl . '/' . $modelPath;

        Log::info("Appel API Hugging Face classique - Modèle: {$model} - URL: {$apiUrl}");

        // Log du token (masqué pour sécurité)
        $tokenPreview = substr($this->huggingfaceApiKey, 0, 7) . '...';
        Log::info("Token Hugging Face: {$tokenPreview}");
        Log::info("Prompt envoyé: " . substr($fullPrompt, 0, 200) . '...');

        try {
            // Appel API Hugging Face Inference avec le champ "inputs"
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->huggingfaceApiKey,
                'Content-Type' => 'application/json',
            ])->timeout(90)->post($apiUrl, [
                'inputs' => $fullPrompt,
                'parameters' => [
                    'max_new_tokens' => 512,
                    'temperature' => 0.7,
                    'top_p' => 0.95,
                    'return_full_text' => false
                ]
            ]);

            Log::info("Réponse HTTP - Status: " . $response->status());

            // Gérer les différents codes de réponse
            if (!$response->successful()) {
                $statusCode = $response->status();
                $errorBody = $response->body();

                // Tenter de parser l'erreur JSON
                $errorData = json_decode($errorBody, true);
                $errorMessage = isset($errorData['error']) ? $errorData['error'] : $errorBody;

                Log::error("Erreur API Hugging Face - Status: {$statusCode}, Modèle: {$model}");
                Log::error("Message d'erreur: " . substr($errorMessage, 0, 500));

                if ($statusCode === 401 || $statusCode === 403) {
                    throw new \Exception("Votre token Hugging Face est INVALIDE ({$statusCode}). Créez un nouveau token 'Fine-Grained' avec la permission 'inference:provider:access' sur https://huggingface.co/settings/tokens. Erreur: {$errorMessage}");
                } elseif ($statusCode === 404) {
                    throw new \Exception("Le modèle '{$model}' n'est pas accessible via l'API publique (404). Vérifiez que le modèle existe et que votre token a les bonnes permissions. Erreur: {$errorMessage}");
                } elseif ($statusCode === 503) {
                    throw new \Exception("Le modèle '{$model}' est temporairement indisponible (503). Il est peut-être en cours de chargement. Veuillez réessayer dans quelques instants.");
                } else {
                    throw new \Exception("Erreur API Hugging Face (Status {$statusCode}): {$errorMessage}");
                }
            }

            // Si on arrive ici, la requête a réussi
            $data = $response->json();

            Log::info("Réponse JSON reçue: " . json_encode($data));

            // Vérifier si le modèle est en cours de chargement (réponse 200 mais avec erreur dans le JSON)
            if (isset($data['error'])) {
                $errorMsg = is_string($data['error']) ? $data['error'] : json_encode($data['error']);
                if (stripos($errorMsg, 'loading') !== false || stripos($errorMsg, 'model is currently loading') !== false) {
                    throw new \Exception("Le modèle '{$model}' est en cours de chargement. Veuillez patienter 30-60 secondes et réessayer. Message: {$errorMsg}");
                }
                throw new \Exception("Erreur avec le modèle '{$model}': {$errorMsg}");
            }

            // Extraire la réponse selon différents formats possibles
            $generatedText = null;

            // Format 1: [{"generated_text": "..."}] (format le plus courant)
            if (isset($data[0]['generated_text'])) {
                $generatedText = $data[0]['generated_text'];
            }
            // Format 2: {"generated_text": "..."}
            elseif (isset($data['generated_text'])) {
                $generatedText = $data['generated_text'];
            }
            // Format 3: {"answer": "..."} (pour certains modèles de QA)
            elseif (isset($data['answer'])) {
                $generatedText = $data['answer'];
            }
            // Format 4: [{"answer": "..."}]
            elseif (isset($data[0]['answer'])) {
                $generatedText = $data[0]['answer'];
            }
            // Format 5: Array de strings
            elseif (isset($data[0]) && is_string($data[0])) {
                $generatedText = $data[0];
            }

            if (!$generatedText || empty(trim($generatedText))) {
                Log::error("Réponse vide reçue du modèle Hugging Face '{$model}'. Réponse complète: " . json_encode($data));
                throw new \Exception("Réponse vide reçue du modèle '{$model}'. La réponse de l'API est invalide ou vide.");
            }

            // Succès ! On a une réponse valide
            Log::info("Réponse générée avec succès par le modèle Hugging Face: {$model}");
            Log::info("Réponse brute (premiers 200 caractères): " . substr($generatedText, 0, 200));

            return $this->cleanHuggingFaceResponse($generatedText, $userPrompt);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error("Erreur de connexion à l'API Hugging Face: " . $e->getMessage());
            throw new \Exception("Impossible de se connecter à l'API Hugging Face. Vérifiez votre connexion internet et réessayez.");
        } catch (\Exception $e) {
            // Propager l'exception telle quelle pour une gestion d'erreur claire
            Log::error("Exception lors de l'appel API Hugging Face: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Construit un prompt complet pour Hugging Face avec contexte médical
     * Utilise le format conversationnel standard: Human (patient) / Assistant
     */
    private function buildFullPromptForHuggingFace(string $systemContext, string $userMessage, array $conversationHistory): string
    {
        // Construire un prompt adaptatif qui fonctionne avec différents types de modèles

        // Si on a un historique, utiliser le format conversationnel Human/Assistant
        if (!empty($conversationHistory)) {
            $prompt = "";
            $recentHistory = array_slice($conversationHistory, -3);

            foreach ($recentHistory as $entry) {
                if (isset($entry['role']) && isset($entry['content'])) {
                    if ($entry['role'] === 'user') {
                        $content = $this->cleanMessageForHistory($entry['content']);
                        $prompt .= "Human: {$content}\n";
                    } else {
                        $content = $this->cleanMessageForHistory($entry['content']);
                        $prompt .= "Assistant: {$content}\n";
                    }
                }
            }

            $prompt .= "Human: {$userMessage}\nAssistant:";
        } else {
            // Première question : format adapté pour les modèles de génération de texte
            // Format plus simple qui fonctionne mieux avec gpt2 et modèles similaires
            $prompt = "Question médicale : {$userMessage}\n\n";
            $prompt .= "En tant qu'assistant médical professionnel, je dois fournir des conseils généraux. ";
            $prompt .= "Je ne pose pas de diagnostic et je ne recommande pas de médicaments spécifiques. ";
            $prompt .= "Réponse empathique et professionnelle : ";
        }

        return $prompt;
    }

    /**
     * Nettoie un message pour l'historique de conversation
     * Enlève l'avertissement médical pour éviter les répétitions
     */
    private function cleanMessageForHistory(string $message): string
    {
        $cleaned = trim($message);

        // Enlever l'avertissement médical de l'historique pour éviter répétition
        $patterns = [
            '/\n\n⚠️.*?professionnel de santé.*/is',
            '/⚠️.*?professionnel de santé.*/is',
            '/\n\nImportant.*?avis médical.*/is'
        ];

        foreach ($patterns as $pattern) {
            $cleaned = preg_replace($pattern, '', $cleaned);
        }

        // Limiter la longueur pour l'historique
        if (mb_strlen($cleaned) > 400) {
            $cleaned = mb_substr($cleaned, 0, 400);
            $lastPeriod = mb_strrpos($cleaned, '.');
            if ($lastPeriod !== false && $lastPeriod > 200) {
                $cleaned = mb_substr($cleaned, 0, $lastPeriod + 1);
            }
        }

        return trim($cleaned);
    }

    /**
     * Nettoie la réponse de Hugging Face et ajoute l'avertissement médical
     * Gère le format conversationnel Human/Assistant
     */
    private function cleanHuggingFaceResponse(string $response, string $userPrompt): string
    {
        // Nettoyer la réponse
        $response = trim($response);

        // Enlever le prompt utilisateur (Human:) si présent dans la réponse
        if (stripos($response, $userPrompt) !== false) {
            $pos = stripos($response, $userPrompt);
            $response = mb_substr($response, $pos + mb_strlen($userPrompt));
            $response = trim($response);
        }

        // Enlever les préfixes conversationnels courants
        $prefixes = [
            'Assistant:',
            'Réponse de l\'assistant médical:',
            'Réponse:',
            'A: ',
            'Human:',
            'Patient:'
        ];

        foreach ($prefixes as $prefix) {
            // Vérifier au début de la réponse
            if (mb_stripos($response, $prefix) === 0) {
                $response = mb_substr($response, mb_strlen($prefix));
                $response = trim($response);
                break;
            }
        }

        // Enlever toute mention "Human:" ou "Patient:" qui pourrait apparaître
        $response = preg_replace('/^(Human|Patient):\s*/i', '', $response);

        // Enlever les répétitions de "Assistant:" en milieu de réponse
        $response = preg_replace('/\n+Assistant:\s*/i', "\n", $response);

        // Limiter la longueur (maximum 1200 caractères pour une réponse claire)
        if (mb_strlen($response) > 1200) {
            $response = mb_substr($response, 0, 1200);
            // Couper à la dernière phrase complète si possible
            $lastPeriod = mb_strrpos($response, '.');
            if ($lastPeriod !== false && $lastPeriod > 600) {
                $response = mb_substr($response, 0, $lastPeriod + 1);
            } else {
                $lastExclamation = mb_strrpos($response, '!');
                if ($lastExclamation !== false && $lastExclamation > 600) {
                    $response = mb_substr($response, 0, $lastExclamation + 1);
                } else {
                    $response .= '...';
                }
            }
        }

        // S'assurer que la réponse n'est pas vide ou trop courte
        if (empty($response) || mb_strlen($response) < 10) {
            return $this->getFallbackResponse($userPrompt);
        }

        // Ajouter systématiquement l'avertissement médical à la fin
        $warningText = "\n\n⚠️ Je ne remplace pas un médecin. Pour un avis personnalisé, consultez un professionnel de santé.";

        // Vérifier si l'avertissement est déjà présent (éviter les doublons)
        $hasWarning = mb_stripos($response, '⚠️') !== false ||
                     mb_stripos($response, 'ne remplace pas un médecin') !== false ||
                     mb_stripos($response, 'professionnel de santé') !== false;

        if (!$hasWarning) {
            $response .= $warningText;
        }

        return $response;
    }

    /**
     * Formate la réponse dans la structure attendue
     */
    private function formatResponse(string $rawResponse): array
    {
        // La réponse brute devrait déjà contenir les sections formatées
        // mais on s'assure qu'elle contient bien l'avertissement médical
        $response = trim($rawResponse);

        // S'assurer que l'avertissement médical est toujours présent
        $warningText = "\n\n⚠️ Je ne remplace pas un médecin. Pour un avis personnalisé, consultez un professionnel de santé.";

        // Vérifier si l'avertissement est déjà présent (éviter les doublons)
        if (stripos($response, '⚠️') === false &&
            stripos($response, 'ne remplace pas un médecin') === false &&
            stripos($response, 'professionnel de santé') === false) {
            $response .= $warningText;
        }

        return [
            'success' => true,
            'message' => $response,
            'timestamp' => now()->toIso8601String()
        ];
    }

    /**
     * Réponse de fallback intelligente si l'API échoue
     * Génère une réponse contextuelle basée sur les mots-clés dans la question
     * Cette fonction est utilisée UNIQUEMENT en dernier recours si tous les modèles AI échouent
     */
    private function getFallbackResponse(string $userMessage): string
    {
        $messageLower = mb_strtolower($userMessage);

        // Détecter le type de question pour une réponse plus pertinente
        $response = "";

        // Maux de tête / Migraine
        if (stripos($messageLower, 'mal à la tête') !== false || stripos($messageLower, 'migraine') !== false || stripos($messageLower, 'céphalée') !== false) {
            $response = "Je comprends que vous ressentez des maux de tête. Les céphalées peuvent avoir plusieurs causes : stress, fatigue, déshydratation, ou problèmes de vision.\n\nPour soulager un mal de tête occasionnel, je vous recommande de :\n- Vous reposer dans un endroit calme et sombre\n- Boire suffisamment d'eau (la déshydratation peut causer des maux de tête)\n- Appliquer une compresse froide sur le front ou les tempes\n- Détendre vos muscles et éviter les écrans si possible\n\nConsultez rapidement un médecin si le mal de tête est soudain et très intense, s'il est accompagné de fièvre ou de raideur de la nuque, s'il survient après un traumatisme, ou s'il persiste plusieurs jours sans amélioration.";
        }
        // Fatigue / Épuisement
        elseif (stripos($messageLower, 'fatigu') !== false || stripos($messageLower, 'épuis') !== false || stripos($messageLower, 'lassitude') !== false) {
            $response = "La fatigue persistante peut être liée à plusieurs facteurs : manque de sommeil, stress, carences nutritionnelles, ou problèmes de santé sous-jacents.\n\nPour améliorer votre niveau d'énergie, je vous suggère :\n- Dormir suffisamment (7-9 heures par nuit pour un adulte)\n- Adopter une alimentation équilibrée riche en fruits, légumes et protéines\n- Pratiquer une activité physique régulière, même légère (marche, yoga)\n- Gérer votre stress avec des techniques de relaxation ou méditation\n- Limiter la consommation excessive de caféine et éviter l'alcool\n\nConsultez un médecin si la fatigue persiste depuis plusieurs semaines, s'accompagne d'autres symptômes (fièvre, perte de poids), ou affecte significativement votre vie quotidienne.";
        }
        // Douleurs de règles / Menstruelles
        elseif ((stripos($messageLower, 'règles') !== false || stripos($messageLower, 'menstru') !== false) && (stripos($messageLower, 'douleur') !== false || stripos($messageLower, 'mal') !== false)) {
            $response = "Les douleurs menstruelles (dysménorrhée) sont fréquentes et peuvent varier en intensité d'une personne à l'autre.\n\nPour soulager les douleurs de règles, vous pouvez essayer :\n- Appliquer une bouillotte ou un coussin chauffant sur le bas-ventre\n- Vous reposer et éviter les activités physiques intenses\n- Pratiquer des exercices de respiration ou du yoga doux\n- Prendre un bain chaud pour détendre les muscles\n- Éviter la caféine qui peut aggraver les crampes\n- Maintenir une activité physique légère régulière\n\nConsultez un gynécologue si les douleurs sont très intenses et handicapantes, s'accompagnent de saignements abondants, ne sont pas soulagées par des méthodes simples, ou apparaissent de manière inhabituelle.";
        }
        // Fièvre / Température
        elseif (stripos($messageLower, 'fièvre') !== false || stripos($messageLower, 'température') !== false || (stripos($messageLower, 'fiévreux') !== false)) {
            $response = "La fièvre est souvent le signe que votre corps combat une infection ou une maladie.\n\nSi vous avez de la fièvre, voici ce que vous pouvez faire :\n- Vous reposer et vous hydrater régulièrement (eau, tisanes)\n- Mesurer votre température régulièrement\n- Porter des vêtements légers pour ne pas surchauffer\n- Appliquer des compresses fraîches sur le front\n- Surveiller les autres symptômes associés\n\nConsultez un médecin rapidement si la fièvre dépasse 38,5°C chez l'adulte, persiste plus de 3 jours, s'accompagne de symptômes graves (difficultés respiratoires, confusion, raideur de la nuque), ou concerne un enfant, une personne âgée ou une personne immunodéprimée.";
        }
        // Douleurs abdominales / Ventre
        elseif (stripos($messageLower, 'ventre') !== false && (stripos($messageLower, 'douleur') !== false || stripos($messageLower, 'mal') !== false)) {
            $response = "Les douleurs abdominales peuvent avoir de nombreuses causes, allant des troubles digestifs simples à des problèmes plus sérieux.\n\nEn cas de douleur abdominale légère, vous pouvez :\n- Identifier les aliments qui peuvent l'avoir déclenchée\n- Vous reposer et éviter les repas copieux\n- Boire de l'eau par petites gorgées\n- Appliquer une source de chaleur douce sur la zone douloureuse\n- Éviter l'automédication sans avis médical\n\nConsultez un médecin rapidement si la douleur est intense, persistante, s'accompagne de fièvre, de nausées ou vomissements, de sang dans les selles, ou si elle interfère avec vos activités quotidiennes.";
        }
        // Toux / Gorge
        elseif (stripos($messageLower, 'toux') !== false || stripos($messageLower, 'gorge') !== false) {
            $response = "La toux est un réflexe de défense de votre organisme pour expulser les irritants ou les sécrétions des voies respiratoires.\n\nPour soulager une toux :\n- Buvez beaucoup de liquides chauds (tisanes, bouillons)\n- Utilisez un humidificateur ou prenez des douches chaudes pour humidifier l'air\n- Évitez les irritants (tabac, poussière, produits chimiques)\n- Reposez-vous suffisamment\n- Maintenez une bonne hydratation\n\nConsultez un médecin si la toux persiste plus de 3 semaines, s'accompagne de fièvre, de difficultés respiratoires, de sang dans les expectorations, ou si elle vous empêche de dormir normalement.";
        }
        // Nausées / Vomissements
        elseif (stripos($messageLower, 'nausée') !== false || stripos($messageLower, 'vomissement') !== false || stripos($messageLower, 'envie de vomir') !== false) {
            $response = "Les nausées et vomissements peuvent être causés par divers facteurs : infections, troubles digestifs, migraines, ou autres conditions médicales.\n\nPour gérer les nausées :\n- Buvez des liquides clairs par petites quantités fréquentes (eau, bouillon)\n- Évitez les aliments gras, épicés ou très sucrés\n- Privilégiez des repas légers et fractionnés\n- Respirez profondément et restez au repos\n- Évitez les odeurs fortes qui peuvent aggraver les nausées\n\nConsultez un médecin si les vomissements persistent plus de 24 heures, s'accompagnent de fièvre élevée, de douleurs abdominales intenses, de signes de déshydratation (sécheresse de la bouche, urine foncée), ou si vous vomissez du sang.";
        }
        // Stress / Anxiété
        elseif (stripos($messageLower, 'stress') !== false || stripos($messageLower, 'anxiété') !== false || stripos($messageLower, 'angoisse') !== false) {
            $response = "Le stress et l'anxiété sont des réactions naturelles de l'organisme, mais quand ils deviennent excessifs, ils peuvent affecter votre santé.\n\nPour gérer le stress et l'anxiété :\n- Pratiquez des techniques de relaxation (respiration profonde, méditation)\n- Maintenez une activité physique régulière\n- Assurez-vous d'avoir un sommeil suffisant et de qualité\n- Limitez la consommation de caféine et d'alcool\n- Partagez vos préoccupations avec des proches ou un professionnel\n- Organisez votre temps et fixez des priorités\n\nConsultez un professionnel de santé mentale si le stress ou l'anxiété interfèrent avec votre vie quotidienne, votre sommeil, ou votre capacité à fonctionner normalement.";
        }
        // Réponse générique intelligente et contextuelle
        else {
            // Analyser les mots-clés pour personnaliser la réponse
            $keywords = [];
            $symptoms = [];

            if (stripos($messageLower, 'douleur') !== false || stripos($messageLower, 'mal') !== false) {
                $symptoms[] = 'douleur';
            }
            if (stripos($messageLower, 'fièvre') !== false || stripos($messageLower, 'température') !== false) {
                $symptoms[] = 'fièvre';
            }
            if (stripos($messageLower, 'nausée') !== false || stripos($messageLower, 'vomissement') !== false) {
                $symptoms[] = 'nausée';
            }
            if (stripos($messageLower, 'toux') !== false) {
                $symptoms[] = 'toux';
            }

            $response = "Je comprends votre préoccupation concernant votre santé. ";

            if (!empty($symptoms)) {
                $response .= "Concernant " . implode(', ', $symptoms) . ", ";
            }

            $response .= "il est important d'être attentif à vos symptômes et d'observer leur évolution.\n\n";
            $response .= "Voici quelques conseils généraux qui peuvent vous aider :\n";
            $response .= "- Notez la fréquence, l'intensité et la durée de vos symptômes\n";
            $response .= "- Observez si certains facteurs les aggravent ou les soulagent\n";
            $response .= "- Assurez-vous de bien vous hydrater et de vous reposer suffisamment\n";
            $response .= "- Évitez l'automédication sans avis médical préalable\n";
            $response .= "- Surveillez l'apparition de nouveaux symptômes\n\n";
            $response .= "Il est fortement recommandé de consulter un professionnel de santé si :\n";
            $response .= "- Vos symptômes persistent ou s'aggravent\n";
            $response .= "- Vous ressentez une douleur intense ou inhabituelle\n";
            $response .= "- Vous avez des doutes ou des inquiétudes concernant votre état\n";
            $response .= "- Les symptômes interfèrent avec votre vie quotidienne\n";
            $response .= "- Vous observez des signes d'urgence (difficultés respiratoires, perte de conscience, etc.)";
        }

        // Ajouter systématiquement l'avertissement médical
        $response .= "\n\n⚠️ Je ne remplace pas un médecin. Pour un avis personnalisé, consultez un professionnel de santé.";

        return $response;
    }
}

