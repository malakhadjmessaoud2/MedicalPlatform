<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ChatDoctorService;
use Illuminate\Support\Facades\Log;

/**
 * Contrôleur ChatDoctor pour l'API
 *
 * Suit le guide d'intégration spécifique pour LuisC23/ChatDoctor
 */
class ChatDoctorController extends Controller
{
    protected $chatDoctor;

    public function __construct(ChatDoctorService $chatDoctor)
    {
        $this->chatDoctor = $chatDoctor;
    }

    /**
     * Traite une question et retourne la réponse du ChatDoctor
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chat(Request $request)
    {
        // Validation de la requête
        $validated = $request->validate([
            'message' => 'required|string|max:2000'
        ]);

        $message = trim($validated['message']);

        Log::info("Requête ChatDoctor reçue: " . substr($message, 0, 100));

        // Appeler le service ChatDoctor
        $response = $this->chatDoctor->ask($message);

        // Si erreur, retourner avec code d'erreur approprié
        if (isset($response['error'])) {
            return response()->json($response, isset($response['status']) ? $response['status'] : 500);
        }

        // Extraire la réponse générée
        // Le service retourne maintenant le format Router API ou classique
        $generatedText = null;

        // Format Router API : [{"generated_text": "..."}] (retourné par askViaRouter)
        if (isset($response[0]['generated_text'])) {
            $generatedText = $response[0]['generated_text'];
        }
        // Format Router API brut : {"choices": [{"message": {"content": "..."}}]}
        elseif (isset($response['choices'][0]['message']['content'])) {
            $generatedText = $response['choices'][0]['message']['content'];
        }
        // Format classique : {"generated_text": "..."}
        elseif (isset($response['generated_text'])) {
            $generatedText = $response['generated_text'];
        }
        // Format alternatif : {"answer": "..."}
        elseif (isset($response['answer'])) {
            $generatedText = $response['answer'];
        }

        // Si on n'a pas pu extraire le texte, retourner la réponse brute
        if ($generatedText === null) {
            Log::warning("Format de réponse ChatDoctor inattendu: " . json_encode($response));
            return response()->json([
                'success' => false,
                'error' => 'Format de réponse inattendu',
                'original_response' => $response,
                'generated_text' => null
            ]);
        }

        // Retourner la réponse formatée
        return response()->json([
            'success' => true,
            'generated_text' => $generatedText,
            'original_response' => $response
        ]);
    }
}

