@extends('dashPharmacie.layout')

@section('content')
<div class="dashboard-container p-6 bg-gray-100 rounded-lg min-h-screen">
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Gestion des Demandes de Dons</h1>

    <!-- Cards Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-blue-500">
            <h3 class="text-gray-500 text-sm">Demandes Totales</h3>
            <p class="text-2xl font-bold text-gray-700">45</p>
            <span class="text-green-500 text-xs">↑ 8% ce mois</span>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-green-500">
            <h3 class="text-gray-500 text-sm">Demandes Acceptées</h3>
            <p class="text-2xl font-bold text-gray-700">28</p>
            <span class="text-green-500 text-xs">↑ 12% ce mois</span>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-yellow-500">
            <h3 class="text-gray-500 text-sm">En Attente</h3>
            <p class="text-2xl font-bold text-gray-700">12</p>
            <span class="text-yellow-500 text-xs">→ Stable</span>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-red-500">
            <h3 class="text-gray-500 text-sm">Demandes Refusées</h3>
            <p class="text-2xl font-bold text-gray-700">5</p>
            <span class="text-red-500 text-xs">↓ 2% ce mois</span>
        </div>
    </div>

    <!-- Liste des Demandes -->
    <div class="space-y-4">
        <!-- Demande 1 - Nouvelle -->
        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-blue-500 relative">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <div class="flex items-center space-x-4 cursor-pointer" onclick="toggleDetails('dem1')">
                        <div class="bg-blue-100 p-2 rounded-full">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold">#DEM-0123 - Centre Hospitalier Saint-Louis</h3>
                            <p class="text-sm text-gray-500">15/03/2024 • Urgence: Haute</p>
                        </div>
                    </div>

                    <!-- Détails de la demande -->
                    <div id="dem1-details" class="hidden pl-12 mt-4">
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Médicaments demandés -->
                            <div class="col-span-2">
                                <h4 class="font-medium mb-2">Médicaments Demandés</h4>
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                                        <div>
                                            <p class="font-medium">Insuline NovoRapid</p>
                                            <p class="text-sm text-gray-500">10 stylos • Urgent</p>
                                        </div>
                                        <span class="text-sm bg-green-100 text-green-800 px-2 py-1 rounded">Disponible</span>
                                    </div>
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                                        <div>
                                            <p class="font-medium">Antibiotiques Amoxicilline</p>
                                            <p class="text-sm text-gray-500">100 boîtes • Standard</p>
                                        </div>
                                        <span class="text-sm bg-yellow-100 text-yellow-800 px-2 py-1 rounded">Stock limité</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Informations complémentaires -->
                            <div class="col-span-2">
                                <h4 class="font-medium mb-2">Informations</h4>
                                <div class="bg-gray-50 p-3 rounded">
                                    <p class="text-sm text-gray-600 mb-2">
                                        <span class="font-medium">Contact:</span> Dr. Marie Laurent
                                    </p>
                                    <p class="text-sm text-gray-600 mb-2">
                                        <span class="font-medium">Téléphone:</span> +33 1 23 45 67 89
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">Motif:</span> Rupture de stock urgente
                                    </p>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="col-span-2 flex space-x-4">
                                <button onclick="openValidationModal('dem1')"
                                        class="flex-1 bg-green-100 text-green-800 px-4 py-2 rounded hover:bg-green-200 transition-colors">
                                    Accepter la demande
                                </button>
                                <button class="flex-1 bg-red-100 text-red-800 px-4 py-2 rounded hover:bg-red-200 transition-colors">
                                    Refuser
                                </button>
                                <button class="flex-1 bg-gray-100 text-gray-800 px-4 py-2 rounded hover:bg-gray-200 transition-colors">
                                    Demander plus d'informations
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Validation -->
        <div id="validationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[90%] max-w-2xl bg-white rounded-lg shadow-xl">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Validation de la Demande #DEM-0123</h3>
                        <button onclick="closeValidationModal()" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <!-- Liste des médicaments à valider -->
                        <div class="space-y-3">
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-medium">Insuline NovoRapid</span>
                                    <span class="text-sm">x10</span>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <label class="flex items-center space-x-2 text-sm">
                                        <input type="checkbox" class="rounded border-gray-300 text-green-600">
                                        <span>Stock suffisant</span>
                                    </label>
                                    <label class="flex items-center space-x-2 text-sm">
                                        <input type="checkbox" class="rounded border-gray-300 text-green-600">
                                        <span>Validé pour don</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Notes de validation</label>
                            <textarea class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500"
                                    rows="3"
                                    placeholder="Ajoutez des notes si nécessaire..."></textarea>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end space-x-3 mt-6">
                            <button onclick="closeValidationModal()"
                                    class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                                Annuler
                            </button>
                            <button onclick="validateDemande()"
                                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                Valider la demande
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleDetails(demId) {
    const details = document.getElementById(`${demId}-details`);
    details.classList.toggle('hidden');
}

function openValidationModal(demId) {
    document.getElementById('validationModal').classList.remove('hidden');
}

function closeValidationModal() {
    document.getElementById('validationModal').classList.add('hidden');
}

function validateDemande() {
    // Logique de validation à implémenter
    closeValidationModal();
    alert('Demande validée avec succès !');
}
</script>
@endsection
