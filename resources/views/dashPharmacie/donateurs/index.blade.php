@extends('dashPharmacie.layout')

@section('content')
<div class="dashboard-container p-8 bg-gray-100 rounded-lg min-h-screen">
    <!-- En-tête avec actions -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Gestion des Dons</h1>
            <p class="text-gray-600 mt-1">Gérez les dons de médicaments et suivez leur statut</p>
        </div>
        <div class="flex gap-4">
            <button onclick="openNewDonModal()"
                    class="flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700
                           transition-all duration-200 shadow-sm hover:shadow">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nouveau Don
            </button>
            <button onclick="openInventaireModal()"
                    class="flex items-center px-4 py-2 bg-white text-gray-700 rounded-lg hover:bg-gray-50
                           transition-all duration-200 shadow-sm hover:shadow border border-gray-200">
                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Inventaire
            </button>
        </div>
    </div>

    <!-- Cards Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Card 1 -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow duration-200">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500">Dons en Attente</p>
                    <p class="text-2xl font-bold text-gray-800 mt-2">24</p>
                    <div class="flex items-center mt-2 text-green-600">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                        </svg>
                        <span class="text-sm">8% cette semaine</span>
                    </div>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Répéter pour les autres cards avec des couleurs différentes -->
    </div>

    <!-- Section Liste des Dons -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Filtres -->
        <div class="p-6 border-b border-gray-100">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex-1 min-w-[200px]">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </span>
                        <input type="text"
                               placeholder="Rechercher un don..."
                               class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2
                                      focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>

                <select class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500
                              focus:border-green-500 bg-white">
                    <option value="">Tous les statuts</option>
                    <option value="en_attente">En attente</option>
                    <option value="valide">Validé</option>
                    <option value="refuse">Refusé</option>
                </select>

                <select class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500
                              focus:border-green-500 bg-white">
                    <option value="">Tous les types</option>
                    <option value="medicaments">Médicaments</option>
                    <option value="materiel">Matériel médical</option>
                </select>

                <button class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200
                              transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Tableau -->
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Donateur
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Médicament
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Quantité
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Date Exp.
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Statut
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="h-10 w-10 flex-shrink-0">
                                <span class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </span>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">Marie Dupont</div>
                                <div class="text-sm text-gray-500">marie.d@email.com</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">Paracétamol 500mg</div>
                        <div class="text-sm text-gray-500">Boîte de 30 comprimés</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">3 boîtes</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">12/2024</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                   bg-yellow-100 text-yellow-800">
                            En attente
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex space-x-3">
                            <button class="text-green-600 hover:text-green-900" onclick="openVerificationModal('don1')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                            <button class="text-blue-600 hover:text-blue-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                <!-- Répéter pour d'autres lignes -->
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100 bg-white">
            <div class="flex items-center justify-between">
                <div class="flex-1 flex justify-between sm:hidden">
                    <button class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium
                                 rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Précédent
                    </button>
                    <button class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm
                                 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Suivant
                    </button>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Affichage de <span class="font-medium">1</span> à <span class="font-medium">10</span> sur
                            <span class="font-medium">97</span> résultats
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <button class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300
                                       bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span class="sr-only">Précédent</span>
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                          clip-rule="evenodd" />
                                </svg>
                            </button>
                            <button class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white
                                       text-sm font-medium text-gray-700 hover:bg-gray-50">
                                1
                            </button>
                            <button class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white
                                       text-sm font-medium text-gray-700 hover:bg-gray-50">
                                2
                            </button>
                            <button class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white
                                       text-sm font-medium text-gray-700 hover:bg-gray-50">
                                3
                            </button>
                            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-gray-50
                                     text-sm font-medium text-gray-700">
                                ...
                            </span>
                            <button class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white
                                       text-sm font-medium text-gray-700 hover:bg-gray-50">
                                8
                            </button>
                            <button class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300
                                       bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span class="sr-only">Suivant</span>
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                          clip-rule="evenodd" />
                                </svg>
                            </button>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Vérification Don -->
