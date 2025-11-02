@extends('dashPatient.layout')

@section('content')
<div class="p-4 md:p-6 bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="mb-4">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl md:text-2xl font-semibold text-gray-900">Chatbot Médical</h1>
                    <p class="text-sm text-gray-600">Conseils généraux de santé</p>
                </div>
            </div>
            <button id="resetChatBtn"
                    class="px-3 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-all duration-200 text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Nouvelle conversation
            </button>
        </div>

        <!-- Avertissement médical compact -->
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 rounded">
            <p class="text-xs text-yellow-800">
                ⚠️ <strong>Avertissement :</strong> Ce chatbot fournit uniquement des conseils généraux. Il ne remplace pas un avis médical professionnel. Consultez un professionnel de santé pour un diagnostic ou traitement.
            </p>
        </div>
    </div>

    <!-- Zone de chat style Messenger -->
    <div class="bg-white rounded-lg shadow-sm flex flex-col" style="height: calc(100vh - 200px); min-height: 500px;">
        <!-- Messages -->
        <div id="chatMessages" class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50">
            <!-- Message de bienvenue -->
            <div class="flex items-start gap-2 justify-start">
                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <div class="max-w-[75%] md:max-w-[60%]">
                    <div class="bg-gray-200 rounded-2xl rounded-tl-sm px-4 py-3 shadow-sm">
                        <p class="text-sm text-gray-800 leading-relaxed">
                            👋 Bonjour ! Je suis votre assistant médical virtuel. Je peux vous fournir des <strong>conseils généraux de santé</strong> de manière empathique.
                        </p>
                        <p class="text-xs text-gray-600 mt-2">
                            <strong>Note :</strong> Je ne pose pas de diagnostic et je ne recommande pas de médicaments spécifiques.
                        </p>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-2">maintenant</p>
                </div>
            </div>
        </div>

        <!-- Zone de saisie style Messenger -->
        <div class="border-t border-gray-200 bg-white p-3 rounded-b-lg">
            <form id="chatForm" class="flex items-end gap-2">
                <div class="flex-1 bg-gray-100 rounded-full px-4 py-2.5 flex items-center gap-2">
                    <textarea
                        id="messageInput"
                        rows="1"
                        placeholder="Tapez votre message..."
                        class="flex-1 bg-transparent border-0 focus:outline-none focus:ring-0 resize-none text-sm"
                        style="min-height: 20px; max-height: 100px;"
                    ></textarea>
                </div>
                <button
                    type="submit"
                    id="sendBtn"
                    class="w-10 h-10 bg-blue-500 hover:bg-blue-600 text-white rounded-full transition-all duration-200 flex items-center justify-center shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed flex-shrink-0"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/dashboard/chatbot.js') }}"></script>
@endpush

<style>
    /* Style scrollbar pour messages */
    #chatMessages {
        scrollbar-width: thin;
        scrollbar-color: #cbd5e0 #f7fafc;
    }

    #chatMessages::-webkit-scrollbar {
        width: 6px;
    }

    #chatMessages::-webkit-scrollbar-track {
        background: #f7fafc;
    }

    #chatMessages::-webkit-scrollbar-thumb {
        background: #cbd5e0;
        border-radius: 3px;
    }

    #chatMessages::-webkit-scrollbar-thumb:hover {
        background: #a0aec0;
    }

    /* Animation pour les nouveaux messages */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .message-bubble {
        animation: slideIn 0.2s ease-out;
    }

    /* Style pour les bulles utilisateur (bleues) */
    .user-bubble {
        background: #0084ff;
        color: white;
        border-radius: 18px 18px 4px 18px;
    }

    /* Style pour les bulles chatbot (grises) */
    .bot-bubble {
        background: #e4e6eb;
        color: #050505;
        border-radius: 18px 18px 18px 4px;
    }

    /* Style pour l'avertissement médical dans les réponses */
    .medical-warning {
        background: #fff3cd;
        border-left: 3px solid #ffc107;
        padding: 8px 12px;
        margin-top: 8px;
        border-radius: 4px;
        font-size: 0.75rem;
    }
</style>
@endsection
