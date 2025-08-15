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
                    $photoUrl = $patient->user && $patient->user->profile_photo_path
                        ? asset('storage/' . $patient->user->profile_photo_path)
                        : 'https://ui-avatars.com/api/?name=' . urlencode($patient->prenom . ' ' . $patient->nom);
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
                                @if(isset($patient->dateNaissance))
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
                        @if(!$dossier || !$dossier->groupe_sanguin)
                            <button onclick="openEditModal('groupe_sanguin')" class="px-3 py-1.5 bg-[#b9ff66] text-gray-800 rounded-full text-sm hover:bg-[#a8eb5f] transition-colors">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Ajouter
                            </button>
                        @else
                            <button onclick="openEditModal('groupe_sanguin')" class="px-3 py-1.5 bg-blue-500 text-white rounded-full text-sm hover:bg-blue-600 transition-colors">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Modifier
                            </button>
                        @endif
                    </div>
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-500">Contact</p>
                            <p class="font-medium">{{ $dossier->tel ?? 'Non renseigné' }}</p>
                            <p class="text-sm text-gray-600">{{ $patient->user->email ?? '' }}</p>
                        </div>
                        @if(!$dossier || !$dossier->tel)
                            <button onclick="openEditModal('tel')" class="px-3 py-1.5 bg-[#b9ff66] text-gray-800 rounded-full text-sm hover:bg-[#a8eb5f] transition-colors">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Ajouter
                            </button>
                        @else
                            <button onclick="openEditModal('tel')" class="px-3 py-1.5 bg-blue-500 text-white rounded-full text-sm hover:bg-blue-600 transition-colors">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Modifier
                            </button>
                        @endif
                    </div>
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-500">Adresse</p>
                            <p class="font-medium">{{ $dossier->adresse ?? 'Non renseignée' }}</p>
                            <p class="text-sm text-gray-600">{{ $patient->ville ?? '' }}{{ $patient->code_postal ? ', '.$patient->code_postal : '' }}</p>
                        </div>
                        @if(!$dossier || !$dossier->adresse)
                            <button onclick="openEditModal('adresse')" class="px-3 py-1.5 bg-[#b9ff66] text-gray-800 rounded-full text-sm hover:bg-[#a8eb5f] transition-colors">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Ajouter
                            </button>
                        @else
                            <button onclick="openEditModal('adresse')" class="px-3 py-1.5 bg-blue-500 text-white rounded-full text-sm hover:bg-blue-600 transition-colors">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
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
                    @if(!$dossier || !$dossier->antecedents_medicaux)
                        <button onclick="openEditModal('antecedents_medicaux')" class="px-3 py-1.5 bg-[#b9ff66] text-gray-800 rounded-full text-sm hover:bg-[#a8eb5f] transition-colors">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Ajouter
                        </button>
                    @else
                        <button onclick="openEditModal('antecedents_medicaux')" class="px-3 py-1.5 bg-blue-500 text-white rounded-full text-sm hover:bg-blue-600 transition-colors">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Modifier
                        </button>
                    @endif
                </div>
                <div class="space-y-3">
                    @if($dossier && $dossier->antecedents_medicaux)
                        @foreach(explode(',', $dossier->antecedents_medicaux) as $ant)
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
                    @if(!$dossier || !$dossier->allergies)
                        <button onclick="openEditModal('allergies')" class="px-3 py-1.5 bg-[#b9ff66] text-gray-800 rounded-full text-sm hover:bg-[#a8eb5f] transition-colors">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Ajouter
                        </button>
                    @else
                        <button onclick="openEditModal('allergies')" class="px-3 py-1.5 bg-blue-500 text-white rounded-full text-sm hover:bg-blue-600 transition-colors">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Modifier
                        </button>
                    @endif
                </div>
                <div class="space-y-2">
                    @if($dossier && $dossier->allergies)
                        @foreach(explode(',', $dossier->allergies) as $all)
                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-sm">{{ $all }}</span>
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
                                <input type="text"
                                       placeholder="Rechercher une consultation..."
                                       class="w-full pl-10 pr-4 py-2 rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <!-- Filtres -->
                            <div class="flex gap-2">
                                <select class="rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                                    <option value="">Type de consultation</option>
                                    <option value="routine">Consultation de routine</option>
                                    <option value="urgence">Consultation d'urgence</option>
                                    <option value="suivi">Consultation de suivi</option>
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
                                <button onclick="openConsultationModal()"
                                        class="bg-[#b9ff66] hover:bg-[#a8eb5f] text-black rounded-lg px-4 py-2 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Nouvelle Consultation
                                </button>
                                <button class="bg-gray-800 text-white rounded-lg px-4 py-2 flex items-center gap-2 hover:bg-gray-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Exporter
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Consultation avec détails extensibles -->
                    <div class="bg-white p-6 rounded-[20px] shadow-sm">
                        <!-- En-tête de consultation (toujours visible) -->
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h4 class="font-bold text-lg">Consultation de routine</h4>
                                <p class="text-gray-500">15 Mars 2024</p>
                                <p class="text-gray-600 mt-1">Motif : Suivi diabète et tension artérielle</p>

                            </div>
                            <div class="flex flex-col items-end gap-2">
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                                    Terminée
                                </span>
                                <button onclick="toggleConsultationDetails('consultation-1')"
                                        class="text-[#b9ff66] hover:text-[#a8eb5f] text-sm flex items-center gap-1">
                                    <span class="show-details">Voir les détails</span>
                                    <span class="hide-details hidden">Masquer les détails</span>
                                    <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Détails de la consultation (initialement cachés) -->
                        <div id="consultation-1" class="hidden space-y-4 mt-4 border-t pt-4">
                            <!-- Informations de base -->
                            <div>
                                <h5 class="font-medium text-gray-700 mb-2">Motif</h5>
                                <p class="text-gray-600">Suivi diabète et tension artérielle</p>
                            </div>
                            <div>
                                <h5 class="font-medium text-gray-700 mb-2">Symptômes</h5>
                                <p class="text-gray-600">Fatigue persistante, soif excessive depuis 3 jours</p>
                            </div>

                            <!-- Paramètres cliniques -->
                            <div>
                                <h5 class="font-medium text-gray-700 mb-2">Paramètres Cliniques</h5>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 bg-gray-50 p-4 rounded-lg">
                                    <div>
                                        <p class="text-sm text-gray-500">Tension artérielle</p>
                                        <p class="font-medium">13/8 mmHg</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Saturation O2</p>
                                        <p class="font-medium">98 %</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Fréquence cardiaque</p>
                                        <p class="font-medium">75 bpm</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Température</p>
                                        <p class="font-medium">37.2 °C</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Glasgow</p>
                                        <p class="font-medium">15</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Examen clinique -->
                            <div>
                                <h5 class="font-medium text-gray-700 mb-2">Examen Clinique</h5>
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-sm text-gray-500">Examen physique</p>
                                        <p class="text-gray-600">Pas de signes cliniques évidents. Bon état général.</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Diagnostic présumé</p>
                                        <p class="text-gray-600">Déséquilibre glycémique léger</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Plan de traitement -->
                            <div>
                                <h5 class="font-medium text-gray-700 mb-2">Plan de Traitement</h5>
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-sm text-gray-500">Médicaments prescrits</p>
                                        <ul class="list-disc list-inside text-gray-600">
                                            <li>Metformine 1000mg - 2x/jour</li>
                                            <li>Amlodipine 5mg - 1x/jour</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Suivi proposé</p>
                                        <p class="text-gray-600">Contrôle dans 2 semaines avec bilan glycémique</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Instructions particulières</p>
                                        <p class="text-gray-600">
                                            - Surveillance glycémique quotidienne<br>
                                            - Régime pauvre en sucres rapides<br>
                                            - Activité physique modérée recommandée
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-3 mt-6 pt-4 border-t">
                                <button class="text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    Voir l'ordonnance
                                </button>
                                <button class="text-[#b9ff66] hover:text-[#a8eb5f] flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Modifier
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Autre consultation (exemple) -->
                    <div class="bg-white p-6 rounded-[20px] shadow-sm">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-bold text-lg">Consultation de contrôle</h4>
                                <p class="text-gray-500">15 Février 2024</p>
                                <p class="text-gray-600 mt-1">Motif : Contrôle tension artérielle</p>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                                    Terminée
                                </span>
                                <button onclick="toggleConsultationDetails('consultation-2')"
                                        class="text-[#b9ff66] hover:text-[#a8eb5f] text-sm flex items-center gap-1">
                                    <span class="show-details">Voir les détails</span>
                                    <span class="hide-details hidden">Masquer les détails</span>
                                    <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div id="consultation-2" class="hidden space-y-4 mt-4 border-t pt-4">
                            <!-- Contenu similaire à la première consultation -->
                        </div>

                    </div>
                    <!-- Pagination -->
            <div class="flex justify-between items-center bg-white p-4 rounded-[20px] shadow-sm">
                <span class="text-sm text-gray-500">Affichage de 1 à 2 sur 15 consultations</span>
                <div class="flex gap-2">
                    <button class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100" disabled>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <button class="w-8 h-8 flex items-center justify-center rounded-full bg-[#b9ff66]">1</button>
                    <button class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100">2</button>
                    <button class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100">3</button>
                    <button class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
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
                            <input type="text"
                                   placeholder="Rechercher une ordonnance..."
                                   class="w-full pl-10 pr-4 py-2 rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Nouvelle Ordonnance
                                </button>
                                <button class="bg-gray-800 text-white rounded-lg px-4 py-2 flex items-center gap-2 hover:bg-gray-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Exporter
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Liste des ordonnances -->
                    <div class="space-y-4">
                        <!-- Ordonnance -->
                        <div class="bg-white p-6 rounded-[20px] shadow-sm">
                            <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-2">
                                        <h3 class="font-bold text-lg">Ordonnance #ORD-001</h3>
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                            En cours
                                        </span>
                                    </div>
                                    <p class="text-gray-500">Prescrite le 15 Mars 2024</p>
                                    <p class="text-gray-600 mt-1">Dr. Alami Mohammed</p>
                                </div>
                                <div class="flex gap-2">
                                    <button class="px-4 py-2 bg-[#b9ff66] hover:bg-[#a8eb5f] rounded-lg flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        Télécharger PDF
                                    </button>
                                    <button class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Voir détails
                                    </button>
                                </div>
                            </div>

                            <!-- Liste des médicaments -->
                            <div class="mt-6 border-t pt-4">
                                <h4 class="font-medium text-gray-700 mb-3">Médicaments prescrits</h4>
                                <div class="space-y-3">
                                    <div class="flex items-start gap-4 bg-gray-50 p-3 rounded-lg">
                                        <div class="flex-1">
                                            <p class="font-medium">Doliprane 1000mg</p>
                                            <p class="text-sm text-gray-600">1 comprimé 3 fois par jour pendant 5 jours</p>
                                        </div>
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                                            Antalgique
                                        </span>
                                    </div>
                                    <div class="flex items-start gap-4 bg-gray-50 p-3 rounded-lg">
                                        <div class="flex-1">
                                            <p class="font-medium">Amoxicilline 500mg</p>
                                            <p class="text-sm text-gray-600">1 gélule matin et soir pendant 7 jours</p>
                                        </div>
                                        <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-sm">
                                            Antibiotique
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Autre ordonnance (exemple) -->
                        <div class="bg-white p-6 rounded-[20px] shadow-sm">
                            <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-2">
                                        <h3 class="font-bold text-lg">Ordonnance #ORD-002</h3>
                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-sm">
                                            Terminée
                                        </span>
                                    </div>
                                    <p class="text-gray-500">Prescrite le 1 Mars 2024</p>
                                    <p class="text-gray-600 mt-1">Dr. Alami Mohammed</p>
                                </div>
                                <!-- Mêmes actions que précédemment -->
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="flex justify-between items-center bg-white p-4 rounded-[20px] shadow-sm">
                        <span class="text-sm text-gray-500">Affichage de 1 à 2 sur 8 ordonnances</span>
                        <div class="flex gap-2">
                            <button class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100" disabled>
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <button class="w-8 h-8 flex items-center justify-center rounded-full bg-[#b9ff66]">1</button>
                            <button class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100">2</button>
                            <button class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Onglet Analyses -->
                <div id="analyses-tab" class="tab-content hidden space-y-6">
                    <!-- Barre d'actions rapides -->
                    <div class="bg-white p-4 rounded-[20px] shadow-sm">
                        <div class="flex flex-col sm:flex-row gap-4 justify-between">
                            <!-- Recherche -->
                            <div class="relative flex-1">
                            <input type="text"
                                   placeholder="Rechercher une analyse..."
                                   class="w-full pl-10 pr-4 py-2 rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Nouvelle Analyse
                                </button>
                                <button class="bg-gray-800 text-white rounded-lg px-4 py-2 flex items-center gap-2 hover:bg-gray-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
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
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Voir les résultats
                                    </button>
                                    <button class="px-4 py-2 bg-[#b9ff66] hover:bg-[#a8eb5f] rounded-lg flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
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
                                                <div class="relative aspect-[16/9] rounded-lg overflow-hidden bg-gray-100">
                                                    <img id="mainImage-analyse-1"
                                                         src="/storage/analyses/analyse1-1.jpg"
                                                         alt="Résultat d'analyse principal"
                                                         class="w-full h-full object-contain cursor-zoom-in"
                                                         onclick="openImageModal(this.src)">
                                                    <div class="absolute bottom-2 right-2">
                                                        <button class="bg-white p-2 rounded-lg shadow-md hover:bg-gray-50 transition-colors">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Miniatures -->
                                            <div class="col-span-full flex gap-2 overflow-x-auto pb-2">
                                                <button class="w-24 h-24 rounded-lg overflow-hidden border-2 border-[#b9ff66]">
                                                    <img src="/storage/analyses/analyse1-1.jpg"
                                                         alt="Miniature 1"
                                                         class="w-full h-full object-cover"
                                                         onclick="switchMainImage('analyse-1', this.src)">
                                                </button>
                                                <button class="w-24 h-24 rounded-lg overflow-hidden border-2 border-transparent hover:border-[#b9ff66]">
                                                    <img src="/storage/analyses/analyse1-2.jpg"
                                                         alt="Miniature 2"
                                                         class="w-full h-full object-cover"
                                                         onclick="switchMainImage('analyse-1', this.src)">
                                                </button>
                                                <button class="w-24 h-24 rounded-lg overflow-hidden border-2 border-transparent hover:border-[#b9ff66]">
                                                    <img src="/storage/analyses/analyse1-3.jpg"
                                                         alt="Miniature 3"
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
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Voir les résultats
                                    </button>
                                    <button class="px-4 py-2 bg-[#b9ff66] hover:bg-[#a8eb5f] rounded-lg flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
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
                            <button class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100" disabled>
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <button class="w-8 h-8 flex items-center justify-center rounded-full bg-[#b9ff66]">1</button>
                            <button class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100">2</button>
                            <button class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
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
                                <input type="text"
                                       placeholder="Rechercher un diagnostic..."
                                       class="w-full pl-10 pr-4 py-2 rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Nouveau
                                </button>
                                <button class="bg-gray-800 text-white rounded-lg px-4 py-2 flex items-center gap-2 hover:bg-gray-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
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
<div id="newConsultationModal"
     class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-[20px] p-8 w-full max-w-4xl mx-4 relative max-h-[90vh] overflow-y-auto">
        <!-- En-tête Modal -->
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-bold">
                NOUVELLE CONSULT<span class="text-[#b9ff66]">A</span>TION
            </h2>
            <button onclick="closeConsultationModal()" class="hover:bg-gray-100 p-2 rounded-full transition-all duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="newConsultationForm" class="space-y-8">
            <!-- Informations sur la Consultation -->
            <div class="bg-[#f8fafc] p-6 rounded-[20px] shadow-sm space-y-6">
                <h3 class="text-xl font-bold flex items-center gap-2">
                    <svg class="w-6 h-6 text-[#b9ff66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Informations sur la Consultation
                </h3>
                <div class="grid grid-cols-1 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Date de consultation</label>
                        <input type="text" value="{{ date('d/m/Y H:i') }}" disabled
                               class="w-full rounded-lg border-gray-300 bg-gray-50">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Type de consultation</label>
                        <select class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                            <option value="">Sélectionner le type de consultation</option>
                            <option value="premiere">Consultation première fois</option>
                            <option value="routine">Consultation de routine</option>
                            <option value="controle">Consultation de contrôle</option>
                        </select>
                        <p class="text-sm text-gray-500">Choisissez le type de consultation approprié</p>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Motif de consultation</label>
                        <input type="text" placeholder="Douleurs abdominales"
                               class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Symptômes actuels</label>
                        <textarea rows="3" placeholder="Douleur persistante depuis 3 jours"
                                  class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]"></textarea>
                    </div>
                </div>
            </div>

            <!-- Paramètres Cliniques -->
            <div class="bg-[#f8fafc] p-6 rounded-[20px] shadow-sm space-y-6">
                <h3 class="text-xl font-bold flex items-center gap-2">
                    <svg class="w-6 h-6 text-[#b9ff66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    Paramètres Cliniques
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Tension artérielle</label>
                        <input type="text" placeholder="123/67 mmHg"
                               class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Saturation en O2</label>
                        <input type="number" placeholder="98"
                               class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                        <span class="text-sm text-gray-500">%</span>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Fréquence cardiaque</label>
                        <input type="number" placeholder="75"
                               class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                        <span class="text-sm text-gray-500">bpm</span>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Température</label>
                        <input type="number" step="0.1" placeholder="37.0"
                               class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                        <span class="text-sm text-gray-500">°C</span>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Glasgow</label>
                        <input type="number" min="3" max="15" placeholder="15"
                               class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                    </div>
                </div>
            </div>

            <!-- Examen Clinique -->
            <div class="bg-[#f8fafc] p-6 rounded-[20px] shadow-sm space-y-6">
                <h3 class="text-xl font-bold flex items-center gap-2">
                    <svg class="w-6 h-6 text-[#b9ff66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    Examen Clinique
                </h3>
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Examen physique</label>
                        <textarea rows="3" placeholder="Pas de signes cliniques évidents"
                                  class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]"></textarea>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Diagnostic présumé</label>
                        <input type="text" placeholder="Gastro-entérite"
                               class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                    </div>
                </div>
            </div>

            <!-- Plan de Traitement -->
            <div class="bg-[#f8fafc] p-6 rounded-[20px] shadow-sm space-y-6">
                <h3 class="text-xl font-bold flex items-center gap-2">
                    <svg class="w-6 h-6 text-[#b9ff66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Plan de Traitement
                </h3>
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Médicaments prescrits</label>
                        <textarea rows="3" placeholder="Paracétamol 500mg, 3 fois par jour pendant 5 jours"
                                  class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]"></textarea>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Propositions de suivi</label>
                        <textarea rows="2" placeholder="Suivi dans 1 semaine"
                                  class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]"></textarea>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Instructions particulières</label>
                        <textarea rows="2" placeholder="Repos recommandé, éviter les aliments gras"
                                  class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]"></textarea>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex justify-end gap-4 pt-6">
                <button type="button" onclick="closeConsultationModal()"
                        class="px-6 py-2.5 bg-white text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-200">
                    Annuler
                </button>
                <button type="submit"
                        class="px-6 py-2.5 bg-[#b9ff66] hover:bg-[#a8eb5f] rounded-lg flex items-center gap-2 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 13l4 4L19 7"/>
                    </svg>
                    Enregistrer la consultation
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Nouvelle Analyse -->
<div id="newAnalyseModal"
     class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-[20px] p-8 w-full max-w-2xl mx-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">
                NOUVELLE AN<span class="text-[#b9ff66]">A</span>LYSE
            </h2>
            <button onclick="closeAnalyseModal()" class="hover:bg-gray-100 p-2 rounded-full">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
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
                    <textarea rows="3"
                              class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]"
                              placeholder="Description de l'analyse demandée..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Document d'analyse</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                      stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label class="relative cursor-pointer bg-white rounded-md font-medium text-[#b9ff66] hover:text-[#a8eb5f]">
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
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
function openEditModal(field) {
    const modal = document.getElementById('editDossierModal');
    const fieldLabel = document.getElementById('fieldLabel');
    const fieldInput = document.getElementById('fieldInput');

    // Configurer le modal selon le champ à éditer
    switch(field) {
        case 'date_naissance':
            fieldLabel.textContent = 'Date de naissance';
            fieldInput.innerHTML = '<input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#b9ff66] focus:border-[#b9ff66]">';
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
        case 'tel':
            fieldLabel.textContent = 'Numéro de téléphone';
            fieldInput.innerHTML = '<input type="tel" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#b9ff66] focus:border-[#b9ff66]" placeholder="Entrez le numéro de téléphone">';
            break;
        case 'adresse':
            fieldLabel.textContent = 'Adresse';
            fieldInput.innerHTML = '<textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#b9ff66] focus:border-[#b9ff66]" rows="3" placeholder="Entrez l\'adresse complète"></textarea>';
            break;
        case 'antecedents_medicaux':
            fieldLabel.textContent = 'Antécédents médicaux';
            fieldInput.innerHTML = '<textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#b9ff66] focus:border-[#b9ff66]" rows="4" placeholder="Entrez les antécédents médicaux (séparés par des virgules)"></textarea>';
            break;
        case 'allergies':
            fieldLabel.textContent = 'Allergies';
            fieldInput.innerHTML = '<textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#b9ff66] focus:border-[#b9ff66]" rows="3" placeholder="Entrez les allergies (séparées par des virgules)"></textarea>';
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

    fetch('/dashboard/medecin/dossier-medical/update', {
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

// Fermeture du modal en cliquant en dehors
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('editDossierModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    }
});
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
</style>
@endsection
