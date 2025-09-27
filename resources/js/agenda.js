// Importation des dépendances nécessaires
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';
import frLocale from '@fullcalendar/core/locales/fr';

document.addEventListener('DOMContentLoaded', function() {
    // Vérifier si l'élément calendar existe sur la page
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    // Configuration du token CSRF pour les requêtes Ajax
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Initialisation du calendrier
    const calendar = new Calendar(calendarEl, {
        plugins: [interactionPlugin, dayGridPlugin, timeGridPlugin, listPlugin],
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        locale: frLocale,
        timeZone: 'local',
        editable: true,
        selectable: true,
        selectMirror: true,
        dayMaxEvents: true,
        slotMinTime: '08:00:00',
        slotMaxTime: '19:00:00',
        slotDuration: '00:30:00',
        allDaySlot: false,
        nowIndicator: true, // Indicateur de l'heure actuelle
        businessHours: {
            daysOfWeek: [1, 2, 3, 4, 5], // Lundi au vendredi
            startTime: '08:00',
            endTime: '19:00',
        },
        eventSources: [
            {
                url: '/api/medecin/rendez-vous',
                method: 'GET',
                extraParams: {
                    _token: csrfToken
                },
                failure: function(error) {
                    console.error('Erreur de chargement des événements:', error);
                    showNotification('Erreur lors du chargement des rendez-vous', 'error');
                },
                // Personnalisation des événements
                eventDataTransform: function(event) {
                    // Ajouter des classes et styles selon le type et le statut
                    let backgroundColor, textColor, borderColor;

                    switch(event.type) {
                        case 'consultation':
                            backgroundColor = '#e8f0fe'; // Bleu clair style Google
                            textColor = '#1a73e8';       // Bleu Google
                            borderColor = '#1a73e8';
                            break;
                        case 'examen':
                            backgroundColor = '#e6f4ea'; // Vert clair style Google
                            textColor = '#0d652d';       // Vert Google
                            borderColor = '#0d652d';
                            break;
                        case 'intervention':
                            backgroundColor = '#fce8e6'; // Rouge clair style Google
                            textColor = '#d93025';       // Rouge Google
                            borderColor = '#d93025';
                            break;
                        case 'autre':
                            backgroundColor = '#fef7e0'; // Jaune clair style Google
                            textColor = '#ea8600';       // Orange Google
                            borderColor = '#ea8600';
                            break;
                        default:
                            backgroundColor = '#f1f3f4'; // Gris clair Google
                            textColor = '#5f6368';       // Gris Google
                            borderColor = '#5f6368';
                    }

                    // Modifier l'apparence selon le statut
                                    if (event.statut === 'cancelled') {
                    textColor = '#80868b';  // Gris Google pour les événements annulés
                    event.textDecoration = 'line-through';
                    event.opacity = 0.7;
                } else if (event.statut === 'pending') {
                    event.borderStyle = 'dashed';
                }

                    event.backgroundColor = backgroundColor;
                    event.textColor = textColor;
                    event.borderColor = borderColor;

                    return event;
                }
            }
        ],

        // Gestion du drag & drop
        eventDrop: function(info) {
            handleEventChange(info.event);
        },

        // Gestion du redimensionnement
        eventResize: function(info) {
            handleEventChange(info.event);
        },

        // Création d'un événement par sélection
        select: function(info) {
            const date = info.start.toISOString().split('T')[0];
            const heure = info.start.toTimeString().split(' ')[0].substring(0, 5);
            openAddRdvModal(date, heure);
        },

        // Clic sur un événement - Amélioration avec formulaire à deux niveaux
        eventClick: function(info) {
            openRdvDetailsModal(info.event, 'compact'); // Ouvrir d'abord en mode compact
        },

        // Personnalisation de l'affichage des événements
        eventContent: function(arg) {
            const event = arg.event;
            const timeText = event.start ? event.start.toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'}) : '';
            const patientName = event.extendedProps.patient_nom || '';
            const isCompact = event.extendedProps.view === 'compact' || window.innerWidth < 768;

            const wrapper = document.createElement('div');
            wrapper.className = `event-card ${event.extendedProps.statut === 'cancelled' ? 'opacity-60' : ''}`;
            wrapper.style.width = '100%';
            wrapper.style.height = '100%';
            wrapper.style.borderLeft = `4px solid ${event.borderColor || '#1a73e8'}`;
            wrapper.style.borderRadius = '4px';
            wrapper.style.backgroundColor = event.backgroundColor || '#e8f0fe';
            wrapper.style.color = event.textColor || '#1a73e8';
            wrapper.style.overflow = 'hidden';
            wrapper.style.cursor = 'pointer';
            wrapper.style.transition = 'all 0.2s ease';

            // Appliquer des styles spécifiques selon le statut
            if (event.extendedProps.statut === 'cancelled') {
                wrapper.style.textDecoration = 'line-through';
            } else if (event.extendedProps.statut === 'pending') {
                wrapper.style.borderStyle = 'dashed';
            }

            // Version compacte pour les vues avec beaucoup d'événements ou sur mobile
            if (isCompact) {
                wrapper.innerHTML = `
                    <div class="p-1 flex items-center justify-between">
                        <span class="text-sm font-medium">${timeText}</span>
                                                    <span class="text-xs px-1 rounded-full ${getStatusClass(event.extendedProps.statut)}">
                                ${event.extendedProps.statut === 'confirmed' ? '✓' :
                                  event.extendedProps.statut === 'pending' ? '⏱' : '✕'}
                            </span>
                    </div>
                    <div class="px-1 font-medium truncate">${event.title}</div>
                `;
            } else {
                // Version détaillée pour les vues avec plus d'espace (style Google Calendar)
                wrapper.innerHTML = `
                    <div class="p-2 h-full flex flex-col">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium">${timeText}</span>
                            <span class="text-xs px-1.5 py-0.5 rounded-full ${getStatusClass(event.extendedProps.statut)}">
                                ${event.extendedProps.statut === 'confirmed' ? '✓' :
                                  event.extendedProps.statut === 'pending' ? '⏱' : '✕'}
                            </span>
                        </div>
                        <div class="font-medium truncate mt-0.5">${event.title}</div>
                        <div class="flex items-center text-xs mt-auto opacity-80">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            ${patientName}
                        </div>
                    </div>
                `;
            }

            // Ajouter des effets de survol
            wrapper.addEventListener('mouseenter', function() {
                this.style.boxShadow = '0 2px 6px rgba(0, 0, 0, 0.15)';
                this.style.transform = 'translateY(-1px)';
            });

            wrapper.addEventListener('mouseleave', function() {
                this.style.boxShadow = 'none';
                this.style.transform = 'translateY(0)';
            });

            return { domNodes: [wrapper] };
        },

        // Améliorer la réactivité
        lazyFetching: false,
        progressiveEventRendering: true,

        // Ajouter un délai avant le rafraîchissement pour éviter les problèmes de concurrence
        rerenderDelay: 150
    });

    calendar.render();
    window.calendar = calendar;

    // Fonction pour gérer les changements d'événements (drag & drop, resize)
    function handleEventChange(event) {
        const eventId = event.id;
        const startTime = event.start.toISOString();
        const endTime = event.end ? event.end.toISOString() : new Date(event.start.getTime() + 3600000).toISOString();

        // Afficher un indicateur de chargement
        showNotification('Mise à jour en cours...', 'info', 1000);

        // Envoyer les nouvelles dates au serveur
        fetch(`/api/medecin/rendez-vous/${eventId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                start: startTime,
                end: endTime
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur lors de la mise à jour');
            }
            return response.json();
        })
        .then(data => {
            showNotification('Rendez-vous mis à jour avec succès', 'success');
        })
        .catch(error => {
            console.error('Erreur:', error);
            showNotification('Erreur lors de la mise à jour du rendez-vous', 'error');
            calendar.refetchEvents(); // Recharger les événements en cas d'erreur
        });
    }

    // Initialiser les filtres de calendrier
    initializeCalendarFilters();

    // Gestion du bouton mini-calendrier
    const btnMiniCalendar = document.getElementById('btn-mini-calendar');
    const miniCalendarEl = document.getElementById('mini-calendar');

    if (btnMiniCalendar && miniCalendarEl) {
        btnMiniCalendar.addEventListener('click', function() {
            miniCalendarEl.classList.toggle('hidden');
        });

        // Fermer le mini-calendrier en cliquant ailleurs
        document.addEventListener('click', function(event) {
            if (!miniCalendarEl.contains(event.target) && !btnMiniCalendar.contains(event.target)) {
                miniCalendarEl.classList.add('hidden');
            }
        });
    }

    // Appliquer les styles au calendrier
    const calendarContainer = document.querySelector('.calendar-container');
    if (calendarContainer) {
        calendarContainer.style.cssText = `
            background: linear-gradient(to bottom right, #ffffff, #f8f9fa);
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        `;
    }

    // Styles pour les événements du calendrier - Mise à jour pour style Google Calendar
    const eventStyles = `
        .fc-event {
            border: none !important;
            padding: 0 !important;
            margin: 1px 0 !important;
            transition: all 0.2s ease;
        }
        .fc-event-main {
            padding: 0 !important;
        }
        .fc-event:hover {
            z-index: 5 !important;
        }
        .fc-button {
            border-radius: 4px !important;
            padding: 6px 12px !important;
            transition: all 0.2s ease !important;
            font-weight: 500 !important;
            text-transform: none !important;
        }
        .fc-button-primary {
            background-color: #b9ff66 !important;
            border-color: #b9ff66 !important;
            color: #000000 !important;
        }
        .fc-button-primary:hover {
            background-color: #a8eb5f !important;
            box-shadow: 0 1px 2px rgba(60, 64, 67, 0.3);
        }
        .fc-button-primary:not(.fc-button-active):focus {
            box-shadow: 0 0 0 3px rgba(185, 255, 102, 0.4) !important;
        }
        .fc-button-active {
            background-color: #a0d959 !important;
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.15) !important;
        }
        .fc-toolbar-title {
            font-size: 1.5rem !important;
            font-weight: 400 !important;
            color: #202124 !important;
        }
        .fc-view {
            border-radius: 8px;
            overflow: hidden;
        }
        .fc-scrollgrid {
            border: none !important;
        }
        .fc-theme-standard td, .fc-theme-standard th {
            border-color: #e0e0e0 !important;
        }
        .fc-day-today {
            background-color: rgba(26, 115, 232, 0.04) !important;
        }
        .fc-timegrid-slot {
            height: 48px !important;
        }
        .fc-col-header-cell {
            background-color: #f8f9fa;
            padding: 8px 0 !important;
        }
        .fc-timegrid-axis {
            padding: 0 8px !important;
        }
        .fc-timegrid-slot-label {
            font-size: 0.8rem;
            color: #70757a;
        }
        .fc-list-day-cushion {
            background-color: #f8f9fa !important;
        }
        .fc-list-event:hover td {
            background-color: rgba(26, 115, 232, 0.04) !important;
        }
        .fc-list-event-dot {
            display: none;
        }
        .fc-list-event-time {
            width: 120px;
        }
    `;

    // Ajouter les styles au document
    const styleSheet = document.createElement("style");
    styleSheet.textContent = eventStyles;
    document.head.appendChild(styleSheet);

    // Améliorer l'apparence des modals
    const modalStyles = `
        .modal-content {
            transform: scale(0.95);
            opacity: 0;
            transition: all 0.3s ease-out;
        }
        .modal-enter .modal-content {
            transform: scale(1);
            opacity: 1;
        }
        .modal-backdrop {
            backdrop-filter: blur(4px);
            background-color: rgba(0, 0, 0, 0.5);
            transition: all 0.3s ease;
        }
    `;

    const modalStyleSheet = document.createElement("style");
    modalStyleSheet.textContent = modalStyles;
    document.head.appendChild(modalStyleSheet);

    // Ajout d'une fonction pour adapter l'affichage en fonction de la vue
    function updateEventDisplay() {
        if (!window.calendar) return;

        const currentView = window.calendar.view.type;
        const events = window.calendar.getEvents();

        events.forEach(event => {
            // Définir si l'événement doit être affiché en mode compact ou détaillé
            if (currentView === 'dayGridMonth') {
                event.setExtendedProp('view', 'compact');
            } else if (currentView === 'listWeek') {
                event.setExtendedProp('view', 'list');
            } else {
                event.setExtendedProp('view', 'full');
            }
        });
    }

    // Mettre à jour l'affichage lors du changement de vue
    window.calendar.on('viewDidMount', function() {
        updateEventDisplay();
    });
});

// Initialisation des filtres du calendrier
function initializeCalendarFilters() {
    const filterForm = document.getElementById('calendar-filters');
    if (!filterForm) return;

    const typeCheckboxes = filterForm.querySelectorAll('input[name="type"]');
    const statutCheckboxes = filterForm.querySelectorAll('input[name="statut"]');

    // Fonction pour appliquer les filtres
    function applyFilters() {
        if (!window.calendar) return;

        const selectedTypes = Array.from(typeCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);

        const selectedStatuts = Array.from(statutCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);

        window.calendar.getEvents().forEach(event => {
            const eventType = event.extendedProps.type;
            const eventStatut = event.extendedProps.statut;

            const typeMatch = selectedTypes.includes(eventType);
            const statutMatch = selectedStatuts.includes(eventStatut);

            if (typeMatch && statutMatch) {
                event.setProp('display', 'auto');
            } else {
                event.setProp('display', 'none');
            }
        });
    }

    // Appliquer les filtres au changement
    typeCheckboxes.forEach(cb => cb.addEventListener('change', applyFilters));
    statutCheckboxes.forEach(cb => cb.addEventListener('change', applyFilters));
}

// Fonction pour afficher une notification
window.showNotification = function(message, type = 'info', duration = 5000) {
    const notification = document.createElement('div');
    notification.className = `
        fixed bottom-4 right-4 p-4 rounded-xl shadow-lg z-50
        flex items-center justify-between max-w-md
        transform transition-all duration-300 ease-out
        ${getNotificationClass(type)}
    `;

    notification.innerHTML = `
        <div class="flex items-center space-x-3">
            ${getNotificationIcon(type)}
            <span class="font-medium">${message}</span>
        </div>
        <button class="ml-4 p-1 rounded-full hover:bg-black/5 transition-colors duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    `;

    document.body.appendChild(notification);

    // Animation d'entrée
    requestAnimationFrame(() => {
        notification.style.transform = 'translateY(0) scale(1)';
        notification.style.opacity = '1';
    });

    // Fermeture automatique
    if (duration > 0) {
        setTimeout(() => {
            notification.style.transform = 'translateY(20px) scale(0.95)';
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        }, duration);
    }
};

// Fonction utilitaire pour les icônes de notification
function getNotificationIcon(type) {
    const baseClass = 'w-5 h-5';
    switch(type) {
        case 'success':
            return `<svg class="${baseClass} text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>`;
        case 'error':
            return `<svg class="${baseClass} text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>`;
        default:
            return `<svg class="${baseClass} text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>`;
    }
}

// Fonction utilitaire pour les classes de type - Mise à jour pour style Google Calendar
function getEventTypeClass(type) {
    // Cette fonction n'est plus utilisée directement pour les classes CSS
    // mais peut être conservée pour d'autres usages
    const classes = {
        consultation: 'bg-blue-50 text-blue-700 border-l-4 border-blue-500',
        suivi: 'bg-green-50 text-green-700 border-l-4 border-green-500',
        urgence: 'bg-red-50 text-red-700 border-l-4 border-red-500'
    };
    return classes[type] || 'bg-gray-50 text-gray-700 border-l-4 border-gray-500';
}

function getStatusClass(status) {
    const classes = {
        confirmed: 'bg-green-100 text-green-800',
        pending: 'bg-yellow-100 text-yellow-800',
        cancelled: 'bg-red-100 text-red-800'
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
}

// Fonction utilitaire pour les classes de notification
function getNotificationClass(type) {
    return {
        success: 'bg-green-50/90 text-green-800 border border-green-200',
        error: 'bg-red-50/90 text-red-800 border border-red-200',
        info: 'bg-blue-50/90 text-blue-800 border border-blue-200'
    }[type] || 'bg-gray-50/90 text-gray-800 border border-gray-200';
}

// Fonction pour initialiser le calendrier avec une meilleure gestion des événements
function initializeCalendar() {
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Créer une instance de calendrier avec des options optimisées
    const calendar = new Calendar(calendarEl, {
        plugins: [interactionPlugin, dayGridPlugin, timeGridPlugin, listPlugin],
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        locale: frLocale,
        timeZone: 'local',
        editable: true,
        selectable: true,
        selectMirror: true,
        dayMaxEvents: true,
        slotMinTime: '08:00:00',
        slotMaxTime: '19:00:00',
        slotDuration: '00:30:00',
        allDaySlot: false,
        nowIndicator: true, // Indicateur de l'heure actuelle
        businessHours: {
            daysOfWeek: [1, 2, 3, 4, 5], // Lundi au vendredi
            startTime: '08:00',
            endTime: '19:00',
        },
        eventSources: [{
            url: '/api/medecin/rendez-vous',
            method: 'GET',
            extraParams: {
                _token: csrfToken
            },
            failure: function(error) {
                console.error('Erreur de chargement des événements:', error);
                showNotification('Erreur lors du chargement des rendez-vous', 'error');
            }
        }],

        // Améliorer la réactivité
        lazyFetching: false,
        progressiveEventRendering: true,

        // Ajouter un délai avant le rafraîchissement pour éviter les problèmes de concurrence
        rerenderDelay: 150
    });

    // Rendre le calendrier accessible globalement
    window.calendar = calendar;

    // Initialiser le calendrier
    calendar.render();

    return calendar;
}

// Fonction pour mettre à jour un événement sans rafraîchir toute la page
async function updateEventStatus(eventId, newStatus) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    try {
        showNotification('Mise à jour en cours...', 'info', 1000);

        const response = await fetch(`/api/medecin/rendez-vous/${eventId}/statut`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                statut: newStatus
            })
        });

        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }

        const data = await response.json();

        // Mettre à jour le calendrier
        if (window.calendar) {
            // Rafraîchir tous les événements
            window.calendar.refetchEvents();
        }

        // Fermer le modal
        const modal = document.getElementById('modal-rdv-details');
        if (modal) {
            modal.classList.add('hidden');
        }

        showNotification('Statut du rendez-vous mis à jour avec succès', 'success');

        // Recharger la page après un court délai
        setTimeout(() => {
            window.location.reload();
        }, 1000);

        return true;
    } catch (error) {
        console.error('Erreur lors de la mise à jour du statut:', error);
        showNotification('Erreur lors de la mise à jour', 'error');
        return false;
    }
}

