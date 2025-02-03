@extends('dashPharmacie.layout')

@section('content')
<div class="dashboard-container p-4 bg-gray-100 rounded-lg min-h-screen">
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Gestion des dons reçus</h1>

    <!-- Cards Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-blue-500">
            <h3 class="text-gray-500 text-sm">Dons Totaux</h3>
            <p class="text-2xl font-bold text-gray-700">156</p>
            <span class="text-green-500 text-xs">↑ 12% vs mois dernier</span>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-green-500">
            <h3 class="text-gray-500 text-sm">Médicaments Disponibles</h3>
            <p class="text-2xl font-bold text-gray-700">1,234</p>
            <span class="text-green-500 text-xs">↑ 5% vs mois dernier</span>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-yellow-500">
            <h3 class="text-gray-500 text-sm">Dons Expirés</h3>
            <p class="text-2xl font-bold text-gray-700">23</p>
            <span class="text-red-500 text-xs">↓ 3% vs mois dernier</span>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-red-500">
            <h3 class="text-gray-500 text-sm">En Attente</h3>
            <p class="text-2xl font-bold text-gray-700">15</p>
            <span class="text-yellow-500 text-xs">→ Stable</span>
        </div>
    </div>

    <!-- Tableau des Dons -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-700">Derniers dons reçus</h2>
            <button class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nouveau Don
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Donateur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Médicament</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expiration</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4">Alexandra Deff</td>
                        <td class="px-6 py-4">Paracétamol 500mg</td>
                        <td class="px-6 py-4">200 comprimés</td>
                        <td class="px-6 py-4">15 Dec 2024</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Disponible</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4">David Oshodl</td>
                        <td class="px-6 py-4">Ibuprofène 200mg</td>
                        <td class="px-6 py-4">150 comprimés</td>
                        <td class="px-6 py-4">01 Nov 2024</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Expire bientôt</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4">Edivin Adeniko</td>
                        <td class="px-6 py-4">Vitamine C</td>
                        <td class="px-6 py-4">50 flacons</td>
                        <td class="px-6 py-4">20 Jan 2025</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">En vérification</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Double Colonne Bas de Page -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Calendrier des Expirations -->
        <div class="bg-white p-4 rounded-lg shadow-sm">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Calendrier d'expiration</h2>
            <div class="p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center mb-3">
                    <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                    <span class="text-sm">01 Dec 2024 - Ibuprofène (50 unités)</span>
                </div>
                <div class="flex items-center mb-3">
                    <div class="w-2 h-2 bg-orange-500 rounded-full mr-2"></div>
                    <span class="text-sm">15 Dec 2024 - Paracétamol (200 unités)</span>
                </div>
            </div>
        </div>

        <!-- Graphique de Distribution -->
        <div class="bg-white p-4 rounded-lg shadow-sm">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Répartition des dons</h2>
            <div class="h-32 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
                [Graphique à implémenter]
            </div>
        </div>
    </div>
</div>
@endsection