<div id="verificationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[90%] max-w-5xl bg-white rounded-lg shadow-xl">
        <!-- En-tête -->
        <div class="flex justify-between items-center p-4 border-b border-gray-200">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Vérification du Don #DON-2024-001</h3>
                <p class="mt-1 text-sm text-gray-500">Vérifié par Dr. Ahmed le 15/03/2024</p>
            </div>
            <button onclick="closeVerificationModal()" class="text-gray-400 hover:text-gray-500">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Contenu avec défilement -->
        <div class="overflow-y-auto" style="max-height: 70vh;">
            <div class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Colonne gauche : Informations du don -->
                    <div class="space-y-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-900 mb-4">Informations du Donateur</h4>
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">Marie Dupont</p>
                                        <p class="text-sm text-gray-500">marie.dupont@email.com</p>
                                    </div>
                                </div>
                                <div class="text-sm">
                                    <p class="text-gray-500">Téléphone: <span class="text-gray-900">+212 6XX XX XX XX</span></p>
                                    <p class="text-gray-500">Profession: <span class="text-gray-900">Pharmacienne</span></p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-900 mb-4">Détails du Médicament</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Nom Commercial:</span>
                                    <span class="font-medium text-gray-900">Paracétamol 500mg</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">DCI:</span>
                                    <span class="font-medium text-gray-900">Paracétamol</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Forme:</span>
                                    <span class="font-medium text-gray-900">Comprimés</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Quantité:</span>
                                    <span class="font-medium text-gray-900">3 boîtes (90 comprimés)</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">N° de Lot:</span>
                                    <span class="font-medium text-gray-900">LOT123456</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Date de fabrication:</span>
                                    <span class="font-medium text-gray-900">01/2023</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Date d'expiration:</span>
                                    <span class="font-medium text-gray-900">12/2024</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Colonne droite : Critères de vérification -->
                    <div class="space-y-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-900 mb-4">Critères de Vérification</h4>
                            <div class="space-y-4">
                                <label class="flex items-center p-3 bg-white rounded-lg border border-gray-200">
                                    <input type="checkbox" class="h-4 w-4 text-green-600 rounded border-gray-300
                                           focus:ring-green-500">
                                    <span class="ml-3">
                                        <span class="block text-sm font-medium text-gray-900">Emballage intact</span>
                                        <span class="block text-sm text-gray-500">Vérifier l'état physique de l'emballage</span>
                                    </span>
                                </label>

                                <label class="flex items-center p-3 bg-white rounded-lg border border-gray-200">
                                    <input type="checkbox" class="h-4 w-4 text-green-600 rounded border-gray-300
                                           focus:ring-green-500">
                                    <span class="ml-3">
                                        <span class="block text-sm font-medium text-gray-900">Date de péremption valide</span>
                                        <span class="block text-sm text-gray-500">Minimum 6 mois avant expiration</span>
                                    </span>
                                </label>

                                <label class="flex items-center p-3 bg-white rounded-lg border border-gray-200">
                                    <input type="checkbox" class="h-4 w-4 text-green-600 rounded border-gray-300
                                           focus:ring-green-500">
                                    <span class="ml-3">
                                        <span class="block text-sm font-medium text-gray-900">Conservation appropriée</span>
                                        <span class="block text-sm text-gray-500">Température et conditions respectées</span>
                                    </span>
                                </label>

                                <label class="flex items-center p-3 bg-white rounded-lg border border-gray-200">
                                    <input type="checkbox" class="h-4 w-4 text-green-600 rounded border-gray-300
                                           focus:ring-green-500">
                                    <span class="ml-3">
                                        <span class="block text-sm font-medium text-gray-900">Notice présente</span>
                                        <span class="block text-sm text-gray-500">Notice d'utilisation incluse</span>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-900 mb-4">Notes de Vérification</h4>
                            <textarea rows="4"
                                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500"
                                    placeholder="Ajoutez vos observations..."></textarea>
                        </div>

                        <!-- Photos du don -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-900 mb-4">Photos du Don</h4>
                            <div class="grid grid-cols-3 gap-4">
                                <div class="relative aspect-square bg-gray-100 rounded-lg overflow-hidden">
                                    <img src="/path/to/image1.jpg" alt="Photo 1" class="object-cover w-full h-full">
                                </div>
                                <div class="relative aspect-square bg-gray-100 rounded-lg overflow-hidden">
                                    <img src="/path/to/image2.jpg" alt="Photo 2" class="object-cover w-full h-full">
                                </div>
                                <div class="relative aspect-square bg-gray-100 rounded-lg border-2 border-dashed
                                            border-gray-300 flex items-center justify-center">
                                    <button class="text-gray-500 hover:text-gray-700">
                                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pied de modal -->
        <div class="p-4 bg-gray-50 border-t border-gray-200">
            <div class="flex justify-between items-center">
                <div class="flex items-center text-yellow-600">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span class="text-sm font-medium">La décision est définitive après validation</span>
                </div>
                <div class="flex space-x-3">
                    <button onclick="refuserDon()"
                            class="px-4 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-lg
                                   hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2
                                   focus:ring-red-500">
                        Refuser le don
                    </button>
                    <button onclick="validerDon()"
                            class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg
                                   hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2
                                   focus:ring-green-500">
                        Valider le don
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openVerificationModal(donId) {
    document.getElementById('verificationModal').classList.remove('hidden');
    document.getElementById('verificationModal').classList.add('flex');
}

function closeVerificationModal() {
    document.getElementById('verificationModal').classList.add('hidden');
    document.getElementById('verificationModal').classList.remove('flex');
}

function validerDon() {
    // Vérifier que tous les critères sont cochés
    const checkboxes = document.querySelectorAll('#verificationModal input[type="checkbox"]');
    const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);

    if (!allChecked) {
        alert('Veuillez vérifier tous les critères avant de valider le don.');
        return;
    }

    // Logique de validation
    alert('Don validé avec succès !');
    closeVerificationModal();
}

function refuserDon() {
    if (confirm('Êtes-vous sûr de vouloir refuser ce don ?')) {
        // Logique de refus
        alert('Don refusé.');
        closeVerificationModal();
    }
}
</script>
@endsection