// Fonction pour supprimer un événement
async function deleteEvent(eventId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    try {
        showNotification('Suppression en cours...', 'info', 1000);

        const response = await fetch(`/api/medecin/rendez-vous/${eventId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        });

        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }

        const data = await response.json();

        // Supprimer l'événement du calendrier
        if (window.calendar) {
            const event = window.calendar.getEventById(eventId);
            if (event) {
                event.remove();
            }
            // Rafraîchir tout le calendrier
            window.calendar.refetchEvents();
        }

        // Fermer le modal
        const modal = document.getElementById('modal-rdv-details');
        if (modal) {
            modal.classList.add('hidden');
        }

        showNotification('Rendez-vous supprimé avec succès', 'success');

        // Recharger la page après un court délai
        setTimeout(() => {
            window.location.reload();
        }, 1000);

        return true;
    } catch (error) {
        console.error('Erreur lors de la suppression:', error);
        showNotification('Erreur lors de la suppression', 'error');
        return false;
    }
}

// Fonction pour ouvrir le modal de détails d'un rendez-vous avec mode compact/complet
window.openRdvDetailsModal = function(event, mode = 'full') {
    // Récupérer les données de l'événement
    const eventData = {
        id: event.id,
        title: event.title,
        start: event.start,
        end: event.end,
        ...event.extendedProps // Inclut type, statut, patient_nom, etc.
    };

    // Créer ou mettre à jour le contenu du modal
    let modal = document.getElementById('modal-rdv-details');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'modal-rdv-details';
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden';
        document.body.appendChild(modal);
    }

    // Déterminer si on doit afficher les boutons de confirmation/annulation
    const isEnAttente = eventData.statut === 'en_attente';
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Contenu du modal en fonction du mode (compact ou complet)
    if (mode === 'compact') {
        modal.innerHTML = `
            <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4 transform transition-all duration-300 ease-out">
                <div class="p-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900">${eventData.title}</h3>
                        <div class="flex space-x-2">
                            <button class="expand-modal p-1 hover:bg-gray-100 rounded-full" title="Voir plus de détails">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <button class="close-modal p-1 hover:bg-gray-100 rounded-full">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getEventTypeClass(eventData.type)}">
                                ${eventData.type}
                            </span>
                            <span class="inline-flex items-center ml-2 px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusClass(eventData.statut)}">
                                ${eventData.statut}
                            </span>
                        </div>
                        <span class="text-sm text-gray-600">
                            ${eventData.start.toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'})}
                            ${eventData.end ? ' - ' + eventData.end.toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'}) : ''}
                        </span>
                    </div>

                    <div class="flex items-center text-gray-700 mb-3">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>${eventData.patient_nom || 'Non spécifié'}</span>
                    </div>

                    <div class="flex justify-end space-x-2 mt-4">
                        <button class="px-3 py-1.5 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors text-sm edit-rdv" data-id="${eventData.id}">
                            Modifier
                        </button>
                        ${isEnAttente ? `
                            <button class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-sm cancel-rdv" data-id="${eventData.id}">
                                Annuler
                            </button>
                            <button class="px-3 py-1.5 bg-[#b9ff66] text-black rounded-lg hover:bg-[#a8eb5f] transition-colors text-sm confirm-rdv" data-id="${eventData.id}">
                                Confirmer
                            </button>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
    } else {
        // Mode complet avec plus de détails et d'options
        modal.innerHTML = `
            <div class="bg-white rounded-xl shadow-xl max-w-lg w-full mx-4 transform transition-all duration-300 ease-out">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-xl font-bold text-gray-900">${eventData.title}</h3>
                        <button class="close-modal p-1 hover:bg-gray-100 rounded-full">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getEventTypeClass(eventData.type)}">
                                    ${eventData.type}
                                </span>
                                <span class="inline-flex items-center ml-2 px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusClass(eventData.statut)}">
                                    ${eventData.statut}
                                </span>
                            </div>
                            <span class="text-sm text-gray-600">
                                ID: ${eventData.id}
                            </span>
                        </div>

                        <div class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>Patient: ${eventData.patient_nom || 'Non spécifié'}</span>
                        </div>

                        <div class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Date: ${eventData.start.toLocaleDateString('fr-FR')}</span>
                        </div>

                        <div class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Heure: ${eventData.start.toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'})}
                            ${eventData.end ? ' - ' + eventData.end.toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'}) : ''}</span>
                        </div>

                        <div class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <span class="capitalize">Type: ${eventData.type || 'Non spécifié'}</span>
                        </div>

                        <div class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span class="capitalize">Statut: ${(eventData.statut || '').replace('_', ' ')}</span>
                        </div>

                        ${eventData.description ? `
                            <div class="mt-4">
                                <h4 class="text-sm font-medium text-gray-700 mb-1">Description</h4>
                                <p class="text-gray-600 bg-gray-50 p-3 rounded-lg">${eventData.description}</p>
                            </div>
                        ` : ''}

                        <div class="flex justify-between pt-4 border-t border-gray-100 mt-4">
                            <div>
                                <button class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-sm delete-rdv" data-id="${eventData.id}">
                                    Supprimer
                                </button>
                            </div>
                            <div class="flex space-x-2">
                                <button class="px-3 py-1.5 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors text-sm compact-view">
                                    Vue simple
                                </button>
                                <button class="px-3 py-1.5 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors text-sm edit-rdv" data-id="${eventData.id}">
                                    Modifier
                                </button>
                                ${isEnAttente ? `
                                    <button class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-sm cancel-rdv" data-id="${eventData.id}">
                                        Annuler
                                    </button>
                                    <button class="px-3 py-1.5 bg-[#b9ff66] text-black rounded-lg hover:bg-[#a8eb5f] transition-colors text-sm confirm-rdv" data-id="${eventData.id}">
                                        Confirmer
                                    </button>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    // Afficher le modal avec animation
    modal.classList.remove('hidden');
    setTimeout(() => {
        const modalContent = modal.querySelector('div');
        modalContent.style.transform = 'scale(1)';
        modalContent.style.opacity = '1';
    }, 10);

    // Gérer la fermeture du modal
    const closeButton = modal.querySelector('.close-modal');
    closeButton.onclick = () => {
        const modalContent = modal.querySelector('div');
        modalContent.style.transform = 'scale(0.95)';
        modalContent.style.opacity = '0';
        setTimeout(() => modal.classList.add('hidden'), 300);
    };

    // Fermer le modal en cliquant en dehors
    modal.onclick = (e) => {
        if (e.target === modal) {
            const modalContent = modal.querySelector('div');
            modalContent.style.transform = 'scale(0.95)';
            modalContent.style.opacity = '0';
            setTimeout(() => modal.classList.add('hidden'), 300);
        }
    };

    // Basculer entre les vues compacte et complète
    if (mode === 'compact') {
        const expandButton = modal.querySelector('.expand-modal');
        if (expandButton) {
            expandButton.onclick = () => {
                openRdvDetailsModal(event, 'full');
            };
        }
    } else {
        const compactButton = modal.querySelector('.compact-view');
        if (compactButton) {
            compactButton.onclick = () => {
                openRdvDetailsModal(event, 'compact');
            };
        }
    }

    // Gérer le bouton de modification
    const editButtons = modal.querySelectorAll('.edit-rdv');
    editButtons.forEach(button => {
        button.onclick = () => {
            modal.classList.add('hidden');
            const rdvId = button.getAttribute('data-id');
            openEditRdvModal(rdvId);
        };
    });

    // Initialiser les événements de la modal de suppression si elle n'existe pas encore
    initializeDeleteModalEvents();

    // Gérer le bouton de suppression
    const deleteButton = modal.querySelector('.delete-rdv');
    if (deleteButton) {
        deleteButton.onclick = async (e) => {
            e.preventDefault();

            const rdvId = deleteButton.getAttribute('data-id');
            console.log('🗑️ Suppression demandée pour RDV ID:', rdvId);

            // Utiliser la modal personnalisée au lieu de confirm()
            if (window.openDeleteModal) {
                // Fermer la modal de détails d'abord
                modal.classList.add('hidden');

                // Ouvrir la modal de confirmation personnalisée
                window.openDeleteModal(rdvId);
            } else {
                console.error('❌ Fonction openDeleteModal non disponible, utilisation de confirm()');
                // Fallback vers confirm() si la fonction n'est pas disponible
                if (!confirm('Êtes-vous sûr de vouloir supprimer ce rendez-vous ?')) {
                    return;
                }

                // Continuer avec la suppression directe
                await performDelete(rdvId, deleteButton, modal);
            }
        };
    }

    // Fonction pour effectuer la suppression
    async function performDelete(rdvId, deleteButton, modal) {
        // Désactiver le bouton et montrer l'état de chargement
        deleteButton.disabled = true;
        const originalText = deleteButton.innerHTML;
        deleteButton.innerHTML = `<span class="inline-flex items-center"><svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Suppression...</span>`;

        const success = await deleteEvent(rdvId);

        if (success) {
            showNotification('Rendez-vous supprimé avec succès', 'success');

            // Fermer le modal avec animation
            const modalContent = modal.querySelector('div');
            modalContent.style.transform = 'scale(0.95)';
            modalContent.style.opacity = '0';
            setTimeout(() => modal.classList.add('hidden'), 300);
        } else {
            // Restaurer le bouton en cas d'échec
            deleteButton.disabled = false;
            deleteButton.innerHTML = originalText;
        }
    }

    // Gérer le bouton de confirmation
    const confirmButtons = modal.querySelectorAll('.confirm-rdv');
    confirmButtons.forEach(button => {
        button.onclick = async (e) => {
            e.preventDefault();

            // Désactiver le bouton et montrer l'état de chargement
            button.disabled = true;
            const originalText = button.innerHTML;
            button.innerHTML = `<span class="inline-flex items-center"><svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Confirmation...</span>`;

            const rdvId = button.getAttribute('data-id');
            const success = await updateEventStatus(rdvId, 'confirmé');

            if (success) {
                showNotification('Rendez-vous confirmé avec succès', 'success');

                // Fermer le modal avec animation
                const modalContent = modal.querySelector('div');
                modalContent.style.transform = 'scale(0.95)';
                modalContent.style.opacity = '0';
                setTimeout(() => modal.classList.add('hidden'), 300);
            } else {
                // Restaurer le bouton en cas d'échec
                button.disabled = false;
                button.innerHTML = originalText;
            }
        };
    });

    // Gérer le bouton d'annulation
    const cancelButtons = modal.querySelectorAll('.cancel-rdv');
    cancelButtons.forEach(button => {
        button.onclick = async (e) => {
            e.preventDefault();

            if (!confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ?')) {
                return;
            }

            // Désactiver le bouton et montrer l'état de chargement
            button.disabled = true;
            const originalText = button.innerHTML;
            button.innerHTML = `<span class="inline-flex items-center"><svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Annulation...</span>`;

            const rdvId = button.getAttribute('data-id');
            const success = await updateEventStatus(rdvId, 'cancelled');

            if (success) {
                showNotification('Rendez-vous annulé avec succès', 'success');

                // Fermer le modal avec animation
                const modalContent = modal.querySelector('div');
                modalContent.style.transform = 'scale(0.95)';
                modalContent.style.opacity = '0';
                setTimeout(() => modal.classList.add('hidden'), 300);
            } else {
                // Restaurer le bouton en cas d'échec
                button.disabled = false;
                button.innerHTML = originalText;
            }
        };
    });
};

// Fonction pour ouvrir le modal de modification de rendez-vous
window.openEditRdvModal = function(rdvId) {
    console.log('🔧 openEditRdvModal appelée avec ID:', rdvId);

    // Fermer toutes les modals ouvertes
    const allModals = document.querySelectorAll('[id^="modal-rdv-"], [id^="modal-edit-rdv-"]');
    allModals.forEach(modal => {
        modal.classList.add('hidden');
    });

    // Ouvrir la modal de modification
    const editModal = document.getElementById(`modal-edit-rdv-${rdvId}`);
    console.log('✏️ Modal de modification trouvée:', editModal);

    if (editModal) {
        editModal.classList.remove('hidden');
        console.log('✅ Modal de modification ouverte');

        // Animation d'entrée
        setTimeout(() => {
            const modalContent = editModal.querySelector('div');
            if (modalContent) {
                modalContent.style.transform = 'scale(1)';
                modalContent.style.opacity = '1';
            }
        }, 10);
    } else {
        console.error('❌ Modal de modification non trouvée avec ID: modal-edit-rdv-' + rdvId);
        showNotification('Impossible de trouver le formulaire de modification', 'error');
    }
};

// Fonction pour ouvrir le modal d'ajout de rendez-vous
window.openAddRdvModal = function(date = null, heure = null) {
    const modal = document.getElementById('modal-add-rdv');
    if (!modal) {
        console.error("Modal d'ajout de rendez-vous non trouvé");
        return;
    }

    // Réinitialiser le formulaire
    const form = modal.querySelector('form');
    if (form) {
        form.reset();
    }

    // Définir la date et l'heure si fournies
    if (date) {
        const dateInput = modal.querySelector('#date_debut');
        if (dateInput) {
            dateInput.value = typeof date === 'object' ? date.toISOString().split('T')[0] : date;
        }
    }

    if (heure) {
        const heureInput = modal.querySelector('#heure_debut');
        if (heureInput) {
            heureInput.value = heure;
        }
    }

    // Afficher le modal avec animation
    modal.classList.remove('hidden');
    modal.classList.add('modal-enter');

    // Gérer la fermeture
    const closeModal = () => {
        modal.classList.add('modal-leave');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('modal-leave');
        }, 200);
    };

    // Fermeture par le bouton
    const closeButton = modal.querySelector('.close-modal');
    if (closeButton) {
        closeButton.addEventListener('click', closeModal);
    }

    // Fermeture par clic en dehors
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal();
        }
    });

    // Fermeture par Echap
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
};

// Fonction de test pour diagnostiquer les modals
window.testModals = function() {
    console.log('🧪 Test des modals disponibles:');

    // Vérifier les modals de détails
    const detailModals = document.querySelectorAll('[id^="modal-rdv-"]');
    console.log('📋 Modals de détails:', detailModals.length);
    detailModals.forEach(modal => {
        console.log('  -', modal.id);
    });

    // Vérifier les modals de modification
    const editModals = document.querySelectorAll('[id^="modal-edit-rdv-"]');
    console.log('✏️ Modals de modification:', editModals.length);
    editModals.forEach(modal => {
        console.log('  -', modal.id);
    });

    // Vérifier les boutons de modification
    const editButtons = document.querySelectorAll('.edit-rdv');
    console.log('🔘 Boutons de modification:', editButtons.length);
    editButtons.forEach(button => {
        console.log('  -', button.getAttribute('data-id'), button);
    });

    // Vérifier les boutons onclick
    const onclickButtons = document.querySelectorAll('button[onclick*="modal-edit-rdv"]');
    console.log('🔘 Boutons onclick:', onclickButtons.length);
    onclickButtons.forEach(button => {
        console.log('  -', button.getAttribute('onclick'), button);
    });
};

// Fonction pour tester l'ouverture d'une modal spécifique
window.testOpenModal = function(rdvId) {
    console.log('🧪 Test d\'ouverture de modal pour ID:', rdvId);
    openEditRdvModal(rdvId);
};

// Fonction pour ouvrir la modal de confirmation de suppression
window.openDeleteModal = function(rdvId) {
    console.log('🗑️ Ouverture de la modal de suppression pour RDV ID:', rdvId);

    // Stocker l'ID du rendez-vous pour la suppression
    window.currentDeleteRdvId = rdvId;

    // Afficher la modal
    const modal = document.getElementById('modal-confirm-delete');
    if (modal) {
        modal.classList.remove('hidden');

        // Animation d'entrée
        setTimeout(() => {
            const modalContent = modal.querySelector('div');
            if (modalContent) {
                modalContent.style.transform = 'scale(1)';
                modalContent.style.opacity = '1';
            }
        }, 10);
    } else {
        console.error('❌ Modal de suppression non trouvée');
    }
};

// Fonction pour fermer la modal de suppression
window.closeDeleteModal = function() {
    const modal = document.getElementById('modal-confirm-delete');
    if (!modal) return;

    const modalContent = modal.querySelector('div');

    // Animation de sortie
    if (modalContent) {
        modalContent.style.transform = 'scale(0.95)';
        modalContent.style.opacity = '0';
    }

    setTimeout(() => {
        modal.classList.add('hidden');
        // Nettoyer la variable globale
        window.currentDeleteRdvId = null;
    }, 300);
};

// Fonction pour confirmer la suppression
window.confirmDelete = function() {
    if (window.currentDeleteRdvId) {
        console.log('✅ Confirmation de suppression pour RDV ID:', window.currentDeleteRdvId);

        // Fermer la modal de confirmation
        closeDeleteModal();

        // Créer un formulaire HTML classique comme dans votre exemple
        const form = document.createElement('form');
        form.action = `/medecin/rendez-vous/${window.currentDeleteRdvId}`;
        form.method = 'POST';
        form.style.display = 'none';

        // Ajouter le token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);

        // Ajouter la méthode DELETE
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        // Ajouter le formulaire au DOM et le soumettre
        document.body.appendChild(form);
        form.submit();
    } else {
        console.error('❌ Aucun ID de rendez-vous trouvé pour la suppression');
    }
};

// Fonction pour initialiser les événements de la modal de suppression
function initializeDeleteModalEvents() {
    // Vérifier si les événements sont déjà initialisés
    if (window.deleteModalEventsInitialized) {
        return;
    }

    const cancelDeleteBtn = document.getElementById('cancel-delete');
    const confirmDeleteBtn = document.getElementById('confirm-delete');
    const deleteModal = document.getElementById('modal-confirm-delete');

    console.log('🔧 Initialisation des événements de suppression:', {
        cancelDeleteBtn: !!cancelDeleteBtn,
        confirmDeleteBtn: !!confirmDeleteBtn,
        deleteModal: !!deleteModal
    });

    if (cancelDeleteBtn) {
        cancelDeleteBtn.addEventListener('click', function() {
            console.log('🚫 Annulation de la suppression');
            closeDeleteModal();
        });
    }

    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            console.log('✅ Confirmation de la suppression');
            confirmDelete();
        });
    }

    // Fermer la modal en cliquant en dehors
    if (deleteModal) {
        deleteModal.addEventListener('click', function(e) {
            if (e.target === deleteModal) {
                closeDeleteModal();
            }
        });
    }

    // Fermer la modal avec la touche Échap
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && deleteModal && !deleteModal.classList.contains('hidden')) {
            closeDeleteModal();
        }
    });

    // Marquer comme initialisé
    window.deleteModalEventsInitialized = true;
}

