// console.log('app.js chargé');
import './bootstrap';

// Import FullCalendar et ses plugins
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';

// Rendre les plugins disponibles globalement
window.Calendar = Calendar;
window.dayGridPlugin = dayGridPlugin;
window.timeGridPlugin = timeGridPlugin;
window.listPlugin = listPlugin;
window.interactionPlugin = interactionPlugin;

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
