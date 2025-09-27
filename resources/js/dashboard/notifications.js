document.addEventListener('DOMContentLoaded', () => {
    // Vérifier que les éléments nécessaires existent
    const list = document.getElementById('notifications-list');
    const empty = document.getElementById('notifications-empty');
    const badge = document.getElementById('notifications-badge');
    const dropdown = document.getElementById('notifications-dropdown');
    const button = document.getElementById('notifications-button');

    // Si les éléments de notification n'existent pas, arrêter l'exécution
    if (!list || !dropdown || !button) {
        console.log('Éléments de notification non trouvés, arrêt du script');
        return;
    }

    // Charger les notifications persistantes au chargement de la page
    loadPersistentNotifications();

    const renderNotification = ({
        title = '',
        subtitle = '',
        time = '',
        statusBadgeHtml = '',
        detailsHtml = '',
        iconBg = 'bg-blue-100 text-blue-700'
    }) => `
        <div class="flex items-start gap-3">
            <div class="shrink-0 w-8 h-8 rounded-full ${iconBg} flex items-center justify-center">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H19v-2z" />
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-start justify-between gap-2">
                    <p class="text-sm text-gray-900">${title}</p>
                    ${statusBadgeHtml}
                </div>
                ${subtitle ? `<p class="text-xs text-gray-600 mt-0.5">${subtitle}</p>` : ''}
                ${detailsHtml ? `<div class="mt-2">${detailsHtml}</div>` : ''}
                ${time ? `<p class="text-[11px] text-gray-400 mt-1">${time}</p>` : ''}
            </div>
        </div>
    `;

    const addNotification = (html, isPersistent = false) => {
        if (!list) return;
        if (empty) empty.remove();

        const item = document.createElement('div');
        item.className = 'px-4 py-3 hover:bg-gray-50 cursor-pointer';
        item.innerHTML = html;
        list.prepend(item);

        if (badge) badge.classList.remove('hidden');

        // Si c'est une notification persistante, ne pas l'ajouter en temps réel
        if (!isPersistent) {
            // Marquer comme lue après 5 secondes pour les notifications temps réel
            setTimeout(() => {
                item.classList.add('opacity-60');
            }, 5000);
        }
    };

    // Fonction pour charger les notifications persistantes
    async function loadPersistentNotifications() {
        try {
            const response = await fetch('/notifications/recent?limit=10');
            if (!response.ok) throw new Error('Erreur lors du chargement des notifications');

            const data = await response.json();
            const notifications = data.notifications || [];

            // Mettre à jour le badge avec le nombre de notifications non lues
            if (badge) {
                const unreadCount = data.unread_count || 0;
                if (unreadCount > 0) {
                    badge.textContent = unreadCount;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            }

            // Afficher les notifications persistantes
            if (notifications.length > 0) {
                if (empty) empty.remove();

                notifications.forEach(notification => {
                    const html = renderPersistentNotification(notification);
                    addNotification(html, true);
                });
            }
        } catch (error) {
            console.error('Erreur lors du chargement des notifications persistantes:', error);
        }
    }

    // Fonction pour rendre une notification persistante
    function renderPersistentNotification(notification) {
        const data = notification.data;
        const isRead = notification.read_at !== null;
        const timeAgo = getTimeAgo(notification.created_at);

        const iconClass = getNotificationIcon(data.type, data.icon);
        const colorClass = getNotificationColor(data.color);

        return `
            <div class="flex items-start gap-3 ${isRead ? 'opacity-60' : ''}" data-notification-id="${notification.id}">
                <div class="shrink-0 w-8 h-8 rounded-full ${colorClass} flex items-center justify-center">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                        <path d="${iconClass}" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-start justify-between gap-2">
                        <p class="text-sm text-gray-900">${data.title}</p>
                        ${!isRead ? '<div class="w-2 h-2 bg-blue-500 rounded-full"></div>' : ''}
                    </div>
                    <p class="text-xs text-gray-600 mt-0.5">${data.message}</p>
                    <p class="text-[11px] text-gray-400 mt-1">${timeAgo}</p>
                </div>
            </div>
        `;
    }

    // Fonction pour obtenir l'icône selon le type de notification
    function getNotificationIcon(type, icon) {
        const icons = {
            'calendar-plus': 'M19 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H19v-2z',
            'calendar-edit': 'M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7',
            'check-circle': 'M22 11.08V12a10 10 0 1 1-5.93-9.14',
            'x-circle': 'M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12s4.48 10 10 10 10-4.48 10-10z',
            'clock': 'M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm4.2 14.2L11 13V7h1.5v5.2l4.5 2.7-.8 1.3z',
            'credit-card': 'M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z',
            'calendar': 'M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11z'
        };

        return icons[icon] || icons['calendar'];
    }

    // Fonction pour obtenir la classe de couleur
    function getNotificationColor(color) {
        const colors = {
            'blue': 'bg-blue-100 text-blue-700',
            'green': 'bg-green-100 text-green-700',
            'yellow': 'bg-yellow-100 text-yellow-700',
            'red': 'bg-red-100 text-red-700',
            'gray': 'bg-gray-100 text-gray-700'
        };

        return colors[color] || colors['blue'];
    }

    // Fonction pour calculer le temps écoulé
    function getTimeAgo(dateString) {
        const now = new Date();
        const date = new Date(dateString);
        const diffInSeconds = Math.floor((now - date) / 1000);

        if (diffInSeconds < 60) return 'À l\'instant';
        if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} min`;
        if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)} h`;
        if (diffInSeconds < 2592000) return `${Math.floor(diffInSeconds / 86400)} j`;
        return date.toLocaleDateString();
    }

    // Fonction pour mettre à jour le compteur de notifications non lues
    async function updateUnreadCount() {
        try {
            const response = await fetch('/notifications/unread-count');
            if (response.ok) {
                const data = await response.json();
                const unreadCount = data.unread_count || 0;

                if (badge) {
                    if (unreadCount > 0) {
                        badge.textContent = unreadCount;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }
            }
        } catch (error) {
            console.error('Erreur lors de la mise à jour du compteur:', error);
        }
    }

    // Toggle dropdown on click, close on outside click or Escape
    if (button && dropdown) {
        button.addEventListener('click', (e) => {
            e.stopPropagation();
            e.preventDefault();

            // Toggle dropdown visibility
            const isHidden = dropdown.classList.contains('hidden');
            if (isHidden) {
                dropdown.classList.remove('hidden');
                // clear badge when opening
                if (badge) {
                    badge.classList.add('hidden');
                }
            } else {
                dropdown.classList.add('hidden');
            }
        });

        // Mark all as read handler
        const markRead = document.getElementById('notifications-markread');
        if (markRead && list) {
            markRead.addEventListener('click', async (e) => {
                e.preventDefault();
                try {
                    const response = await fetch('/notifications/mark-all-as-read', {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    if (response.ok) {
                        const items = Array.from(list.children);
                        items.forEach((el) => {
                            el.classList.add('opacity-60');
                            // Supprimer l'indicateur de non-lu
                            const unreadIndicator = el.querySelector('.w-2.h-2.bg-blue-500');
                            if (unreadIndicator) unreadIndicator.remove();
                        });
                        if (badge) badge.classList.add('hidden');
                    }
                } catch (error) {
                    console.error('Erreur lors du marquage des notifications:', error);
                }
            });
        }

        // Gestion des clics sur les notifications individuelles
        if (list) {
            list.addEventListener('click', async (e) => {
                const notificationItem = e.target.closest('[data-notification-id]');
                if (notificationItem) {
                    const notificationId = notificationItem.getAttribute('data-notification-id');

                    // Marquer comme lue
                    try {
                        const response = await fetch(`/notifications/${notificationId}/mark-as-read`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });

                        if (response.ok) {
                            notificationItem.classList.add('opacity-60');
                            // Supprimer l'indicateur de non-lu
                            const unreadIndicator = notificationItem.querySelector('.w-2.h-2.bg-blue-500');
                            if (unreadIndicator) unreadIndicator.remove();

                            // Mettre à jour le badge
                            updateUnreadCount();
                        }
                    } catch (error) {
                        console.error('Erreur lors du marquage de la notification:', error);
                    }
                }
            });
        }

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
    if (window.Echo) {
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

                // Recharger les notifications persistantes pour avoir la dernière
                loadPersistentNotifications();
            });
        } catch (err) {
            // fail silently to avoid breaking UI
            console.error('Notifications listener error:', err);
        }
    } else {
        console.log('Laravel Echo non disponible, notifications temps réel désactivées');
    }

    // Listen for doctor-created appointments to notify the patient (RendezVousModifie: action "created")
    if (window.Echo) {
        try {
            window.Echo.channel('rendez-vous')
                .listen('.RendezVousModifie', (e) => {
                const action = e?.action;
                const payload = e?.rendezVous || {};
                const extended = payload.extendedProps || {};

                // Only act on creations
                if (action !== 'created') return;

                // Match current user to patient_id
                const currentUserId = document.querySelector('meta[name="user-id"]')?.getAttribute('content');
                const patientId = extended.patient_id;
                if (!currentUserId || !patientId || String(currentUserId) !== String(patientId)) return;

                // Debug log for patient console
                // Shows full event and rendez-vous payload when patient receives notification
                // eslint-disable-next-line no-console
                console.log('Patient notification: rendez-vous created', { event: e, rendezVous: payload });

                const time = payload.start ? new Date(payload.start).toLocaleString([], { hour: '2-digit', minute: '2-digit' }) : '';
                const type = extended.type || 'rendez-vous';

                addNotification(renderNotification({
                    title: `Nouveau ${type} planifié par votre médecin`,
                    time,
                    iconBg: 'bg-green-100 text-green-700'
                }));

                // Recharger les notifications persistantes pour avoir la dernière
                loadPersistentNotifications();
            })
            .listen('.RendezVousModifie', (e) => {
                const action = e?.action;
                const payload = e?.rendezVous || {};
                const extended = payload.extendedProps || {};

                if (action !== 'updated') return;

                const currentUserId = document.querySelector('meta[name="user-id"]')?.getAttribute('content');
                const patientId = extended.patient_id;
                if (!currentUserId || !patientId || String(currentUserId) !== String(patientId)) return;

                // eslint-disable-next-line no-console
                console.log('Patient notification: rendez-vous updated', { event: e, rendezVous: payload, changes: e?.changes || {} });

                const start = payload.start ? new Date(payload.start) : null;
                const end = payload.end ? new Date(payload.end) : null;
                const type = extended.type || 'rendez-vous';

                // Build change details for the notification (show only real changes)
                const changes = e?.changes || {};
                const changeLines = [];
                let statusBadgeHtml = '';

                const isSame = (a, b) => {
                    // Normalize dates and strings
                    if (a instanceof Date || b instanceof Date) {
                        const da = a ? new Date(a) : null;
                        const db = b ? new Date(b) : null;
                        return da && db ? da.getTime() === db.getTime() : a === b;
                    }
                    const na = (a ?? '').toString().trim();
                    const nb = (b ?? '').toString().trim();
                    return na === nb;
                };

                const formatDate = (v) => v ? new Date(v).toLocaleString([], { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit' }) : '';

                if (changes.date_debut && !isSame(changes.date_debut.old, changes.date_debut.new)) {
                    changeLines.push(`<li>Heure de début modifiée: <span class="font-medium">${formatDate(changes.date_debut.old)}</span> ➜ <span class="font-medium">${formatDate(changes.date_debut.new)}</span></li>`);
                }
                if (changes.date_fin && !isSame(changes.date_fin.old, changes.date_fin.new)) {
                    changeLines.push(`<li>Heure de fin modifiée: <span class="font-medium">${formatDate(changes.date_fin.old)}</span> ➜ <span class="font-medium">${formatDate(changes.date_fin.new)}</span></li>`);
                }
                if (changes.type && !isSame(changes.type.old, changes.type.new)) {
                    changeLines.push(`<li>Type: <span class="font-medium">${changes.type.old ?? ''}</span> ➜ <span class="font-medium">${changes.type.new ?? ''}</span></li>`);
                }
                if (changes.statut && !isSame(changes.statut.old, changes.statut.new)) {
                    const oldS = (changes.statut.old || '').toString();
                    const newS = (changes.statut.new || '').toString();
                    const toLabel = (s) => ({
                        pending: 'En attente',
                        confirmed: 'Confirmé',
                        cancelled: 'Annulé',
                        rejected: 'Rejeté',
                        completed: 'Terminé',
                        'en_attente': 'En attente',
                        'confirmé': 'Confirmé',
                        'annulé': 'Annulé',
                    })[s] || s;
                    const badgeClass = (s) => ({
                        pending: 'bg-yellow-100 text-yellow-700 border border-yellow-200',
                        confirmed: 'bg-green-100 text-green-700 border border-green-200',
                        cancelled: 'bg-gray-100 text-gray-700 border border-gray-200',
                        rejected: 'bg-red-100 text-red-700 border border-red-200',
                        completed: 'bg-blue-100 text-blue-700 border border-blue-200',
                        'en_attente': 'bg-yellow-100 text-yellow-700 border border-yellow-200',
                        'confirmé': 'bg-green-100 text-green-700 border border-green-200',
                        'annulé': 'bg-gray-100 text-gray-700 border border-gray-200',
                    })[newS] || 'bg-gray-100 text-gray-700 border border-gray-200';

                    statusBadgeHtml = `<span class='inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium ${badgeClass(newS)}'>${toLabel(newS)}</span>`;
                    changeLines.push(`<li>Statut: <span class="font-medium">${toLabel(oldS)}</span> ➜ <span class="font-medium">${toLabel(newS)}</span></li>`);
                }
                if (changes.description && !isSame(changes.description.old, changes.description.new)) {
                    const oldText = (changes.description.old ?? '').toString();
                    const newText = (changes.description.new ?? '').toString();
                    const summarize = (txt) => txt.length > 80 ? `${txt.slice(0, 77)}...` : txt;
                    changeLines.push(`<li>Description mise à jour</li><li><span class="text-[11px] text-gray-500">Ancien:</span> <span class="font-medium">${summarize(oldText)}</span></li><li><span class="text-[11px] text-gray-500">Nouveau:</span> <span class="font-medium">${summarize(newText)}</span></li>`);
                }
                if (changes.lien_en_ligne && !isSame(changes.lien_en_ligne.old, changes.lien_en_ligne.new)) {
                    const oldLink = (changes.lien_en_ligne.old ?? '').toString().trim();
                    const newLink = (changes.lien_en_ligne.new ?? '').toString().trim();
                    const isUrl = (s) => /^https?:\/\//i.test(s);

                    // Conservative rule: only show if the new link is a valid URL (added or modified)
                    // Avoid showing "supprimé" which may be misleading due to partial payloads
                    if (isUrl(newLink)) {
                        if (!isUrl(oldLink)) {
                            changeLines.push('<li>Lien de consultation <span class="font-medium">ajouté</span></li>');
                        } else if (oldLink !== newLink) {
                            changeLines.push('<li>Lien de consultation <span class="font-medium">modifié</span></li>');
                        }
                    }
                }

                const stripLi = (s) => {
                    let t = s || '';
                    if (t.startsWith('<li>')) t = t.slice(4);
                    if (t.endsWith('</li>')) t = t.slice(0, -5);
                    return t;
                };

                const detailsHtml = changeLines.length
                    ? `<ul class='text-xs text-gray-700 space-y-1'>${changeLines.map(li => `<li class=\"flex items-start gap-2\"><span class=\"w-1.5 h-1.5 mt-1 rounded-full bg-gray-400\"></span><span>${stripLi(li)}</span></li>`).join('')}</ul>`
                    : '';
                // Only show time if a date changed
                const showTime = !!(changes.date_debut || changes.date_fin);
                const time = showTime
                    ? (start && end
                        ? `${start.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })} - ${end.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}`
                        : (start ? start.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : ''))
                    : '';
                // Only show subtitle (type) if type changed
                const subtitle = changes.type ? (type.charAt(0).toUpperCase() + type.slice(1)) : '';

                addNotification(renderNotification({
                    title: '<span class="font-semibold">Consultation</span> modifiée par votre médecin',
                    subtitle,
                    time,
                    statusBadgeHtml,
                    detailsHtml,
                    iconBg: 'bg-yellow-100 text-yellow-700'
                }));

                // Recharger les notifications persistantes pour avoir la dernière
                loadPersistentNotifications();
            });
        } catch (err) {
            console.error('Patient notifications listener error:', err);
        }
    } else {
        console.log('Laravel Echo non disponible pour les notifications patient');
    }
});


