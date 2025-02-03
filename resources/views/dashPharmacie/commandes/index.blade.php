@extends('dashPharmacie.layout')

@section('content')
<div class="dashboard-container p-6 bg-gray-100 rounded-lg min-h-screen">
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Suivi des Commandes Patients</h1>

    <!-- Liste des Commandes -->
    <div class="space-y-4">
        <!-- Commande 1 -->
        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-blue-500 relative">
            <div class="flex justify-between items-start">
                <!-- Info Patient -->
                <div class="flex-1">
                    <div class="flex items-center space-x-4 cursor-pointer" onclick="toggleDetails('cmd1')">
                        <div class="bg-blue-100 p-2 rounded-full">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold">#CMD-0245 - Ahmed Touizi</h3>
                            <p class="text-sm text-gray-500">15/03/2024 10:30 • Ordonnance #ORD-7894</p>
                        </div>
                    </div>

                    <!-- Détails Cachés -->
                    <div id="cmd1-details" class="hidden pl-12 mt-4">
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Médicaments -->
                            <div class="col-span-2">
                                <h4 class="font-medium mb-2">Médicaments Prescrits</h4>
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                                        <div>
                                            <p class="font-medium">Paracétamol 500mg</p>
                                            <p class="text-sm text-gray-500">30 comprimés • 3 fois/jour</p>
                                        </div>
                                        <span class="text-sm bg-green-100 text-green-800 px-2 py-1 rounded">En stock</span>
                                    </div>
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                                        <div>
                                            <p class="font-medium">Vitamine C</p>
                                            <p class="text-sm text-gray-500">60 gélules • 1 fois/jour</p>
                                        </div>
                                        <span class="text-sm bg-red-100 text-red-800 px-2 py-1 rounded">Stock limité</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Stepper Interactif -->
                            <div class="col-span-2 mt-4">
                                <div class="stepper">
                                    <!-- Étape 1 - Réception -->
                                    <div class="step completed" onclick="changeStep('cmd1', 1)">
                                        <div class="step-circle">1</div>
                                        <div class="step-title">Réception</div>
                                    </div>

                                    <!-- Étape 2 - Vérification -->
                                    <div class="step current" onclick="changeStep('cmd1', 2)">
                                        <div class="step-circle">2</div>
                                        <div class="step-title">Vérification Ordonnance</div>
                                    </div>

                                    <!-- Étape 3 - Préparation -->
                                    <div class="step" onclick="changeStep('cmd1', 3)">
                                        <div class="step-circle">3</div>
                                        <div class="step-title">Préparation</div>
                                    </div>

                                    <!-- Étape 4 - Validation -->
                                    <div class="step" onclick="changeStep('cmd1', 4)">
                                        <div class="step-circle">4</div>
                                        <div class="step-title">Validation Finale</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions Rapides -->
                <div class="flex space-x-2">
                    <button class="p-2 hover:bg-gray-100 rounded" onclick="openVerificationModal('cmd1')">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

       <!-- Commande 1 - En cours de vérification -->
<div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-blue-500 relative">
    <div class="flex justify-between items-start">
        <div class="flex-1">
            <div class="flex items-center space-x-4 cursor-pointer" onclick="toggleDetails('cmd1')">
                <div class="bg-blue-100 p-2 rounded-full">
                    <i class="fas fa-clipboard-check text-blue-600"></i>
                </div>
                <div>
                    <h3 class="font-semibold">#CMD-0245 - Ahmed Touizi</h3>
                    <p class="text-sm text-gray-500">15/03/2024 10:30 • Ordonnance #ORD-7894</p>
                </div>
            </div>
            <!-- ... reste du contenu existant ... -->
        </div>
    </div>
</div>

