@extends('dashMedecin.layout')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection

@section('content')
<div class="p-4 sm:p-8 bg-[#e4e4e4] min-h-screen">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">
                🏥 Génération IA - Médecine Ambulatoire
            </h2>

        </div>

        <!-- Consultation Info -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <h3 class="text-lg font-semibold mb-3">Informations de la Consultation</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Patient</label>
                    <p class="text-gray-900">{{ $consultation->rendezVous->patient->prenom }} {{ $consultation->rendezVous->patient->nom }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date</label>
                    <p class="text-gray-900">{{ \Carbon\Carbon::parse($consultation->date)->format('d/m/Y') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Type</label>
                    <p class="text-gray-900">{{ ucfirst($consultation->type) }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Motif</label>
                    <p class="text-gray-900">{{ $consultation->motif ?? 'Non spécifié' }}</p>
                </div>
            </div>
        </div>

        <!-- AI Generation Section -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 mb-6 border border-blue-200">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-robot text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Génération Automatique de Compte-Rendu</h3>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    L'IA analyse les informations de la consultation et génère automatiquement un compte-rendu complet
                    incluant le diagnostic, le traitement, les recommandations et le plan de suivi.
                </p>
            </div>

            <div class="text-center">
                <button id="generateButton" onclick="generateTeleconsultation()"
                        class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-bold py-4 px-8 rounded-xl transition duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <i class="fas fa-magic mr-3"></i>
                    <span id="buttonText">Générer le Compte-Rendu Complet</span>
                </button>
                <p id="buttonDescription" class="text-sm text-gray-500 mt-3">
                    <i class="fas fa-clock mr-1"></i>
                    Génération en 10-15 secondes
                </p>
            </div>
        </div>

        <!-- Loading Indicator -->
        <div id="loadingIndicator" class="hidden bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-xl p-6 mb-6">
            <div class="text-center">
                <div class="w-12 h-12 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <div class="animate-spin rounded-full h-6 w-6 border-2 border-white border-t-transparent"></div>
                </div>
                <h4 class="text-lg font-semibold text-gray-800 mb-2">Génération en cours...</h4>
                <p class="text-gray-600">L'IA analyse la consultation et génère le compte-rendu</p>
            </div>
        </div>

        <!-- Results Display -->
        <div id="resultsContainer" class="hidden">
            <!-- Téléconsultation Results -->
            <div id="teleconsultationResult" class="hidden mb-8">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-check text-white"></i>
                            </div>
                            <h3 class="text-xl font-bold text-green-800">Compte-Rendu Généré avec Succès</h3>
                        </div>
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                            <i class="fas fa-robot mr-1"></i>IA
                        </span>
                    </div>

                    <div class="bg-white rounded-lg p-6 mb-6 shadow-sm border">
                        <div class="flex items-center mb-4">
                            <i class="fas fa-file-medical text-blue-500 mr-2"></i>
                            <h4 class="text-lg font-semibold text-gray-800">Compte-Rendu de Téléconsultation</h4>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <pre id="teleconsultationContent" class="whitespace-pre-wrap text-sm text-gray-800 leading-relaxed"></pre>
                        </div>
                    </div>


                </div>
            </div>

        </div>

        <!-- Error Display -->
        <div id="errorContainer" class="hidden bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 rounded-xl p-6 mb-6">
            <div class="text-center">
                <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-white"></i>
                </div>
                <h4 class="text-lg font-semibold text-red-800 mb-2">Erreur de Génération</h4>
                <p id="errorMessage" class="text-red-700"></p>
                <button onclick="location.reload()"
                        class="mt-4 bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg font-medium transition duration-200">
                    <i class="fas fa-refresh mr-2"></i>
                    Réessayer
                </button>
            </div>
        </div>

        <!-- AI Information -->
        <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl p-6 border border-gray-200">
            <div class="text-center mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-brain text-white"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Intelligence Artificielle Médicale</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div class="text-center p-4 bg-white rounded-lg shadow-sm">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-shield-alt text-white text-xs"></i>
                    </div>
                    <p class="font-medium text-gray-800">Sécurisé</p>
                    <p class="text-gray-600">Données protégées</p>
                </div>
                <div class="text-center p-4 bg-white rounded-lg shadow-sm">
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-bolt text-white text-xs"></i>
                    </div>
                    <p class="font-medium text-gray-800">Rapide</p>
                    <p class="text-gray-600">Génération instantanée</p>
                </div>
                <div class="text-center p-4 bg-white rounded-lg shadow-sm">
                    <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-graduation-cap text-white text-xs"></i>
                    </div>
                    <p class="font-medium text-gray-800">Expert</p>
                    <p class="text-gray-600">Basé sur les meilleures pratiques</p>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
const consultationId = {{ $consultation->id }};

// Charger les données existantes au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    loadExistingData();
});

function showLoading() {
    document.getElementById('loadingIndicator').classList.remove('hidden');
    document.getElementById('resultsContainer').classList.add('hidden');
    document.getElementById('errorContainer').classList.add('hidden');
}

function hideLoading() {
    document.getElementById('loadingIndicator').classList.add('hidden');
}

function showError(message) {
    hideLoading();
    document.getElementById('errorMessage').textContent = message;
    document.getElementById('errorContainer').classList.remove('hidden');
}

function showResults() {
    hideLoading();
    document.getElementById('resultsContainer').classList.remove('hidden');
}

// Fonction pour charger les données existantes
function loadExistingData() {
    fetch(`/medecin/ambulatory-ai/consultations/${consultationId}/existing-data`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Afficher les données existantes
            displayExistingReport(data.data);
        } else {
            // Pas de données existantes, afficher l'interface normale
            console.log('Aucun compte-rendu IA existant pour cette consultation');
        }
    })
    .catch(error => {
        console.log('Erreur lors du chargement des données existantes:', error);
        // En cas d'erreur, continuer avec l'interface normale
    });
}

