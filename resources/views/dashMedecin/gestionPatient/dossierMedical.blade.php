@extends('dashMedecin.layout')

@section('content')
    <div class="p-4 sm:p-8 bg-[#e4e4e4] min-h-screen">
        <!-- En-tête du dossier -->
        <div class="flex flex-col sm:flex-row justify-between items-center mb-8">
            <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-8 mb-4 sm:mb-0">
                <h1 class="text-2xl sm:text-4xl font-bold">
                    DOSSIER MÉDIC<span class="text-[#b9ff66]">A</span>L
                </h1>
                <!-- Informations du patient -->
                <div class="flex items-center gap-4 bg-white p-4 rounded-[20px] shadow-sm">

                    @php
                        $photoUrl =
                            $patient->user && $patient->user->profile_photo_path
                                ? asset('storage/' . $patient->user->profile_photo_path)
                                : 'https://ui-avatars.com/api/?name=' .
                                    urlencode($patient->prenom . ' ' . $patient->nom);
                    @endphp
                    <img src="{{ $photoUrl }}" class="w-12 h-12 rounded-full">
                    <div>
                        <h2 class="font-bold text-lg">{{ $patient->prenom }} {{ $patient->nom }}</h2>
                        <p class="text-gray-500 text-sm">ID: #{{ $patient->id }}</p>
                    </div>
                </div>
            </div>


        </div>

        <!-- Contenu principal -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Colonne gauche : infos personnelles dynamiques -->
            <div class="lg:col-span-3 space-y-6">
                <!-- Informations personnelles -->
                <div class="bg-white p-6 rounded-[20px] shadow-sm">
                    <h3 class="text-lg font-bold mb-4">Informations Personnelles</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-500">Date de naissance</p>
                                <p class="font-medium">
                                    {{ isset($patient->dateNaissance) ? \Carbon\Carbon::parse($patient->dateNaissance)->translatedFormat('d F Y') : 'Non renseignée' }}
                                    @if (isset($patient->dateNaissance))
                                        ({{ \Carbon\Carbon::parse($patient->dateNaissance)->age }} ans)
                                    @endif
                                </p>
                            </div>

                        </div>
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-500">Groupe sanguin</p>
                                <p class="font-medium">{{ $dossier->groupe_sanguin ?? 'Non renseigné' }}</p>
                            </div>
                            @if (!$dossier || !$dossier->groupe_sanguin)
                                <button onclick="openEditModal('groupe_sanguin')"
                                    class="px-3 py-1.5 bg-[#b9ff66] text-gray-800 rounded-full text-sm hover:bg-[#a8eb5f] transition-colors">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Ajouter
                                </button>
                            @else
                                <button onclick="openEditModal('groupe_sanguin')"
                                    class="px-3 py-1.5 bg-blue-500 text-white rounded-full text-sm hover:bg-blue-600 transition-colors">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Modifier
                                </button>
                            @endif
                        </div>

                    </div>
                </div>
                <!-- Antécédents médicaux -->
                <div class="bg-white p-6 rounded-[20px] shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold">Antécédents Médicaux</h3>
                        @if (!$dossier || !$dossier->antecedents_medicaux)
                            <button onclick="openEditModal('antecedents_medicaux', '{{ $dossier->antecedents_medicaux ?? '' }}')"
                                class="px-3 py-1.5 bg-[#b9ff66] text-gray-800 rounded-full text-sm hover:bg-[#a8eb5f] transition-colors">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Ajouter
                            </button>
                        @else
                            <button onclick="openEditModal('antecedents_medicaux', '{{ $dossier->antecedents_medicaux }}')"
                                class="px-3 py-1.5 bg-blue-500 text-white rounded-full text-sm hover:bg-blue-600 transition-colors">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Modifier
                            </button>
                        @endif
                    </div>
                    <div class="space-y-3">
                        @if ($dossier && $dossier->antecedents_medicaux)
                            @foreach (explode(',', $dossier->antecedents_medicaux) as $ant)
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                    <p>{{ $ant }}</p>
                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-500">Aucun antécédent renseigné.</p>
                        @endif
                    </div>
                </div>
                <!-- Allergies -->
                <div class="bg-white p-6 rounded-[20px] shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold">Allergies</h3>
                        @if (!$dossier || !$dossier->allergies)
                            <button onclick="openEditModal('allergies', '{{ $dossier->allergies ?? ''}}')"
                                class="px-3 py-1.5 bg-[#b9ff66] text-gray-800 rounded-full text-sm hover:bg-[#a8eb5f] transition-colors">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Ajouter
                            </button>
                        @else
                            <button onclick="openEditModal('allergies','{{ $dossier->allergies }}')"
                                class="px-3 py-1.5 bg-blue-500 text-white rounded-full text-sm hover:bg-blue-600 transition-colors">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Modifier
                            </button>
                        @endif
                    </div>
                    <div class="space-y-2">
                        @if ($dossier && $dossier->allergies)
                            @foreach (explode(',', $dossier->allergies) as $all)
                                <span
                                    class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-sm">{{ $all }}</span>
                            @endforeach
                        @else
                            <span class="text-gray-500">Aucune allergie renseignée.</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Historique médical - Colonne droite -->
            <div class="lg:col-span-9 space-y-6">
                <!-- Onglets -->
                <div class="bg-white rounded-[20px] p-4 shadow-sm">
                    <div class="flex gap-4 border-b">
                        <button onclick="switchTab('consultations')"
                            class="px-4 py-2 text-[#b9ff66] border-b-2 border-[#b9ff66] font-medium tab-btn"
                            data-tab="consultations">
                            Consultations
                        </button>
                        <button onclick="switchTab('ordonnances')"
                            class="px-4 py-2 text-gray-500 hover:text-gray-700 border-b-2 border-transparent tab-btn"
                            data-tab="ordonnances">
                            Ordonnances
                        </button>
                        <button onclick="switchTab('analyses')"
                            class="px-4 py-2 text-gray-500 hover:text-gray-700 border-b-2 border-transparent tab-btn"
                            data-tab="analyses">
                            Analyses
                        </button>
                        <button onclick="switchTab('Diagnostique')"
                            class="px-4 py-2 text-gray-500 hover:text-gray-700 border-b-2 border-transparent tab-btn"
                            data-tab="Diagnostique">
                            Diagnostique
                        </button>
                    </div>
                </div>

                <!-- Conteneur des contenus d'onglets -->
                <div class="mt-6">
                    <!-- Onglet Consultations -->
                    <div id="consultations-tab" class="tab-content space-y-4">
                        <!-- Barre d'actions rapides -->
                        <div class="bg-white p-4 rounded-[20px] shadow-sm">
                            <div class="flex flex-col sm:flex-row gap-4 justify-between">
                                <!-- Recherche -->
                                <div class="relative flex-1">
                                    <input id="consultations-search" type="text"
                                        placeholder="Rechercher une consultation..."
                                        class="w-full pl-10 pr-4 py-2 rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <!-- Filtres -->
                                <div class="flex gap-2">
                                    <select id="consultations-type"
                                        class="rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                                        <option value="">Type de consultation</option>
                                        <option value="routine">Premiere consultation</option>
                                        <option value="urgence">Consultation de controle</option>
                                        <option value="suivi">Consultation urgente</option>
                                        <option value="suivi">Consultation de routine</option>

                                    </select>
                                    <select id="consultations-period"
                                        class="rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                                        <option value="">Période</option>
                                        <option value="semaine">Cette semaine</option>
                                        <option value="mois">Ce mois</option>
                                        <option value="annee">Cette année</option>
                                    </select>
                                </div>
                                <!-- Actions -->
                                <div class="flex gap-2">
                                    <button onclick="openConsultationModal()"
                                        class="bg-[#b9ff66] hover:bg-[#a8eb5f] text-black rounded-lg px-4 py-2 flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                        Nouvelle Consultation
                                    </button>
                                    <button
                                        class="bg-gray-800 text-white rounded-lg px-4 py-2 flex items-center gap-2 hover:bg-gray-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Exporter
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Liste dynamique des consultations -->
                        <div id="consultations-list" class="space-y-4">
                            <div id="consultations-loading"
                                class="bg-white p-6 rounded-[20px] shadow-sm text-center text-gray-500">
                                Chargement des consultations...
                            </div>
                        </div>
                    </div>

                    <!-- Onglet Ordonnances -->
                    <div id="ordonnances-tab" class="tab-content hidden space-y-6">
                        <!-- Barre d'actions rapides -->
                        <div class="bg-white p-4 rounded-[20px] shadow-sm">
                            <div class="flex flex-col sm:flex-row gap-4 justify-between">
                                <!-- Recherche -->
                                <div class="relative flex-1">
                                    <input type="text" placeholder="Rechercher une ordonnance..."
                                        class="w-full pl-10 pr-4 py-2 rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <!-- Filtres -->
                                <div class="flex gap-2">
                                    <select class="rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                                        <option value="">Type de médicament</option>
                                        <option value="antibiotiques">Antibiotiques</option>
                                        <option value="antalgiques">Antalgiques</option>
                                        <option value="antiinflammatoires">Anti-inflammatoires</option>
                                    </select>
                                    <select class="rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                                        <option value="">Statut</option>
                                        <option value="active">Active</option>
                                        <option value="terminee">Terminée</option>
                                        <option value="renouvelable">Renouvelable</option>
                                    </select>
                                </div>
                                <!-- Actions -->
                                <div class="flex gap-2">
                                    <button onclick="openOrdonnanceModal()"
                                        class="bg-[#b9ff66] hover:bg-[#a8eb5f] text-black rounded-lg px-4 py-2 flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                        Nouvelle Ordonnance
                                    </button>
                                    <button
                                        class="bg-gray-800 text-white rounded-lg px-4 py-2 flex items-center gap-2 hover:bg-gray-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Exporter
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Liste des ordonnances -->
                        <div class="space-y-4">
                            @if($ordonnances && $ordonnances->count() > 0)
                                @foreach($ordonnances as $ordonnance)
                                    <div class="bg-white p-6 rounded-[20px] shadow-sm ordonnance-card">
                                        <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                                            <div>
                                                <div class="flex items-center gap-2 mb-2">
                                                    <h3 class="font-bold text-lg">Ordonnance #ORD-{{ str_pad($ordonnance->id, 3, '0', STR_PAD_LEFT) }}</h3>
                                                    <span class="px-2 py-1 {{ $ordonnance->file ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }} rounded-full text-sm">
                                                        {{ $ordonnance->file ? 'Disponible' : 'En cours' }}
                                                    </span>
                                                </div>
                                                <p class="text-gray-500">Prescrite le {{ $ordonnance->created_at ? $ordonnance->created_at->format('d F Y') : 'Date non spécifiée' }}</p>
                                                <p class="text-gray-600 mt-1">{{ $ordonnance->consultation && $ordonnance->consultation->rendezVous && $ordonnance->consultation->rendezVous->medecin ? 'Dr. ' . $ordonnance->consultation->rendezVous->medecin->prenom . ' ' . $ordonnance->consultation->rendezVous->medecin->nom : 'Médecin non spécifié' }}</p>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <div class="flex items-center gap-1 text-sm text-gray-500">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span>Créée le {{ $ordonnance->created_at ? $ordonnance->created_at->format('d/m/Y à H:i') : 'Date inconnue' }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Contenu détaillé de l'ordonnance -->
                                        <div class="mt-6 border-t pt-4">
                                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 ordonnance-grid">

                                                <!-- Médicaments -->
                                                <div class="lg:col-span-2">
                                                    <div class="flex items-center gap-2 mb-3">
                                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center section-icon">
                                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                                            </svg>
                                                        </div>
                                                        <h4 class="font-semibold text-gray-800">Médicaments prescrits</h4>
                                                    </div>

                                                    @if($ordonnance->medicaments && !empty($ordonnance->medicaments))
                                                        <div class="space-y-3">
                                                            @php
                                                                // Gérer différents formats de données
                                                                $medicaments = [];

                                                                if (is_string($ordonnance->medicaments)) {
                                                                    // Essayer de décoder le JSON
                                                                    $decoded = json_decode($ordonnance->medicaments, true);
                                                                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                                                        // JSON valide
                                                                        $medicaments = $decoded;
                                                                    } else {
                                                                        // Si ce n'est pas du JSON valide, traiter comme du texte simple
                                                                        $medicaments = [['nom' => $ordonnance->medicaments, 'posologie' => 'Non spécifiée']];
                                                                    }
                                                                } elseif (is_array($ordonnance->medicaments)) {
                                                                    $medicaments = $ordonnance->medicaments;
                                                                }

                                                                // S'assurer que $medicaments est un tableau
                                                                if (!is_array($medicaments)) {
                                                                    $medicaments = [['nom' => 'Médicament', 'posologie' => 'Non spécifiée']];
                                                                }
                                                            @endphp

                                                            @if(count($medicaments) > 0)
                                                                @foreach($medicaments as $index => $medicament)
                                                                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-xl border border-blue-100 hover:shadow-md transition-all duration-200 medicament-item">
                                                                        <div class="flex items-start justify-between">
                                                                            <div class="flex-1">
                                                                                <div class="flex items-center gap-2 mb-2">
                                                                                    <span class="w-6 h-6 bg-blue-600 text-white text-xs font-bold rounded-full flex items-center justify-center">{{ $index + 1 }}</span>
                                                                                    <p class="font-semibold text-gray-800">
                                                                                        @if(is_array($medicament))
                                                                                            {{ $medicament['nom'] ?? ($medicament['name'] ?? 'Médicament non spécifié') }}
                                                                                        @else
                                                                                            {{ $medicament }}
                                                                                        @endif
                                                                                    </p>
                                                                                </div>
                                                                                @if(is_array($medicament))
                                                                                    <p class="text-sm text-gray-700 mb-1">{{ $medicament['posologie'] ?? ($medicament['dosage'] ?? 'Posologie non spécifiée') }}</p>
                                                                                    @if(isset($medicament['duree']) || isset($medicament['duration']))
                                                                                        <p class="text-xs text-blue-600 font-medium">Durée: {{ $medicament['duree'] ?? $medicament['duration'] }}</p>
                                                                                    @endif
                                                                                @else
                                                                                    <p class="text-sm text-gray-700 mb-1">Posologie non spécifiée</p>
                                                                                @endif
                                                                            </div>
                                                                            @if(is_array($medicament) && isset($medicament['type']))
                                                                                <span class="px-3 py-1 {{ $medicament['type'] === 'Antibiotique' ? 'bg-purple-100 text-purple-800 border border-purple-200' : ($medicament['type'] === 'Antalgique' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-orange-100 text-orange-800 border border-orange-200') }} rounded-full text-xs font-medium">
                                                                                    {{ $medicament['type'] }}
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @else
                                                                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                                                                    <p class="text-gray-700">{{ $ordonnance->medicaments }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 text-center empty-state">
                                                            <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                                            </svg>
                                                            <p class="text-gray-500 text-sm">Aucun médicament prescrit</p>
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Notes et Fichier -->
                                                <div class="space-y-6">

                                                    <!-- Notes -->
                                                    <div>
                                                        <div class="flex items-center gap-2 mb-3">
                                                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center section-icon">
                                                                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                                </svg>
                                                            </div>
                                                            <h4 class="font-semibold text-gray-800">Notes du médecin</h4>
                                                        </div>

                                                        @if($ordonnance->notes)
                                                            <div class="bg-gradient-to-r from-yellow-50 to-amber-50 p-4 rounded-xl border border-yellow-200">
                                                                <p class="text-gray-700 text-sm leading-relaxed">{{ $ordonnance->notes }}</p>
                                                            </div>
                                                        @else
                                                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 text-center empty-state">
                                                                <svg class="w-6 h-6 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                                </svg>
                                                                <p class="text-gray-500 text-sm">Aucune note</p>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- Fichier PDF -->
                                                    <div>
                                                        <div class="flex items-center gap-2 mb-3">
                                                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center section-icon">
                                                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                </svg>
                                                            </div>
                                                            <h4 class="font-semibold text-gray-800">Document PDF</h4>
                                                        </div>

                                                        @if($ordonnance->file && !empty($ordonnance->file))
                                                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 rounded-xl border border-green-200">
                                                                <div class="flex items-center gap-3 mb-3">
                                                                    <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                                                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                                        </svg>
                                                                    </div>
                                                                    <div>
                                                                        <p class="font-medium text-gray-800">Ordonnance PDF</p>
                                                                        <p class="text-xs text-gray-500">{{ pathinfo($ordonnance->file, PATHINFO_EXTENSION) }} • {{ date('d/m/Y', strtotime($ordonnance->created_at)) }}</p>
                                                                    </div>
                                                                </div>

                                                                <div class="flex gap-2">
                                                                    <button onclick="openOrdonnanceModal('{{ $ordonnance->file }}', '{{ $ordonnance->id }}')"
                                                                        class="flex-1 px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 flex items-center justify-center gap-2 action-button">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                                        </svg>
                                                                        Voir
                                                                    </button>
                                                                    <a href="{{ route('medecin.ordonnances.download') }}?file={{ urlencode($ordonnance->file) }}"
                                                                        class="flex-1 px-3 py-2 bg-white hover:bg-gray-50 text-green-600 border border-green-600 text-sm font-medium rounded-lg transition-colors duration-200 flex items-center justify-center gap-2 action-button">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                                        </svg>
                                                                        Télécharger
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 text-center empty-state">
                                                                <svg class="w-6 h-6 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                </svg>
                                                                <p class="text-gray-500 text-sm">PDF en cours de génération</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="bg-white p-6 rounded-[20px] shadow-sm text-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune ordonnance</h3>
                                    <p class="text-gray-600">Ce patient n'a pas encore d'ordonnances prescrites.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Pagination -->
                        @if($ordonnances && $ordonnances->count() > 0)
                            <div class="flex justify-between items-center bg-white p-4 rounded-[20px] shadow-sm">
                                <span class="text-sm text-gray-500">Affichage de {{ $ordonnances->count() }} ordonnance{{ $ordonnances->count() > 1 ? 's' : '' }}</span>
                                <div class="flex gap-2">
                                    @if($ordonnances->count() > 5)
                                        <button class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100"
                                            disabled>
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 19l-7-7 7-7" />
                                            </svg>
                                        </button>
                                        <button
                                            class="w-8 h-8 flex items-center justify-center rounded-full bg-[#b9ff66]">1</button>
                                        <button
                                            class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100">2</button>
                                        <button class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Onglet Analyses -->
                    <div id="analyses-tab" class="tab-content hidden space-y-6">
                        <!-- Barre d'actions rapides -->
                        <div class="bg-white p-4 rounded-[20px] shadow-sm">
                            <div class="flex flex-col sm:flex-row gap-4 justify-between">
                                <!-- Recherche -->
                                <div class="relative flex-1">
                                    <input type="text" placeholder="Rechercher une analyse..."
                                        class="w-full pl-10 pr-4 py-2 rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <!-- Filtres -->
                                <div class="flex gap-2">
                                    <select class="rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                                        <option value="">Type d'analyse</option>
                                        <option value="sang">Analyse de sang</option>
                                        <option value="urine">Analyse d'urine</option>
                                        <option value="bacterio">Analyse bactériologique</option>
                                    </select>
                                    <select class="rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                                        <option value="">Statut</option>
                                        <option value="en_attente">En attente</option>
                                        <option value="en_cours">En cours</option>
                                        <option value="termine">Terminé</option>
                                    </select>
                                </div>
                                <!-- Actions -->
                                <div class="flex gap-2">
                                    <button onclick="openAnalyseModal()"
                                        class="bg-[#b9ff66] hover:bg-[#a8eb5f] text-black rounded-lg px-4 py-2 flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                        Nouvelle Analyse
                                    </button>
                                    <button
                                        class="bg-gray-800 text-white rounded-lg px-4 py-2 flex items-center gap-2 hover:bg-gray-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Exporter
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Liste des analyses -->
                        <div class="space-y-4">
                            <!-- Analyse avec résultats -->
                            <div class="bg-white p-6 rounded-[20px] shadow-sm">
                                <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                                    <div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <h3 class="font-bold text-lg">Bilan sanguin complet</h3>
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                                                Résultats disponibles
                                            </span>
                                        </div>
                                        <p class="text-gray-500">Effectué le 15 Mars 2024</p>
                                        <p class="text-gray-600 mt-1">Laboratoire Central</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <button onclick="viewAnalyseDetails('analyse-1')"
                                            class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Voir les résultats
                                        </button>
                                        <button
                                            class="px-4 py-2 bg-[#b9ff66] hover:bg-[#a8eb5f] rounded-lg flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            Télécharger PDF
                                        </button>
                                    </div>
                                </div>

                                <!-- Détails de l'analyse (initialement cachés) -->
                                <div id="analyse-1" class="hidden mt-6 border-t pt-4">
                                    <div class="space-y-4">
                                        <!-- Galerie d'images des résultats -->
                                        <div>
                                            <h4 class="font-medium text-gray-700 mb-4">Images des résultats</h4>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <!-- Image principale -->
                                                <div class="col-span-full">
                                                    <div
                                                        class="relative aspect-[16/9] rounded-lg overflow-hidden bg-gray-100">
                                                        <img id="mainImage-analyse-1"
                                                            src="/storage/analyses/analyse1-1.jpg"
                                                            alt="Résultat d'analyse principal"
                                                            class="w-full h-full object-contain cursor-zoom-in"
                                                            onclick="openImageModal(this.src)">
                                                        <div class="absolute bottom-2 right-2">
                                                            <button
                                                                class="bg-white p-2 rounded-lg shadow-md hover:bg-gray-50 transition-colors">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Miniatures -->
                                                <div class="col-span-full flex gap-2 overflow-x-auto pb-2">
                                                    <button
                                                        class="w-24 h-24 rounded-lg overflow-hidden border-2 border-[#b9ff66]">
                                                        <img src="/storage/analyses/analyse1-1.jpg" alt="Miniature 1"
                                                            class="w-full h-full object-cover"
                                                            onclick="switchMainImage('analyse-1', this.src)">
                                                    </button>
                                                    <button
                                                        class="w-24 h-24 rounded-lg overflow-hidden border-2 border-transparent hover:border-[#b9ff66]">
                                                        <img src="/storage/analyses/analyse1-2.jpg" alt="Miniature 2"
                                                            class="w-full h-full object-cover"
                                                            onclick="switchMainImage('analyse-1', this.src)">
                                                    </button>
                                                    <button
                                                        class="w-24 h-24 rounded-lg overflow-hidden border-2 border-transparent hover:border-[#b9ff66]">
                                                        <img src="/storage/analyses/analyse1-3.jpg" alt="Miniature 3"
                                                            class="w-full h-full object-cover"
                                                            onclick="switchMainImage('analyse-1', this.src)">
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Informations complémentaires -->
                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <h4 class="font-medium text-gray-700 mb-2">Informations</h4>
                                            <div class="space-y-2">
                                                <p class="text-sm text-gray-600">
                                                    <span class="font-medium">Date de réalisation :</span> 15 Mars 2024
                                                </p>
                                                <p class="text-sm text-gray-600">
                                                    <span class="font-medium">Laboratoire :</span> Laboratoire Central
                                                </p>
                                                <p class="text-sm text-gray-600">
                                                    <span class="font-medium">Type d'analyse :</span> Bilan sanguin complet
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Autre analyse -->
                            <div class="bg-white p-6 rounded-[20px] shadow-sm">
                                <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                                    <div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <h3 class="font-bold text-lg">Analyse d'urine</h3>
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                                                Résultats disponibles
                                            </span>
                                        </div>
                                        <p class="text-gray-500">Effectué le 10 Mars 2024</p>
                                        <p class="text-gray-600 mt-1">Laboratoire Central</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <button onclick="viewAnalyseDetails('analyse-2')"
                                            class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Voir les résultats
                                        </button>
                                        <button
                                            class="px-4 py-2 bg-[#b9ff66] hover:bg-[#a8eb5f] rounded-lg flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            Télécharger PDF
                                        </button>
                                    </div>
                                </div>

                                <!-- Détails de l'analyse (initialement cachés) -->
                                <div id="analyse-2" class="hidden mt-6 border-t pt-4">
                                    <!-- Contenu similaire à la première analyse -->
                                </div>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div class="flex justify-between items-center bg-white p-4 rounded-[20px] shadow-sm">
                            <span class="text-sm text-gray-500">Affichage de 1 à 2 sur 5 analyses</span>
                            <div class="flex gap-2">
                                <button class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100"
                                    disabled>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                                <button
                                    class="w-8 h-8 flex items-center justify-center rounded-full bg-[#b9ff66]">1</button>
                                <button
                                    class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100">2</button>
                                <button class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Onglet Diagnostique -->
                    <div id="Diagnostique-tab" class="tab-content hidden space-y-6">
                        <!-- Barre d'actions rapides -->
                        <div class="bg-white p-4 rounded-[20px] shadow-sm">
                            <div class="flex flex-col sm:flex-row gap-4 justify-between">
                                <!-- Recherche -->
                                <div class="relative flex-1">
                                    <input type="text" placeholder="Rechercher un diagnostic..."
                                        class="w-full pl-10 pr-4 py-2 rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <!-- Filtres -->
                                <div class="flex gap-2">
                                    <select class="rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                                        <option value="">Type de diagnostic</option>
                                        <option value="initial">Initial</option>
                                        <option value="differentiel">Différentiel</option>
                                        <option value="final">Final</option>
                                    </select>
                                    <select class="rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                                        <option value="">Période</option>
                                        <option value="semaine">Cette semaine</option>
                                        <option value="mois">Ce mois</option>
                                        <option value="annee">Cette année</option>
                                    </select>
                                </div>
                                <!-- Actions -->
                                <div class="flex gap-2">
                                    <button onclick="openDiagnosticModal()"
                                        class="bg-[#b9ff66] hover:bg-[#a8eb5f] text-black rounded-lg px-4 py-2 flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                        Nouveau
                                    </button>
                                    <button
                                        class="bg-gray-800 text-white rounded-lg px-4 py-2 flex items-center gap-2 hover:bg-gray-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Exporter
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Contenu du diagnostique -->
                        <div class="bg-white p-6 rounded-[20px] shadow-sm text-center text-gray-500">
                            Fonctionnalité à venir
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <!-- Modal Nouvelle Consultation -->
    <div id="newConsultationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-[20px] p-8 w-full max-w-5xl mx-4 relative max-h-[95vh] overflow-y-auto">
            <!-- En-tête Modal -->
            <div class="flex justify-between items-center mb-8 border-b border-gray-200 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#b9ff66] rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">
                    NOUVELLE CONSULT<span class="text-[#b9ff66]">A</span>TION
                </h2>
                </div>
                <button onclick="closeConsultationModal()"
                    class="hover:bg-gray-100 p-2 rounded-full transition-all duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="newConsultationForm" method="POST" action="{{ route('medecin.consultations.store') }}"
                class="space-y-8">
                @csrf
                <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                <input type="hidden" name="medecin_id" value="{{ auth()->user()->medecin->id ?? '' }}">
                <input type="hidden" name="date_consultation" value="{{ now() }}">

                <!-- 1. INFORMATIONS ESSENTIELLES -->
                <div class="bg-gradient-to-r from-[#f0f9ff] to-[#e0f2fe] p-6 rounded-[20px] shadow-sm border-l-4 border-[#b9ff66]">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 bg-[#b9ff66] rounded-full flex items-center justify-center">
                            <span class="text-gray-800 font-bold text-sm">1</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-6 h-6 text-[#b9ff66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                            Informations Essentielles
                    </h3>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Type de consultation - PRIORITÉ 1 -->
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 flex items-center gap-2">
                                <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                Type de consultation *
                            </label>
                            <select name="type_consultation" id="type_consultation" required
                                class="w-full rounded-lg border-2 border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66] transition-all duration-200">
                                @if (isset($typesConsultation))
                                    @foreach ($typesConsultation as $key => $label)
                                        <option value="{{ is_string($key) ? $key : $label }}">{{ $label }}</option>
                                    @endforeach
                                @else
                                    <option value="premiere">Première consultation</option>
                                    <option value="routine">Consultation de routine</option>
                                    <option value="controle">Consultation de contrôle</option>
                                    <option value="urgence">Consultation d'urgence</option>
                                    <option value="suivi">Consultation de suivi</option>
                                @endif
                            </select>
                        </div>

                        <!-- Rendez-vous du jour -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Rendez-vous du jour</label>
                            @php
                                $rvItems = $rendezVousDuJour ?? [];
                                if (!is_iterable($rvItems)) {
                                    $rvItems = isset($rvItems) ? [$rvItems] : [];
                                }
                                $rvItems = collect($rvItems)->filter(function ($rv) use ($patient) {
                                    $dtRaw = $rv->date_debut ?? ($rv->date ?? ($rv->date_rendezvous ?? ($rv->scheduled_at ?? ($rv->datetime ?? null))));
                                    $samePatient = property_exists($rv, 'patient_id') ? $rv->patient_id == ($patient->id ?? null) : true;
                                    return $samePatient && ($dtRaw ? \Carbon\Carbon::parse($dtRaw)->isToday() : true);
                                });
                            @endphp
                            @if ($rvItems->count() > 0)
                                <select name="rendezvous_id"
                                    class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                                    @foreach ($rvItems as $rv)
                                        @php
                                            $dtRaw = $rv->date_debut ?? ($rv->date ?? ($rv->date_rendezvous ?? ($rv->scheduled_at ?? ($rv->datetime ?? null))));
                                            $timeLabel = $dtRaw ? \Carbon\Carbon::parse($dtRaw)->format('H:i') : '';
                                            $label = $rv->titre ?? ($rv->objet ?? ($rv->description ?? null));
                                        @endphp
                                        <option value="{{ $rv->id }}">#{{ $rv->id }} — {{ $timeLabel }}
                                            @if ($label) — {{ $label }} @endif
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <input type="text" class="w-full rounded-lg border-gray-300 bg-gray-50"
                                    value="Aucun rendez-vous pour aujourd'hui" disabled>
                            @endif
                        </div>

                        <!-- Motif de consultation - PRIORITÉ 1 -->
                        <div class="lg:col-span-2 space-y-2">
                            <label class="block text-sm font-bold text-gray-700 flex items-center gap-2">
                                <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                Motif de consultation *
                            </label>
                            <input type="text" name="motif_consultation" required placeholder="Motif principal de la consultation"
                                class="w-full rounded-lg border-2 border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66] transition-all duration-200">
                        </div>

                        <!-- Symptômes - PRIORITÉ 1 -->
                        <div class="lg:col-span-2 space-y-2">
                            <label class="block text-sm font-bold text-gray-700 flex items-center gap-2">
                                <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                Symptômes *
                            </label>
                            <textarea name="symptomes" rows="3" placeholder="Décrire les symptômes du patient"
                                class="w-full rounded-lg border-2 border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66] transition-all duration-200"></textarea>
                        </div>

                        <!-- Gravité - PRIORITÉ 1 -->
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 flex items-center gap-2">
                                <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                Gravité *
                            </label>
                            <select name="gravite" required
                                class="w-full rounded-lg border-2 border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66] transition-all duration-200">
                                <option value="">Sélectionner la gravité</option>
                                <option value="faible">Faible</option>
                                <option value="moderee">Modérée</option>
                                <option value="severe">Sévère</option>
                                <option value="critique">Critique</option>
                            </select>
                        </div>

                        <!-- Début des symptômes - PRIORITÉ 1 -->
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 flex items-center gap-2">
                                <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                Début des symptômes *
                            </label>
                            <input type="date" name="debut_symptomes" required
                                class="w-full rounded-lg border-2 border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66] transition-all duration-200">
                        </div>

                        <!-- Symptômes aigus - PRIORITÉ URGENCE -->
                        <div id="symptomes-aigus-section" class="lg:col-span-2 space-y-2 hidden">
                            <label class="block text-sm font-bold text-red-700 flex items-center gap-2">
                                <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                Symptômes aigus (URGENCE)
                            </label>
                            <textarea name="symptomes_aigus" rows="2" placeholder="Décrire les symptômes aigus nécessitant une attention immédiate"
                                class="w-full rounded-lg border-2 border-red-300 focus:ring-red-500 focus:border-red-500 transition-all duration-200"></textarea>
                        </div>

                        <!-- Orientation du patient -->
                        <div class="lg:col-span-2 space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Orientation du patient</label>
                            <input type="text" name="orientation_patient"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]"
                                placeholder="Ex: Spécialiste, Urgences, etc.">
                        </div>
                    </div>
                </div>

                <!-- 2. PARAMÈTRES CLINIQUES VITAUX -->
                <div id="parametres-cliniques-section" class="bg-gradient-to-r from-[#f0fdf4] to-[#dcfce7] p-6 rounded-[20px] shadow-sm border-l-4 border-[#b9ff66]">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 bg-[#b9ff66] rounded-full flex items-center justify-center">
                            <span class="text-gray-800 font-bold text-sm">2</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-6 h-6 text-[#b9ff66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                            Paramètres Cliniques Vitaux
                    </h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Tension artérielle -->
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 flex items-center gap-2">
                                <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                Tension artérielle *
                            </label>
                            <input type="text" name="tension_arterielle" required placeholder="120/80 mmHg"
                                class="w-full rounded-lg border-2 border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66] transition-all duration-200">
                        </div>

                        <!-- Saturation O₂ -->
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 flex items-center gap-2">
                                <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                Saturation O₂ (%) *
                            </label>
                            <input type="number" name="saturation_o2" required min="0" max="100" placeholder="98"
                                class="w-full rounded-lg border-2 border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66] transition-all duration-200">
                        </div>

                        <!-- Fréquence cardiaque -->
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 flex items-center gap-2">
                                <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                Fréquence cardiaque (bpm) *
                            </label>
                            <input type="number" name="frequence_cardiaque" required min="30" max="200" placeholder="75"
                                class="w-full rounded-lg border-2 border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66] transition-all duration-200">
                        </div>

                        <!-- Température -->
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 flex items-center gap-2">
                                <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                Température (°C) *
                            </label>
                            <input type="number" step="0.1" name="temperature" required min="30" max="45" placeholder="37.0"
                                class="w-full rounded-lg border-2 border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66] transition-all duration-200">
                        </div>

                        <!-- Score Glasgow -->
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 flex items-center gap-2">
                                <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                Score Glasgow *
                            </label>
                            <input type="number" min="3" max="15" name="score_glasgow" required placeholder="15"
                                class="w-full rounded-lg border-2 border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66] transition-all duration-200">
                        </div>
                    </div>
                </div>

                <!-- 3. MESURES PHYSIQUES -->
                <div id="mesures-physiques-section" class="bg-gradient-to-r from-[#f0f9ff] to-[#e0f2fe] p-6 rounded-[20px] shadow-sm border-l-4 border-[#b9ff66]">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 bg-[#b9ff66] rounded-full flex items-center justify-center">
                            <span class="text-gray-800 font-bold text-sm">3</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-6 h-6 text-[#b9ff66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Mesures Physiques
                    </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Poids (kg)</label>
                            <input type="number" step="0.1" name="poids" id="poids-input" placeholder="70"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66] transition-all duration-200">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Taille (cm)</label>
                            <input type="number" step="0.1" name="taille" id="taille-input" placeholder="175"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66] transition-all duration-200">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">IMC (calculé automatiquement)</label>
                            <input type="number" step="0.1" name="imc" id="imc-input" placeholder="Auto-calculé" readonly
                                class="w-full rounded-lg border-gray-300 bg-gray-100 text-gray-600">
                        </div>
                    </div>
                </div>

                <!-- 4. EXAMEN, DIAGNOSTIC ET SUIVI -->
                <div id="examen-diagnostic-section" class="bg-gradient-to-r from-[#f0fdf4] to-[#dcfce7] p-6 rounded-[20px] shadow-sm border-l-4 border-[#b9ff66]">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 bg-[#b9ff66] rounded-full flex items-center justify-center">
                            <span class="text-gray-800 font-bold text-sm">4</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                            <svg class="w-6 h-6 text-[#b9ff66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Examen, Diagnostic et Suivi
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Examen physique</label>
                            <textarea name="examen_physique" rows="3" placeholder="Décrire l'examen physique effectué"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66] transition-all duration-200"></textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Diagnostic présumé</label>
                            <textarea name="diagnostic_presume" rows="3" placeholder="Diagnostic présumé ou différentiel"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66] transition-all duration-200"></textarea>
                        </div>
                        <div class="space-y-2 md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Médicaments prescrits</label>
                            <textarea name="medicaments_prescrits" rows="3" placeholder="Liste des médicaments prescrits avec posologie"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66] transition-all duration-200"></textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Propositions de suivi</label>
                            <textarea name="propositions_suivi" rows="2" placeholder="Plan de suivi proposé"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66] transition-all duration-200"></textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Instructions particulières</label>
                            <textarea name="instructions_particulieres" rows="2" placeholder="Instructions spéciales pour le patient"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66] transition-all duration-200"></textarea>
                        </div>
                    </div>
                </div>

                <!-- 5. HABITUDES DE VIE ET ÉVOLUTION -->
                <div id="habitudes-evolution-section" class="bg-gradient-to-r from-[#fdf2f8] to-[#fce7f3] p-6 rounded-[20px] shadow-sm border-l-4 border-[#b9ff66]">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 bg-[#b9ff66] rounded-full flex items-center justify-center">
                            <span class="text-gray-800 font-bold text-sm">5</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                            <svg class="w-6 h-6 text-[#b9ff66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            Habitudes de vie et Évolution
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Habitudes de vie</label>
                            <textarea name="habitudes_vie" rows="2" placeholder="Tabac, alcool, activité physique, alimentation..."
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66] transition-all duration-200"></textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Traitement actuel</label>
                            <textarea name="traitement_actuel" rows="2" placeholder="Médicaments en cours, posologie..."
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66] transition-all duration-200"></textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Évolution des symptômes</label>
                            <textarea name="evolution_symptomes" rows="2" placeholder="Amélioration, aggravation, stabilité..."
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66] transition-all duration-200"></textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Effets secondaires</label>
                            <textarea name="effets_secondaires" rows="2" placeholder="Effets indésirables observés..."
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66] transition-all duration-200"></textarea>
                        </div>
                        <div class="space-y-2 md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Examens de contrôle</label>
                            <textarea name="examens_controle" rows="2" placeholder="Examens complémentaires prescrits..."
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66] transition-all duration-200"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-8 border-t border-gray-200">
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Les champs marqués d'un * sont obligatoires</span>
                    </div>
                    <div class="flex gap-4">
                    <button type="button" onclick="closeConsultationModal()"
                            class="px-6 py-2.5 bg-white text-gray-700 hover:bg-gray-100 border border-gray-300 rounded-lg transition-all duration-200 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        Annuler
                    </button>
                        <button type="submit" id="submit-consultation-btn"
                            class="px-6 py-2.5 bg-[#b9ff66] hover:bg-[#a8eb5f] text-gray-800 rounded-lg flex items-center gap-2 transition-all duration-200 font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Enregistrer la consultation
                    </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Nouvelle Analyse -->
    <div id="newAnalyseModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-[20px] p-8 w-full max-w-2xl mx-4">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">
                    NOUVELLE AN<span class="text-[#b9ff66]">A</span>LYSE
                </h2>
                <button onclick="closeAnalyseModal()" class="hover:bg-gray-100 p-2 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form class="space-y-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type d'analyse</label>
                        <select class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                            <option value="">Sélectionner le type d'analyse</option>
                            <option value="sang">Analyse de sang</option>
                            <option value="urine">Analyse d'urine</option>
                            <option value="bacterio">Analyse bactériologique</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea rows="3" class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]"
                            placeholder="Description de l'analyse demandée..."></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Document d'analyse</label>
                        <div
                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                    viewBox="0 0 48 48">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label
                                        class="relative cursor-pointer bg-white rounded-md font-medium text-[#b9ff66] hover:text-[#a8eb5f]">
                                        <span>Télécharger un fichier</span>
                                        <input type="file" class="sr-only">
                                    </label>
                                    <p class="pl-1">ou glisser-déposer</p>
                                </div>
                                <p class="text-xs text-gray-500">
                                    PNG, JPG, PDF jusqu'à 10MB
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4 pt-4">
                    <button type="button" onclick="closeAnalyseModal()"
                        class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                        Annuler
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-[#b9ff66] hover:bg-[#a8eb5f] rounded-lg flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal d'édition du dossier médical -->
    <div id="editDossierModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-[30px] p-8 w-96 max-h-[80vh] overflow-y-auto">
            <div class="flex justify-between items-start mb-6">
                <h3 class="text-2xl font-bold">Modifier le dossier médical</h3>
                <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="space-y-6">
                <div>
                    <label id="fieldLabel" class="block text-sm font-medium text-gray-700 mb-2">
                        Champ à modifier
                    </label>
                    <div id="fieldInput">
                        <!-- Le contenu sera généré dynamiquement par JavaScript -->
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <button onclick="closeEditModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Annuler
                    </button>
                    <button onclick="saveDossierField()"
                        class="flex-1 px-4 py-2 bg-[#b9ff66] text-gray-800 rounded-lg hover:bg-[#a8eb5f] transition-colors">
                        Enregistrer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal d'affichage des ordonnances -->
    <div id="ordonnanceModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-[20px] w-full max-w-6xl mx-4 max-h-[95vh] overflow-hidden">
            <!-- En-tête de la modal -->
            <div class="flex justify-between items-center p-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Ordonnance #<span id="ordonnanceModalId">-</span></h2>
                        <p class="text-sm text-gray-600">Visualisation du document</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="downloadCurrentOrdonnance()"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg flex items-center gap-2 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Télécharger
                    </button>
                    <button onclick="closeOrdonnanceModal()"
                        class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-full transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Contenu de la modal -->
            <div class="p-6">
                <!-- Zone de chargement -->
                <div id="ordonnanceLoading" class="flex items-center justify-center py-12">
                    <div class="text-center">
                        <div class="w-12 h-12 border-4 border-green-200 border-t-green-600 rounded-full animate-spin mx-auto mb-4"></div>
                        <p class="text-gray-600">Chargement de l'ordonnance...</p>
                    </div>
                </div>

                <!-- Zone d'erreur -->
                <div id="ordonnanceError" class="hidden text-center py-12">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Erreur de chargement</h3>
                    <p class="text-gray-600 mb-4">Impossible de charger l'ordonnance. Vérifiez que le fichier existe et que vous avez les permissions nécessaires.</p>
                    <button onclick="retryLoadOrdonnance()"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                        Réessayer
                    </button>
                </div>

                <!-- Conteneur du fichier -->
                <div id="ordonnanceContent" class="hidden">
                    <!-- Pour les PDF -->
                    <div id="pdfViewer" class="hidden">
                        <iframe id="pdfFrame"
                            class="w-full h-[600px] border border-gray-300 rounded-lg"
                            frameborder="0">
                        </iframe>
                    </div>

                    <!-- Pour les images -->
                    <div id="imageViewer" class="hidden">
                        <div class="text-center">
                            <img id="imageFrame"
                                class="max-w-full max-h-[600px] mx-auto border border-gray-300 rounded-lg shadow-lg"
                                alt="Ordonnance">
                        </div>
                    </div>

                    <!-- Pour les autres types de fichiers -->
                    <div id="fileViewer" class="hidden">
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Fichier non visualisable</h3>
                            <p class="text-gray-600 mb-4">Ce type de fichier ne peut pas être affiché dans le navigateur.</p>
                            <a id="downloadLink" href="#"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Télécharger le fichier
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript pour la modal -->
    <script>
        function openConsultationModal() {
            document.getElementById('newConsultationModal').classList.remove('hidden');
            document.getElementById('newConsultationModal').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeConsultationModal() {
            document.getElementById('newConsultationModal').classList.add('hidden');
            document.getElementById('newConsultationModal').classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        // Fermeture en cliquant en dehors
        document.getElementById('newConsultationModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeConsultationModal();
            }
        });

        function toggleConsultationDetails(consultationId) {
            const detailsDiv = document.getElementById(consultationId);
            const button = detailsDiv.previousElementSibling.querySelector('button');
            const showText = button.querySelector('.show-details');
            const hideText = button.querySelector('.hide-details');
            const arrow = button.querySelector('svg');

            if (detailsDiv.classList.contains('hidden')) {
                // Afficher les détails
                detailsDiv.classList.remove('hidden');
                showText.classList.add('hidden');
                hideText.classList.remove('hidden');
                arrow.style.transform = 'rotate(180deg)';
            } else {
                // Masquer les détails
                detailsDiv.classList.add('hidden');
                showText.classList.remove('hidden');
                hideText.classList.add('hidden');
                arrow.style.transform = 'rotate(0deg)';
            }
        }

        let currentTab = 'consultations';

        function switchTab(tabName) {
            if (currentTab === tabName) return;

            // Masquer l'ancien contenu avec une transition
            const oldContent = document.getElementById(currentTab + '-tab');
            oldContent.style.opacity = '0';

            setTimeout(() => {
                // Masquer tous les contenus
                document.querySelectorAll('.tab-content').forEach(tab => {
                    tab.classList.add('hidden');
                    tab.style.opacity = '0';
                });

                // Réinitialiser tous les boutons
                document.querySelectorAll('.tab-btn').forEach(btn => {
                    btn.classList.remove('text-[#b9ff66]', 'border-[#b9ff66]', 'font-medium');
                    btn.classList.add('text-gray-500', 'border-transparent');
                });

                // Afficher le nouveau contenu
                const newContent = document.getElementById(tabName + '-tab');
                newContent.classList.remove('hidden');

                // Déclencher le repaint avant l'animation
                requestAnimationFrame(() => {
                    newContent.style.opacity = '1';
                });

                // Activer le nouveau bouton
                const activeBtn = document.querySelector(`[data-tab="${tabName}"]`);
                activeBtn.classList.remove('text-gray-500', 'border-transparent');
                activeBtn.classList.add('text-[#b9ff66]', 'border-[#b9ff66]', 'font-medium');

                currentTab = tabName;
            }, 150); // Durée de la transition de sortie
        }

        // Ajouter les styles de transition
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.style.transition = 'opacity 150ms ease-in-out';
        });

        function openAnalyseModal() {
            document.getElementById('newAnalyseModal').classList.remove('hidden');
            document.getElementById('newAnalyseModal').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeAnalyseModal() {
            document.getElementById('newAnalyseModal').classList.add('hidden');
            document.getElementById('newAnalyseModal').classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        // Fermeture en cliquant en dehors
        document.getElementById('newAnalyseModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAnalyseModal();
            }
        });

        function viewAnalyseDetails(analyseId) {
            const detailsDiv = document.getElementById(analyseId);
            const button = detailsDiv.previousElementSibling.querySelector('button');

            if (detailsDiv.classList.contains('hidden')) {
                detailsDiv.classList.remove('hidden');
                button.querySelector('svg').style.transform = 'rotate(180deg)';
            } else {
                detailsDiv.classList.add('hidden');
                button.querySelector('svg').style.transform = 'rotate(0deg)';
            }
        }

        // Fonctions pour le modal d'édition du dossier médical
        function openEditModal(field, value = '') {
            const modal = document.getElementById('editDossierModal');
            const fieldLabel = document.getElementById('fieldLabel');
            const fieldInput = document.getElementById('fieldInput');

            // Configurer le modal selon le champ à éditer
            switch (field) {
                case 'date_naissance':
                    fieldLabel.textContent = 'Date de naissance';
                    fieldInput.innerHTML =
                        '<input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#b9ff66] focus:border-[#b9ff66]">';
                    break;
                case 'groupe_sanguin':
                    fieldLabel.textContent = 'Groupe sanguin';
                    fieldInput.innerHTML = `
                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                    <option value="">Sélectionner un groupe</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                </select>
            `;
                    break;

                case 'antecedents_medicaux':
                    fieldLabel.textContent = 'Antécédents médicaux';
                    fieldInput.innerHTML = `
                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#b9ff66] focus:border-[#b9ff66]"
                          rows="4"
                          placeholder="Entrez les antécédents médicaux (séparés par des virgules)">${value ?? ''}</textarea>
            `;
                    break;
                case 'allergies':
                    fieldLabel.textContent = 'Allergies';
                    fieldInput.innerHTML =
                        `<textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#b9ff66] focus:border-[#b9ff66]"
                          rows="3"
                          placeholder="Entrez les allergies (séparées par des virgules)">${value ?? ''}</textarea>`;
                    break;
            }

            // Stocker le champ en cours d'édition
            modal.dataset.field = field;

            // Afficher le modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeEditModal() {
            const modal = document.getElementById('editDossierModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';

            // Réinitialiser le formulaire
            const fieldInput = document.getElementById('fieldInput');
            fieldInput.innerHTML = '';
        }

        function saveDossierField() {
            const modal = document.getElementById('editDossierModal');
            const field = modal.dataset.field;
            const fieldInput = document.getElementById('fieldInput');

            let value = '';
            const selectElement = fieldInput.querySelector('select');
            const textareaElement = fieldInput.querySelector('textarea');
            const inputElement = fieldInput.querySelector('input');

            if (selectElement) {
                value = selectElement.value;
            } else if (textareaElement) {
                value = textareaElement.value;
            } else if (inputElement) {
                value = inputElement.value;
            }

            if (!value.trim()) {
                alert('Veuillez remplir le champ');
                return;
            }

            // Envoyer les données au serveur
            const formData = new FormData();
            formData.append('field', field);
            formData.append('value', value);
            formData.append('patient_id', '{{ $patient->id }}');
            formData.append('_token', '{{ csrf_token() }}');

            fetch('/medecin/dossier-medical/update', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Recharger la page pour afficher les nouvelles données
                        location.reload();
                    } else {
                        alert('Erreur lors de la sauvegarde: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Erreur lors de la sauvegarde');
                });
        }


        // Variables globales pour la modal d'ordonnance
        let currentOrdonnanceFile = null;
        let currentOrdonnanceId = null;

        // Fonctions pour la modal d'ordonnance
        function openOrdonnanceModal(filePath, ordonnanceId) {
            currentOrdonnanceFile = filePath;
            currentOrdonnanceId = ordonnanceId;

            // Mettre à jour l'ID dans l'en-tête
            document.getElementById('ordonnanceModalId').textContent = ordonnanceId;

            // Afficher la modal
            const modal = document.getElementById('ordonnanceModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';

            // Charger le fichier
            loadOrdonnanceFile(filePath);
        }

        function closeOrdonnanceModal() {
            const modal = document.getElementById('ordonnanceModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';

            // Réinitialiser les variables
            currentOrdonnanceFile = null;
            currentOrdonnanceId = null;
        }

        function loadOrdonnanceFile(filePath) {
            // Afficher le loader
            showOrdonnanceLoading();

            // Déterminer le type de fichier
            const fileExtension = filePath.split('.').pop().toLowerCase();

            // Construire l'URL de visualisation
            const viewUrl = `{{ route('medecin.ordonnances.view') }}?file=${encodeURIComponent(filePath)}`;
            const downloadUrl = `{{ route('medecin.ordonnances.download') }}?file=${encodeURIComponent(filePath)}`;

            // Mettre à jour le lien de téléchargement
            document.getElementById('downloadLink').href = downloadUrl;

            // Gérer selon le type de fichier
            if (fileExtension === 'pdf') {
                loadPDFFile(viewUrl);
            } else if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'].includes(fileExtension)) {
                loadImageFile(viewUrl);
            } else {
                showFileViewer(downloadUrl);
            }
        }

        function showOrdonnanceLoading() {
            document.getElementById('ordonnanceLoading').classList.remove('hidden');
            document.getElementById('ordonnanceError').classList.add('hidden');
            document.getElementById('ordonnanceContent').classList.add('hidden');
        }

        function showOrdonnanceError() {
            document.getElementById('ordonnanceLoading').classList.add('hidden');
            document.getElementById('ordonnanceError').classList.remove('hidden');
            document.getElementById('ordonnanceContent').classList.add('hidden');
        }

        function showOrdonnanceContent() {
            document.getElementById('ordonnanceLoading').classList.add('hidden');
            document.getElementById('ordonnanceError').classList.add('hidden');
            document.getElementById('ordonnanceContent').classList.remove('hidden');
        }

        function loadPDFFile(url) {
            const iframe = document.getElementById('pdfFrame');
            iframe.src = url;

            iframe.onload = function() {
                showOrdonnanceContent();
                document.getElementById('pdfViewer').classList.remove('hidden');
                document.getElementById('imageViewer').classList.add('hidden');
                document.getElementById('fileViewer').classList.add('hidden');
            };

            iframe.onerror = function() {
                showOrdonnanceError();
            };
        }

        function loadImageFile(url) {
            const img = document.getElementById('imageFrame');
            img.src = url;

            img.onload = function() {
                showOrdonnanceContent();
                document.getElementById('imageViewer').classList.remove('hidden');
                document.getElementById('pdfViewer').classList.add('hidden');
                document.getElementById('fileViewer').classList.add('hidden');
            };

            img.onerror = function() {
                showOrdonnanceError();
            };
        }

        function showFileViewer(downloadUrl) {
            showOrdonnanceContent();
            document.getElementById('fileViewer').classList.remove('hidden');
            document.getElementById('pdfViewer').classList.add('hidden');
            document.getElementById('imageViewer').classList.add('hidden');
        }

        function downloadCurrentOrdonnance() {
            if (currentOrdonnanceFile) {
                const downloadUrl = `{{ route('medecin.ordonnances.download') }}?file=${encodeURIComponent(currentOrdonnanceFile)}`;
                window.open(downloadUrl, '_blank');
            }
        }

        function retryLoadOrdonnance() {
            if (currentOrdonnanceFile) {
                loadOrdonnanceFile(currentOrdonnanceFile);
            }
        }

        // Fermeture du modal en cliquant en dehors
        document.addEventListener('DOMContentLoaded', function() {
            const editModal = document.getElementById('editDossierModal');
            if (editModal) {
                editModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeEditModal();
                    }
                });
            }

            const ordonnanceModal = document.getElementById('ordonnanceModal');
            if (ordonnanceModal) {
                ordonnanceModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeOrdonnanceModal();
                    }
                });
            }

            // Logique d'affichage dynamique des champs selon le type de consultation
            const typeConsultationSelect = document.getElementById('type_consultation');
            if (typeConsultationSelect) {
                typeConsultationSelect.addEventListener('change', function() {
                    toggleFieldsByConsultationType(this.value);
                });

                // Initialiser l'affichage au chargement
                toggleFieldsByConsultationType(typeConsultationSelect.value);
            }

            // Nettoyer les erreurs de validation quand l'utilisateur tape
            const form = document.getElementById('newConsultationForm');
            if (form) {
                form.addEventListener('input', function(e) {
                    if (e.target.classList.contains('border-red-500')) {
                        e.target.classList.remove('border-red-500');
                        e.target.classList.add('border-gray-300');
                    }
                });

                form.addEventListener('change', function(e) {
                    if (e.target.classList.contains('border-red-500')) {
                        e.target.classList.remove('border-red-500');
                        e.target.classList.add('border-gray-300');
                    }
                });
            }

            // Auto-calc IMC when poids/taille change
            const poidsInput = document.getElementById('poids-input');
            const tailleInput = document.getElementById('taille-input');
            const imcInput = document.getElementById('imc-input');

            function updateImc() {
                const poids = parseFloat(poidsInput && poidsInput.value ? poidsInput.value : '');
                const tailleCm = parseFloat(tailleInput && tailleInput.value ? tailleInput.value : '');
                if (!isNaN(poids) && poids > 0 && !isNaN(tailleCm) && tailleCm > 0) {
                    const tailleM = tailleCm / 100;
                    const imc = poids / (tailleM * tailleM);
                    if (imcInput) {
                        imcInput.value = Math.round(imc * 10) / 10;
                    }
                }
            }
            if (poidsInput) poidsInput.addEventListener('input', updateImc);
            if (tailleInput) tailleInput.addEventListener('input', updateImc);

            // AJAX submit for new consultation
            const consultationForm = document.getElementById('newConsultationForm');
            if (consultationForm) {
                consultationForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Validation personnalisée avant soumission
                    if (!validateConsultationForm()) {
                        return false;
                    }

                    const submitBtn = consultationForm.querySelector('button[type="submit"]');
                    const originalBtnHtml = submitBtn ? submitBtn.innerHTML : '';
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML =
                            '<svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8v0m0 0a8 8 0 018 8m0 0a8 8 0 01-8 8m0 0a8 8 0 01-8-8"/></svg> Enregistrement...';
                    }

                    const formData = new FormData(consultationForm);
                    fetch(consultationForm.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    }).then(async (res) => {
                        const data = await res.json().catch(() => ({}));
                        if (!res.ok || data.success === false) {
                            const message = (data && (data.message || (data.errors ?
                                'Veuillez vérifier les champs.' : 'Erreur.'))) || 'Erreur';
                            showToast(message, 'error');
                            throw new Error(message);
                        }
                        // Success
                        showToast('Consultation créée avec succès', 'success');
                        closeConsultationModal();
                        // Recharger pour refléter la nouvelle consultation
                        setTimeout(() => {
                        window.location.reload();
                        }, 1000);
                    }).catch((error) => {
                        console.error('Erreur lors de la soumission:', error);
                        showToast('Erreur lors de l\'enregistrement de la consultation', 'error');
                    }).finally(() => {
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalBtnHtml;
                        }
                    });
                });
            }

            // Fonction de validation personnalisée du formulaire
            function validateConsultationForm() {
                const form = document.getElementById('newConsultationForm');
                const typeConsultation = form.querySelector('select[name="type_consultation"]').value;
                let isValid = true;
                let firstInvalidField = null;

                // Validation des champs obligatoires de base
                const requiredFields = [
                    'type_consultation',
                    'motif_consultation',
                    'symptomes',
                    'gravite',
                    'debut_symptomes'
                ];

                requiredFields.forEach(fieldName => {
                    const field = form.querySelector(`[name="${fieldName}"]`);
                    if (field && !field.value.trim()) {
                        isValid = false;
                        if (!firstInvalidField) firstInvalidField = field;
                        field.classList.add('border-red-500');
                        field.classList.remove('border-gray-300');
                    } else if (field) {
                        field.classList.remove('border-red-500');
                        field.classList.add('border-gray-300');
                    }
                });

                // Validation des champs selon le type de consultation
                const visibleSections = document.querySelectorAll('#newConsultationModal [id$="-section"]:not(.hidden-section)');
                visibleSections.forEach(section => {
                    const requiredInputs = section.querySelectorAll('input[required], select[required], textarea[required]');
                    requiredInputs.forEach(input => {
                        if (!input.value.trim()) {
                            isValid = false;
                            if (!firstInvalidField) firstInvalidField = input;
                            input.classList.add('border-red-500');
                            input.classList.remove('border-gray-300');
                        } else {
                            input.classList.remove('border-red-500');
                            input.classList.add('border-gray-300');
                        }
                    });
                });

                // Validation spécifique pour les champs numériques
                const numericFields = [
                    { name: 'saturation_o2', min: 0, max: 100 },
                    { name: 'frequence_cardiaque', min: 30, max: 200 },
                    { name: 'temperature', min: 30, max: 45 },
                    { name: 'score_glasgow', min: 3, max: 15 }
                ];

                numericFields.forEach(fieldConfig => {
                    const field = form.querySelector(`[name="${fieldConfig.name}"]`);
                    if (field && field.value) {
                        const value = parseFloat(field.value);
                        if (isNaN(value) || value < fieldConfig.min || value > fieldConfig.max) {
                            isValid = false;
                            if (!firstInvalidField) firstInvalidField = field;
                            field.classList.add('border-red-500');
                            field.classList.remove('border-gray-300');
                            showToast(`Valeur invalide pour ${fieldConfig.name}. Doit être entre ${fieldConfig.min} et ${fieldConfig.max}`, 'error');
                        } else {
                            field.classList.remove('border-red-500');
                            field.classList.add('border-gray-300');
                        }
                    }
                });

                // Focus sur le premier champ invalide
                if (!isValid && firstInvalidField) {
                    firstInvalidField.focus();
                    firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    showToast('Veuillez remplir tous les champs obligatoires', 'error');
                }

                return isValid;
            }

            // Fonction toast améliorée
            function showToast(message, type = 'info') {
                const toast = document.createElement('div');
                toast.textContent = message;

                const colors = {
                    success: 'bg-green-500 text-white',
                    error: 'bg-red-500 text-white',
                    warning: 'bg-yellow-500 text-gray-800',
                    info: 'bg-blue-500 text-white'
                };

                toast.className = `fixed top-4 right-4 z-[60] ${colors[type] || colors.info} px-4 py-2 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
                document.body.appendChild(toast);

                // Animation d'entrée
                setTimeout(() => {
                    toast.classList.remove('translate-x-full');
                }, 100);

                // Auto-remove après 4 secondes
                setTimeout(() => {
                    toast.classList.add('translate-x-full');
                    setTimeout(() => {
                        if (toast.parentNode) {
                            toast.parentNode.removeChild(toast);
                        }
                    }, 300);
                }, 4000);
            }

            // Dynamique: charger les consultations du patient
            const consultationsList = document.getElementById('consultations-list');
            const loadingEl = document.getElementById('consultations-loading');
            const searchEl = document.getElementById('consultations-search');
            const typeEl = document.getElementById('consultations-type');
            const periodEl = document.getElementById('consultations-period');

            async function fetchConsultations() {
                if (!consultationsList) return;
                const params = new URLSearchParams();
                if (searchEl && searchEl.value) params.append('search', searchEl.value);
                if (typeEl && typeEl.value) params.append('type', typeEl.value);
                if (periodEl && periodEl.value) params.append('period', periodEl.value);
                const url =
                    `/api/medecin/patients/{{ $patient->id }}/consultations/list?${params.toString()}`;
                try {
                    if (loadingEl) loadingEl.classList.remove('hidden');
                    const res = await fetch(url, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    const data = await res.json();
                    if (!data.success) throw new Error('Erreur chargement');
                    renderConsultations(data.consultations || []);
                } catch (e) {
                    consultationsList.innerHTML =
                        `<div class="bg-white p-6 rounded-[20px] shadow-sm text-center text-red-600">Erreur de chargement</div>`;
                } finally {
                    if (loadingEl) loadingEl.classList.add('hidden');
                }
            }

            function renderConsultations(items) {
                if (!items.length) {
                    consultationsList.innerHTML =
                        `<div class="bg-white p-6 rounded-[20px] shadow-sm text-center text-gray-500">Aucune consultation trouvée</div>`;
                    return;
                }
                consultationsList.innerHTML = items.map((c) => consultationCardHtml(c)).join('');
                // Rebind toggle buttons
                consultationsList.querySelectorAll('[data-toggle-id]').forEach((btn) => {
                    btn.addEventListener('click', () => {
                        const id = btn.getAttribute('data-toggle-id');
                        toggleConsultationDetails(id);
                        const textShow = btn.querySelector('.show-details');
                        const textHide = btn.querySelector('.hide-details');
                        const arrow = btn.querySelector('svg');
                        const detailsDiv = document.getElementById(id);
                        if (detailsDiv && !detailsDiv.classList.contains('hidden')) {
                            if (textShow) textShow.classList.add('hidden');
                            if (textHide) textHide.classList.remove('hidden');
                            if (arrow) arrow.style.transform = 'rotate(180deg)';
                        } else {
                            if (textShow) textShow.classList.remove('hidden');
                            if (textHide) textHide.classList.add('hidden');
                            if (arrow) arrow.style.transform = 'rotate(0deg)';
                        }
                    });
                });
            }

            function escapeHtml(s) {
                if (s === null || s === undefined) return '';
                return String(s)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function consultationCardHtml(c) {
                const idBase = `consultation-${c.id}`;
                const dateStr = c.date_consultation ? new Date(c.date_consultation).toLocaleDateString() : '';
                const typeLabelMap = {
                    premiere: 'Consultation première fois',
                    routine: 'Consultation de routine',
                    controle: 'Consultation de contrôle',
                    urgence: "Consultation d'urgence",
                    suivi: 'Consultation de suivi'
                };
                const typeLabel = typeLabelMap[c.type_consultation] || c.type_consultation || 'Consultation';
                const motif = escapeHtml(c.motif_consultation || '');
                const sympt = escapeHtml(c.symptomes || '');
                const exam = escapeHtml(c.examen_physique || '');
                const diag = escapeHtml(c.diagnostic_presume || '');
                const meds = escapeHtml(c.medicaments_prescrits || '');
                const suivi = escapeHtml(c.propositions_suivi || '');
                const instr = escapeHtml(c.instructions_particulieres || '');
                const symptAigus = escapeHtml(c.symptomes_aigus || '');
                const debutSympt = c.debut_symptomes ? new Date(c.debut_symptomes).toLocaleDateString() : '';
                const gravite = escapeHtml(c.gravite || '');
                const orientation = escapeHtml(c.orientation_patient || '');
                const ta = escapeHtml(c.tension_arterielle || '');
                const spo2 = c.saturation_o2 != null ? `${c.saturation_o2} %` : '';
                const fc = c.frequence_cardiaque != null ? `${c.frequence_cardiaque} bpm` : '';
                const temp = c.temperature != null ? `${c.temperature} °C` : '';
                const glasgow = c.score_glasgow != null ? `${c.score_glasgow}` : '';
                return `
        <div class="bg-white p-6 rounded-[20px] shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h4 class="font-bold text-lg">${escapeHtml(typeLabel)}</h4>
                    <p class="text-gray-500">${escapeHtml(dateStr)}</p>
                    ${motif ? `<p class=\"text-gray-600 mt-1\">Motif : ${motif}</p>` : ''}
                </div>
                <div class="flex items-center gap-2">
                    <button data-toggle-id="${idBase}"
                            class="text-[#b9ff66] hover:text-[#a8eb5f] text-sm flex items-center gap-1">
                        <span class="show-details">Voir les détails</span>
                        <span class="hide-details hidden">Masquer les détails</span>
                        <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <a href="/medecin/consultations/${c.id}"
                       class="px-2 py-1.5 text-gray-700 hover:bg-gray-100 rounded-lg text-sm flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Voir
                    </a>
                    <a href="/medecin/consultations/${c.id}/edit"
                       class="px-2 py-1.5 text-[#1f2937] bg-[#e8ffd0] hover:bg-[#d9ffad] rounded-lg text-sm flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Modifier
                    </a>
                    <!-- Génération IA Ambulatoire -->
                    <a href="/medecin/ambulatory-ai/consultations/${c.id}/generation"
                       class="px-2 py-1.5 text-[#1f2937] bg-[#e8ffd0] hover:bg-[#d9ffad] rounded-lg text-sm flex items-center gap-1">
                        <i class="fas fa-robot mr-2 text-green-500"></i>🏥 IA Ambulatoire
                    </a>
                    <!-- Fin Génération IA Ambulatoire -->
                </div>
            </div>
            <div id="${idBase}" class="hidden space-y-4 mt-4 border-t pt-4">
                ${sympt ? `<div><h5 class=\"font-medium text-gray-700 mb-2\">Symptômes</h5><p class=\"text-gray-600\">${sympt}</p></div>` : ''}
                ${(symptAigus || debutSympt || gravite || orientation) ? `
                        <div class=\"grid grid-cols-1 md:grid-cols-2 gap-4\">
                            ${symptAigus ? `<div><p class=\"text-sm text-gray-500\">Symptômes aigus</p><p class=\"text-gray-600\">${symptAigus}</p></div>` : ''}
                            ${debutSympt ? `<div><p class=\"text-sm text-gray-500\">Début des symptômes</p><p class=\"text-gray-600\">${debutSympt}</p></div>` : ''}
                            ${gravite ? `<div><p class=\"text-sm text-gray-500\">Gravité</p><p class=\"text-gray-600\">${gravite}</p></div>` : ''}
                            ${orientation ? `<div><p class=\"text-sm text-gray-500\">Orientation</p><p class=\"text-gray-600\">${orientation}</p></div>` : ''}
                        </div>` : ''}
                ${(ta || spo2 || fc || temp || glasgow) ? `
                        <div>
                            <h5 class=\"font-medium text-gray-700 mb-2\">Paramètres Cliniques</h5>
                            <div class=\"grid grid-cols-2 sm:grid-cols-3 gap-4 bg-gray-50 p-4 rounded-lg\">
                                ${ta ? `<div><p class=\"text-sm text-gray-500\">Tension artérielle</p><p class=\"font-medium\">${ta}</p></div>` : ''}
                                ${spo2 ? `<div><p class=\"text-sm text-gray-500\">Saturation O2</p><p class=\"font-medium\">${spo2}</p></div>` : ''}
                                ${fc ? `<div><p class=\"text-sm text-gray-500\">Fréquence cardiaque</p><p class=\"font-medium\">${fc}</p></div>` : ''}
                                ${temp ? `<div><p class=\"text-sm text-gray-500\">Température</p><p class=\"font-medium\">${temp}</p></div>` : ''}
                                ${glasgow ? `<div><p class=\"text-sm text-gray-500\">Glasgow</p><p class=\"font-medium\">${glasgow}</p></div>` : ''}
                            </div>
                        </div>` : ''}
                ${(exam || diag) ? `
                        <div>
                            <h5 class=\"font-medium text-gray-700 mb-2\">Examen Clinique</h5>
                            <div class=\"space-y-3\">
                                ${exam ? `<div><p class=\"text-sm text-gray-500\">Examen physique</p><p class=\"text-gray-600\">${exam}</p></div>` : ''}
                                ${diag ? `<div><p class=\"text-sm text-gray-500\">Diagnostic présumé</p><p class=\"text-gray-600\">${diag}</p></div>` : ''}
                            </div>
                        </div>` : ''}
                ${(meds || suivi || instr) ? `
                        <div>
                            <h5 class=\"font-medium text-gray-700 mb-2\">Plan de Traitement</h5>
                            <div class=\"space-y-3\">
                                ${meds ? `<div><p class=\"text-sm text-gray-500\">Médicaments prescrits</p><p class=\"text-gray-600\">${meds}</p></div>` : ''}
                                ${suivi ? `<div><p class=\"text-sm text-gray-500\">Suivi proposé</p><p class=\"text-gray-600\">${suivi}</p></div>` : ''}
                                ${instr ? `<div><p class=\"text-sm text-gray-500\">Instructions particulières</p><p class=\"text-gray-600\">${instr}</p></div>` : ''}
                            </div>
                        </div>` : ''}
            </div>
        </div>`;
            }

            // Déclenchements
            if (searchEl) searchEl.addEventListener('input', debounce(fetchConsultations, 300));
            if (typeEl) typeEl.addEventListener('change', fetchConsultations);
            if (periodEl) periodEl.addEventListener('change', fetchConsultations);

            // Charger initialement
            fetchConsultations();

            // utilitaire debounce
            function debounce(fn, wait) {
                let t;
                return (...args) => {
                    clearTimeout(t);
                    t = setTimeout(() => fn.apply(null, args), wait);
                };
            }
        });

        // Fonction pour afficher/masquer les champs selon le type de consultation
        function toggleFieldsByConsultationType(type) {
            const sections = {
                'parametres-cliniques-section': document.getElementById('parametres-cliniques-section'),
                'mesures-physiques-section': document.getElementById('mesures-physiques-section'),
                'examen-diagnostic-section': document.getElementById('examen-diagnostic-section'),
                'habitudes-evolution-section': document.getElementById('habitudes-evolution-section'),
                'symptomes-aigus-section': document.getElementById('symptomes-aigus-section')
            };

            // Désactiver la validation des champs dans les sections masquées
            Object.values(sections).forEach(section => {
                if (section) {
                    const inputs = section.querySelectorAll('input, select, textarea');
                    inputs.forEach(input => {
                        input.removeAttribute('required');
                        input.setAttribute('data-was-required', input.hasAttribute('required') ? 'true' : 'false');
                    });
                }
            });

            // Masquer toutes les sections d'abord avec animation
            Object.values(sections).forEach(section => {
                if (section) {
                    section.style.opacity = '0';
                    section.style.transform = 'translateY(-10px)';
                    section.classList.add('hidden-section');
                    setTimeout(() => {
                        section.style.display = 'none';
                    }, 300);
                }
            });

            // Définir les sections à afficher selon le type de consultation
            let sectionsToShow = [];
            let delay = 100;

            switch (type) {
                case 'premiere':
                    // Première consultation : tous les champs
                    sectionsToShow = [
                        'parametres-cliniques-section',
                        'mesures-physiques-section',
                        'examen-diagnostic-section',
                        'habitudes-evolution-section'
                    ];
                    break;

                case 'urgence':
                    // Consultation d'urgence : champs vitaux et symptômes aigus en priorité
                    sectionsToShow = [
                        'parametres-cliniques-section',
                        'symptomes-aigus-section',
                        'examen-diagnostic-section'
                    ];
                    break;

                case 'controle':
                    // Consultation de contrôle : suivi du traitement et évolution
                    sectionsToShow = [
                        'examen-diagnostic-section',
                        'habitudes-evolution-section',
                        'parametres-cliniques-section'
                    ];
                    break;

                case 'routine':
                    // Consultation de routine : paramètres de base et suivi simple
                    sectionsToShow = [
                        'parametres-cliniques-section',
                        'mesures-physiques-section',
                        'examen-diagnostic-section'
                    ];
                    break;

                case 'suivi':
                    // Consultation de suivi : évolution et examens de contrôle
                    sectionsToShow = [
                        'habitudes-evolution-section',
                        'examen-diagnostic-section'
                    ];
                    break;

                default:
                    // Par défaut, afficher toutes les sections
                    sectionsToShow = [
                        'parametres-cliniques-section',
                        'mesures-physiques-section',
                        'examen-diagnostic-section',
                        'habitudes-evolution-section'
                    ];
            }

            // Afficher les sections sélectionnées avec animation
            sectionsToShow.forEach(sectionId => {
                const section = sections[sectionId];
                if (section) {
                    setTimeout(() => {
                        section.style.display = 'block';
                        section.classList.remove('hidden-section');
                        // Forcer le repaint
                        section.offsetHeight;
                        section.style.opacity = '1';
                        section.style.transform = 'translateY(0)';

                        // Réactiver la validation des champs dans les sections visibles
                        const inputs = section.querySelectorAll('input, select, textarea');
                        inputs.forEach(input => {
                            if (input.getAttribute('data-was-required') === 'true') {
                                input.setAttribute('required', 'required');
                            }
                        });
                    }, delay);
                    delay += 150; // Délai progressif pour l'animation
                }
            });

            // Ajouter des transitions CSS à toutes les sections
            Object.values(sections).forEach(section => {
                if (section) {
                    section.style.transition = 'all 0.3s ease-in-out';
                }
            });

            // Mettre à jour l'indicateur visuel du type de consultation
            updateConsultationTypeIndicator(type);
        }

        // Fonction pour mettre à jour l'indicateur visuel du type de consultation
        function updateConsultationTypeIndicator(type) {
            const typeLabels = {
                'premiere': 'Première consultation - Tous les champs requis',
                'urgence': 'Consultation d\'urgence - Champs vitaux prioritaires',
                'controle': 'Consultation de contrôle - Suivi du traitement',
                'routine': 'Consultation de routine - Paramètres de base',
                'suivi': 'Consultation de suivi - Évolution et examens'
            };

            // Créer ou mettre à jour l'indicateur
            let indicator = document.getElementById('consultation-type-indicator');
            if (!indicator) {
                indicator = document.createElement('div');
                indicator.id = 'consultation-type-indicator';
                indicator.className = 'fixed top-4 right-4 z-50 bg-[#b9ff66] text-gray-800 px-4 py-2 rounded-lg shadow-lg text-sm font-medium';
                document.body.appendChild(indicator);
            }

            indicator.textContent = typeLabels[type] || 'Type de consultation sélectionné';

            // Animation d'apparition
            indicator.style.opacity = '0';
            indicator.style.transform = 'translateX(100%)';
            setTimeout(() => {
                indicator.style.transition = 'all 0.3s ease-in-out';
                indicator.style.opacity = '1';
                indicator.style.transform = 'translateX(0)';
            }, 100);

            // Masquer l'indicateur après 3 secondes
            setTimeout(() => {
                indicator.style.opacity = '0';
                indicator.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (indicator.parentNode) {
                        indicator.parentNode.removeChild(indicator);
                    }
                }, 300);
            }, 3000);
        }
    </script>

    <style>
        /* Styles pour les transitions */
        .tab-content {
            transition: opacity 150ms ease-in-out;
        }

        /* Éviter le chevauchement des contenus pendant les transitions */
        .tab-content.hidden {
            display: none;
        }

        /* Améliorer l'apparence des onglets */
        .tab-btn {
            position: relative;
            transition: all 150ms ease-in-out;
        }

        .tab-btn::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #b9ff66;
            transform: scaleX(0);
            transition: transform 150ms ease-in-out;
        }

        .tab-btn.active::after {
            transform: scaleX(1);
        }

        /* Améliorer le contraste des onglets inactifs au survol */
        .tab-btn:hover:not(.active) {
            color: #4a5568;
        }

        /* Styles pour la modal de consultation améliorée */
        #newConsultationModal .bg-gradient-to-r {
            background-size: 200% 200%;
            animation: gradientShift 3s ease infinite;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Améliorer les transitions des sections */
        #newConsultationModal [id$="-section"] {
            transition: all 0.3s ease-in-out;
            transform: translateY(0);
        }

        #newConsultationModal [id$="-section"]:not([style*="display: block"]) {
            transform: translateY(-10px);
        }

        /* Styles pour les champs obligatoires */
        #newConsultationModal input[required],
        #newConsultationModal select[required],
        #newConsultationModal textarea[required] {
            border-left: 3px solid #ef4444;
        }

        #newConsultationModal input[required]:focus,
        #newConsultationModal select[required]:focus,
        #newConsultationModal textarea[required]:focus {
            border-left-color: #b9ff66;
        }

        /* Améliorer l'apparence des labels avec icônes */
        #newConsultationModal label svg {
            transition: transform 0.2s ease;
        }

        #newConsultationModal label:hover svg {
            transform: scale(1.1);
        }

        /* Styles pour les sections avec numérotation */
        #newConsultationModal .w-8.h-8 {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Améliorer la lisibilité des placeholders */
        #newConsultationModal input::placeholder,
        #newConsultationModal textarea::placeholder {
            color: #9ca3af;
            font-style: italic;
        }

        /* Styles pour les boutons d'action */
        #submit-consultation-btn {
            box-shadow: 0 4px 6px rgba(185, 255, 102, 0.3);
        }

        #submit-consultation-btn:hover {
            box-shadow: 0 6px 8px rgba(185, 255, 102, 0.4);
            transform: translateY(-1px);
        }

        /* Responsive improvements */
        @media (max-width: 768px) {
            #newConsultationModal .grid {
                grid-template-columns: 1fr;
            }

            #newConsultationModal .lg\\:col-span-2 {
                grid-column: span 1;
            }
        }

        /* Styles pour l'affichage dynamique des sections */
        #newConsultationModal [id$="-section"] {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateY(0);
            opacity: 1;
        }

        #newConsultationModal [id$="-section"].hidden {
            opacity: 0;
            transform: translateY(-20px);
            pointer-events: none;
        }

        /* Animation pour l'indicateur de type de consultation */
        #consultation-type-indicator {
            backdrop-filter: blur(10px);
            border: 1px solid rgba(185, 255, 102, 0.3);
            box-shadow: 0 8px 32px rgba(185, 255, 102, 0.2);
        }

        /* Améliorer les transitions des champs obligatoires */
        #newConsultationModal input[required]:focus,
        #newConsultationModal select[required]:focus,
        #newConsultationModal textarea[required]:focus {
            box-shadow: 0 0 0 3px rgba(185, 255, 102, 0.1);
            border-color: #b9ff66;
        }

        /* Animation pour les sections qui apparaissent */
        @keyframes slideInFromTop {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #newConsultationModal [id$="-section"].animate-in {
            animation: slideInFromTop 0.4s ease-out;
        }

        /* Styles pour les sections selon leur priorité */
        #newConsultationModal #parametres-cliniques-section {
            border-left: 4px solid #b9ff66;
        }

        #newConsultationModal #mesures-physiques-section {
            border-left: 4px solid #b9ff66;
        }

        #newConsultationModal #examen-diagnostic-section {
            border-left: 4px solid #b9ff66;
        }

        #newConsultationModal #habitudes-evolution-section {
            border-left: 4px solid #b9ff66;
        }

        #newConsultationModal #symptomes-aigus-section {
            border-left: 4px solid #ef4444;
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        }

        /* Améliorer la visibilité des champs critiques */
        #newConsultationModal #symptomes-aigus-section label {
            color: #dc2626;
            font-weight: 700;
        }

        #newConsultationModal #symptomes-aigus-section textarea {
            border-color: #fca5a5;
            background-color: #fef2f2;
        }

        #newConsultationModal #symptomes-aigus-section textarea:focus {
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

        /* Styles pour les champs en erreur */
        #newConsultationModal .border-red-500 {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
        }

        #newConsultationModal .border-red-500:focus {
            border-color: #dc2626 !important;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.2) !important;
        }

        /* Animation pour les champs en erreur */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        #newConsultationModal .border-red-500 {
            animation: shake 0.5s ease-in-out;
        }

        /* Améliorer la visibilité des sections masquées */
        #newConsultationModal .hidden-section {
            display: none !important;
            opacity: 0;
            transform: translateY(-20px);
            pointer-events: none;
        }

        /* Styles pour les toasts */
        .toast-enter {
            transform: translateX(100%);
            opacity: 0;
        }

        .toast-enter-active {
            transform: translateX(0);
            opacity: 1;
            transition: all 0.3s ease-in-out;
        }

        .toast-exit {
            transform: translateX(0);
            opacity: 1;
        }

        .toast-exit-active {
            transform: translateX(100%);
            opacity: 0;
            transition: all 0.3s ease-in-out;
        }

        /* Styles pour l'affichage ergonomique des ordonnances */
        .ordonnance-card {
            transition: all 0.3s ease;
        }

        .ordonnance-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .medicament-item {
            transition: all 0.2s ease;
        }

        .medicament-item:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
        }

        .notes-section {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        }

        .pdf-section {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        }

        .medicaments-section {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        }

        /* Animation pour les icônes */
        .section-icon {
            transition: all 0.3s ease;
        }

        .section-icon:hover {
            transform: scale(1.1) rotate(5deg);
        }

        /* Styles pour les boutons d'action */
        .action-button {
            transition: all 0.2s ease;
        }

        .action-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        /* Responsive design pour les ordonnances */
        @media (max-width: 1024px) {
            .ordonnance-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Animation d'apparition */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .ordonnance-card {
            animation: fadeInUp 0.5s ease-out;
        }

        /* Styles pour les états vides */
        .empty-state {
            transition: all 0.3s ease;
        }

        .empty-state:hover {
            background-color: #f9fafb;
        }

        /* Styles pour la modal d'ordonnance */
        #ordonnanceModal {
            backdrop-filter: blur(4px);
        }

        #ordonnanceModal .bg-white {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        /* Animation d'entrée pour la modal */
        #ordonnanceModal {
            animation: modalFadeIn 0.3s ease-out;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Styles pour l'iframe PDF */
        #pdfFrame {
            transition: all 0.3s ease;
        }

        #pdfFrame:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Styles pour l'image */
        #imageFrame {
            transition: all 0.3s ease;
            cursor: zoom-in;
        }

        #imageFrame:hover {
            transform: scale(1.02);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        /* Animation du loader */
        .animate-spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        /* Responsive pour la modal */
        @media (max-width: 768px) {
            #ordonnanceModal .max-w-6xl {
                max-width: 95vw;
                margin: 0.5rem;
            }

            #pdfFrame {
                height: 400px;
            }

            #imageFrame {
                max-height: 400px;
            }
        }

        /* Styles pour les boutons d'action */
        .action-button {
            transition: all 0.2s ease;
        }

        .action-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        /* Animation pour les zones de contenu */
        #ordonnanceContent {
            animation: contentFadeIn 0.5s ease-out;
        }

        @keyframes contentFadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection
