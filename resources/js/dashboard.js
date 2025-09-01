document.addEventListener('DOMContentLoaded', function() {
    loadTimeline();
    setInterval(loadTimeline, 60000);
});

function loadTimeline() {
    fetch('/api/medecin/timeline', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        credentials: 'same-origin'
    })
        .then(response => { if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`); return response.json(); })
        .then(data => { if (data.error) { console.error('Erreur API:', data.error); showTimelineError(data.error); } else { updateTimeline(data); } })
        .catch(error => { console.error('Erreur lors du chargement du timeline:', error); showTimelineError(error.message); });
}

function updateTimeline(data) {
    const timelineContent = document.getElementById('timeline-content');
    const currentDate = document.getElementById('current-date');
    if (currentDate) currentDate.textContent = data.current_date;

    let timelineHTML = '';
    for (let hour = 8; hour <= 17; hour++) {
        const timeSlot = data.timeline.find(slot => slot.hour === hour);
        const isCurrentHour = hour === data.current_hour;
        const isPast = hour < data.current_hour;
        const isFuture = hour > data.current_hour;
        const rdv = timeSlot ? timeSlot.rendez_vous[0] : null;
        timelineHTML += `
            <div class="timeline-slot ${isCurrentHour ? 'current' : isPast ? 'past' : 'future'}" data-hour="${hour}">
                ${rdv ? `
                    <div class="slot-chip enhanced" data-tooltip="👤 ${rdv.patient_name}${rdv.is_active ? ' (En cours)' : rdv.is_late ? ' (En retard)' : ''}${rdv.has_consultation_link ? ' - 📹 Téléconsultation' : ''} - 🕐 ${rdv.start_time} à ${rdv.end_time}${rdv.has_consultation_link ? (isConsultationLinkActive(rdv.start_time, rdv.end_time) ? ' - ✅ Lien actif' : ' - ⏰ Lien disponible de 5 min avant à la fin') : ''}">
                        <img src="${rdv.patient_photo}" class="slot-avatar" alt="Patient">
                        <span class="slot-name">${rdv.patient_name}</span>
                        <span class="slot-time">${rdv.start_time}</span>
                        ${rdv.has_consultation_link && isConsultationLinkActive(rdv.start_time, rdv.end_time) ?
                            `<img src=\"https://upload.wikimedia.org/wikipedia/commons/9/9b/Google_Meet_icon_%282020%29.svg\"
                                  class=\"w-3 h-3 meet-icon\"
                                  alt=\"Meet\"
                                  title=\"Cliquer pour ouvrir la téléconsultation\"
                                  onclick=\"openConsultationLink('${rdv.lien_en_ligne || ''}', ${rdv.id})\"
                                  style=\"cursor: pointer;\">` :
                            rdv.has_consultation_link ?
                            `<img src=\"https://upload.wikimedia.org/wikipedia/commons/9/9b/Google_Meet_icon_%282020%29.svg\"
                                  class=\"w-3 h-3 meet-icon disabled\"
                                  alt=\"Meet (inactif)\"
                                  title=\"Lien de consultation disponible de 5 min avant le début jusqu'à la fin\"
                                  style=\"cursor: not-allowed;\">` :
                            ''
                        }
                    </div>
                ` : `
                    <div class="slot-chip free" data-tooltip="🕐 Créneau libre de ${hour.toString().padStart(2, '0')}:00 à ${(hour + 1).toString().padStart(2, '0')}:00">
                        <span class="slot-time">${hour.toString().padStart(2, '0')}:00</span>
                        <span class="slot-name">Libre</span>
                    </div>
                `}
            </div>
        `;
    }

    if (data.total_rendez_vous > 0) {
        timelineHTML = `
            <div class=\"timeline-slot stats-container\" data-tooltip=\"📊 Résumé de la journée: ${data.completed_rendez_vous} terminés, ${data.pending_rendez_vous} en attente, ${data.total_rendez_vous} total\">
                <div class=\"bg-white/90 backdrop-blur-sm rounded-lg p-2 shadow-sm border border-white/50 h-full flex items-center justify-center\" data-tooltip=\"📊 Résumé de la journée: ${data.completed_rendez_vous} terminés, ${data.pending_rendez_vous} en attente, ${data.total_rendez_vous} total\">
                    <div class=\"flex items-center gap-3 text-xs\">
                        <div class=\"flex items-center gap-1\"><div class=\"w-2 h-2 bg-green-500 rounded-full\"></div><span class=\"text-black/70\">${data.completed_rendez_vous}</span></div>
                        <div class=\"flex items-center gap-1\"><div class=\"w-2 h-2 bg-yellow-500 rounded-full\"></div><span class=\"text-black/70\">${data.pending_rendez_vous}</span></div>
                        <div class=\"flex items-center gap-1\"><div class=\"w-2 h-2 bg-blue-500 rounded-full\"></div><span class=\"text-black/70\">${data.total_rendez_vous}</span></div>
                    </div>
                </div>
            </div>` + timelineHTML;
    }

    timelineContent.innerHTML = timelineHTML;

    const tooltipBar = document.getElementById('timeline-tooltip');
    const tooltipInner = tooltipBar ? tooltipBar.querySelector('.tooltip-inner') : null;
    if (tooltipBar && tooltipInner) {
        const chips = timelineContent.querySelectorAll('.slot-chip');
        chips.forEach(chip => {
            const text = chip.getAttribute('data-tooltip') || chip.textContent.trim();
            const isMeetIcon = chip.querySelector('.meet-icon');
            chip.addEventListener('mouseenter', () => {
                let displayText = text;
                if (isMeetIcon) {
                    const patientName = chip.querySelector('.slot-name')?.textContent || '';
                    const startTime = chip.querySelector('.slot-time')?.textContent || '';
                    const isDisabled = chip.querySelector('.meet-icon.disabled');
                    displayText = isDisabled ? `👤 ${patientName} - 🕐 ${startTime} - ⏰ Lien de consultation disponible de 5 min avant le début jusqu'à la fin` : `👤 ${patientName} - 🕐 ${startTime} - 📹 Cliquez sur l'icône Meet pour ouvrir la téléconsultation`;
                }
                tooltipInner.innerHTML = displayText;
                tooltipBar.classList.add('visible');
            });
            chip.addEventListener('mouseleave', () => { tooltipBar.classList.remove('visible'); });
            chip.addEventListener('focus', () => {
                let displayText = text;
                if (isMeetIcon) {
                    const patientName = chip.querySelector('.slot-name')?.textContent || '';
                    const startTime = chip.querySelector('.slot-time')?.textContent || '';
                    const isDisabled = chip.querySelector('.meet-icon.disabled');
                    displayText = isDisabled ? `👤 ${patientName} - 🕐 ${startTime} - ⏰ Lien de consultation disponible de 5 min avant le début jusqu'à la fin` : `👤 ${patientName} - 🕐 ${startTime} - 📹 Cliquez sur l'icône Meet pour ouvrir la téléconsultation`;
                }
                tooltipInner.innerHTML = displayText;
                tooltipBar.classList.add('visible');
            });
            chip.addEventListener('blur', () => { tooltipBar.classList.remove('visible'); });
        });
    }

    setTimeout(() => { scrollToCurrentHour(data.current_hour); }, 200);
    updateScrollButtons();

    const slots = timelineContent.querySelectorAll('.timeline-slot');
    slots.forEach((slot, index) => {
        slot.style.opacity = '0';
        slot.style.transform = 'translateY(20px)';
        setTimeout(() => {
            slot.style.transition = 'all 0.5s cubic-bezier(0.4, 0, 0.2, 1)';
            slot.style.opacity = '1';
            slot.style.transform = 'translateY(0)';
        }, index * 50);
    });
}