// Fonction pour afficher le rapport existant
function displayExistingReport(data) {
    // Afficher le contenu du compte-rendu
    document.getElementById('teleconsultationContent').textContent = data.teleconsultation_report;
    document.getElementById('teleconsultationResult').classList.remove('hidden');

    // Afficher les informations de génération
    const resultContainer = document.getElementById('teleconsultationResult');
    const infoDiv = document.createElement('div');
    infoDiv.className = 'bg-blue-100 border border-blue-300 rounded-lg p-3 mb-4';
    infoDiv.innerHTML = `
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                <span class="text-blue-800 font-medium">Compte-rendu existant</span>
            </div>
            <div class="text-sm text-blue-600">
                Généré le ${data.generated_at || 'Date inconnue'}
                <!-- avec ${data.ai_service || 'IA'} -->
            </div>
        </div>
    `;

    // Insérer l'info avant le contenu
    const contentDiv = resultContainer.querySelector('.bg-white');
    contentDiv.parentNode.insertBefore(infoDiv, contentDiv);

    // Mettre à jour le bouton pour indiquer qu'un compte-rendu existe
    const button = document.getElementById('generateButton');
    const buttonText = document.getElementById('buttonText');
    const buttonDescription = document.getElementById('buttonDescription');

    buttonText.textContent = 'Régénérer le Compte-Rendu';
    buttonDescription.innerHTML = '<i class="fas fa-sync-alt mr-1"></i>Cliquez pour générer une nouvelle version';
    button.classList.add('from-orange-500', 'to-red-600', 'hover:from-orange-600', 'hover:to-red-700');
    button.classList.remove('from-blue-500', 'to-indigo-600', 'hover:from-blue-600', 'hover:to-indigo-700');

    // Afficher la section des résultats
    showResults();
}

function generateTeleconsultation() {
    showLoading();

    fetch(`/medecin/ambulatory-ai/consultations/${consultationId}/teleconsultation-report`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('teleconsultationContent').textContent = data.data.teleconsultation_report;
            document.getElementById('teleconsultationResult').classList.remove('hidden');
            showResults();
        } else {
            showError(data.message || 'Erreur lors de la génération du compte-rendu');
        }
    })
    .catch(error => {
        showError('Erreur de connexion: ' + error.message);
    });
}


function downloadPDF() {
    window.open(`/medecin/ambulatory-ai/consultations/${consultationId}/pdf`, '_blank');
}

function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    const text = element.textContent;

    navigator.clipboard.writeText(text).then(() => {
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check mr-2"></i>Copié!';
        button.classList.add('bg-green-500');

        setTimeout(() => {
            button.innerHTML = originalText;
            button.classList.remove('bg-green-500');
        }, 2000);
    }).catch(err => {
        console.error('Erreur lors de la copie: ', err);
    });
}
</script>
@endsection
