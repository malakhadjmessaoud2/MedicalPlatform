@extends('dashPharmacie.layout')

@section('content')
<div class="dashboard-container p-8 bg-gray-100 rounded-lg">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Gestion du Stock</h1>
        <div class="flex items-center space-x-4">
            <!-- Menu déroulant Import/Export -->
            <div class="relative inline-block text-left">
                <button onclick="toggleDropdown()"
                        class="flex items-center px-4 py-2 bg-white text-emerald-800 rounded-full border border-emerald-800
                               hover:bg-emerald-50 transition-all duration-300 shadow-sm">
                    <i class="fas fa-file-import mr-2"></i>
                    <span class="font-medium">Import/Export</span>
                    <i class="fas fa-chevron-down ml-2 text-sm"></i>
                </button>

                <!-- Menu déroulant -->
                <div id="dropdownMenu"
                     class="hidden absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                    <div class="py-1">
                        <a href="#"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-800">
                            <i class="fas fa-file-pdf mr-2 text-red-500"></i>
                            PDF
                        </a>
                        <a href="#"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-800">
                            <i class="fas fa-file-excel mr-2 text-green-500"></i>
                            Excel
                        </a>
                        <a href="#"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-800">
                            <i class="fas fa-file-csv mr-2 text-blue-500"></i>
                            CSV
                        </a>
                    </div>
                </div>
            </div>

            <!-- Bouton Nouveau Médicament -->
            <button class="flex items-center px-4 py-2 bg-emerald-800 text-white rounded-full hover:bg-emerald-900
                         transition-all duration-300 shadow-sm">
                <span class="text-lg mr-2">+</span>
                <span class="font-medium">Nouveau Médicament</span>
            </button>
        </div>
    </div>

    <script>
    // Fonction pour gérer l'affichage du menu déroulant
    function toggleDropdown() {
        const dropdownMenu = document.getElementById('dropdownMenu');
        dropdownMenu.classList.toggle('hidden');
    }

    // Fermer le menu si on clique en dehors
    document.addEventListener('click', function(event) {
        const dropdown = document.querySelector('.relative.inline-block');
        const dropdownMenu = document.getElementById('dropdownMenu');

        if (!dropdown.contains(event.target)) {
            dropdownMenu.classList.add('hidden');
        }
    });
    </script>

    <style>
    /* Assurez-vous que ces styles sont bien chargés */
    .text-emerald-800 {
        color: rgb(6, 95, 70);
    }

    .bg-emerald-800 {
        background-color: rgb(6, 95, 70);
    }

    .border-emerald-800 {
        border-color: rgb(6, 95, 70);
    }

    .hover\:bg-emerald-50:hover {
        background-color: rgb(236, 253, 245);
    }

    .hover\:bg-emerald-900:hover {
        background-color: rgb(6, 78, 59);
    }
    </style>
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Carte 1 : Médicaments en stock -->
        <div class="bg-white p-6 rounded-xl shadow-sm stats-card">
            <div class="flex items-center">
                <div class="bg-primary-light p-3 rounded-lg mr-4">
                    <svg class="w-8 h-8 text-primary-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Médicaments en stock</p>
                    <p class="text-2xl font-bold">1,234</p>
                </div>
            </div>
        </div>

        <!-- Carte 2 : En réapprovisionnement -->
        <div class="bg-white p-6 rounded-xl shadow-sm stats-card">
            <div class="flex items-center">
                <div class="bg-primary-light p-3 rounded-lg mr-4">
                    <svg class="w-8 h-8 text-primary-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">En réapprovisionnement</p>
                    <p class="text-2xl font-bold">56</p>
                </div>
            </div>
        </div>

        <!-- Carte 3 : Expirant ce mois -->
        <div class="bg-white p-6 rounded-xl shadow-sm stats-card">
            <div class="flex items-center">
                <div class="bg-primary-light p-3 rounded-lg mr-4">
                    <svg class="w-8 h-8 text-primary-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Expirant ce mois</p>
                    <p class="text-2xl font-bold">23</p>
                </div>
            </div>
        </div>

        <!-- Carte 4 : Alertes stock -->
        <div class="bg-white p-6 rounded-xl shadow-sm stats-card">
            <div class="flex items-center">
                <div class="bg-primary-light p-3 rounded-lg mr-4">
                    <svg class="w-8 h-8 text-primary-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Alertes stock</p>
                    <p class="text-2xl font-bold">12</p>
                </div>
            </div>
        </div>
    </div>

   <!-- Tableau des médicaments -->
