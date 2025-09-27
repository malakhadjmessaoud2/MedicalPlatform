// console.log('app.js chargé');
import './bootstrap';
import Alpine from 'alpinejs';
import './agenda'; // Importez votre fichier agenda.js
// import './dashboard'; // Fichier dashboard.js principal - supprimé (duplication)
// Import des fichiers du dashboard
import './dashboard/navbar-timeline';
import './dashboard/consultation-manager';
import './dashboard/dossier-manager';
import './dashboard/notifications';

// Import FullCalendar et ses plugins
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';
import frLocale from '@fullcalendar/core/locales/fr';

// Rendre les plugins disponibles globalement
window.Calendar = Calendar;
window.dayGridPlugin = dayGridPlugin;
window.timeGridPlugin = timeGridPlugin;
window.listPlugin = listPlugin;
window.interactionPlugin = interactionPlugin;
window.frLocale = frLocale;

// Initialiser FullCalendar lorsque le DOM est chargé
document.addEventListener('DOMContentLoaded', function() {
    // Vérifier si l'élément calendar existe avant d'initialiser
    const calendarEl = document.getElementById('calendar');
    if (calendarEl) {
        const calendar = new Calendar(calendarEl, {
            plugins: [interactionPlugin, dayGridPlugin, timeGridPlugin, listPlugin],
            initialView: 'timeGridWeek',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },
            locale: 'fr',
            // Autres options de configuration
        });

        calendar.render();
        window.calendar = calendar;
    }
});

// Initialiser les tooltips et popovers
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function(tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialiser les popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.forEach(function(popoverTriggerEl) {
        new bootstrap.Popover(popoverTriggerEl);
    });
});

// Initialiser Alpine.js de manière conditionnelle pour éviter les conflits avec Livewire
// On vérifie si Alpine n'est pas déjà initialisé par Livewire
if (!window.Alpine) {
    window.Alpine = Alpine;
    Alpine.start();
} else {
    // Si Alpine est déjà initialisé par Livewire, on s'assure que nos composants fonctionnent
    console.log('Alpine.js déjà initialisé par Livewire');
}

// Refresh avatar images when profile photo updates
window.addEventListener('profile-photo-updated', () => {
    fetch('/me/profile-photo-url', { credentials: 'same-origin' })
        .then(r => r.json())
        .then(({ url }) => {
            window.__avatarVersion = Date.now();
            const avatars = document.querySelectorAll('img[data-avatar]');
            avatars.forEach((img) => {
                const base = (url || img.getAttribute('src')?.split('?')[0] || '');
                img.setAttribute('src', `${base}?v=${window.__avatarVersion}`);
            });
        })
        .catch(() => {
            // fallback to cache-busting current src
            window.__avatarVersion = Date.now();
            const avatars = document.querySelectorAll('img[data-avatar]');
            avatars.forEach((img) => {
                const base = img.getAttribute('src')?.split('?')[0] || '';
                img.setAttribute('src', `${base}?v=${window.__avatarVersion}`);
            });
        });
});
