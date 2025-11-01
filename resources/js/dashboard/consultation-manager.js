/**
 * ConsultationManager - Module pour gérer les consultations
 */
class ConsultationManager {
    constructor() {
        this.csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content");
        this.cache = new Map();
        this.cacheTimeout = 30000; // 30 secondes
        this.currentFilter = 'aujourdhui';
        this.currentDate = null;
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.loadInitialData();
        this.startAutoRefresh();
        // Écouter les mises à jour faites via le modal global
        window.addEventListener('rendezvous:status-updated', () => {
            try {
                this.clearCache();
                this.loadInitialData();
            } catch (e) {
                console.warn('[ConsultationManager] refresh after modal failed', e);
            }
        });
    }

    setupEventListeners() {
        // Event delegation pour les boutons
        document.addEventListener("click", (e) => {
            if (e.target.closest('[data-action="change-status"]')) {
                e.preventDefault();
                const button = e.target.closest(
                    '[data-action="change-status"]'
                );
                const rendezVousId = button.dataset.rendezVousId;
                const currentStatus = button.dataset.currentStatus;
                this.changeStatus(rendezVousId, currentStatus);
            }

            if (e.target.closest('[data-action="open-consultation"]')) {
                e.preventDefault();
                const link = e.target.closest(
                    '[data-action="open-consultation"]'
                );
                const lienMeet = link.dataset.lienMeet;
                const consultationActive =
                    link.dataset.consultationActive === "true";
                const rendezVousId = link.dataset.rendezVousId;
                const statut = link.dataset.statut;
                this.openConsultation(
                    lienMeet,
                    consultationActive,
                    rendezVousId,
                    statut
                );
            }
        });
    }

    async loadInitialData() {
        try {
            await Promise.all([
                this.loadRendezVousDuJour(),
                this.loadConsultationsTerminees(),
            ]);
        } catch (error) {
            console.error("Erreur lors du chargement initial:", error);
            this.showError("Erreur lors du chargement des données");
        }
    }

    async loadRendezVousDuJour() {
        const cacheKey = "rendez-vous-du-jour";
        const cached = this.getCachedData(cacheKey);

        if (cached) {
            this.renderPatientsList(cached);
            return cached;
        }

        try {
            const response = await fetch('rendez-vous-du-jour', {
                method: 'GET',
                headers: { 'Accept': 'application/json' },
                credentials: 'include'
            });
            if (!response.ok) throw new Error("Erreur réseau");

            const data = await response.json();
            this.cacheData(cacheKey, data);
            this.renderPatientsList(data);
            return data;
        } catch (error) {
            console.error("Erreur lors du chargement des rendez-vous:", error);
            this.showError("Impossible de charger les rendez-vous du jour");
            throw error;
        }
    }

    // Charger les consultations selon un filtre (aujourdhui, demain, semaine, semaine_prochaine, mois, date, periode)
    async applyFilter(filtre = 'aujourdhui', date = null) {
        this.currentFilter = filtre;
        this.currentDate = date;

        try {
            const params = new URLSearchParams({ filtre });
            if (date) params.append('date', date);

            const response = await fetch(`/medecin/consultations-filtrees?${params.toString()}`, {
                method: 'GET',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin'
            });
            if (!response.ok) throw new Error(`Erreur HTTP ${response.status}`);

            const data = await response.json();

            // Utiliser le même rendu que pour le jour
            this.renderPatientsList(data);
            const terminees = this.filterConsultationsTerminees(data);
            this.renderConsultationsTerminees(terminees);
            return data;
        } catch (error) {
            console.error('[ConsultationManager] Erreur applyFilter:', error);
            this.showError("Impossible de charger les consultations filtrées");
            throw error;
        }
    }

