// console.log('app.js chargé');
import './bootstrap';
import './agenda'; // Importez votre fichier agenda.js
import './dashboard'; // Fichier dashboard.js principal
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