<!-- Commande 2 - Prête pour retrait -->
<div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-green-500 relative">
    <div class="flex justify-between items-start">
        <div class="flex-1">
            <div class="flex items-center space-x-4 cursor-pointer" onclick="toggleDetails('cmd2')">
                <div class="bg-green-100 p-2 rounded-full">
                    <i class="fas fa-check text-green-600"></i>
                </div>
                <div>
                    <h3 class="font-semibold">#CMD-0244 - Sarah Benani</h3>
                    <p class="text-sm text-gray-500">15/03/2024 09:45 • Ordonnance #ORD-7893</p>
                </div>
            </div>

            <div id="cmd2-details" class="hidden pl-12 mt-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <h4 class="font-medium mb-2">Médicaments Prescrits</h4>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                                <div>
                                    <p class="font-medium">Amoxicilline 1g</p>
                                    <p class="text-sm text-gray-500">15 comprimés • 3 fois/jour</p>
                                </div>
                                <span class="text-sm bg-green-100 text-green-800 px-2 py-1 rounded">Préparé</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                                <div>
                                    <p class="font-medium">Doliprane 1000mg</p>
                                    <p class="text-sm text-gray-500">20 comprimés • 2 fois/jour</p>
                                </div>
                                <span class="text-sm bg-green-100 text-green-800 px-2 py-1 rounded">Préparé</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-2">
                        <div class="stepper">
                            <div class="step completed">
                                <div class="step-circle">1</div>
                                <div class="step-title">Réception</div>
                            </div>
                            <div class="step completed">
                                <div class="step-circle">2</div>
                                <div class="step-title">Vérification</div>
                            </div>
                            <div class="step completed">
                                <div class="step-circle">3</div>
                                <div class="step-title">Préparation</div>
                            </div>
                            <div class="step completed">
                                <div class="step-circle">4</div>
                                <div class="step-title">Prêt pour retrait</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Commande 3 - En attente de stock -->
<div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-yellow-500 relative">
    <div class="flex justify-between items-start">
        <div class="flex-1">
            <div class="flex items-center space-x-4 cursor-pointer" onclick="toggleDetails('cmd3')">
                <div class="bg-yellow-100 p-2 rounded-full">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
                <div>
                    <h3 class="font-semibold">#CMD-0243 - Karim Alami</h3>
                    <p class="text-sm text-gray-500">15/03/2024 09:15 • Ordonnance #ORD-7892</p>
                </div>
            </div>

            <div id="cmd3-details" class="hidden pl-12 mt-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <h4 class="font-medium mb-2">Médicaments Prescrits</h4>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                                <div>
                                    <p class="font-medium">Insuline NovoRapid</p>
                                    <p class="text-sm text-gray-500">2 stylos • selon prescription</p>
                                </div>
                                <span class="text-sm bg-yellow-100 text-yellow-800 px-2 py-1 rounded">En attente</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                                <div>
                                    <p class="font-medium">Bandelettes glycémie</p>
                                    <p class="text-sm text-gray-500">2 boîtes</p>
                                </div>
                                <span class="text-sm bg-green-100 text-green-800 px-2 py-1 rounded">Disponible</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-2">
                        <div class="stepper">
                            <div class="step completed">
                                <div class="step-circle">1</div>
                                <div class="step-title">Réception</div>
                            </div>
                            <div class="step completed">
                                <div class="step-circle">2</div>
                                <div class="step-title">Vérification</div>
                            </div>
                            <div class="step current">
                                <div class="step-circle">3</div>
                                <div class="step-title">En attente de stock</div>
                            </div>
                            <div class="step">
                                <div class="step-circle">4</div>
                                <div class="step-title">Finalisation</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Commande 4 - Annulée -->
<div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-red-500 relative">
    <div class="flex justify-between items-start">
        <div class="flex-1">
            <div class="flex items-center space-x-4">
                <div class="bg-red-100 p-2 rounded-full">
                    <i class="fas fa-times text-red-600"></i>
                </div>
                <div>
                    <h3 class="font-semibold">#CMD-0242 - Fatima Zahra</h3>
                    <p class="text-sm text-gray-500">15/03/2024 08:30 • Ordonnance #ORD-7891</p>
                    <p class="text-sm text-red-500 mt-1">Annulée par le patient</p>
                </div>
            </div>
        </div>
    </div>
</div>
    </div>

   <!-- Modal de Vérification -->