    async loadConsultationsTerminees() {
        const cacheKey = "consultations-terminees";
        const cached = this.getCachedData(cacheKey);

        if (cached) {
            this.renderConsultationsTerminees(cached);
            return cached;
        }

        try {
            const data = await this.loadRendezVousDuJour();
            const consultationsTerminees =
                this.filterConsultationsTerminees(data);
            this.cacheData(cacheKey, consultationsTerminees);
            this.renderConsultationsTerminees(consultationsTerminees);
            return consultationsTerminees;
        } catch (error) {
            console.error(
                "Erreur lors du chargement des consultations terminées:",
                error
            );
            this.renderConsultationsTerminees([]);
            throw error;
        }
    }

    filterConsultationsTerminees(data) {
        return data.filter((patient) => {
            const statut = (patient.statut || '').toLowerCase();
            if (statut === 'cancelled') return false;

            const start = new Date(patient.date_debut);
            const fin = new Date(patient.date_fin || start.getTime() + 30 * 60 * 1000);
            const maintenant = new Date();
            const aujourdhui = new Date();
            aujourdhui.setHours(0, 0, 0, 0);

            // Afficher si marqué completed OU si l'heure de fin est dépassée, et uniquement pour aujourd'hui
            const isCompleted = statut === 'completed';
            const timePassed = maintenant > fin;
            const isToday = fin.toDateString() === aujourdhui.toDateString();
            return isToday && (isCompleted || timePassed);
        });
    }

    renderPatientsList(data) {
        const patientsList = document.getElementById("patientsList");
        const patientsCount = document.getElementById("patientsCount");

        const consultationsAVenir = data.filter((patient) => {
            const statut = (patient.statut || '').toLowerCase();
            // Exclure les terminées et annulées de "à venir"
            if (statut === 'completed' || statut === 'cancelled') return false;

            const start = new Date(patient.date_debut);
            const fin = new Date(patient.date_fin || start.getTime() + 30 * 60 * 1000);
            const maintenant = new Date();
            // Garder seulement les consultations dont la fin n'est pas encore passée
            return maintenant <= fin;
        });

        if (consultationsAVenir.length > 0) {
            patientsCount.textContent = `${consultationsAVenir.length} Consultations`;
            this.renderPatientsCards(patientsList, consultationsAVenir);
        } else {
            patientsCount.textContent = "0 Consultation";
            this.renderEmptyState(patientsList);
        }
    }

    renderPatientsCards(container, patients) {
        const fragment = document.createDocumentFragment();

        patients.forEach((patient) => {
            const card = this.createPatientCard(patient);
            fragment.appendChild(card);
        });

        // Optimisation DOM : remplacement en une seule opération
        container.innerHTML = "";
        container.appendChild(fragment);
    }

