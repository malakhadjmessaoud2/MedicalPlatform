@extends('dashPatient.layout')

@section('content')
<style>
    @keyframes fade-in {
        0% { opacity: 0; transform: translateY(10px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }

    .alert {
        animation: slideInDown 0.5s ease-out;
    }

    @keyframes slideInDown {
        from { transform: translateY(-100%); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .tooltip {
        visibility: hidden;
        opacity: 0;
        transition: all 0.3s ease;
    }

    .has-tooltip:hover .tooltip {
        visibility: visible;
        opacity: 1;
    }
</style>

<div class="p-6 lg:p-8  from-[#f8f9fa] to-[#e9ecef] min-h-screen" x-data="{
    showNewRequestModal: false,
    showDetailsModal: false,
    selectedRequest: null,
    showAlert: false,
    alertType: '',
    alertMessage: '',
    showConfirmModal: false,
    requests: [
        {
            id: 1,
            status: 'pending',
            statusText: 'En attente',
            medicine: 'Doliprane 1000mg',
            quantity: 2,
            reason: 'Urgence médicale',
            date: '2024-02-20',
            documents: ['ordonnance.pdf'],
            urgencyLevel: 'Haute',
            prescription: {
                doctor: 'Dr. Martin',
                hospital: 'Hôpital Central',
                date: '2024-02-19'
            },
            donors: [],
            history: [
                { date: '2024-02-20', status: 'Demande créée', description: 'Demande soumise en attente de validation' }
            ]
        },
        {
            id: 2,
            status: 'validated',
            statusText: 'Validée',
            medicine: 'Insuline Lantus',
            quantity: 1,
            reason: 'Traitement mensuel',
            date: '2024-02-19',
            documents: ['prescription.pdf'],
            urgencyLevel: 'Moyenne',
            prescription: {
                doctor: 'Dr. Dubois',
                hospital: 'Clinique Saint-Joseph',
                date: '2024-02-18'
            },
            pharmacy: {
                name: 'Pharmacie Centrale',
                address: '123 Rue de la Santé',
                phone: '0123456789',
                distance: '2.5 km'
            },
            donor: {
                name: 'Jean Dupont',
                date: '2024-02-19',
                rating: '4.8/5'
            },
            history: [
                { date: '2024-02-19', status: 'Validée', description: 'Demande acceptée par la pharmacie' },
                { date: '2024-02-19', status: 'Donateur trouvé', description: 'Un donateur s\'est manifesté' },
                { date: '2024-02-18', status: 'Demande créée', description: 'Demande soumise en attente de validation' }
            ]
        },
        {
            id: 3,
            status: 'in_delivery',
            statusText: 'En livraison',
            medicine: 'Ventoline 100µg',
            quantity: 1,
            reason: 'Renouvellement traitement',
            date: '2024-02-18',
            documents: ['ordonnance_ventoline.pdf'],
            urgencyLevel: 'Normale',
            prescription: {
                doctor: 'Dr. Bernard',
                hospital: 'Cabinet Médical du Centre',
                date: '2024-02-17'
            },
            pharmacy: {
                name: 'Pharmacie du Parc',
                address: '45 Avenue des Fleurs',
                phone: '0987654321',
                distance: '1.8 km'
            },
            donor: {
                name: 'Marie Lambert',
                date: '2024-02-18',
                rating: '4.9/5'
            },
            delivery: {
                estimatedTime: '30 min',
                status: 'En route',
                trackingCode: 'DEL-123456'
            },
            history: [
                { date: '2024-02-18', status: 'En livraison', description: 'Le médicament est en cours de livraison' },
                { date: '2024-02-18', status: 'Préparation', description: 'La pharmacie prépare votre commande' },
                { date: '2024-02-17', status: 'Validée', description: 'Demande acceptée par la pharmacie' }
            ]
        },
        {
            id: 4,
            status: 'closed',
            statusText: 'Clôturée',
            medicine: 'Amoxicilline 1g',
            quantity: 1,
            reason: 'Traitement infection',
            date: '2024-02-15',
            documents: ['prescription_amox.pdf'],
            urgencyLevel: 'Terminée',
            prescription: {
                doctor: 'Dr. Robert',
                hospital: 'Clinique des Lilas',
                date: '2024-02-14'
            },
            pharmacy: {
                name: 'Pharmacie des Alpes',
                address: '78 Rue Principale',
                phone: '0123456789'
            },
            donor: {
                name: 'Sophie Martin',
                date: '2024-02-15',
                rating: '5/5'
            },
            completion: {
                date: '2024-02-15',
                rating: 5,
                feedback: 'Excellent service, livraison rapide'
            },
            history: [
                { date: '2024-02-15', status: 'Clôturée', description: 'Médicament reçu et confirmé' },
                { date: '2024-02-15', status: 'Livré', description: 'Médicament livré au patient' },
                { date: '2024-02-14', status: 'Validée', description: 'Demande acceptée par la pharmacie' }
            ]
        }
    ],
    displayAlert(type, message) {
        this.alertType = type;
        this.alertMessage = message;
        this.showAlert = true;
        setTimeout(() => this.showAlert = false, 5000);
    }
}">
    <!-- Système d'alertes -->
    <div x-show="showAlert"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-2"
         class="fixed top-4 right-4 z-50 alert"
         @click="showAlert = false">
        <div x-show="alertType === 'success'"
             class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-lg cursor-pointer">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span x-text="alertMessage"></span>
            </div>
        </div>
        <div x-show="alertType === 'error'"
             class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-lg cursor-pointer">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span x-text="alertMessage"></span>
            </div>
        </div>
    </div>


    <!-- Header Section -->
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 mb-8">
            <div class="flex flex-col lg:flex-row items-start lg:items-center gap-4 lg:gap-8">
                <h1 class="text-2xl lg:text-4xl font-bold">Dons Médicaux</h1>
                <button @click="showNewRequestModal = true"
                        class="bg-[#b9ff66] text-black rounded-full px-4 py-2.5 flex items-center gap-2 hover:bg-[#a5e65c] transition-all">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                        <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <span class="text-sm">Nouvelle Demande</span>
                </button>
            </div>

            <!-- Statistiques -->
            <div class="flex gap-6 lg:gap-12">
                <div class="text-center relative bg-white p-4 rounded-xl shadow-sm">
                    <span class="text-3xl lg:text-4xl font-bold">3</span>
                    <span class="absolute -top-1 -right-2 text-xs bg-[#b9ff66] px-1.5 rounded-full text-xs">+1</span>
                    <div class="text-gray-500 text-sm mt-1">Demandes</div>
                </div>
                <div class="text-center relative bg-white p-4 rounded-xl shadow-sm">
                    <span class="text-3xl lg:text-4xl font-bold">1</span>
                    <span class="absolute -top-1 -right-2 text-xs bg-orange-200 px-1.5 rounded-full text-xs">+1</span>
                    <div class="text-gray-500 text-sm mt-1">En attente</div>
                </div>
            </div>
        </div>

        <!-- Filtres améliorés avec compteurs -->
        <div class="bg-white/90 backdrop-blur-md rounded-2xl p-4 mb-8 shadow-lg">
            <div class="flex flex-wrap gap-3">
                <button class="px-6 py-2.5 bg-[#b9ff66] rounded-full text-sm font-medium shadow-sm hover:shadow-md transition-all duration-300 flex items-center gap-2">
                    Toutes
                    <span class="bg-white/50 px-2 py-0.5 rounded-full text-xs">12</span>
                </button>
                <button class="px-6 py-2.5 hover:bg-gray-100 rounded-full text-sm font-medium transition-all duration-300 flex items-center gap-2">
                    En attente
                    <span class="bg-gray-100 px-2 py-0.5 rounded-full text-xs">3</span>
                </button>
                <button class="px-4 py-2 hover:bg-gray-100 rounded-full text-sm">Validées</button>
                <button class="px-4 py-2 hover:bg-gray-100 rounded-full text-sm">Acceptées</button>
                <button class="px-4 py-2 hover:bg-gray-100 rounded-full text-sm">En livraison</button>
                <button class="px-4 py-2 hover:bg-gray-100 rounded-full text-sm">Clôturées</button>
            </div>
        </div>

        <!-- Liste des demandes avec états détaillés -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <template x-for="request in requests" :key="request.id">
                <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 card-hover">
                    <!-- En-tête avec statut et date -->
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1.5 rounded-full text-xs font-medium"
                                  :class="{
                                      'bg-yellow-100 text-yellow-800': request.status === 'pending',
                                      'bg-green-100 text-green-800': request.status === 'validated',
                                      'bg-purple-100 text-purple-800': request.status === 'in_delivery',
                                      'bg-gray-100 text-gray-800': request.status === 'closed'
                                  }"
                                  x-text="request.statusText"></span>
                            <span class="text-xs bg-red-50 text-red-600 px-2 py-1 rounded-full" x-show="request.urgencyLevel === 'Haute'">
                                Urgent
                            </span>
                        </div>
                        <span class="text-sm text-gray-500" x-text="request.date"></span>
                    </div>

                    <!-- Informations principales -->
                    <h3 class="font-bold text-xl mb-3" x-text="request.medicine"></h3>
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center gap-2 text-sm">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <span class="text-gray-600" x-text="`Quantité: ${request.quantity}`"></span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span class="text-gray-600" x-text="`Dr. ${request.prescription.doctor}`"></span>
                        </div>
                    </div>

                    <!-- Informations pharmacie si disponible -->
                    <div x-show="request.pharmacy" class="bg-gray-50 rounded-lg p-3 mb-4">
                        <div class="flex items-center gap-2 text-sm mb-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span class="font-medium" x-text="request.pharmacy?.name"></span>
                        </div>
                        <div class="text-xs text-gray-500" x-show="request.pharmacy?.distance">
                            <span x-text="`Distance: ${request.pharmacy?.distance}`"></span>
                        </div>
                    </div>

                    <!-- Documents -->
                    <div class="flex gap-2 mb-4">
                        <template x-for="doc in request.documents">
                            <span class="px-3 py-1.5 bg-gray-100 rounded-lg text-sm flex items-center gap-2 hover:bg-gray-200 transition-all cursor-pointer">
                                <svg class="w-4 h-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                <span x-text="doc"></span>
                            </span>
                        </template>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-between items-center">
                        <button @click="showDetailsModal = true; selectedRequest = request"
                                class="text-sm text-[#45a049] hover:text-[#3d8b40] transition-colors font-medium hover:underline flex items-center gap-1">
                            <span>Voir détails</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                        <button x-show="request.status === 'in_delivery'"
                                @click="showConfirmModal = true; selectedRequest = request"
                                class="px-4 py-2 bg-[#b9ff66] rounded-full text-sm hover:bg-[#a5e65c] transition-all shadow-sm hover:shadow-md flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Confirmer réception</span>
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Modal Nouvelle Demande -->
    <div x-show="showNewRequestModal"
         class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
         @click.self="showNewRequestModal = false">
        <div class="bg-white rounded-xl p-6 w-full max-w-lg mx-4">
            <h2 class="text-2xl font-bold mb-6">Nouvelle Demande de Don</h2>

            <form class="space-y-4">
                <!-- Médicament -->
                <div>
                    <label class="block text-sm font-medium mb-1">Médicament requis</label>
                    <input type="text" class="w-full px-4 py-2 rounded-lg border focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                </div>

                <!-- Quantité -->
                <div>
                    <label class="block text-sm font-medium mb-1">Quantité</label>
                    <input type="number" class="w-full px-4 py-2 rounded-lg border focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                </div>

                <!-- Raison -->
                <div>
                    <label class="block text-sm font-medium mb-1">Raison de la demande</label>
                    <textarea class="w-full px-4 py-2 rounded-lg border focus:ring-[#b9ff66] focus:border-[#b9ff66] h-24"></textarea>
                </div>

                <!-- Documents -->
                <div>
                    <label class="block text-sm font-medium mb-1">Documents justificatifs</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                        <input type="file" multiple class="hidden" id="documents">
                        <label for="documents" class="cursor-pointer text-sm text-gray-500">
                            Cliquez pour ajouter ou glissez vos documents ici
                        </label>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="flex justify-end gap-4 mt-6">
                    <button type="button" @click="showNewRequestModal = false"
                            class="px-4 py-2 text-gray-500 hover:bg-gray-100 rounded-lg">
                        Annuler
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-[#b9ff66] rounded-lg hover:bg-[#a5e65c]">
                        Soumettre la demande
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Détails améliorée -->
    <div x-show="showDetailsModal"
         class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
         @click.self="showDetailsModal = false">
        <div class="bg-white rounded-xl p-6 w-full max-w-3xl mx-4 max-h-[90vh] overflow-y-auto">
            <template x-if="selectedRequest">
                <div>
                    <div class="flex justify-between items-start mb-6">
                        <h2 class="text-2xl font-bold" x-text="'Demande - ' + selectedRequest.medicine"></h2>
                        <span x-text="selectedRequest.status"
                              :class="{
                                  'px-3 py-1 rounded-full text-xs': true,
                                  'bg-yellow-100 text-yellow-800': selectedRequest.status === 'pending',
                                  'bg-green-100 text-green-800': selectedRequest.status === 'validated',
                                  'bg-purple-100 text-purple-800': selectedRequest.status === 'in_delivery',
                                  'bg-gray-100 text-gray-800': selectedRequest.status === 'closed'
                              }"></span>
                    </div>

                    <!-- Informations principales -->
                    <div class="grid grid-cols-2 gap-6 mb-8">
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Date de demande</h3>
                                <p class="mt-1" x-text="selectedRequest.date"></p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Quantité</h3>
                                <p class="mt-1" x-text="selectedRequest.quantity + ' unité(s)'"></p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Raison</h3>
                                <p class="mt-1" x-text="selectedRequest.reason"></p>
                            </div>
                        </div>

                        <!-- Documents -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Documents fournis</h3>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="doc in selectedRequest.documents">
                                    <a href="#" class="px-3 py-2 bg-gray-100 rounded-lg text-sm hover:bg-gray-200 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                        <span x-text="doc"></span>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Pharmacie -->
                    <div class="mb-8" x-show="selectedRequest.pharmacy">
                        <h3 class="text-lg font-semibold mb-4">Pharmacie assignée</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="font-medium" x-text="selectedRequest.pharmacy?.name"></p>
                            <p class="text-sm text-gray-500 mt-1" x-text="selectedRequest.pharmacy?.address"></p>
                            <p class="text-sm text-gray-500" x-text="selectedRequest.pharmacy?.phone"></p>
                        </div>
                    </div>

                    <!-- Donateur -->
                    <div class="mb-8" x-show="selectedRequest.donor">
                        <h3 class="text-lg font-semibold mb-4">Donateur</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="font-medium" x-text="selectedRequest.donor?.name"></p>
                            <p class="text-sm text-gray-500 mt-1">
                                Don confirmé le <span x-text="selectedRequest.donor?.date"></span>
                            </p>
                        </div>
                    </div>

                    <!-- Historique -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Historique de la demande</h3>
                        <div class="space-y-4">
                            <template x-for="(event, index) in selectedRequest.history">
                                <div class="flex items-start gap-4">
                                    <div class="w-2 h-2 rounded-full bg-green-500 mt-2"></div>
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start">
                                            <p class="font-medium" x-text="event.status"></p>
                                            <span class="text-sm text-gray-500" x-text="event.date"></span>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1" x-text="event.description"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex justify-end gap-4 mt-8 pt-4 border-t">
                        <button @click="showDetailsModal = false"
                                class="px-4 py-2 text-gray-500 hover:bg-gray-100 rounded-lg">
                            Fermer
                        </button>
                        <button x-show="selectedRequest.status === 'in_delivery'"
                                class="px-4 py-2 bg-[#b9ff66] rounded-lg hover:bg-[#a5e65c]">
                            Confirmer réception
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Modal de confirmation -->
    <div x-show="showConfirmModal"
         class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50"
         @click.self="showConfirmModal = false">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4 shadow-xl">
            <div class="text-center mb-6">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Confirmer la réception</h3>
                <p class="text-sm text-gray-500">
                    Êtes-vous sûr d'avoir reçu votre médicament ? Cette action ne peut pas être annulée.
                </p>
            </div>
            <div class="flex justify-end gap-4">
                <button @click="showConfirmModal = false"
                        class="px-4 py-2 text-gray-500 hover:bg-gray-100 rounded-lg transition-all">
                    Annuler
                </button>
                <button @click="confirmReception(selectedRequest)"
                        class="px-4 py-2 bg-[#b9ff66] rounded-lg hover:bg-[#a5e65c] transition-all">
                    Confirmer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function getStatusDescription(status) {
        const descriptions = {
            'pending': 'Votre demande est en cours de validation par notre équipe',
            'validated': 'Votre demande a été validée et est en attente d\'un donateur',
            'in_delivery': 'Un donateur a été trouvé et la livraison est en cours',
            'closed': 'Votre demande a été clôturée avec succès'
        };
        return descriptions[status] || 'Statut inconnu';
    }

    function confirmReception(request) {
        // Logique de confirmation
        this.showConfirmModal = false;
        this.displayAlert('success', 'Réception confirmée avec succès !');
    }
</script>
@endsection
