@extends('dashMedecin.layout')

@section('content')
<div class="p-4 sm:p-8 bg-[#e4e4e4] min-h-screen">
    <!-- Debug Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {


                    // Charger dynamiquement la liste des rendez-vous du jour
        fetch('/dashboard/medecin/rendez-vous-du-jour')
            .then(response => response.json())
            .then(data => {
                const patientsList = document.getElementById('patientsList');
                const patientsCount = document.getElementById('patientsCount');

                // Filtrer pour n'afficher que les consultations à venir (non terminées)
                const consultationsAVenir = data.filter(patient => {
                    if (patient.statut !== 'confirmé') return true; // Garder les non confirmés

                    // Pour les confirmés, vérifier qu'ils ne sont pas terminés
                    const heureFin = new Date(patient.date_fin || new Date(patient.date_debut).getTime() + 30 * 60 * 1000);
                    const maintenant = new Date();
                    return maintenant <= heureFin; // Garder si pas encore terminé
                });

                if (consultationsAVenir.length > 0) {
                    patientsCount.textContent = consultationsAVenir.length + ' Consultations';
                    patientsList.innerHTML = consultationsAVenir.map(patient => `
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-4 bg-gray-50 rounded-lg shadow-sm hover:shadow-md transition-all mb-2">
                            <div class="flex items-center gap-4">
                                <img src="${patient.photo}" alt="Photo" width="56" height="56" class="rounded-full object-cover border-2 border-[#b9ff66] shadow" style="min-width:56px;min-height:56px;">
                                <div>
                                    <div class="text-lg font-bold text-gray-800">${patient.prenom} ${patient.nom}</div>
                                    <div class="text-sm text-gray-500 flex gap-2 items-center">
                                        <span class="inline-flex items-center gap-1"><svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3" stroke-linecap="round" stroke-linejoin="round"/></svg>${patient.heure} - ${patient.heure_fin}</span>
                                        <span class="inline-flex items-center gap-1"><svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>${patient.type.charAt(0).toUpperCase() + patient.type.slice(1)}</span>
                                    </div>
                                    <!-- Indicateur de statut de consultation -->
                                    <!-- États possibles: non confirmé, terminée, en cours, peut commencer, à venir -->
                                    <div class="consultation-indicator text-xs mt-1">
                                        ${patient.statut !== 'confirmé' ?
                                            '<span class="text-red-500 font-medium">❌ Rendez-vous non confirmé</span>' :
                                            patient.consultation_terminee ?
                                            '<span class="text-gray-600 font-medium">🏁 Consultation terminée</span>' :
                                            patient.consultation_en_cours ?
                                            '<span class="text-green-600 font-medium">🟢 Consultation en cours</span>' :
                                            patient.consultation_active ?
                                            '<span class="text-blue-600 font-medium">🔵 Consultation peut commencer (5 min avant)</span>' :
                                            '<span class="text-gray-500">⏰ Consultation à venir</span>'
                                        }
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col sm:flex-row items-center gap-3">
                                                                    <span class="statut-label px-4 py-1.5 rounded-full text-sm font-semibold
                                        ${patient.statut === 'confirmé' ? 'bg-green-100 text-green-700' : ''}
                                        ${patient.statut === 'en_attente' ? 'bg-yellow-100 text-yellow-700' : ''}
                                        ${patient.statut === 'annulé' ? 'bg-red-100 text-red-700' : ''}">
                                        ${patient.statut.charAt(0).toUpperCase() + patient.statut.slice(1)}
                                    </span>
                                <div class="flex items-center gap-2">
                                    <!-- Bouton de changement de statut -->
                                    <div class="relative">
                                        <button onclick="changerStatutRendezVous(${patient.id}, '${patient.statut}')"
                                                class="p-2 bg-blue-500 hover:bg-blue-600 text-white rounded-full transition-all"
                                                title="Changer le statut du rendez-vous">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Lien de consultation en ligne (actif seulement si confirmé et dans la fenêtre de temps) -->
                                    <a href="#"
                                       onclick="ouvrirConsultationEnLigne('${patient.lien_meet}', ${patient.consultation_active}, ${patient.id}, '${patient.statut}')"
                                       class="consultation-link p-2 rounded-full transition-all ${patient.statut === 'confirmé' && patient.consultation_active && !patient.consultation_terminee ? 'bg-green-500 hover:bg-green-600 text-white' : 'bg-gray-300 text-gray-500 cursor-not-allowed'}"
                                       title="${patient.statut !== 'confirmé' ? 'Rendez-vous non confirmé' : patient.consultation_terminee ? 'Consultation terminée' : patient.consultation_active ? 'Rejoindre la consultation' : 'Consultation active 5 min avant le début'}">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                    </a>

                                    <!-- Lien vers les rendez-vous -->
                                    <a href="/dashboard/medecin/rendez-vous" class="p-2 bg-[#b9ff66] hover:bg-[#a8eb5f] rounded-full transition-all" title="Voir le rendez-vous">
                                        <svg class="w-5 h-5 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    `).join('');
                } else {
                    patientsCount.textContent = '0 Consultation';
                    patientsList.innerHTML = `
                        <div class="text-center py-8 bg-gray-50 rounded-lg">
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-gray-500 text-lg">Aucune consultation à venir aujourd'hui</p>
                            <p class="text-gray-400 text-sm mt-2">Les consultations à venir apparaîtront ici</p>
                        </div>
                    `;
                }
            });
        });

        // Fonction pour ouvrir la consultation en ligne
        function ouvrirConsultationEnLigne(lienMeet, consultationActive, rendezVousId, statut) {
            // Vérifier d'abord le statut du rendez-vous
            if (statut !== 'confirmé') {
                alert('La consultation ne peut pas commencer car le rendez-vous n\'est pas confirmé.');
                return;
            }

            // Vérifier si la consultation est terminée
            if (consultationActive === false && statut === 'confirmé') {
                // Récupérer les données du patient pour vérifier le statut exact
                fetch('/dashboard/medecin/rendez-vous-du-jour')
                    .then(response => response.json())
                    .then(data => {
                        const patient = data.find(p => p.id === rendezVousId);
                        if (patient && patient.consultation_terminee) {
                            alert('Cette consultation est terminée et ne peut plus être rejointe.');
                            return;
                        }
                        // Si pas terminée, afficher le message normal
                        alert('La consultation n\'est pas encore active. Elle peut commencer 5 minutes avant l\'heure prévue si le rendez-vous est confirmé.');
                    });
                return;
            }

            if (!consultationActive) {
                alert('La consultation n\'est pas encore active. Elle peut commencer 5 minutes avant l\'heure prévue si le rendez-vous est confirmé.');
                return;
            }

            // Si le lien n'existe pas encore, le créer via l'API
            if (!lienMeet || lienMeet === '') {
                creerLienConsultation(rendezVousId);
                return;
            }

            // Ouvrir Google Meet dans un nouvel onglet
            window.open(lienMeet, '_blank');

            // Optionnel : Afficher une notification
            if ('Notification' in window && Notification.permission === 'granted') {
                new Notification('Consultation en ligne', {
                    body: 'Ouverture de la consultation Google Meet',
                    icon: '/favicon.ico'
                });
            }
        }

        // Fonction pour créer le lien de consultation
        function creerLienConsultation(rendezVousId) {
            fetch(`/dashboard/medecin/api/rendez-vous/${rendezVousId}/creer-lien-consultation`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Ouvrir le lien de consultation
                    window.open(data.lien_consultation, '_blank');

                    // Optionnel : Afficher une notification
                    if ('Notification' in window && Notification.permission === 'granted') {
                        new Notification('Consultation en ligne', {
                            body: 'Lien de consultation créé et ouvert',
                            icon: '/favicon.ico'
                        });
                    }
                } else {
                    alert('Erreur: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erreur lors de la création du lien:', error);
                alert('Erreur lors de la création du lien de consultation');
            });
        }

        // Fonction pour rafraîchir automatiquement le statut des consultations
        function rafraichirStatutConsultations() {
            // Rafraîchir la liste des patients et les consultations terminées toutes les 30 secondes
            setInterval(() => {
                fetch('/dashboard/medecin/rendez-vous-du-jour')
                    .then(response => response.json())
                    .then(data => {
                        // Mettre à jour seulement les indicateurs de statut sans recharger toute la liste
                        const consultationLinks = document.querySelectorAll('.consultation-link');
                        const statutLabels = document.querySelectorAll('.statut-label');
                        const consultationIndicators = document.querySelectorAll('.consultation-indicator');

                        consultationLinks.forEach((link, index) => {
                            if (data[index]) {
                                const patient = data[index];
                                // Mettre à jour les classes CSS selon le statut et l'état de consultation
                                if (patient.statut === 'confirmé' && patient.consultation_active && !patient.consultation_terminee) {
                                    link.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
                                    link.classList.add('bg-green-500', 'hover:bg-green-600', 'text-white');
                                    link.title = 'Rejoindre la consultation';
                                } else {
                                    link.classList.remove('bg-green-500', 'hover:bg-green-600', 'text-white');
                                    link.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
                                    if (patient.statut !== 'confirmé') {
                                        link.title = 'Rendez-vous non confirmé';
                                    } else if (patient.consultation_terminee) {
                                        link.title = 'Consultation terminée';
                                    } else {
                                        link.title = 'Consultation active 5 min avant le début';
                                    }
                                }
                            }
                        });

                        // Mettre à jour les labels de statut
                        statutLabels.forEach((label, index) => {
                            if (data[index]) {
                                const patient = data[index];
                                label.textContent = patient.statut.charAt(0).toUpperCase() + patient.statut.slice(1);
                                label.className = `statut-label px-4 py-1.5 rounded-full text-sm font-semibold ${
                                    patient.statut === 'confirmé' ? 'bg-green-100 text-green-700' :
                                    patient.statut === 'en_attente' ? 'bg-yellow-100 text-yellow-700' :
                                    'bg-red-100 text-red-700'
                                }`;
                            }
                        });

                        // Mettre à jour les indicateurs de consultation
                        consultationIndicators.forEach((indicator, index) => {
                            if (data[index]) {
                                const patient = data[index];
                                if (patient.statut !== 'confirmé') {
                                    indicator.innerHTML = '<span class="text-red-500 font-medium">❌ Rendez-vous non confirmé</span>';
                                } else if (patient.consultation_terminee) {
                                    indicator.innerHTML = '<span class="text-gray-600 font-medium">🏁 Consultation terminée</span>';
                                } else if (patient.consultation_en_cours) {
                                    indicator.innerHTML = '<span class="text-green-600 font-medium">🟢 Consultation en cours</span>';
                                } else if (patient.consultation_active) {
                                    indicator.innerHTML = '<span class="text-blue-600 font-medium">🔵 Consultation peut commencer (5 min avant)</span>';
                                } else {
                                    indicator.innerHTML = '<span class="text-gray-500">⏰ Consultation à venir</span>';
                                }
                            }
                        });

                        // Mettre à jour les consultations terminées
                        chargerConsultationsTerminees();
                    });
            }, 30000); // Rafraîchir toutes les 30 secondes pour une meilleure réactivité
        }

        // Démarrer le rafraîchissement automatique
        rafraichirStatutConsultations();

        // Charger les consultations terminées au démarrage
        chargerConsultationsTerminees();

        // Fonction pour charger les consultations terminées de ce jour
        function chargerConsultationsTerminees() {
            fetch('/dashboard/medecin/rendez-vous-du-jour')
                .then(response => response.json())
                .then(data => {
                    const consultationsTermineesList = document.getElementById('consultationsTermineesList');

                    // Filtrer les consultations terminées de ce jour
                    const consultationsTerminees = data.filter(patient => {
                        if (patient.statut !== 'confirmé') return false;

                        const heureFin = new Date(patient.date_fin || new Date(patient.date_debut).getTime() + 30 * 60 * 1000);
                        const maintenant = new Date();
                        const aujourdhui = new Date();
                        aujourdhui.setHours(0, 0, 0, 0);

                        // Consultation terminée aujourd'hui
                        return maintenant > heureFin && heureFin.toDateString() === aujourdhui.toDateString();
                    });

                    if (consultationsTerminees.length > 0) {
                        consultationsTermineesList.innerHTML = consultationsTerminees.map(patient => `
                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center gap-3">
                                    <img src="${patient.photo}" alt="Photo" width="40" height="40" class="rounded-full object-cover border-2 border-gray-300" style="min-width:40px;min-height:40px;">
                                    <div class="flex-1 min-w-0">
                                        <div class="font-medium text-gray-800 truncate">${patient.prenom} ${patient.nom}</div>
                                        <div class="text-xs text-gray-500">
                                            ${patient.heure} - ${patient.heure_fin}
                                        </div>
                                        <div class="text-xs text-gray-600 mt-1">
                                            🏁 Consultation terminée
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `).join('');
                    } else {
                        consultationsTermineesList.innerHTML = `
                            <div class="text-center py-4">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-gray-500 text-sm">Aucune consultation terminée aujourd'hui</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des consultations terminées:', error);
                    document.getElementById('consultationsTermineesList').innerHTML = `
                        <div class="text-center py-4">
                            <p class="text-red-500 text-sm">Erreur de chargement</p>
                        </div>
                    `;
                });
        }

        // Fonction pour changer le statut d'un rendez-vous
        function changerStatutRendezVous(rendezVousId, statutActuel) {
            // Définir les statuts disponibles et leur ordre
            const statuts = ['en_attente', 'confirmé', 'annulé'];
            const statutIndex = statuts.indexOf(statutActuel);
            const nouveauStatut = statuts[(statutIndex + 1) % statuts.length];

            // Afficher une confirmation
            const statutLabels = {
                'en_attente': 'En attente',
                'confirmé': 'Confirmé',
                'annulé': 'Annulé'
            };

            if (confirm(`Changer le statut de "${statutLabels[statutActuel]}" vers "${statutLabels[nouveauStatut]}" ?`)) {
                // Appeler l'API pour mettre à jour le statut
                fetch(`/dashboard/medecin/api/rendez-vous/${rendezVousId}/statut`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        statut: nouveauStatut
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Afficher un message de succès
                        afficherNotification('Statut mis à jour avec succès', 'success');

                        // Recharger la liste des patients pour mettre à jour l'affichage
                        chargerPatientsDuJour();
                    } else {
                        afficherNotification('Erreur lors de la mise à jour du statut', 'error');
                    }
                })
                .catch(error => {
                    console.error('Erreur lors du changement de statut:', error);
                    afficherNotification('Erreur lors de la mise à jour du statut', 'error');
                });
            }
        }

        // Fonction pour afficher des notifications
        function afficherNotification(message, type = 'info') {
            // Créer l'élément de notification
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
                type === 'success' ? 'bg-green-500 text-white' :
                type === 'error' ? 'bg-red-500 text-white' :
                'bg-blue-500 text-white'
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

            // Ajouter à la page
            document.body.appendChild(notification);

            // Animer l'entrée
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);

            // Supprimer automatiquement après 3 secondes
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 300);
            }, 3000);
        }

        // Fonction pour recharger la liste des patients
        function chargerPatientsDuJour() {
            fetch('/dashboard/medecin/rendez-vous-du-jour')
                .then(response => response.json())
                .then(data => {
                    const patientsList = document.getElementById('patientsList');
                    const patientsCount = document.getElementById('patientsCount');

                    // Filtrer pour n'afficher que les consultations à venir (non terminées)
                    const consultationsAVenir = data.filter(patient => {
                        if (patient.statut !== 'confirmé') return true; // Garder les non confirmés

                        // Pour les confirmés, vérifier qu'ils ne sont pas terminés
                        const heureFin = new Date(patient.date_fin || new Date(patient.date_debut).getTime() + 30 * 60 * 1000);
                        const maintenant = new Date();
                        return maintenant <= heureFin; // Garder si pas encore terminé
                    });

                    if (consultationsAVenir.length > 0) {
                        patientsCount.textContent = consultationsAVenir.length + ' Consultations';
                        patientsList.innerHTML = consultationsAVenir.map(patient => `
                            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-4 bg-gray-50 rounded-lg shadow-sm hover:shadow-md transition-all mb-2">
                                <div class="flex items-center gap-4">
                                    <img src="${patient.photo}" alt="Photo" width="56" height="56" class="rounded-full object-cover border-2 border-[#b9ff66] shadow" style="min-width:56px;min-height:56px;">
                                    <div>
                                        <div class="text-lg font-bold text-gray-800">${patient.prenom} ${patient.nom}</div>
                                        <div class="text-sm text-gray-500 flex gap-2 items-center">
                                            <span class="inline-flex items-center gap-1"><svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3" stroke-linecap="round" stroke-linejoin="round"/></svg>${patient.heure} - ${patient.heure_fin}</span>
                                            <span class="inline-flex items-center gap-1"><svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>${patient.type.charAt(0).toUpperCase() + patient.type.slice(1)}</span>
                                        </div>
                                        <!-- Indicateur de statut de consultation -->
                                        <div class="consultation-indicator text-xs mt-1">
                                            ${patient.statut !== 'confirmé' ?
                                                '<span class="text-red-500 font-medium">❌ Rendez-vous non confirmé</span>' :
                                                patient.consultation_terminee ?
                                                '<span class="text-gray-600 font-medium">🏁 Consultation terminée</span>' :
                                                patient.consultation_en_cours ?
                                                '<span class="text-green-600 font-medium">🟢 Consultation en cours</span>' :
                                                patient.consultation_active ?
                                                '<span class="text-blue-600 font-medium">🔵 Consultation peut commencer (5 min avant)</span>' :
                                                '<span class="text-gray-500">⏰ Consultation à venir</span>'
                                            }
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col sm:flex-row items-center gap-3">
                                    <span class="statut-label px-4 py-1.5 rounded-full text-sm font-semibold
                                        ${patient.statut === 'confirmé' ? 'bg-green-100 text-green-700' : ''}
                                        ${patient.statut === 'en_attente' ? 'bg-yellow-100 text-yellow-700' : ''}
                                        ${patient.statut === 'annulé' ? 'bg-red-100 text-red-700' : ''}">
                                        ${patient.statut.charAt(0).toUpperCase() + patient.statut.slice(1)}
                                    </span>
                                    <div class="flex items-center gap-2">
                                        <!-- Bouton de changement de statut -->
                                        <div class="relative">
                                            <button onclick="changerStatutRendezVous(${patient.id}, '${patient.statut}')"
                                                    class="p-2 bg-blue-500 hover:bg-blue-600 text-white rounded-full transition-all"
                                                    title="Changer le statut du rendez-vous">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                </svg>
                                            </button>
                                        </div>

                                        <!-- Lien de consultation en ligne (actif seulement si confirmé et dans la fenêtre de temps) -->
                                        <a href="#"
                                           onclick="ouvrirConsultationEnLigne('${patient.lien_meet}', ${patient.consultation_active}, ${patient.id}, '${patient.statut}')"
                                           class="consultation-link p-2 rounded-full transition-all ${patient.statut === 'confirmé' && patient.consultation_active && !patient.consultation_terminee ? 'bg-green-500 hover:bg-green-600 text-white' : 'bg-gray-300 text-gray-500 cursor-not-allowed'}"
                                           title="${patient.statut !== 'confirmé' ? 'Rendez-vous non confirmé' : patient.consultation_terminee ? 'Consultation terminée' : patient.consultation_active ? 'Rejoindre la consultation' : 'Consultation active 5 min avant le début'}">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                        </a>

                                        <!-- Lien vers les rendez-vous -->
                                        <a href="/dashboard/medecin/rendez-vous" class="p-2 bg-[#b9ff66] hover:bg-[#a8eb5f] rounded-full transition-all" title="Voir le rendez-vous">
                                            <svg class="w-5 h-5 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        `).join('');
                    } else {
                        patientsCount.textContent = '0 Consultation';
                        patientsList.innerHTML = `
                            <div class="text-center py-8 bg-gray-50 rounded-lg">
                                <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-gray-500 text-lg">Aucune consultation à venir aujourd'hui</p>
                                <p class="text-gray-400 text-sm mt-2">Les consultations à venir apparaîtront ici</p>
                            </div>
                        `;
                    }
                });
        }

        // Fonction de test pour vérifier les liens de consultation
        function testerLiensConsultation() {
            console.log('Test des liens de consultation...');
            fetch('/dashboard/medecin/rendez-vous-du-jour')
                .then(response => response.json())
                .then(data => {
                    console.log('Rendez-vous du jour:', data);
                    data.forEach(rdv => {
                        if (rdv.lien_meet) {
                            console.log(`RDV ${rdv.id}: ${rdv.lien_meet}`);
                        } else {
                            console.log(`RDV ${rdv.id}: Pas de lien`);
                        }
                    });
                });
        }

        // Exécuter le test au chargement de la page
        setTimeout(testerLiensConsultation, 2000);

        // Fonction de test pour vérifier les codes Google Meet
        function testerCodesGoogleMeet() {
            console.log('Test des codes Google Meet...');
            fetch('/dashboard/medecin/api/test-codes-consultation')
                .then(response => response.json())
                .then(data => {
                    console.log('Codes de test générés:', data);
                    data.forEach((item, index) => {
                        console.log(`Code ${index + 1}: ${item.code} - Valide: ${item.valide}`);
                        console.log(`  Lien: ${item.lien}`);
                    });
                })
                .catch(error => {
                    console.error('Erreur lors du test des codes:', error);
                });
        }

        // Exécuter le test des codes après 3 secondes
        setTimeout(testerCodesGoogleMeet, 3000);

        // Fonction pour tester un lien Google Meet
        function testerLienGoogleMeet() {
            console.log('Test d\'un lien Google Meet...');
            fetch('/dashboard/medecin/api/test-lien-google-meet')
                .then(response => response.json())
                .then(data => {
                    console.log('Lien de test généré:', data);
                    console.log('Code:', data.code);
                    console.log('Format attendu: xxx-yyyy-zzz');

                    // Ouvrir le lien dans un nouvel onglet pour tester
                    if (confirm('Voulez-vous tester ce lien Google Meet ?')) {
                        window.open(data.lien, '_blank');
                    }
                })
                .catch(error => {
                    console.error('Erreur lors du test du lien:', error);
                });
        }

        // Exécuter le test du lien après 4 secondes
        setTimeout(testerLienGoogleMeet, 4000);
    </script>

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-8 sm:mb-12">
        <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-8 mb-4 sm:mb-0">
            <h1 class="text-2xl sm:text-4xl font-bold">
                TABLEAU DE B<span class="text-[#b9ff66]">O</span>RD
            </h1>
        </div>
    </div>

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Main Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-12">
        <!-- Left Column - Statistics and Quick Actions -->
        <div class="lg:col-span-8 space-y-6">
            <!-- Detailed Statistics -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                <!-- Consultations -->
                <div class="bg-white p-4 sm:p-6 rounded-[20px] shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="p-2 bg-blue-100 rounded-full">👥</span>
                        <h3 class="font-semibold">Consultations</h3>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Aujourd'hui</span>
                            <span class="text-2xl font-bold">{{ is_numeric($consultationsAujourdhui) ? $consultationsAujourdhui : 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Cette semaine</span>
                            <span class="text-xl font-semibold">{{ is_numeric($consultationsSemaine) ? $consultationsSemaine : 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Ce mois</span>
                            <span class="text-xl font-semibold">{{ is_numeric($consultationsMois) ? $consultationsMois : 0 }}</span>
                        </div>
                    </div>
                </div>

                <!-- Patients -->
                <div class="bg-white p-4 sm:p-6 rounded-[20px] shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="p-2 bg-green-100 rounded-full">🏥</span>
                        <h3 class="font-semibold">Patients</h3>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Total suivis</span>
                            <span class="text-2xl font-bold">{{ is_numeric($totalPatients) ? $totalPatients : 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Nouveaux (mois)</span>
                            <span class="text-lg font-semibold text-green-500">+{{ is_numeric($nouveauxPatients) ? $nouveauxPatients : 0 }}</span>
                        </div>
                        <a href="{{ route('medecin.patients') }}" class="w-full bg-[#b9ff66] text-sm py-2 rounded-full mt-2 hover:bg-[#a8eb5f] transition-all block text-center">
                            Voir tous les patients
                        </a>
                    </div>
                </div>

                <!-- Pending Appointments -->
                <div class="bg-white p-4 sm:p-6 rounded-[20px] shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="p-2 bg-orange-100 rounded-full">📅</span>
                        <h3 class="font-semibold">RDV en attente</h3>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">À confirmer</span>
                            <span class="text-2xl font-bold text-orange-500">{{ is_numeric($rdvEnAttente) ? $rdvEnAttente : 0 }}</span>
                        </div>
                        <a href="{{ route('medecin.rendez-vous.index') }}" class="w-full bg-[#b9ff66] text-sm py-2 rounded-full mt-2 hover:bg-[#a8eb5f] transition-all block text-center">
                            Gérer les demandes
                        </a>
                    </div>
                </div>
            </div>

            <!-- Today's Patients -->
            <div class="bg-white rounded-[25px] p-4 sm:p-8">
                <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                    <h2 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-0">Consultations à venir</h2>
                    <div class="flex items-center gap-4">
                        <span class="bg-[#b9ff66] px-3 sm:px-4 py-1.5 sm:py-2 rounded-full text-sm font-medium" id="patientsCount"></span>
                    </div>
                </div>
                <div class="space-y-4 sm:space-y-6" id="patientsList"></div>
            </div>

            @php $dossiers = $dossiers ?? collect([]); @endphp
            @if(isset($dossiers) && $dossiers->count() > 0)
            <div class="bg-white rounded-[20px] p-6 shadow-sm mt-8">
                <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-[#b9ff66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3 3m0 0l-3-3m3 3V8"/>
                    </svg>
                    Dossiers médicaux de vos patients ({{ $dossiers->count() }})
                </h2>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 rounded-lg">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Patient</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Contact</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Groupe sanguin</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Allergies</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Antécédents</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($dossiers as $dossier)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @php
                                            $user = $dossier->patient->user ?? null;
                                            $photoUrl = $user && $user->profile_photo_path
                                                ? asset('storage/' . $user->profile_photo_path)
                                                : 'https://ui-avatars.com/api/?name=' . urlencode($dossier->patient->prenom . ' ' . $dossier->patient->nom);
                                        @endphp
                                        <img src="{{ $photoUrl }}" class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $dossier->patient->prenom }} {{ $dossier->patient->nom }}</div>
                                            <div class="text-xs text-gray-500">ID: #{{ $dossier->patient->id }}</div>
                                            @if($dossier->patient->dateNaissance)
                                                <div class="text-xs text-gray-400">
                                                    {{ \Carbon\Carbon::parse($dossier->patient->dateNaissance)->age }} ans
                                                </div>
                                            @endif
                                            <!-- Indicateur de consultation en ligne -->
                                            @php
                                                $rdvAujourdhui = $dossier->patient->rendezVous()
                                                    ->where('medecin_id', auth()->user()->medecin->id)
                                                    ->whereDate('date_debut', \Carbon\Carbon::today())
                                                    ->first();
                                                $consultationActive = false;
                                                $consultationEnCours = false;
                                                if ($rdvAujourdhui) {
                                                    $heureDebut = \Carbon\Carbon::parse($rdvAujourdhui->date_debut);
                                                    $heureFin = \Carbon\Carbon::parse($rdvAujourdhui->date_fin ?? $rdvAujourdhui->date_debut->addMinutes(30));
                                                    $maintenant = \Carbon\Carbon::now();

                                                    // Nouvelle logique : actif seulement si confirmé et dans la fenêtre de 5 minutes
                                                    if ($rdvAujourdhui->statut === 'confirmé') {
                                                        $consultationActive = $maintenant->between($heureDebut->copy()->subMinutes(5), $heureFin);
                                                        $consultationEnCours = $maintenant->between($heureDebut, $heureFin);
                                                    }
                                                }
                                            @endphp
                                            @if($rdvAujourdhui)
                                                <div class="text-xs mt-1">
                                                    @if($rdvAujourdhui->statut !== 'confirmé')
                                                        <span class="text-red-500 font-medium">❌ Rendez-vous non confirmé</span>
                                                    @elseif($consultationEnCours)
                                                        <span class="text-green-600 font-medium">🟢 Consultation en cours</span>
                                                    @elseif($consultationActive)
                                                        <span class="text-blue-600 font-medium">🔵 Consultation peut commencer (5 min avant)</span>
                                                    @else
                                                        <span class="text-gray-500">⏰ RDV: {{ \Carbon\Carbon::parse($rdvAujourdhui->date_debut)->format('H:i') }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        @if($dossier->tel)
                                            <div class="text-sm text-gray-600">{{ $dossier->tel }}</div>
                                        @endif
                                        @if($dossier->adresse)
                                            <div class="text-xs text-gray-500 max-w-xs truncate" title="{{ $dossier->adresse }}">
                                                {{ $dossier->adresse }}
                                            </div>
                                        @endif
                                        @if(!$dossier->tel && !$dossier->adresse)
                                            <span class="text-gray-400 text-sm">Non renseigné</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($dossier->groupe_sanguin)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            {{ $dossier->groupe_sanguin }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-sm">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($dossier->allergies && $dossier->allergies !== 'Aucune')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            {{ $dossier->allergies }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-sm">Aucune</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($dossier->antecedents_medicaux && $dossier->antecedents_medicaux !== 'Aucun')
                                        <div class="max-w-xs">
                                            <span class="text-sm text-gray-700">{{ Str::limit($dossier->antecedents_medicaux, 50) }}</span>
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm">Aucun</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <button onclick="openDossierModal({{ $dossier->id }})"
                                                class="text-blue-600 hover:text-blue-800 hover:bg-blue-50 px-3 py-1 rounded-md transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                        <a href="{{ route('medecin.dossiermedical', ['patient_id' => $dossier->patient->id]) }}"
                                           class="text-green-600 hover:text-green-800 hover:bg-green-50 px-3 py-1 rounded-md transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($dossiers->hasPages())
                <div class="flex justify-between items-center mt-6">
                    {{ $dossiers->links() }}
                </div>
                @endif
            </div>
            @else
            <div class="bg-white rounded-[20px] p-6 shadow-sm mt-8">
                <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-[#b9ff66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3 3m0 0l-3-3m3 3V8"/>
                    </svg>
                    Dossiers médicaux de vos patients
                </h2>
                <div class="text-center py-8">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-gray-500 text-lg mb-2">Aucun dossier médical trouvé</p>
                    <p class="text-gray-400 text-sm">Les dossiers médicaux de vos patients apparaîtront ici</p>
                    <a href="{{ route('medecin.dossiers.medicaux') }}" class="inline-flex items-center px-4 py-2 mt-4 bg-[#b9ff66] text-gray-800 rounded-full hover:bg-[#a8eb5f] transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Créer un dossier médical
                    </a>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column - Quick Actions -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white p-4 sm:p-6 rounded-[20px] shadow-sm">
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <h2 class="text-xl font-bold">Actions Rapides</h2>
                </div>
                <div class="space-y-3">
                    <a href="{{ route('medecin.rendez-vous.index') }}" class="p-3 rounded-lg hover:bg-gray-50 transition-all cursor-pointer block">
                        <div class="flex items-center gap-3">
                            <span class="p-2 bg-blue-100 rounded-full">📋</span>
                            <div>
                                <h4 class="font-medium">Rendez-vous</h4>
                                <p class="text-sm text-gray-500">Gérer les rendez-vous</p>
                            </div>
                        </div>
                    </a>

                </div>
            </div>

            <!-- Consultations terminées de ce jour -->
            <div class="bg-white p-4 sm:p-6 rounded-[20px] shadow-sm">
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <h2 class="text-xl font-bold">Consultations terminées de ce jour</h2>
                </div>
                <div id="consultationsTermineesList" class="space-y-3">
                    <!-- Le contenu sera chargé dynamiquement -->
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Dossier Médical -->
    <div id="dossierModal"
         class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-[30px] p-8 w-3/4 max-h-[80vh] overflow-y-auto">
            <div class="flex justify-between items-start mb-6">
                <h3 class="text-2xl font-bold">Dossier Médical</h3>
                <button onclick="closeDossierModal()"
                        class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div id="dossierContent">
                <!-- Le contenu sera chargé dynamiquement via JavaScript -->
            </div>
        </div>
    </div>

    <!-- JavaScript pour le modal -->
    <script>
        function openDossierModal(dossierId) {
            if (!dossierId) {
                console.error('ID du dossier non valide');
                return;
            }

            document.getElementById('dossierModal').classList.remove('hidden');
            document.getElementById('dossierModal').classList.add('flex');

            // Charger les données du dossier via AJAX
            fetch(`/dashboard/medecin/dossiers/${dossierId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur lors du chargement du dossier');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.html) {
                        document.getElementById('dossierContent').innerHTML = data.html;
                    } else {
                        throw new Error('Format de données invalide');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    document.getElementById('dossierContent').innerHTML = `
                        <div class="text-center py-8">
                            <p class="text-red-500">Une erreur est survenue lors du chargement du dossier</p>
                            <p class="text-gray-500 text-sm mt-2">${error.message}</p>
                        </div>
                    `;
                });
        }

        function closeDossierModal() {
            document.getElementById('dossierModal').classList.add('hidden');
            document.getElementById('dossierModal').classList.remove('flex');
            document.getElementById('dossierContent').innerHTML = '';
        }

        // Fermer le modal en cliquant en dehors
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('dossierModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeDossierModal();
                    }
                });
            }
        });
    </script>
</div>

<style>
.pagination-btn {
    @apply flex items-center justify-center w-8 h-8 rounded-full text-sm hover:bg-gray-100 transition-all;
}

.pagination-btn.active {
    @apply bg-[#b9ff66] hover:bg-[#a8eb5f];
}

.pagination-btn[disabled] {
    @apply opacity-50 cursor-not-allowed hover:bg-transparent;
}
</style>
@endsection