    createPatientCard(patient) {
        const template = document.getElementById("patient-card-template");
        const card = template.content.cloneNode(true);

        // Remplir les données
        card.querySelector(".patient-photo").src = patient.photo;
        card.querySelector(
            ".patient-name"
        ).textContent = `${patient.prenom} ${patient.nom}`;
        card.querySelector(
            ".patient-time"
        ).textContent = `${patient.heure} - ${patient.heure_fin}`;
        // Afficher la date lisible sous l'horaire
        try {
            const dateStart = new Date(patient.date_debut);
            const dateStr = `${String(dateStart.getDate()).padStart(2,'0')}/${String(dateStart.getMonth()+1).padStart(2,'0')}/${dateStart.getFullYear()}`;
            const timeEl = card.querySelector('.patient-time');
            if (timeEl && timeEl.parentElement) {
                const dateEl = document.createElement('div');
                dateEl.className = 'patient-date text-sm text-gray-600 flex items-center gap-2 mt-1';
                dateEl.innerHTML = `<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg><span class="font-semibold">${dateStr}</span>`;
                timeEl.parentElement.insertBefore(dateEl, timeEl.nextSibling);
            }
        } catch(e) {}
        card.querySelector(".patient-type").textContent =
            patient.type.charAt(0).toUpperCase() + patient.type.slice(1);

        // Statut
        const statusLabel = card.querySelector(".status-label");
        statusLabel.textContent =
            patient.statut.charAt(0).toUpperCase() + patient.statut.slice(1);
        statusLabel.className = `status-label px-4 py-1.5 rounded-full text-sm font-semibold ${this.getStatusClasses(
            patient.statut
        )}`;

        // Indicateur de consultation
        const indicator = card.querySelector(".consultation-indicator");
        indicator.innerHTML = this.getConsultationStatus(patient);

        // Boutons avec data attributes
        const statusButton = card.querySelector(
            '[data-action="change-status"]'
        );
        const buttonContainer = statusButton.parentElement;

        // Supprimer l'ancien bouton
        statusButton.remove();

        // Créer un menu déroulant des statuts disponibles
        const statusSelect = document.createElement('select');
        statusSelect.className = 'status-select-custom';
        statusSelect.title = 'Changer le statut du rendez-vous';
        statusSelect.dataset.rendezVousId = patient.id;
        statusSelect.dataset.currentStatus = patient.statut;

        // Définir les statuts disponibles (autoriser toutes transitions pour le médecin)
        const availableStatuses = [
            { value: 'pending', label: 'En attente', icon: '⏳' },
            { value: 'confirmed', label: 'Confirmé', icon: '✅' },
            { value: 'payed', label: 'Payé', icon: '💳' },
            { value: 'cancelled', label: 'Annulé', icon: '❌' },
            { value: 'completed', label: 'Terminé', icon: '🏁' },
        ];

        // Option par défaut avec style amélioré
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = `📋 ${this.getStatusLabel(patient.statut)}`;
        defaultOption.disabled = true;
        defaultOption.selected = true;
        statusSelect.appendChild(defaultOption);

        // Séparateur visuel
        const separatorOption = document.createElement('option');
        separatorOption.disabled = true;
        separatorOption.textContent = '──────────';
        statusSelect.appendChild(separatorOption);

        // Options des statuts disponibles avec styles améliorés
        availableStatuses.forEach(status => {
            const option = document.createElement('option');
            option.value = status.value;
            option.textContent = `${status.icon} ${status.label}`;
            option.className = status.value; // Classe CSS pour le style
            statusSelect.appendChild(option);
        });

        // Event listener pour le changement de statut
        statusSelect.addEventListener('change', (e) => {
            const newStatus = e.target.value;
            if (newStatus && newStatus !== patient.statut) {
                // Si le modal global est dispo, l'utiliser
                if (typeof window.showStatusChangeModal === 'function') {
                    const consultationData = {
                        prenom: patient.prenom,
                        nom: patient.nom,
                        photo: patient.photo,
                        heure: patient.heure,
                        heure_fin: patient.heure_fin,
                        statut: patient.statut,
                    };
                    window.showStatusChangeModal(patient.id, newStatus, e.target, consultationData);
                } else {
                    // Fallback sans modal
                    this.changeStatus(patient.id, newStatus);
                }
                // Remettre l'affichage du select sur le header
                setTimeout(() => { e.target.value = ''; }, 100);
            }
        });

        buttonContainer.appendChild(statusSelect);

        const consultationButton = card.querySelector(
            '[data-action="open-consultation"]'
        );
        consultationButton.dataset.lienMeet = patient.lien_meet;
        // Déterminer la fenêtre d'activation (5 min avant le début jusqu'à la fin)
        const start = new Date(patient.date_debut);
        const fin = new Date(
            patient.date_fin || start.getTime() + 30 * 60 * 1000
        );
        const maintenant = new Date();
        const windowOpen =
            maintenant >= new Date(start.getTime() - 5 * 60 * 1000) &&
            maintenant <= fin;

        // Statuts autorisés pour activer le bouton (confirmé/confirmé/payed)
        const statutLower = (patient.statut || '').toLowerCase();
        const isAllowedStatus =
            statutLower === 'confirmed' ||
            statutLower === 'confirmé' ||
            statutLower === 'payed';

        // Actif si statut autorisé et dans la fenêtre
        const isActive = isAllowedStatus && windowOpen;

        // Exposer l'état actif sur le dataset pour les handlers globaux
        consultationButton.dataset.consultationActive = isActive;
        consultationButton.dataset.rendezVousId = patient.id;
        consultationButton.dataset.statut = patient.statut;

        // Classes CSS pour le bouton de consultation
        consultationButton.className = `consultation-link p-2 rounded-full transition-all ${isActive
            ? "bg-green-500 hover:bg-green-600 text-white"
            : "bg-gray-300 text-gray-500 cursor-not-allowed"
            }`;
        consultationButton.title = this.getConsultationButtonTitle(patient);

        // Bouton "Voir le rendez-vous" → redirection vers le dossier médical du patient
        try {
            const viewButton = card.querySelector('[data-action="view-dossier"]');
            const patientId = patient.patient_id || patient.id_patient || (patient.patient && patient.patient.id);
            if (viewButton && patientId) {
                viewButton.addEventListener('click', (ev) => {
                    ev.preventDefault();
                    // Utiliser la même logique/URL que le bouton existant: /medecin/dossier?patient_id=ID
                    const baseUrl = (window.ROUTES && window.ROUTES.dossier) || '/medecin/dossier';
                    window.location.href = `${baseUrl}?patient_id=${patientId}`;
                });
            }
        } catch(e) { /* ignore binding error */ }

        return card;
    }