<!-- Tableau des médicaments -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8">
    <!-- En-tête du tableau -->
    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-emerald-900">Liste des médicaments</h3>
        <div class="flex items-center space-x-4">
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                    <i class="fas fa-search text-gray-400"></i>
                </span>
                <input type="text"
                       placeholder="Rechercher..."
                       class="pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-1
                              focus:ring-emerald-900 focus:border-emerald-900 w-64">
            </div>
            <button class="flex items-center px-4 py-2 bg-gray-50 text-emerald-900 rounded-lg
                         hover:bg-gray-100 transition-colors duration-200">
                <i class="fas fa-filter mr-2"></i>
                Filtres
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <!-- En-tête du tableau -->
            <thead class="bg-green-800  text-white">
                <tr>
                    <th class="text-left py-4 px-6 font-medium text-sm uppercase tracking-wider">Nom</th>
                    <th class="text-left py-4 px-6 font-medium text-sm uppercase tracking-wider">DCI</th>
                    <th class="text-left py-4 px-6 font-medium text-sm uppercase tracking-wider">Lot</th>
                    <th class="text-left py-4 px-6 font-medium text-sm uppercase tracking-wider">Expiration</th>
                    <th class="text-left py-4 px-6 font-medium text-sm uppercase tracking-wider">Stock</th>
                    <th class="text-left py-4 px-6 font-medium text-sm uppercase tracking-wider">Actions</th>
                </tr>
            </thead>

            <!-- Corps du tableau -->
            <tbody class="divide-y divide-gray-200">
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="py-4 px-6">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center mr-3">
                                <i class="fas fa-pills text-emerald-900"></i>
                            </div>
                            <span class="font-medium text-gray-700">Paracétamol 500mg</span>
                        </div>
                    </td>
                    <td class="py-4 px-6 text-gray-500">Paracetamol</td>
                    <td class="py-4 px-6 text-gray-500">P2345X</td>
                    <td class="py-4 px-6">
                        <span class="px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-700">
                            15/12/2024
                        </span>
                    </td>
                    <td class="py-4 px-6">
                        <span class="px-3 py-1 rounded-full text-sm bg-emerald-100 text-emerald-800 font-medium">
                            152 unités
                        </span>
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex space-x-2">
                            <button class="p-2 text-emerald-900 hover:bg-gray-100 rounded-lg
                                       transition-colors duration-200">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="p-2 text-emerald-700 hover:bg-gray-100 rounded-lg
                                       transition-colors duration-200">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="p-2 text-red-600 hover:bg-gray-100 rounded-lg
                                       transition-colors duration-200">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- Exemple d'une autre ligne avec un stock faible -->
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="py-4 px-6">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center mr-3">
                                <i class="fas fa-pills text-red-600"></i>
                            </div>
                            <span class="font-medium text-gray-700">Amoxicilline 1g</span>
                        </div>
                    </td>
                    <td class="py-4 px-6 text-gray-500">Amoxicilline</td>
                    <td class="py-4 px-6 text-gray-500">A789B</td>
                    <td class="py-4 px-6">
                        <span class="px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-700">
                            20/11/2024
                        </span>
                    </td>
                    <td class="py-4 px-6">
                        <span class="px-3 py-1 rounded-full text-sm bg-red-100 text-red-600 font-medium">
                            8 unités
                        </span>
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex space-x-2">
                            <button class="p-2 text-emerald-900 hover:bg-gray-100 rounded-lg
                                       transition-colors duration-200">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="p-2 text-emerald-700 hover:bg-gray-100 rounded-lg
                                       transition-colors duration-200">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="p-2 text-red-600 hover:bg-gray-100 rounded-lg
                                       transition-colors duration-200">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

<!-- Pagination -->
<div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
    <div class="text-sm text-gray-700">
        Affichage de <span class="font-medium">1</span> à <span class="font-medium">10</span> sur <span class="font-medium">50</span> entrées
    </div>
    <div class="flex items-center gap-2">
        <!-- Bouton Précédent -->
        <button class="inline-flex items-center px-3 py-2 rounded-lg text-gray-700 bg-white border border-gray-200
                     hover:bg-green-50 hover:text-green-800 hover:border-green-800 transition-all duration-200">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Précédent
        </button>

        <!-- Numéros de page -->
        <div class="hidden sm:flex items-center gap-1">
            <button class="px-3 py-2 rounded-lg bg-green-800 text-white font-medium hover:bg-green-700 transition-colors">
                1
            </button>
            <button class="px-3 py-2 rounded-lg hover:bg-green-50 text-gray-700 font-medium transition-colors">
                2
            </button>
            <button class="px-3 py-2 rounded-lg hover:bg-green-50 text-gray-700 font-medium transition-colors">
                3
            </button>
            <span class="px-3 py-2 text-gray-500">...</span>
            <button class="px-3 py-2 rounded-lg hover:bg-green-50 text-gray-700 font-medium transition-colors">
                8
            </button>
        </div>

        <!-- Bouton Suivant -->
        <button class="inline-flex items-center px-3 py-2 rounded-lg text-gray-700 bg-white border border-gray-200
                     hover:bg-green-50 hover:text-green-800 hover:border-green-800 transition-all duration-200">
            Suivant
            <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>

        <!-- Sélecteur de pages par page (optionnel) -->
        <select class="ml-2 px-3 py-2 bg-white border border-gray-200 rounded-lg text-gray-700
                     hover:border-green-800 focus:outline-none focus:ring-2 focus:ring-green-800 focus:border-green-800">
            <option value="10">10 / page</option>
            <option value="25">25 / page</option>
            <option value="50">50 / page</option>
            <option value="100">100 / page</option>
        </select>
    </div>
