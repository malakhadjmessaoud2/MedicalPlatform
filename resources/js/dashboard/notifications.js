document.addEventListener('DOMContentLoaded', () => {
    if (!window.Echo) return;

    const list = document.getElementById('notifications-list');
    const empty = document.getElementById('notifications-empty');
    const badge = document.getElementById('notifications-badge');
    const dropdown = document.getElementById('notifications-dropdown');
    const button = document.getElementById('notifications-button');

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

    const addNotification = (html) => {
        if (!list) return;
        if (empty) empty.remove();

        const item = document.createElement('div');
        item.className = 'px-4 py-3 hover:bg-gray-50 cursor-pointer';
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

        // Mark all as read handler
        const markRead = document.getElementById('notifications-markread');
        if (markRead && list) {
            markRead.addEventListener('click', (e) => {
                e.preventDefault();
                const items = Array.from(list.children);
                items.forEach((el) => {
                    el.classList.add('opacity-60');
                });
                if (badge) badge.classList.add('hidden');
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

    // Listen for doctor-created appointments to notify the patient (RendezVousModifie: action "created")
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
            });
    } catch (err) {
        console.error('Patient notifications listener error:', err);
    }
});