    renderEmptyState(container) {
        container.innerHTML = `
            <div class="text-center py-8 bg-gray-50 rounded-lg">
                <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-gray-500 text-lg">Aucune consultation à venir aujourd'hui</p>
                <p class="text-gray-400 text-sm mt-2">Les consultations à venir apparaîtront ici</p>
            </div>
        `;
    }

    renderConsultationsTerminees(consultations) {
        const container = document.getElementById("consultationsTermineesList");

        if (consultations.length > 0) {
            const fragment = document.createDocumentFragment();

            consultations.forEach((patient) => {
                const item = this.createConsultationTermineeItem(patient);
                fragment.appendChild(item);
            });

            container.innerHTML = "";
            container.appendChild(fragment);
        } else {
            container.innerHTML = `
                <div class="text-center py-4">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-gray-500 text-sm">Aucune consultation terminée aujourd'hui</p>
                </div>
            `;
        }
    }

    createConsultationTermineeItem(patient) {
        const div = document.createElement("div");
        div.className = "p-3 bg-gray-50 rounded-lg border border-gray-200";
        div.innerHTML = `
            <div class="flex items-center gap-3">
                <img src="${patient.photo}" alt="Photo" width="40" height="40" class="rounded-full object-cover border-2 border-gray-300" style="min-width:40px;min-height:40px;">
                <div class="flex-1 min-w-0">
                    <div class="font-medium text-gray-800 truncate">${patient.prenom} ${patient.nom}</div>
                    <div class="text-xs text-gray-500">${patient.heure} - ${patient.heure_fin}</div>
                    <div class="text-xs text-gray-600 mt-1">🏁 Consultation terminée</div>
                </div>
            </div>
        `;
        return div;
    }

    async changeStatus(rendezVousId, newStatus) {
        // Définir les messages de confirmation selon le statut cible
        const confirmMessages = {
            'confirmed': 'Confirmer le rendez-vous ?',
            'payed': 'Marquer le rendez-vous comme payé ?',
            'cancelled': 'Annuler le rendez-vous ?',
            'pending': 'Remettre le rendez-vous en attente ?',
            'completed': 'Terminer la consultation ?'
        };

        const successMessages = {
            'confirmed': 'Rendez-vous confirmé avec succès',
            'payed': 'Rendez-vous marqué comme payé',
            'cancelled': 'Rendez-vous annulé avec succès',
            'pending': 'Rendez-vous remis en attente avec succès',
            'completed': 'Consultation terminée avec succès'
        };

        const message = confirmMessages[newStatus];
        if (!message) {
            console.error('[DEBUG] Statut non géré:', newStatus);
            this.showNotification('Statut non géré', 'error');
            return;
        }

        if (confirm(message)) {
            try {
                console.log('[DEBUG] changeStatus → request', {
                    id: rendezVousId,
                    to: newStatus
                });

                const response = await fetch(
                    `/api/medecin/rendez-vous/${rendezVousId}/statut`,
                    {
                        method: "PUT",
                        headers: {
                            "Content-Type": "application/json",
                            Accept: "application/json",
                            "X-CSRF-TOKEN": document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute("content"),
                        },
                        credentials: "same-origin",
                        body: JSON.stringify({ statut: newStatus }),
                    }
                );

                console.log('[DEBUG] changeStatus → response status', response.status);
                const data = await response.json();
                console.log('[DEBUG] changeStatus → response body', data);

                if (data.success) {
                    this.showNotification(
                        successMessages[newStatus],
                        "success"
                    );
                    this.clearCache();
                    await this.loadInitialData();
                } else {
                    console.warn('[DEBUG] changeStatus → API error body', data);
                    this.showNotification(
                        data.message || "Erreur lors de la mise à jour du statut",
                        "error"
                    );
                }
            } catch (error) {
                console.error("Erreur lors du changement de statut:", error);
                this.showNotification(
                    "Erreur lors de la mise à jour du statut",
                    "error"
                );
            }
        }
    }