function showTimelineError(errorMessage = 'Erreur de chargement') {
    const timelineContent = document.getElementById('timeline-content');
    timelineContent.innerHTML = `<div class=\"flex items-center justify-center w-full\"><div class=\"bg-red-50 border border-red-200 rounded-lg p-4 flex items-center\"><svg class=\"w-6 h-6 text-red-500 mr-3\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z\"/></svg><div><span class=\"text-red-700 font-medium\">${errorMessage}</span><button onclick=\"loadTimeline()\" class=\"ml-2 text-red-600 hover:text-red-800 underline text-sm\">Réessayer</button></div></div></div>`;
}

function refreshTimeline() { loadTimeline(); }
function openConsultation(rendezVousId) { window.location.href = `/medecin/rendez-vous/${rendezVousId}/consultation`; }

function isConsultationLinkActive(startTime, endTime) {
    if (!startTime || !endTime) return false;
    const now = new Date();
    const today = now.toISOString().split('T')[0];
    const startDateTime = new Date(`${today}T${startTime}`);
    const endDateTime = new Date(`${today}T${endTime}`);
    const fiveMinutesBefore = new Date(startDateTime.getTime() - 5 * 60 * 1000);
    return now >= fiveMinutesBefore && now <= endDateTime;
}

function openConsultationLink(link, rendezVousId) {
    if (link && link.trim() !== '' && link !== 'undefined') {
        try { new URL(link); window.open(link, '_blank'); console.log(`Consultation ouverte pour le rendez-vous ${rendezVousId}: ${link}`); }
        catch { console.error('URL invalide:', link); alert("Le lien de consultation n'est pas une URL valide"); }
    } else { console.error('Lien de consultation non disponible ou invalide:', link); alert('Lien de consultation non disponible pour ce rendez-vous. Veuillez vérifier que le lien a été généré.'); }
}

