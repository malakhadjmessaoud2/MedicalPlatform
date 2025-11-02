<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Services\ChatBotService;
use App\Models\Conversation;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Contrôleur pour le chatbot médical conversationnel
 */
class ChatBotController extends Controller
{
    protected $chatBotService;

    public function __construct(ChatBotService $chatBotService)
    {
        $this->chatBotService = $chatBotService;
    }

    /**
     * Affiche l'interface du chatbot avec l'historique
     */
    public function index()
    {
        $user = Auth::user();

        // Récupérer ou créer la conversation actuelle
        $conversation = Conversation::where('user_id', $user->id)
            ->latest()
            ->first();

        // Si pas de conversation ou conversation de plus de 24h, créer une nouvelle
        if (!$conversation || $conversation->created_at->lt(now()->subDay())) {
            $conversation = Conversation::create([
                'user_id' => $user->id,
                'title' => 'Nouvelle conversation'
            ]);
        }

        // Charger les messages
        $messages = $conversation->messages()->orderBy('created_at', 'asc')->get();

        return view('dashPatient.chatbot.index', [
            'conversation' => $conversation,
            'messages' => $messages
        ]);
    }

    /**
     * Traite un message du patient et retourne la réponse du chatbot
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessage(Request $request)
    {
        // S'assurer que la requête attend du JSON
        $request->headers->set('Accept', 'application/json');

        try {
            // Validation des données
            $validated = $request->validate([
                'message' => 'required|string|max:2000',
                'conversation_id' => 'sometimes|nullable|exists:chatbot_conversations,id'
            ]);

            $user = Auth::user();
            $message = trim($request->input('message'));

            // Récupérer ou créer la conversation
            $conversationId = $request->input('conversation_id');
            if ($conversationId) {
                $conversation = Conversation::where('user_id', $user->id)
                    ->findOrFail($conversationId);
            } else {
                // Créer une nouvelle conversation
                $conversation = Conversation::create([
                    'user_id' => $user->id,
                    'title' => substr($message, 0, 50) // Première question comme titre
                ]);
            }

            // Sauvegarder le message utilisateur
            $userMessage = ChatMessage::create([
                'conversation_id' => $conversation->id,
                'content' => $message,
                'is_from_user' => true
            ]);

            // Récupérer l'historique depuis la base de données
            $conversationHistory = $this->buildConversationHistory($conversation);

            // Générer la réponse via le service
            $response = $this->chatBotService->generateResponse($message, $conversationHistory);

            // Si l'API a échoué, retourner l'erreur explicite
            if (!$response['success']) {
                return response()->json([
                    'success' => false,
                    'error' => $response['error'] ?? 'Erreur inconnue lors de la génération de la réponse',
                    'error_details' => $response['error_details'] ?? null
                ], 500);
            }

            // Sauvegarder la réponse du chatbot
            $botMessage = ChatMessage::create([
                'conversation_id' => $conversation->id,
                'content' => $response['message'],
                'is_from_user' => false
            ]);

            return response()->json([
                'success' => true,
                'message' => $response['message'],
                'conversation_id' => $conversation->id,
                'user_message_id' => $userMessage->id,
                'bot_message_id' => $botMessage->id,
                'timestamp' => $response['timestamp'] ?? now()->toIso8601String()
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erreur de validation des données.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erreur ChatBot Controller: ' . $e->getMessage());
            \Illuminate\Support\Facades\Log::error('Trace: ' . $e->getTraceAsString());

            // Toujours retourner du JSON avec une erreur explicite
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la génération de la réponse: ' . $e->getMessage(),
                'error_details' => config('app.debug') ? [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ] : null
            ], 500);
        }
    }

    /**
     * Récupère l'historique d'une conversation
     */
    public function getHistory(Request $request, $conversationId = null)
    {
        $user = Auth::user();

        if ($conversationId) {
            $conversation = Conversation::where('user_id', $user->id)
                ->with('messages')
                ->findOrFail($conversationId);
        } else {
            $conversation = Conversation::where('user_id', $user->id)
                ->latest()
                ->with('messages')
                ->first();
        }

        if (!$conversation) {
            return response()->json([
                'success' => true,
                'messages' => [],
                'conversation_id' => null
            ]);
        }

        $messages = $conversation->messages()
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'content' => $msg->content,
                    'is_from_user' => $msg->is_from_user,
                    'timestamp' => $msg->created_at->toIso8601String(),
                    'time' => $msg->created_at->format('H:i')
                ];
            });

        return response()->json([
            'success' => true,
            'messages' => $messages,
            'conversation_id' => $conversation->id
        ]);
    }

    /**
     * Réinitialise la conversation (crée une nouvelle)
     */
    public function resetConversation()
    {
        $user = Auth::user();

        // Créer une nouvelle conversation
        $conversation = Conversation::create([
            'user_id' => $user->id,
            'title' => 'Nouvelle conversation'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Nouvelle conversation créée',
            'conversation_id' => $conversation->id
        ]);
    }

    /**
     * Construit l'historique de conversation depuis la base de données
     */
    private function buildConversationHistory(Conversation $conversation): array
    {
        $messages = $conversation->messages()
            ->orderBy('created_at', 'asc')
            ->get();

        $history = [];
        foreach ($messages as $msg) {
            $history[] = [
                'role' => $msg->is_from_user ? 'user' : 'assistant',
                'content' => $msg->content
            ];
        }

        return $history;
    }
}