    // Méthode pour obtenir les statuts disponibles selon l'état actuel
    getAvailableStatuses(currentStatus, dateDebut, dateFin) {
        const maintenant = new Date();
        const start = new Date(dateDebut);
        const fin = new Date(dateFin || start.getTime() + 30 * 60 * 1000);
        const windowOpen = maintenant >= new Date(start.getTime() - 5 * 60 * 1000) && maintenant <= fin;

        const allStatuses = {
            'pending': { value: 'pending', label: 'En attente', icon: '⏳' },
            'confirmed': { value: 'confirmed', label: 'Confirmé', icon: '✅' },
            'payed': { value: 'payed', label: 'Payé', icon: '💳' },
            'cancelled': { value: 'cancelled', label: 'Annulé', icon: '❌' },
            'completed': { value: 'completed', label: 'Terminé', icon: '🏁' }
        };

        // Définir les transitions autorisées selon le statut actuel
        const transitions = {
            'pending': ['confirmed', 'cancelled'],
            'confirmed': ['cancelled'],
            'payed': [], // le médecin ne peut plus modifier
            'cancelled': [],
            'completed': []
        };

        const availableStatuses = transitions[currentStatus] || [];
        return availableStatuses.map(status => allStatuses[status]);
    }

    // Méthode pour obtenir le label d'un statut
    getStatusLabel(status) {
        const labels = {
            'pending': 'En attente',
            'confirmed': 'Confirmé',
            'cancelled': 'Annulé',
            'completed': 'Terminé'
        };
        return labels[status] || status;
    }

    async openConsultation(lienMeet, consultationActive, rendezVousId, statut) {
        if (statut !== "payed") {
            alert(
                "La consultation ne peut pas commencer car le rendez-vous n'est pas payé."
            );
            return;
        }

        if (!consultationActive) {
            alert(
                "La consultation n'est pas encore active. Elle peut commencer 5 minutes avant l'heure prévue si le rendez-vous est confirmé."
            );
            return;
        }

        if (!lienMeet || lienMeet === "") {
            await this.createConsultationLink(rendezVousId);
            return;
        }

        window.open(lienMeet, "_blank");
        this.showNotification(
            "Ouverture de la consultation Jitsi Meet",
            "info"
        );
    }

