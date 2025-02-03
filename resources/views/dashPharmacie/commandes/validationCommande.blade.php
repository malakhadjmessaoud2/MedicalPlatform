@extends('dashPharmacie.layout')

@section('content')
<div class="dashboard-container p-6 bg-gray-50 min-h-screen">
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Validation des Commandes Patients</h1>

    <!-- Liste des Commandes Reçues -->
    <div class="grid grid-cols-1 gap-4 mb-8">
        <!-- Commande 1 -->
        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-blue-500">
            <div class="flex justify-between items-start">
                <!-- Info Patient -->
                <div class="flex-1">
                    <div class="flex items-center space-x-4">
                        <div class="bg-blue-100 p-2 rounded-full">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold">N°CMD-0245 - Ahmed Touizi</h3>
                            <p class="text-sm text-gray-500">Date: 15/03/2024 10:30</p>
                        </div>
                    </div>

                    <!-- Détails Ordonnance -->
                    <div class="mt-4 pl-12">
                        <div class="flex items-center space-x-2 text-sm mb-2">
                            <span class="font-medium">Ordonnance:</span>
                            <span class="text-gray-600">Dr. Leila Mansouri</span>
                            <span class="px-2 py-1 bg-gray-100 rounded-full">N°ORD-7894</span>
                        </div>

                        <!-- Liste Médicaments -->
                        <div class="grid grid-cols-2 gap-2">
                            <div class="flex items-center space-x-2 p-2 bg-gray-50 rounded">
                                <span class="text-blue-600">•</span>
                                <span>Paracétamol 500mg</span>
                                <span class="text-gray-500">x30 comprimés</span>
                            </div>
                            <div class="flex items-center space-x-2 p-2 bg-gray-50 rounded">
                                <span class="text-blue-600">•</span>
                                <span>Vitamine C</span>
                                <span class="text-gray-500">x60 gélules</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stepper de Validation -->
                <div class="w-64 ml-4">
                    <div class="stepper">
                        <!-- Étape 1 -->
                        <div class="step completed">
                            <div class="step-circle">1</div>
                            <div class="step-title">Réception</div>
                        </div>

                        <!-- Étape 2 -->
                        <div class="step current">
                            <div class="step-circle">2</div>
                            <div class="step-title">Vérification Ordonnance</div>
                        </div>

                        <!-- Étape 3 -->
                        <div class="step">
                            <div class="step-circle">3</div>
                            <div class="step-title">Préparation</div>
                        </div>

                        <!-- Étape 4 -->
                        <div class="step">
                            <div class="step-circle">4</div>
                            <div class="step-title">Validation Finale</div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-4 space-y-2">
                        <button class="w-full px-4 py-2 bg-blue-100 text-blue-800 rounded-lg hover:bg-blue-200 text-sm">
                            Vérifier l'ordonnance
                        </button>
                        <button class="w-full px-4 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 text-sm">
                            Demander des précisions
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Commande 2 -->
        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-yellow-500">
            <!-- Structure similaire avec état différent -->
        </div>
    </div>

    <!-- Modal de Vérification -->
    <div class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-xl p-6 w-96">
            <h3 class="text-lg font-semibold mb-4">Vérification d'ordonnance</h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Médicament prescrit</label>
                    <input type="text" class="w-full p-2 border rounded" value="Paracétamol 500mg x30" disabled>
                </div>

                <div class="flex items-center space-x-2">
                    <input type="checkbox" class="rounded">
                    <label class="text-sm">Stock disponible</label>
                </div>

                <div class="flex items-center space-x-2">
                    <input type="checkbox" class="rounded">
                    <label class="text-sm">Dose conforme</label>
                </div>

                <div class="flex justify-end space-x-2 mt-4">
                    <button class="px-4 py-2 bg-gray-100 rounded">Annuler</button>
                    <button class="px-4 py-2 bg-blue-600 text-white rounded">Confirmer</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stepper {
    position: relative;
    padding-left: 2rem;
}

.step {
    position: relative;
    margin-bottom: 1.5rem;
}

.step-circle {
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    background: #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute;
    left: -2rem;
    top: 0;
}

.step.completed .step-circle {
    background: #3b82f6;
    color: white;
}

.step.current .step-circle {
    background: #f59e0b;
    color: white;
}

.step::after {
    content: '';
    position: absolute;
    left: -1.7rem;
    top: 2rem;
    width: 2px;
    height: calc(100% + 1rem);
    background: #e5e7eb;
}

.step:last-child::after {
    display: none;
}

.step-title {
    font-size: 0.875rem;
    margin-left: 0.5rem;
}
</style>
@endsection