<div id="verificationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center">
    <div class="bg-white rounded-xl p-6 w-[800px] max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold">Validation de l'ordonnance #ORD-7894</h3>
            <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="grid grid-cols-2 gap-6">
            <!-- Colonne gauche : Aperçu de l'ordonnance -->
            <div class="col-span-1">
                <h4 class="text-sm font-medium mb-3">Ordonnance PDF</h4>
                <div class="border border-gray-200 rounded-lg p-2">
                    <!-- Aperçu PDF -->
                    <div class="aspect-[3/4] bg-gray-50 rounded-lg mb-3">
                        <embed
                            src="/storage/ordonnances/ORD-7894.pdf"
                            type="application/pdf"
                            class="w-full h-full rounded-lg"
                        />
                    </div>
                    <!-- Actions PDF -->
                    <div class="flex justify-between items-center">
                        <a href="/storage/ordonnances/ORD-7894.pdf"
                           target="_blank"
                           class="text-sm text-green-800 hover:text-green-700">
                            <i class="fas fa-external-link-alt mr-1"></i>
                            Ouvrir en grand
                        </a>
                        <a href="/storage/ordonnances/ORD-7894.pdf"
                           download
                           class="text-sm text-green-800 hover:text-green-700">
                            <i class="fas fa-download mr-1"></i>
                            Télécharger
                        </a>
                    </div>
                </div>
            </div>

            <!-- Colonne droite : Validation -->
            <div class="col-span-1">
                <h4 class="text-sm font-medium mb-3">Médicaments à valider</h4>
                <div class="space-y-4">
                    <!-- Liste des médicaments -->
                    <div class="space-y-3">
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-medium">Paracétamol 500mg</span>
                                <span class="text-sm">x30</span>
                            </div>
                            <div class="flex items-center space-x-4">
                                <label class="flex items-center space-x-2 text-sm">
                                    <input type="checkbox" class="rounded border-gray-300 text-green-800
                                           focus:ring-green-800">
                                    <span>Stock disponible</span>
                                </label>
                                <label class="flex items-center space-x-2 text-sm">
                                    <input type="checkbox" class="rounded border-gray-300 text-green-800
                                           focus:ring-green-800">
                                    <span>Dosage conforme</span>
                                </label>
                            </div>
                        </div>

                        <div class="p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-medium">Vitamine C</span>
                                <span class="text-sm">x60</span>
                            </div>
                            <div class="flex items-center space-x-4">
                                <label class="flex items-center space-x-2 text-sm">
                                    <input type="checkbox" class="rounded border-gray-300 text-green-800
                                           focus:ring-green-800">
                                    <span>Stock disponible</span>
                                </label>
                                <label class="flex items-center space-x-2 text-sm">
                                    <input type="checkbox" class="rounded border-gray-300 text-green-800
                                           focus:ring-green-800">
                                    <span>Dosage conforme</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-medium mb-2">Notes de validation</label>
                        <textarea
                            class="w-full rounded-lg border-gray-300 focus:ring-green-800 focus:border-green-800"
                            rows="3"
                            placeholder="Ajoutez des notes si nécessaire..."></textarea>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex justify-end space-x-3 pt-4 border-t">
                        <button onclick="closeModal()"
                                class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200
                                       transition-colors duration-200">
                            Annuler
                        </button>
                        <button onclick="validatePrescription()"
                                class="px-4 py-2 bg-green-800 text-white rounded-lg hover:bg-green-700
                                       transition-colors duration-200">
                            Valider l'ordonnance
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function validatePrescription() {
    // Vérifier que toutes les cases sont cochées
    const checkboxes = document.querySelectorAll('#verificationModal input[type="checkbox"]');
    const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);

    if (!allChecked) {
        alert('Veuillez vérifier tous les éléments avant de valider.');
        return;
    }

    // Logique de validation
    closeModal();
    // Mettre à jour le statut de la commande
    changeStep('cmd1', 3); // Passer à l'étape suivante
}
</script>
</div>

<style>
.stepper {
    position: relative;
    padding-left: 2rem;
}

.step {
    position: relative;
    margin-bottom: 1.5rem;
    cursor: pointer;
    transition: all 0.3s;
}

.step:hover .step-circle {
    transform: scale(1.1);
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
    transition: all 0.3s;
}

.step.completed .step-circle {
    background: #3b82f6;
    color: white;
}

.step.current .step-circle {
    background: #f59e0b;
    color: white;
    box-shadow: 0 0 0 3px #fde68a;
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
    transition: color 0.3s;
}

.step:hover .step-title {
    color: #3b82f6;
}
</style>

<script>
function toggleDetails(cmdId) {
    const details = document.getElementById(`${cmdId}-details`);
    details.classList.toggle('hidden');
}

let currentCommand = null;

function openVerificationModal(cmdId) {
    currentCommand = cmdId;
    document.getElementById('verificationModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('verificationModal').classList.add('hidden');
}

function validateStep() {
    // Logique de validation à implémenter
    closeModal();
    alert('Validation réussie !');
}

function changeStep(cmdId, stepNumber) {
    const steps = document.querySelectorAll(`#${cmdId}-details .step`);
    steps.forEach((step, index) => {
        step.classList.remove('current', 'completed');
        if(index + 1 < stepNumber) {
            step.classList.add('completed');
        }
        if(index + 1 === stepNumber) {
            step.classList.add('current');
        }
    });
}
</script>
@endsection
