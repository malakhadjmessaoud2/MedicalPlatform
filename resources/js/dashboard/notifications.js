document.addEventListener('DOMContentLoaded', () => {
    if (!window.Echo) return;

    const list = document.getElementById('notifications-list');
    const empty = document.getElementById('notifications-empty');
    const badge = document.getElementById('notifications-badge');
    const dropdown = document.getElementById('notifications-dropdown');
    const button = document.getElementById('notifications-button');

    const addNotification = (html) => {
        if (!list) return;
        if (empty) empty.remove();

        const item = document.createElement('div');
        item.className = 'px-4 py-2 hover:bg-gray-50 cursor-pointer';
        item.innerHTML = html;
        list.prepend(item);

        if (badge) badge.classList.remove('hidden');
    };

    // Toggle dropdown on click, close on outside click or Escape
    if (button && dropdown) {
        button.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdown.classList.toggle('hidden');
            // clear badge when opening
            if (!dropdown.classList.contains('hidden') && badge) {
                badge.classList.add('hidden');
            }
        });

        document.addEventListener('click', (e) => {
            if (dropdown.classList.contains('hidden')) return;
            const container = document.getElementById('notifications');
            if (container && !container.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                dropdown.classList.add('hidden');
            }
        });
    }

    // Listen to public channel 'rendez-vous' for event 'create'
    try {
        window.Echo.channel('rendez-vous')
            .listen('.create', (e) => {
                // e.rendezVous contains rendez-vous with relations
                const rdv = e?.rendezVous || {};
                const patient = rdv.patient ? `${rdv.patient.prenom ?? ''} ${rdv.patient.nom ?? ''}`.trim() : 'Patient';
                const medecinId = rdv.medecin_id;

                // Only show to the concerned doctor if we can check current user id (via meta tag)
                const currentUserId = document.querySelector('meta[name="user-id"]')?.getAttribute('content');
                if (currentUserId && medecinId && String(currentUserId) !== String(medecinId)) {
                    return; // not for this doctor
                }

                const date = rdv.date_debut ? new Date(rdv.date_debut) : null;
                const time = date ? date.toLocaleString([], { hour: '2-digit', minute: '2-digit' }) : '';
                const type = rdv.type ? rdv.type : 'rendez-vous';

                addNotification(`
                    <p class="text-sm text-gray-600">Nouveau ${type} demandé par <span class="font-medium">${patient}</span></p>
                    <p class="text-xs text-gray-400">${time}</p>
                `);
            });
    } catch (err) {
        // fail silently to avoid breaking UI
        console.error('Notifications listener error:', err);
    }
});