function scrollToCurrentHour(currentHour) {
    const timelineContent = document.getElementById('timeline-content');
    const currentHourElement = timelineContent.querySelector(`[data-hour="${currentHour}"]`);
    if (currentHourElement) {
        const containerWidth = timelineContent.clientWidth;
        const elementLeft = currentHourElement.offsetLeft;
        const elementWidth = currentHourElement.offsetWidth;
        const scrollPosition = elementLeft - (containerWidth / 2) + (elementWidth / 2);
        timelineContent.scrollTo({ left: scrollPosition, behavior: 'smooth' });
        currentHourElement.style.transform = 'scale(1.1)';
        setTimeout(() => { currentHourElement.style.transform = ''; }, 1000);
    }
}

function updateScrollButtons() {
    const timelineContent = document.getElementById('timeline-content');
    const scrollLeftBtn = document.getElementById('scroll-left');
    const scrollRightBtn = document.getElementById('scroll-right');
    scrollLeftBtn.style.opacity = timelineContent.scrollLeft > 0 ? '1' : '0.3';
    const maxScroll = timelineContent.scrollWidth - timelineContent.clientWidth;
    scrollRightBtn.style.opacity = timelineContent.scrollLeft < maxScroll ? '1' : '0.3';
}

document.addEventListener('DOMContentLoaded', function() {
    const timelineContent = document.getElementById('timeline-content');
    const scrollLeftBtn = document.getElementById('scroll-left');
    const scrollRightBtn = document.getElementById('scroll-right');
    scrollLeftBtn.addEventListener('click', function() { timelineContent.scrollBy({ left: -300, behavior: 'smooth' }); this.style.transform = 'scale(0.9)'; setTimeout(() => { this.style.transform = ''; }, 150); });
    scrollRightBtn.addEventListener('click', function() { timelineContent.scrollBy({ left: 300, behavior: 'smooth' }); this.style.transform = 'scale(0.9)'; setTimeout(() => { this.style.transform = ''; }, 150); });
    timelineContent.addEventListener('scroll', updateScrollButtons);
    let isScrolling = false; let startX = 0; let scrollLeft = 0;
    timelineContent.addEventListener('touchstart', function(e) { isScrolling = true; startX = e.touches[0].pageX - timelineContent.offsetLeft; scrollLeft = timelineContent.scrollLeft; });
    timelineContent.addEventListener('touchmove', function(e) { if (!isScrolling) return; e.preventDefault(); const x = e.touches[0].pageX - timelineContent.offsetLeft; const walk = (x - startX) * 2; timelineContent.scrollLeft = scrollLeft - walk; });
    timelineContent.addEventListener('touchend', function() { isScrolling = false; });
});