</div>
</div>
    <!-- Historique des modifications -->
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold">Historique des Modifications</h3>
        </div>
        <div class="divide-y">
            <div class="p-4 hover:bg-gray-50 flex items-center history-entry">
                <div class="bg-primary-light p-2 rounded-lg mr-4">
                    <svg class="w-6 h-6 text-primary-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </div>
                <div>
                    <p class="font-medium">Ajout de stock - Paracétamol</p>
                    <p class="text-sm text-gray-500">+150 unités par Dr. Smith • 15/06/2024 14:30</p>
                </div>

            </div>
                    <!-- Modification de stock -->
        <div class="p-4 hover:bg-gray-50 flex items-center history-entry">
            <div class="bg-blue-600 p-3 rounded-lg mr-4">
                <i class="fas fa-edit text-emerald-50"></i>
            </div>
            <div class="flex-1">
                <p class="font-medium text-emerald-50">Modification - Amoxicilline</p>
                <p class="text-sm text-emerald-300">Mise à jour du lot #A789 • 15/06/2024 13:45</p>
            </div>

        </div>

        <!-- Suppression de stock -->
        <div class="p-4 hover:bg-gray-50 flex items-center history-entry">
            <div class="bg-red-600 p-3 rounded-lg mr-4">
                <i class="fas fa-trash text-emerald-50"></i>
            </div>
            <div class="flex-1">
                <p class="font-medium text-emerald-50">Suppression - Ibuprofène</p>
                <p class="text-sm text-emerald-300">Lot expiré #I456 • 15/06/2024 11:20</p>
            </div>

        </div>

        <!-- Sortie de stock -->
        <div class="p-4 hover:bg-gray-50 flex items-center history-entry">
            <div class="bg-amber-600 p-3 rounded-lg mr-4">
                <i class="fas fa-arrow-right text-emerald-50"></i>
            </div>
            <div class="flex-1">
                <p class="font-medium text-emerald-50">Sortie de stock - Doliprane</p>
                <p class="text-sm text-emerald-300">-50 unités pour Service Pédiatrie • 15/06/2024 10:15</p>
            </div>
            <span class="px-3 py-1 bg-amber-500/20 text-amber-100 rounded-full text-sm">
                -50
            </span>
        </div>

        <!-- Alerte stock bas -->
        <div class="p-4 hover:bg-gray-50 flex items-center history-entry">
            <div class="bg-orange-600 p-3 rounded-lg mr-4">
                <i class="fas fa-exclamation-triangle text-emerald-50"></i>
            </div>
            <div class="flex-1">
                <p class="font-medium text-emerald-50">Alerte Stock - Insuline</p>
                <p class="text-sm text-emerald-300">Stock minimum atteint (10 unités) • 15/06/2024 09:30</p>
            </div>
            <span class="px-3 py-1 bg-orange-500/20 text-orange-100 rounded-full text-sm">
                Alerte
            </span>
        </div>

        <!-- Stock périmé -->
        <div class="p-4 hover:bg-gray-50 flex items-center history-entry">
            <div class="bg-purple-600 p-3 rounded-lg mr-4">
                <i class="fas fa-calendar-times text-emerald-50"></i>
            </div>
            <div class="flex-1">
                <p class="font-medium text-emerald-50">Péremption - Vaccin Covid</p>
                <p class="text-sm text-emerald-300">Lot #V123 expiré • 15/06/2024 08:45</p>
            </div>
            <span class="px-3 py-1 bg-purple-500/20 text-purple-100 rounded-full text-sm">
                Périmé
            </span>
        </div>
            <!-- Plus d'entrées d'historique -->
        </div>
    </div>
</div>

<style>
    .bg-primary-dark { background-color: #13452d; }
    .bg-primary-medium { background-color: #227d53; }
    .bg-primary-light { background-color: #5fbd92; }
    .text-primary-dark { color: #13452d; }
    .text-primary-medium { color: #227d53; }
    .hover\:bg-primary-dark:hover { background-color: #0f3a23; }

    /* Animation for stats cards */
    .stats-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    /* Animation for buttons */
    .button-animation {
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .button-animation:hover {
        background-color: #0f3a23;
        transform: scale(1.05);
    }

    /* Animation for table rows */
    .table-row {
        transition: background-color 0.3s ease;
    }

    .table-row:hover {
        background-color: #f0fdf4;
    }

    /* Animation for history entries */
    .history-entry {
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .history-entry:hover {
        background-color: #f0fdf4;
        transform: translateX(5px);
    }

    /* Loading spinner animation */
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .loading-spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #227d53;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
    }

    /* Fade-in animation */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    /* Slide-in animation */
    @keyframes slideIn {
        from { transform: translateX(-100%); }
        to { transform: translateX(0); }
    }

    .slide-in {
        animation: slideIn 0.5s ease-out;
    }

    /* Pulse animation */
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    .pulse {
        animation: pulse 1.5s infinite;
    }
</style>

@endsection