    async createConsultationLink(rendezVousId) {
        try {
            const response = await fetch(
                `/api/medecin/rendez-vous/${rendezVousId}/creer-lien-consultation`,
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                    credentials: "same-origin",
                }
            );

            const data = await response.json();

            if (data.success) {
                window.open(data.lien_consultation, "_blank");
                this.showNotification(
                    "Lien de consultation créé et ouvert",
                    "success"
                );
            } else {
                this.showNotification("Erreur: " + data.message, "error");
            }
        } catch (error) {
            console.error("Erreur lors de la création du lien:", error);
            this.showNotification(
                "Erreur lors de la création du lien de consultation",
                "error"
            );
        }
    }

    startAutoRefresh() {
        setInterval(async () => {
            try {
                await Promise.all([
                    this.loadRendezVousDuJour(),
                    this.loadConsultationsTerminees(),
                ]);
            } catch (error) {
                console.error(
                    "Erreur lors du rafraîchissement automatique:",
                    error
                );
            }
        }, 30000);
    }

    // Méthodes utilitaires
    getStatusClasses(statut) {
        const classes = {
            confirmed: "bg-green-100 text-green-700",
            pending: "bg-yellow-100 text-yellow-700",
            payed: "bg-blue-100 text-blue-700",
            cancelled: "bg-red-100 text-red-700",
            completed: "bg-gray-100 text-gray-700",
        };
        return classes[statut] || "bg-gray-100 text-gray-700";
    }

    getConsultationStatus(patient) {
        // Statuts finaux et explicites d'abord
        if (patient.statut === "completed") {
            return '<span class="text-gray-600 font-medium">🏁 Consultation terminée</span>';
        }
        if (patient.statut === "cancelled") {
            return '<span class="text-red-500 font-medium">❌ Rendez-vous annulé</span>';
        }
        if (patient.statut === "payed") {
            return '<span class="text-blue-600 font-medium">💳 Payé - en attente de complétion</span>';
        }
        const isConfirmed = patient.statut === "confirmed" || patient.statut === "confirmé";
        if (!isConfirmed) {
            return '<span class="text-red-500 font-medium">❌ Rendez-vous non confirmé</span>';
        }
        const start = new Date(patient.date_debut);
        const fin = new Date(
            patient.date_fin || start.getTime() + 30 * 60 * 1000
        );
        const maintenant = new Date();
        if (maintenant > fin) {
            return '<span class="text-gray-600 font-medium">🏁 Consultation terminée</span>';
        }
        if (maintenant >= start && maintenant <= fin) {
            return '<span class="text-green-600 font-medium">🟢 Consultation en cours</span>';
        }
        if (maintenant >= new Date(start.getTime() - 5 * 60 * 1000)) {
            return '<span class="text-blue-600 font-medium">🔵 Consultation peut commencer (5 min avant)</span>';
        }
        return '<span class="text-gray-500">⏰ Consultation à venir</span>';
    }

    getConsultationButtonTitle(patient) {
        if (patient.statut === "completed") return "Consultation terminée";
        if (patient.statut === "cancelled") return "Rendez-vous annulé";
        const isConfirmed = patient.statut === "confirmed" || patient.statut === "confirmé";
        if (!isConfirmed) return "Rendez-vous non confirmé";
        if (patient.consultation_terminee) return "Consultation terminée";
        if (patient.consultation_active) return "Rejoindre la consultation";
        return "Consultation active 5 min avant le début";
    }

    // Cache management
    cacheData(key, data) {
        this.cache.set(key, {
            data,
            timestamp: Date.now(),
        });
    }

    getCachedData(key) {
        const cached = this.cache.get(key);
        if (cached && Date.now() - cached.timestamp < this.cacheTimeout) {
            return cached.data;
        }
        return null;
    }

    clearCache() {
        this.cache.clear();
    }

    // Notifications
    showNotification(message, type = "info") {
        const notification = document.createElement("div");
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${type === "success"
            ? "bg-green-500 text-white"
            : type === "error"
                ? "bg-red-500 text-white"
                : "bg-blue-500 text-white"
            }`;

        notification.innerHTML = `
            <div class="flex items-center gap-2">
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-white hover:text-gray-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        `;

        document.body.appendChild(notification);

        setTimeout(
            () => notification.classList.remove("translate-x-full"),
            100
        );
        setTimeout(() => {
            notification.classList.add("translate-x-full");
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    showError(message) {
        const errorDiv = document.createElement("div");
        errorDiv.className =
            "bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4";
        errorDiv.innerHTML = `<span class="block lg:inline">${message}</span>`;

        const container = document.querySelector(".p-4.lg\\:p-8");
        container.insertBefore(errorDiv, container.firstChild);
    }
}

// Export pour utilisation globale
window.ConsultationManager = ConsultationManager;
