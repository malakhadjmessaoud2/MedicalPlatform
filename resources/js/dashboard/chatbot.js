/**
 * Gestion du chatbot médical conversationnel - Interface style Messenger
 *
 * Fonctionnalités :
 * - Interface style Messenger/Facebook avec bulles bleues (patient) et grises (chatbot)
 * - Gestion de l'historique depuis la base de données
 * - Auto-scroll vers les nouveaux messages
 * - Réinitialisation de conversation
 */

document.addEventListener('DOMContentLoaded', function() {
    // Éléments du DOM
    const chatForm = document.getElementById('chatForm');
    const messageInput = document.getElementById('messageInput');
    const chatMessages = document.getElementById('chatMessages');
    const sendBtn = document.getElementById('sendBtn');
    const resetChatBtn = document.getElementById('resetChatBtn');

    // Configuration
    let conversationId = null;
    const config = {
        apiUrl: '/patient/chatbot/message',
        historyUrl: '/patient/chatbot/history',
        resetUrl: '/patient/chatbot/reset',
        csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    };

    /**
     * Initialise le chatbot
     */
    function init() {
        // Charger l'historique depuis la base de données
        loadHistory();

        // Event listeners
        chatForm.addEventListener('submit', handleSubmit);
        resetChatBtn.addEventListener('click', resetConversation);

        // Gestion de la touche Entrée (Maj+Entrée pour nouvelle ligne)
        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                handleSubmit(e);
            }
        });

        // Auto-resize du textarea
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 100) + 'px';
        });

        // Focus sur l'input au chargement
        messageInput.focus();
    }

    /**
     * Charge l'historique depuis la base de données
     */
    async function loadHistory() {
        try {
            const url = conversationId
                ? `${config.historyUrl}/${conversationId}`
                : config.historyUrl;

            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': config.csrfToken
                }
            });

            const data = await response.json();

            if (data.success && data.messages.length > 0) {
                conversationId = data.conversation_id;

                // Vider les messages actuels (sauf le message de bienvenue)
                chatMessages.innerHTML = '';

                // Afficher tous les messages
                data.messages.forEach(msg => {
                    if (msg.is_from_user) {
                        addUserMessage(msg.content, msg.time || getCurrentTime());
                    } else {
                        addBotMessage(msg.content, msg.time || getCurrentTime());
                    }
                });

                scrollToBottom();
            }
        } catch (error) {
            console.error('Erreur chargement historique:', error);
        }
    }

    /**
     * Gère l'envoi d'un message
     */
    async function handleSubmit(e) {
        e.preventDefault();

        const message = messageInput.value.trim();

        if (!message) {
            return;
        }

        // Désactiver le formulaire pendant l'envoi
        setFormDisabled(true);

        // Afficher le message de l'utilisateur
        const userTime = getCurrentTime();
        addUserMessage(message, userTime);

        // Effacer l'input
        messageInput.value = '';
        messageInput.style.height = 'auto';

        try {
            // Envoyer la requête au serveur
            const response = await fetch(config.apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': config.csrfToken
                },
                body: JSON.stringify({
                    message: message,
                    conversation_id: conversationId
                })
            });

            // Vérifier le Content-Type avant de parser
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                console.error('Réponse non-JSON reçue:', text.substring(0, 200));
                throw new Error('Le serveur a retourné une erreur. Veuillez vérifier les logs ou réessayer.');
            }

            const data = await response.json();

            if (!response.ok || !data.success) {
                // Si c'est une erreur API, afficher le message détaillé
                const errorMessage = data.error || 'Une erreur est survenue lors de la génération de la réponse';

                // Logger les détails pour le debug
                if (data.error_details) {
                    console.error('Détails de l\'erreur:', data.error_details);
                }

                throw new Error(errorMessage);
            }

            // Mettre à jour l'ID de conversation
            if (data.conversation_id) {
                conversationId = data.conversation_id;
            }

            // Afficher la réponse du chatbot
            const botTime = data.timestamp
                ? new Date(data.timestamp).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })
                : getCurrentTime();
            addBotMessage(data.message, botTime);

        } catch (error) {
            console.error('Erreur:', error);

            // Message d'erreur plus informatif
            let errorMessage = 'Désolé, une erreur est survenue lors de la génération de la réponse.';

            if (error.message) {
                errorMessage += ' ' + error.message;
            } else {
                errorMessage += ' Veuillez réessayer. Si le problème persiste, vérifiez votre connexion internet.';
            }

            addBotMessage(
                errorMessage,
                getCurrentTime(),
                true
            );
        } finally {
            setFormDisabled(false);
            messageInput.focus();
        }
    }

    /**
     * Ajoute un message utilisateur à l'interface (bulle bleue)
     */
    function addUserMessage(message, time) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'flex items-start gap-2 justify-end message-bubble';

        messageDiv.innerHTML = `
            <div class="max-w-[75%] md:max-w-[60%]">
                <div class="user-bubble px-4 py-2.5 shadow-sm">
                    <p class="text-sm text-white leading-relaxed whitespace-pre-wrap">${escapeHtml(message)}</p>
                </div>
                <p class="text-xs text-gray-500 mt-1 mr-2 text-right">${time}</p>
            </div>
        `;

        chatMessages.appendChild(messageDiv);
        scrollToBottom();
    }

    /**
     * Ajoute un message du chatbot à l'interface (bulle grise)
     */
    function addBotMessage(message, time, isError = false) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'flex items-start gap-2 justify-start message-bubble';

        // Formater le message
        const formattedMessage = formatBotMessage(message, isError);

        messageDiv.innerHTML = `
            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
            </div>
            <div class="max-w-[75%] md:max-w-[60%]">
                <div class="bot-bubble px-4 py-2.5 shadow-sm ${isError ? 'border-l-4 border-red-400' : ''}">
                    ${formattedMessage}
                </div>
                <p class="text-xs text-gray-500 mt-1 ml-2">${time}</p>
            </div>
        `;

        chatMessages.appendChild(messageDiv);
        scrollToBottom();
    }

    /**
     * Formate le message du chatbot
     */
    function formatBotMessage(message, isError = false) {
        if (isError) {
            return `<p class="text-sm leading-relaxed">${escapeHtml(message)}</p>`;
        }

        let formatted = escapeHtml(message);

        // Remplacer les sauts de ligne par des <br>
        formatted = formatted.replace(/\n\n/g, '</p><p class="text-sm leading-relaxed mt-2">');
        formatted = formatted.replace(/\n/g, '<br>');

        // Mettre en évidence l'avertissement médical
        formatted = formatted.replace(/(⚠️[^⚠️\n]+)/g, function(match) {
            return `<div class="medical-warning mt-2">${escapeHtml(match)}</div>`;
        });

        return `<p class="text-sm leading-relaxed">${formatted}</p>`;
    }

    /**
     * Réinitialise la conversation
     */
    async function resetConversation() {
        if (!confirm('Êtes-vous sûr de vouloir démarrer une nouvelle conversation ?')) {
            return;
        }

        try {
            const response = await fetch(config.resetUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': config.csrfToken
                }
            });

            const data = await response.json();

            if (data.success) {
                conversationId = data.conversation_id;
                chatMessages.innerHTML = '';

                // Réafficher le message de bienvenue
                const welcomeDiv = document.createElement('div');
                welcomeDiv.className = 'flex items-start gap-2 justify-start';
                welcomeDiv.innerHTML = `
                    <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <div class="max-w-[75%] md:max-w-[60%]">
                        <div class="bot-bubble px-4 py-2.5 shadow-sm">
                            <p class="text-sm leading-relaxed">
                                👋 Bonjour ! Je suis votre assistant médical virtuel. Je peux vous fournir des <strong>conseils généraux de santé</strong> de manière empathique.
                            </p>
                            <p class="text-xs mt-2">
                                <strong>Note :</strong> Je ne pose pas de diagnostic et je ne recommande pas de médicaments spécifiques.
                            </p>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 ml-2">maintenant</p>
                    </div>
                `;
                chatMessages.appendChild(welcomeDiv);
            }
        } catch (error) {
            console.error('Erreur réinitialisation:', error);
            alert('Une erreur est survenue lors de la réinitialisation.');
        }

        messageInput.focus();
    }

    /**
     * Active/désactive le formulaire pendant l'envoi
     */
    function setFormDisabled(disabled) {
        messageInput.disabled = disabled;
        sendBtn.disabled = disabled;

        if (disabled) {
            sendBtn.innerHTML = `
                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            `;
        } else {
            sendBtn.innerHTML = `
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            `;
        }
    }

    /**
     * Fait défiler vers le bas pour afficher le dernier message
     */
    function scrollToBottom() {
        setTimeout(() => {
            chatMessages.scrollTo({
                top: chatMessages.scrollHeight,
                behavior: 'smooth'
            });
        }, 100);
    }

    /**
     * Obtient l'heure actuelle formatée
     */
    function getCurrentTime() {
        const now = new Date();
        return now.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
    }

    /**
     * Échappe le HTML pour éviter les injections XSS
     */
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Initialiser le chatbot
    init();
});
