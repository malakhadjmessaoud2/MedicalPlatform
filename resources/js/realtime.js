import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

console.log('Initialisation du module realtime.js');

document.addEventListener('DOMContentLoaded', function() {
    // Configuration de Pusher
    window.Pusher = Pusher;
    Pusher.logToConsole = true; // Activer les logs pour le débogage

    // Initialiser Echo avec les informations de configuration
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: document.querySelector('meta[name="pusher-key"]')?.content,
        cluster: document.querySelector('meta[name="pusher-cluster"]')?.content,
        forceTLS: true
    });

    if (window.Echo) {
        console.log('Echo initialisé avec succès');

        // S'abonner au canal rendez-vous
        window.Echo.channel('rendez-vous')
            .listen('.RendezVousModifie', (event) => {
                console.log('Événement reçu:', event);

                // Détecter le type d'interface (médecin ou patient)
                const isMedecinPage = document.getElementById('calendar') !== null;
                const isPatientPage = document.querySelector('.patient-dashboard') !== null;

                if (isMedecinPage) {
                    handleMedecinEvent(event);
                } else if (isPatientPage) {
                    handlePatientEvent(event);
                }
            });
    } else {
        console.error('Échec de l\'initialisation d\'Echo');
    }
});

// Gestion des événements pour l'interface médecin
function handleMedecinEvent(event) {
    console.log('Traitement de l\'événement pour le médecin');

    if (!window.calendar) {
        console.error('Le calendrier n\'est pas disponible');
        return;
    }

    const { action, rendezVous } = event;

    switch (action) {
        case 'created':
            // Ajouter un nouvel événement au calendrier
            window.calendar.addEvent({
                id: rendezVous.id,
                title: rendezVous.title,
                start: rendezVous.start,
                end: rendezVous.end,
                backgroundColor: rendezVous.backgroundColor,
                borderColor: rendezVous.borderColor,
                textColor: rendezVous.textColor,
                extendedProps: rendezVous.extendedProps
            });
            showNotification('Nouveau rendez-vous ajouté');
            break;

        case 'updated':
            // Mettre à jour un événement existant
            const existingEvent = window.calendar.getEventById(rendezVous.id);
            if (existingEvent) {
                existingEvent.setProp('title', rendezVous.title);
                existingEvent.setStart(rendezVous.start);
                existingEvent.setEnd(rendezVous.end);
                existingEvent.setProp('backgroundColor', rendezVous.backgroundColor);
                existingEvent.setProp('borderColor', rendezVous.borderColor);

                // Mettre à jour les propriétés étendues
                Object.keys(rendezVous.extendedProps).forEach(key => {
                    existingEvent.setExtendedProp(key, rendezVous.extendedProps[key]);
                });
            } else {
                // Si l'événement n'est pas trouvé, actualiser tous les événements
                window.calendar.refetchEvents();
            }
            showNotification('Rendez-vous mis à jour');
            break;

        case 'deleted':
            // Supprimer un événement
            const eventToDelete = window.calendar.getEventById(rendezVous.id);
            if (eventToDelete) {
                eventToDelete.remove();
            } else {
                // Si l'événement n'est pas trouvé, actualiser tous les événements
                window.calendar.refetchEvents();
            }
            showNotification('Rendez-vous supprimé');
            break;

        default:
            console.warn('Action non reconnue:', action);
            // Rafraîchir par sécurité
            window.calendar.refetchEvents();
    }
}

// Gestion des événements pour l'interface patient
function handlePatientEvent(event) {
    console.log('Traitement de l\'événement pour le patient:', event);

    const { action, rendezVous } = event;
    const patientId = document.querySelector('meta[name="patient-id"]')?.content;

    // Vérifier si le rendez-vous concerne ce patient
    if (patientId && rendezVous.extendedProps.patient_id == patientId) {
        // Sauvegarde de l'état actuel du stepper si présent
        const stepperState = saveStepperState();

        // Mettre à jour l'interface utilisateur sans rechargement complet
        fetch('/patient/rendez-vous/sections?_=' + new Date().getTime())
            .then(response => response.json())
            .then(data => {
                updatePatientSections(data);

                // Restaurer l'état du stepper si nécessaire
                if (stepperState) {
                    restoreStepperState(stepperState);
                }

                // Afficher une notification
                let message;
                switch (action) {
                    case 'created':
                        message = 'Nouveau rendez-vous ajouté';
                        break;
                    case 'updated':
                        message = 'Rendez-vous mis à jour';
                        break;
                    case 'deleted':
                        message = 'Rendez-vous supprimé';
                        break;
                    default:
                        message = 'Changement de rendez-vous';
                }
                showNotification(message);
            })
            .catch(error => {
                console.error('Erreur lors de la mise à jour des sections:', error);
                showNotification('Erreur lors de la mise à jour des sections', 'error');
            });
    }
}

