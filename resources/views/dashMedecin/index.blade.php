@extends('dashMedecin.layout')

@section('content')
<div class="p-4 lg:p-8 bg-[#e4e4e4] min-h-screen">
    <!-- Template pour les cartes de patients -->
    @include('components.patient-card-template')

    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row justify-between items-center mb-8 lg:mb-12">
        <div class="flex flex-col lg:flex-row items-center gap-4 lg:gap-8 mb-4 lg:mb-0">
            <h1 class="text-2xl lg:text-4xl font-bold">
                TABLEAU DE B<span class="text-[#b9ff66]">O</span>RD
            </h1>
        </div>
    </div>

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block lg:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block lg:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Main Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-12">
        <!-- Left Column - Statistics and Quick Actions -->
        <div class="lg:col-span-8 space-y-6">
            <!-- Detailed Statistics -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6">
                <!-- Consultations -->
                <div class="bg-white p-4 lg:p-6 rounded-[20px] shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="p-2 bg-blue-100 rounded-full">👥</span>
                        <h3 class="font-semibold">Consultations</h3>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Aujourd'hui</span>
                            <span class="text-2xl font-bold">{{ is_numeric($consultationsAujourdhui) ? $consultationsAujourdhui : 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Cette semaine</span>
                            <span class="text-xl font-semibold">{{ is_numeric($consultationsSemaine) ? $consultationsSemaine : 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Ce mois</span>
                            <span class="text-xl font-semibold">{{ is_numeric($consultationsMois) ? $consultationsMois : 0 }}</span>
                        </div>
                    </div>
                </div>

                <!-- Patients -->
                <div class="bg-white p-4 lg:p-6 rounded-[20px] shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="p-2 bg-green-100 rounded-full">🏥</span>
                        <h3 class="font-semibold">Patients</h3>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Total suivis</span>
                            <span class="text-2xl font-bold">{{ is_numeric($totalPatients) ? $totalPatients : 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Nouveaux (mois)</span>
                            <span class="text-lg font-semibold text-green-500">+{{ is_numeric($nouveauxPatients) ? $nouveauxPatients : 0 }}</span>
                        </div>
                        <a href="{{ route('medecin.dossiers.medicaux') }}" class="w-full bg-[#b9ff66] text-sm py-2 rounded-full mt-2 hover:bg-[#a8eb5f] transition-all block text-center">
                            Voir tous les patients
                        </a>
                    </div>
                </div>

                <!-- Pending Appointments -->
                <div class="bg-white p-4 lg:p-6 rounded-[20px] shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="p-2 bg-orange-100 rounded-full">📅</span>
                        <h3 class="font-semibold">RDV en attente</h3>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">À confirmer</span>
                            <span class="text-2xl font-bold text-orange-500">{{ is_numeric($rdvEnAttente) ? $rdvEnAttente : 0 }}</span>
                        </div>
                        <a href="{{ route('medecin.rendez-vous.index') }}" class="w-full bg-[#b9ff66] text-sm py-2 rounded-full mt-2 hover:bg-[#a8eb5f] transition-all block text-center">
                            Gérer les demandes
                        </a>
                    </div>
                </div>
            </div>

            <!-- Today's Patients -->
            <div class="bg-white rounded-[25px] p-4 lg:p-8 shadow-sm">
                <div class="flex flex-col lg:flex-row justify-between items-center mb-6">
                    <h2 class="text-xl lg:text-2xl font-bold mb-4 lg:mb-0">Consultations à venir</h2>
                    <div class="flex items-center gap-4">
                        <span class="bg-[#b9ff66] px-3 lg:px-4 py-1.5 lg:py-2 rounded-full text-sm font-medium" id="patientsCount"></span>
                    </div>
                </div>
                <div class="space-y-4 lg:space-y-6" id="patientsList">
                    <div class="text-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#b9ff66] mx-auto"></div>
                        <p class="text-gray-500 mt-2">Chargement des consultations...</p>
                    </div>
                </div>
            </div>

            <!-- Dossiers médicaux -->
            @php $dossiers = $dossiers ?? collect([]); @endphp
            @if(isset($dossiers) && $dossiers->count() > 0)
                <div class="bg-white rounded-[20px] p-6 shadow-sm">
                <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-[#b9ff66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3 3m0 0l-3-3m3 3V8"/>
                    </svg>
                    Dossiers médicaux de vos patients ({{ $dossiers->count() }})
                </h2>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 rounded-lg">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Patient</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Contact</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Groupe sanguin</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Allergies</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Antécédents</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($dossiers as $dossier)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @php
                                            $user = $dossier->patient->user ?? null;
                                            $photoUrl = $user && $user->profile_photo_path
                                                ? asset('storage/' . $user->profile_photo_path)
                                                : 'https://ui-avatars.com/api/?name=' . urlencode($dossier->patient->prenom . ' ' . $dossier->patient->nom);
                                        @endphp
                                        <img src="{{ $photoUrl }}" class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $dossier->patient->prenom }} {{ $dossier->patient->nom }}</div>
                                            <div class="text-xs text-gray-500">ID: #{{ $dossier->patient->id }}</div>
                                            @if($dossier->patient->dateNaissance)
                                                <div class="text-xs text-gray-400">
                                                    {{ \Carbon\Carbon::parse($dossier->patient->dateNaissance)->age }} ans
                                                </div>
                                            @endif
                                            <!-- Indicateur de consultation en ligne -->
                                            @php
                                                $rdvAujourdhui = $dossier->patient->rendezVousCommePatient()
                                                    ->where('medecin_id', auth()->user()->id)
                                                    ->whereDate('date_debut', \Carbon\Carbon::today())
                                                    ->first();
                                                $consultationActive = false;
                                                $consultationEnCours = false;
                                                if ($rdvAujourdhui) {
                                                    $heureDebut = \Carbon\Carbon::parse($rdvAujourdhui->date_debut);
                                                    $heureFin = \Carbon\Carbon::parse($rdvAujourdhui->date_fin ?? $rdvAujourdhui->date_debut->addMinutes(30));
                                                    $maintenant = \Carbon\Carbon::now();

                                                    if ($rdvAujourdhui->statut === 'confirmé') {
                                                        $consultationActive = $maintenant->between($heureDebut->copy()->subMinutes(5), $heureFin);
                                                        $consultationEnCours = $maintenant->between($heureDebut, $heureFin);
                                                    }
                                                }
                                            @endphp
                                            @if($rdvAujourdhui)
                                                <div class="text-xs mt-1">
                                                    @if($rdvAujourdhui->statut !== 'confirmé')
                                                        <span class="text-red-500 font-medium">❌ Rendez-vous non confirmé</span>
                                                    @elseif($consultationEnCours)
                                                        <span class="text-green-600 font-medium">🟢 Consultation en cours</span>
                                                    @elseif($consultationActive)
                                                        <span class="text-blue-600 font-medium">🔵 Consultation peut commencer (5 min avant)</span>
                                                    @else
                                                        <span class="text-gray-500">⏰ RDV: {{ \Carbon\Carbon::parse($rdvAujourdhui->date_debut)->format('H:i') }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        @if($dossier->tel)
                                            <div class="text-sm text-gray-600">{{ $dossier->tel }}</div>
                                        @endif
                                        @if($dossier->adresse)
                                            <div class="text-xs text-gray-500 max-w-xs truncate" title="{{ $dossier->adresse }}">
                                                {{ $dossier->adresse }}
                                            </div>
                                        @endif
                                        @if(!$dossier->tel && !$dossier->adresse)
                                            <span class="text-gray-400 text-sm">Non renseigné</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($dossier->groupe_sanguin)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            {{ $dossier->groupe_sanguin }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-sm">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($dossier->allergies && $dossier->allergies !== 'Aucune')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            {{ $dossier->allergies }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-sm">Aucune</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($dossier->antecedents_medicaux && $dossier->antecedents_medicaux !== 'Aucun')
                                        <div class="max-w-xs">
                                            <span class="text-sm text-gray-700">{{ Str::limit($dossier->antecedents_medicaux, 50) }}</span>
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm">Aucun</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                            <button data-action="open-dossier" data-dossier-id="{{ $dossier->id }}"
                                                class="text-blue-600 hover:text-blue-800 hover:bg-blue-50 px-3 py-1 rounded-md transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                        <a href="{{ route('medecin.dossiermedical', ['patient_id' => $dossier->patient->id]) }}"
                                           class="text-green-600 hover:text-green-800 hover:bg-green-50 px-3 py-1 rounded-md transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($dossiers->hasPages())
                <div class="flex justify-between items-center mt-6">
                    {{ $dossiers->links() }}
                </div>
                @endif
            </div>
            @else
                <div class="bg-white rounded-[20px] p-6 shadow-sm">
                <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-[#b9ff66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3 3m0 0l-3-3m3 3V8"/>
                    </svg>
                    Dossiers médicaux de vos patients
                </h2>
                <div class="text-center py-8">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-gray-500 text-lg mb-2">Aucun dossier médical trouvé</p>
                    <p class="text-gray-400 text-sm">Les dossiers médicaux de vos patients apparaîtront ici</p>
                    <a href="{{ route('medecin.dossiers.medicaux') }}" class="inline-flex items-center px-4 py-2 mt-4 bg-[#b9ff66] text-gray-800 rounded-full hover:bg-[#a8eb5f] transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Créer un dossier médical
                    </a>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column - Quick Actions -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white p-4 lg:p-6 rounded-[20px] shadow-sm">
                <div class="flex items-center justify-between mb-4 lg:mb-6">
                    <h2 class="text-xl font-bold">Actions Rapides</h2>
                </div>
                <div class="space-y-3">
                    <a href="{{ route('medecin.rendez-vous.index') }}" class="p-3 rounded-lg hover:bg-gray-50 transition-all cursor-pointer block">
                        <div class="flex items-center gap-3">
                            <span class="p-2 bg-blue-100 rounded-full">📋</span>
                            <div>
                                <h4 class="font-medium">Rendez-vous</h4>
                                <p class="text-sm text-gray-500">Gérer les rendez-vous</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Consultations terminées de ce jour -->
            <div class="bg-white p-4 lg:p-6 rounded-[20px] shadow-sm">
                <div class="flex items-center justify-between mb-4 lg:mb-6">
                    <h2 class="text-xl font-bold">Consultations terminées de ce jour</h2>
                </div>
                <div id="consultationsTermineesList" class="space-y-3">
                    <div class="text-center py-4">
                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-[#b9ff66] mx-auto"></div>
                        <p class="text-gray-500 text-sm">Chargement...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Dossier Médical -->
    <div id="dossierModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-[30px] p-8 w-3/4 max-h-[80vh] overflow-y-auto">
            <div class="flex justify-between items-start mb-6">
                <h3 class="text-2xl font-bold">Dossier Médical</h3>
                <button data-action="close-dossier" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div id="dossierContent">
                <!-- Le contenu sera chargé dynamiquement via JavaScript -->
            </div>
            </div>
        </div>
    </div>

<!-- Scripts -->
@vite(['resources/css/app.css', 'resources/js/app.js'])
<script>
// Initialisation des modules
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les gestionnaires
    if (window.ConsultationManager) {
        window.consultationManager = new window.ConsultationManager();
    }
    if (window.DossierManager) {
        window.dossierManager = new window.DossierManager();
    }
});

// Fonction pour changer le statut d'un rendez-vous
async function changeRendezVousStatus(rendezVousId, newStatus) {
    const statusLabels = {
        'pending': 'En attente',
        'confirmed': 'Confirmé',
        'cancelled': 'Annulé',
        'completed': 'Terminé'
    };

    console.log('[DEBUG] changeRendezVousStatus appelé', {
        rendezVousId,
        newStatus,
        currentUrl: window.location.href,
        csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    });

    if (confirm(`Changer le statut vers "${statusLabels[newStatus]}" ?`)) {
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!csrfToken) {
                console.error('[DEBUG] Token CSRF manquant');
                showNotification('Erreur: Token CSRF manquant', 'error');
                return;
            }

            console.log('[DEBUG] Envoi de la requête', {
                url: `/api/medecin/rendez-vous/${rendezVousId}/statut`,
                method: 'PUT',
                body: { statut: newStatus },
                csrfToken
            });

            const response = await fetch(`/api/medecin/rendez-vous/${rendezVousId}/statut`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                credentials: 'same-origin',
                body: JSON.stringify({ statut: newStatus })
            });

            console.log('[DEBUG] Réponse reçue', {
                status: response.status,
                statusText: response.statusText,
                headers: Object.fromEntries(response.headers.entries())
            });

            const data = await response.json();
            console.log('[DEBUG] Données de réponse', data);

            if (response.ok && data.success) {
                showNotification('Statut mis à jour avec succès', 'success');
                // Recharger la page pour mettre à jour l'affichage
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                console.error('[DEBUG] Erreur API', {
                    status: response.status,
                    data: data
                });

                let errorMessage = 'Erreur lors de la mise à jour du statut';
                if (data.message) {
                    errorMessage = data.message;
                } else if (response.status === 401) {
                    errorMessage = 'Erreur d\'authentification. Veuillez vous reconnecter.';
                } else if (response.status === 403) {
                    errorMessage = 'Accès refusé. Vous n\'êtes pas autorisé à effectuer cette action.';
                } else if (response.status === 422) {
                    errorMessage = 'Données invalides. Veuillez réessayer.';
                }

                showNotification(errorMessage, 'error');
            }
        } catch (error) {
            console.error('[DEBUG] Erreur lors du changement de statut:', error);
            showNotification('Erreur réseau lors de la mise à jour du statut', 'error');
        }
    }
}

// Fonction pour afficher les notifications
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        'bg-blue-500 text-white'
    }`;

    notification.innerHTML = `
        <div class="flex items-center gap-2">
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-white hover:text-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    `;

    document.body.appendChild(notification);

    setTimeout(() => notification.classList.remove('translate-x-full'), 100);
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
</script>
@endsection
