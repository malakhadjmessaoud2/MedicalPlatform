@extends('dashDonateur.layout')

@section('content')
<div class="dashboard-container p-6 bg-gray-100 rounded-lg space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Demandes de Dons</h1>
            <p class="text-sm text-gray-500">Aidez les patients dans le besoin</p>
        </div>
        <div class="flex gap-4">
            <!-- Filtres -->
            <div class="flex space-x-4">
                <select class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="">Toutes les pharmacies</option>
                    <option value="1">Pharmacie Centrale</option>
                    <option value="2">Pharmacie du Nord</option>
                </select>
                <select class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="">Tous les statuts</option>
                    <option value="urgent">Urgent</option>
                    <option value="normal">Normal</option>
                </select>
            </div>
            <div class="relative w-64">
                <input type="text" placeholder="Rechercher une demande..."
                    class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500">
                <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <div class="flex items-center space-x-4">
                <div class="bg-red-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-bold text-gray-800">25</p>
                    <p class="text-sm text-gray-500">Demandes Urgentes</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm">
            <div class="flex items-center space-x-4">
                <div class="bg-teal-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-bold text-gray-800">156</p>
                    <p class="text-sm text-gray-500">Dons Réalisés</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm">
            <div class="flex items-center space-x-4">
                <div class="bg-blue-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-bold text-gray-800">89</p>
                    <p class="text-sm text-gray-500">Patients Aidés</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm">
            <div class="flex items-center space-x-4">
                <div class="bg-purple-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-bold text-gray-800">12</p>
                    <p class="text-sm text-gray-500">Pharmacies Partenaires</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Onglets -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="border-b">
            <div class="flex">
                <button onclick="switchTab('demandes')" id="tab-demandes"
                        class="px-6 py-4 text-sm font-medium border-b-2 border-teal-600 text-teal-600">
                    Demandes de Dons Actives
                </button>
                <button onclick="switchTab('historique')" id="tab-historique"
                        class="px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700">
                    Historique des Dons
                </button>
            </div>
        </div>

        <!-- Contenu des onglets -->
        <div id="content-demandes" class="p-6">
            <!-- Liste des demandes actives -->
            <div class="space-y-6">
                <!-- Demande 1 -->
                <div class="bg-white border rounded-lg shadow-sm p-6">
                    <div class="flex flex-col md:flex-row justify-between gap-6">
                        <!-- Informations Patient -->
                        <div class="flex-1">
                            <div class="flex items-center space-x-4">
                                <span class="px-2 py-1 bg-red-100 text-red-600 rounded-full text-xs font-medium">Urgent</span>
                                <h3 class="text-lg font-semibold">Patient #12345</h3>
                            </div>
                            <div class="mt-4 space-y-2">
                                <p class="text-sm"><span class="font-medium">Médicament:</span> Insuline Lantus</p>
                                <p class="text-sm"><span class="font-medium">Quantité:</span> 3 stylos</p>
                                <p class="text-sm"><span class="font-medium">Date limite:</span> 15/04/2024</p>
                            </div>
                        </div>

                        <!-- Informations Pharmacie -->
                        <div class="flex-1">
                            <div class="flex items-center space-x-4">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <div>
                                    <h4 class="font-medium">Pharmacie Centrale</h4>
                                    <p class="text-sm text-gray-500">Paris 75001</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <p class="text-lg font-bold text-teal-600">150 €</p>
                                <p class="text-sm text-gray-500">Coût total</p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col justify-center space-y-3">
                            <button onclick="showDonationModal('12345')"
                                    class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700">
                                Faire un Don
                            </button>
                            <button onclick="showDetails('12345')"
                                    class="px-4 py-2 border text-gray-600 rounded-lg hover:bg-gray-50">
                                Voir Détails
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Demande 2 -->
                <div class="bg-white border rounded-lg shadow-sm p-6">
                    <!-- Structure similaire avec données différentes -->
                </div>
            </div>
        </div>

        <div id="content-historique" class="hidden p-6">
            <!-- Tableau d'historique -->
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Médicament</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pharmacie</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- Don 1 -->
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">10/03/2024</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">Patient #12340</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">Paracétamol 1000mg</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">Pharmacie du Nord</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-teal-600">75 €</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Complété</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <button onclick="showDonDetails('DON-001')"
                                    class="text-teal-600 hover:text-teal-800">
                                Voir reçu
                            </button>
                        </td>
                    </tr>
                    <!-- Autres dons... -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal de Don -->
<div id="donationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-[500px]">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold">Faire un Don</h3>
            <button onclick="closeDonationModal()" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form onsubmit="processDonation(event)">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Montant du don</label>
                    <div class="relative">
                        <input type="number" min="0" step="0.01" required
                               class="w-full pl-8 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500">
                        <span class="absolute left-3 top-2 text-gray-500">€</span>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Message (optionnel)</label>
                    <textarea class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500" rows="3"></textarea>
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="closeDonationModal()"
                            class="px-4 py-2 border rounded-lg hover:bg-gray-50">
                        Annuler
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700">
                        Confirmer le Don
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function showDonationModal(requestId) {
    document.getElementById('donationModal').classList.remove('hidden');
    document.getElementById('donationModal').classList.add('flex');
}

function closeDonationModal() {
    document.getElementById('donationModal').classList.add('hidden');
    document.getElementById('donationModal').classList.remove('flex');
}

function processDonation(event) {
    event.preventDefault();
    // Logique de traitement du don
    alert('Don effectué avec succès !');
    closeDonationModal();
}

function showDetails(requestId) {
    // Logique d'affichage des détails
}

function switchTab(tabName) {
    // Masquer tous les contenus
    document.getElementById('content-demandes').classList.add('hidden');
    document.getElementById('content-historique').classList.add('hidden');

    // Réinitialiser tous les onglets
    document.getElementById('tab-demandes').classList.remove('border-teal-600', 'text-teal-600');
    document.getElementById('tab-historique').classList.remove('border-teal-600', 'text-teal-600');

    // Afficher le contenu sélectionné
    document.getElementById('content-' + tabName).classList.remove('hidden');

    // Activer l'onglet sélectionné
    document.getElementById('tab-' + tabName).classList.add('border-teal-600', 'text-teal-600');
}
</script>
@endsection