// Fonction pour sauvegarder l'état actuel du stepper
function saveStepperState() {
    const stepper = document.querySelector('.stepper-container');
    if (!stepper) return null;

    return {
        activeStep: stepper.querySelector('.step.active')?.dataset.step,
        scrollPosition: window.scrollY,
        expandedSections: Array.from(document.querySelectorAll('.expandable-section.expanded')).map(section => section.id)
    };
}

// Fonction pour restaurer l'état du stepper
function restoreStepperState(state) {
    if (!state) return;

    // Restaurer l'étape active
    if (state.activeStep) {
        const step = document.querySelector(`.step[data-step="${state.activeStep}"]`);
        if (step) {
            document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
            step.classList.add('active');

            // Restaurer le contenu visible associé
            document.querySelectorAll('.step-content').forEach(content => {
                content.classList.add('hidden');
            });
            const activeContent = document.querySelector(`.step-content[data-step="${state.activeStep}"]`);
            if (activeContent) {
                activeContent.classList.remove('hidden');
            }
        }
    }

    // Restaurer les sections développées
    if (state.expandedSections) {
        state.expandedSections.forEach(sectionId => {
            const section = document.getElementById(sectionId);
            if (section) {
                section.classList.add('expanded');
                const content = section.querySelector('.expandable-content');
                if (content) {
                    content.style.maxHeight = content.scrollHeight + 'px';
                }
            }
        });
    }

    // Restaurer la position de défilement
    if (state.scrollPosition) {
        window.scrollTo(0, state.scrollPosition);
    }
}

// Fonction pour mettre à jour les sections du dashboard patient
function updatePatientSections(data) {
    if (data.prochainRdv) {
        const prochainRdvSection = document.getElementById('prochain-rdv');
        if (prochainRdvSection) {
            // Remplacer le contenu mais préserver les écouteurs d'événements
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = data.prochainRdv;

            // Ne remplacer que l'intérieur de la section
            const contentNode = tempDiv.firstChild;
            if (contentNode) {
                prochainRdvSection.innerHTML = '';
                prochainRdvSection.appendChild(contentNode);
            }

            // Réattacher les écouteurs d'événements
            attachEventListeners(prochainRdvSection);
        }
    }

    if (data.rdvEnAttente) {
        const rdvEnAttenteSection = document.getElementById('rdv-en-attente');
        if (rdvEnAttenteSection) {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = data.rdvEnAttente;

            const contentNode = tempDiv.firstChild;
            if (contentNode) {
                rdvEnAttenteSection.innerHTML = '';
                rdvEnAttenteSection.appendChild(contentNode);
            }

            attachEventListeners(rdvEnAttenteSection);
        }
    }

    if (data.historiqueRdv) {
        const historiqueRdvSection = document.getElementById('historique-rdv');
        if (historiqueRdvSection) {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = data.historiqueRdv;

            const contentNode = tempDiv.firstChild;
            if (contentNode) {
                historiqueRdvSection.innerHTML = '';
                historiqueRdvSection.appendChild(contentNode);
            }

            attachEventListeners(historiqueRdvSection);
        }
    }
}

// Fonction pour réattacher les écouteurs d'événements
function attachEventListeners(section) {
    // Réattache les écouteurs pour les boutons d'annulation de rendez-vous
    section.querySelectorAll('form[action*="cancel"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ?')) {
                e.preventDefault();
            }
        });
    });

    // Réattache les écouteurs pour les boutons du stepper
    section.querySelectorAll('.step-button').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const step = this.dataset.step;
            activateStep(step);
        });
    });

    // Réattache les écouteurs pour les sections dépliables
    section.querySelectorAll('.expandable-header').forEach(header => {
        header.addEventListener('click', function() {
            const section = this.closest('.expandable-section');
            const content = section.querySelector('.expandable-content');

            if (section.classList.contains('expanded')) {
                section.classList.remove('expanded');
                content.style.maxHeight = '0px';
            } else {
                section.classList.add('expanded');
                content.style.maxHeight = content.scrollHeight + 'px';
            }
        });
    });
}

// Fonction pour activer une étape du stepper
function activateStep(stepNumber) {
    // Désactiver toutes les étapes
    document.querySelectorAll('.step').forEach(step => {
        step.classList.remove('active');
    });

    // Masquer tous les contenus
    document.querySelectorAll('.step-content').forEach(content => {
        content.classList.add('hidden');
    });

    // Activer l'étape sélectionnée
    const activeStep = document.querySelector(`.step[data-step="${stepNumber}"]`);
    if (activeStep) {
        activeStep.classList.add('active');
    }

    // Afficher le contenu correspondant
    const activeContent = document.querySelector(`.step-content[data-step="${stepNumber}"]`);
    if (activeContent) {
        activeContent.classList.remove('hidden');
    }
}

// Fonction pour afficher des notifications
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed bottom-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500' :
        type === 'warning' ? 'bg-yellow-500' :
        type === 'error' ? 'bg-red-500' :
        'bg-blue-500'
    } text-white`;
    notification.textContent = message;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.classList.add('opacity-0', 'transition-opacity', 'duration-500');
        setTimeout(() => notification.remove(), 500);
    }, 3000);
}
