@extends('dashMedecin.layout')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection

@section('content')
<div class="p-4 sm:p-8 bg-[#e4e4e4] min-h-screen">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">
                🤖 Génération IA de Comptes-Rendus
            </h2>
            <div class="flex space-x-2">
                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                    🆓 Gratuit
                </span>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                    ⚡ Rapide
                </span>
            </div>
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

        <!-- AI Generation Buttons -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <!-- Compte-Rendu Complet -->
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
                <div class="flex items-center mb-3">
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-file-medical text-white"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-blue-800">Compte-Rendu Complet</h4>
                </div>
                <p class="text-sm text-blue-600 mb-4">Génère un compte-rendu détaillé avec diagnostic, traitement et recommandations</p>
                <button onclick="generateCompteRendu()"
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                    <i class="fas fa-robot mr-2"></i>
                    Générer avec IA
                </button>
            </div>

            <!-- Résumé Court -->
            <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                <div class="flex items-center mb-3">
                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-clipboard-list text-white"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-green-800">Résumé Court</h4>
                </div>
                <p class="text-sm text-green-600 mb-4">Génère un résumé concis de la consultation (max 150 mots)</p>
                <button onclick="generateResume()"
                        class="w-full bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                    <i class="fas fa-compress-alt mr-2"></i>
                    Générer Résumé
                </button>
            </div>

            <!-- Lettre de Sortie -->
            <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg p-4 border border-purple-200">
                <div class="flex items-center mb-3">
                    <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-envelope text-white"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-purple-800">Lettre de Sortie</h4>
                </div>
                <p class="text-sm text-purple-600 mb-4">Génère une lettre de sortie pour le médecin traitant</p>
                <button onclick="generateLettreSortie()"
                        class="w-full bg-purple-500 hover:bg-purple-600 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Générer Lettre
                </button>
            </div>
        </div>

        <!-- Loading Indicator -->
        <div id="loadingIndicator" class="hidden bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-yellow-600 mr-3"></div>
                <span class="text-yellow-800 font-medium">Génération en cours...</span>
            </div>
        </div>

        <!-- Results Display -->
        <div id="resultsContainer" class="hidden">
            <!-- Compte-Rendu Results -->
            <div id="compteRenduResult" class="hidden mb-6">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-blue-800 mb-3">
                        <i class="fas fa-file-medical mr-2"></i>
                        Compte-Rendu Généré
                    </h3>
                    <div class="bg-white rounded-lg p-4 mb-4">
                        <pre id="compteRenduContent" class="whitespace-pre-wrap text-sm text-gray-800"></pre>
                    </div>
                    <div class="flex space-x-2">
                        <button onclick="downloadPDF()"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                            <i class="fas fa-download mr-2"></i>
                            Télécharger PDF
                        </button>
                        <button onclick="copyToClipboard('compteRenduContent')"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                            <i class="fas fa-copy mr-2"></i>
                            Copier
                        </button>
                    </div>
                </div>
            </div>

            <!-- Résumé Results -->
            <div id="resumeResult" class="hidden mb-6">
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-green-800 mb-3">
                        <i class="fas fa-clipboard-list mr-2"></i>
                        Résumé Généré
                    </h3>
                    <div class="bg-white rounded-lg p-4">
                        <p id="resumeContent" class="text-gray-800"></p>
                    </div>
                    <button onclick="copyToClipboard('resumeContent')"
                            class="mt-3 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-copy mr-2"></i>
                        Copier Résumé
                    </button>
                </div>
            </div>

            <!-- Lettre de Sortie Results -->
            <div id="lettreSortieResult" class="hidden mb-6">
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-purple-800 mb-3">
                        <i class="fas fa-envelope mr-2"></i>
                        Lettre de Sortie Générée
                    </h3>
                    <div class="bg-white rounded-lg p-4">
                        <pre id="lettreSortieContent" class="whitespace-pre-wrap text-sm text-gray-800"></pre>
                    </div>
                    <button onclick="copyToClipboard('lettreSortieContent')"
                            class="mt-3 bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-copy mr-2"></i>
                        Copier Lettre
                    </button>
                </div>
            </div>
        </div>

        <!-- Error Display -->
        <div id="errorContainer" class="hidden bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-red-500 mr-3"></i>
                <span id="errorMessage" class="text-red-800"></span>
            </div>
        </div>

        <!-- AI Services Info -->
        <div class="bg-gray-50 rounded-lg p-4 mt-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">
                <i class="fas fa-info-circle mr-2"></i>
                Services IA Utilisés
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-2">
                        <i class="fas fa-robot text-white text-xs"></i>
                    </div>
                    <div>
                        <p class="font-medium">Google Gemini Pro</p>
                        <p class="text-gray-600">Gratuit (60 req/min)</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-2">
                        <i class="fas fa-brain text-white text-xs"></i>
                    </div>
                    <div>
                        <p class="font-medium">Hugging Face</p>
                        <p class="text-gray-600">100% Gratuit</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center mr-2">
                        <i class="fas fa-file-alt text-white text-xs"></i>
                    </div>
                    <div>
                        <p class="font-medium">Template Basique</p>
                        <p class="text-gray-600">Fallback local</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const consultationId = {{ $consultation->id }};

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

function generateCompteRendu() {
    showLoading();

    fetch(`/medecin/ai/consultations/${consultationId}/generate-compte-rendu`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('compteRenduContent').textContent = data.data.compte_rendu;
            document.getElementById('compteRenduResult').classList.remove('hidden');
            showResults();
        } else {
            showError(data.message || 'Erreur lors de la génération du compte-rendu');
        }
    })
    .catch(error => {
        showError('Erreur de connexion: ' + error.message);
    });
}

function generateResume() {
    showLoading();

    fetch(`/medecin/ai/consultations/${consultationId}/generate-resume`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('resumeContent').textContent = data.resume;
            document.getElementById('resumeResult').classList.remove('hidden');
            showResults();
        } else {
            showError(data.message || 'Erreur lors de la génération du résumé');
        }
    })
    .catch(error => {
        showError('Erreur de connexion: ' + error.message);
    });
}

function generateLettreSortie() {
    showLoading();

    fetch(`/medecin/ai/consultations/${consultationId}/generate-lettre-sortie`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('lettreSortieContent').textContent = data.lettre_sortie;
            document.getElementById('lettreSortieResult').classList.remove('hidden');
            showResults();
        } else {
            showError(data.message || 'Erreur lors de la génération de la lettre de sortie');
        }
    })
    .catch(error => {
        showError('Erreur de connexion: ' + error.message);
    });
}

function downloadPDF() {
    window.open(`/medecin/ai/consultations/${consultationId}/pdf`, '_blank');
}

function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    const text = element.textContent;

    navigator.clipboard.writeText(text).then(() => {
        // Show success message
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
