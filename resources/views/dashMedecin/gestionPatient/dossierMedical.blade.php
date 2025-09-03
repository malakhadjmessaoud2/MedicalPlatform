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
                                        <button
                                            class="px-4 py-2 bg-[#b9ff66] hover:bg-[#a8eb5f] rounded-lg flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            Télécharger PDF
                                        </button>
                                        <button
                                            class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
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
                                                <p class="text-sm text-gray-600">1 comprimé 3 fois par jour pendant 5 jours
                                                </p>
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
        <div class="bg-white rounded-[20px] p-8 w-full max-w-4xl mx-4 relative max-h-[90vh] overflow-y-auto">
            <!-- En-tête Modal -->
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-bold">
                    NOUVELLE CONSULT<span class="text-[#b9ff66]">A</span>TION
                </h2>
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

                <!-- Informations sur la Consultation -->
                <div class="bg-[#f8fafc] p-6 rounded-[20px] shadow-sm space-y-6">
                    <h3 class="text-xl font-bold flex items-center gap-2">
                        <svg class="w-6 h-6 text-[#b9ff66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Informations sur la Consultation
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Type de consultation</label>
                            <select name="type_consultation" required
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                                @if (isset($typesConsultation))
                                    @foreach ($typesConsultation as $key => $label)
                                        <option value="{{ is_string($key) ? $key : $label }}">{{ $label }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="premiere">Première consultation</option>
                                    <option value="routine">Consultation de routine</option>
                                    <option value="controle">Consultation de contrôle</option>
                                    <option value="suivi">Consultation de suivi</option>
                                @endif
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Rendez-vous du jour</label>
                            @php
                                $rvItems = $rendezVousDuJour ?? [];
                                if (!is_iterable($rvItems)) {
                                    $rvItems = isset($rvItems) ? [$rvItems] : [];
                                }
                                $rvItems = collect($rvItems)->filter(function ($rv) use ($patient) {
                                    $dtRaw =
                                        $rv->date_debut ??
                                        ($rv->date ??
                                            ($rv->date_rendezvous ?? ($rv->scheduled_at ?? ($rv->datetime ?? null))));
                                    $samePatient = property_exists($rv, 'patient_id')
                                        ? $rv->patient_id == ($patient->id ?? null)
                                        : true;
                                    return $samePatient && ($dtRaw ? \Carbon\Carbon::parse($dtRaw)->isToday() : true);
                                });
                            @endphp
                            @if ($rvItems->count() > 0)
                                <select name="rendezvous_id"
                                    class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                                    @foreach ($rvItems as $rv)
                                        @php
                                            $dtRaw =
                                                $rv->date_debut ??
                                                ($rv->date ??
                                                    ($rv->date_rendezvous ??
                                                        ($rv->scheduled_at ?? ($rv->datetime ?? null))));
                                            $timeLabel = $dtRaw ? \Carbon\Carbon::parse($dtRaw)->format('H:i') : '';
                                            $label = $rv->titre ?? ($rv->objet ?? ($rv->description ?? null));
                                        @endphp
                                        <option value="{{ $rv->id }}">#{{ $rv->id }} — {{ $timeLabel }}
                                            @if ($label)
                                                — {{ $label }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <input type="text" class="w-full rounded-lg border-gray-300 bg-gray-50"
                                    value="Aucun rendez-vous pour aujourd'hui" disabled>
                            @endif
                        </div>



                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Orientation du patient</label>
                            <input type="text" name="orientation_patient"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]"
                                placeholder="Ex: Spécialiste, Urgences, etc.">
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Motif de consultation</label>
                            <input type="text" name="motif_consultation" required placeholder="Motif principal"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Symptômes</label>
                            <textarea name="symptomes" rows="3" placeholder="Décrire les symptômes"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]"></textarea>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Début des symptômes</label>
                            <input type="date" name="debut_symptomes"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Gravité</label>
                            <select name="gravite"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                                <option value="">Sélectionner</option>
                                <option value="faible">Faible</option>
                                <option value="moderee">Modérée</option>
                                <option value="severe">Sévère</option>
                            </select>
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Symptômes aigus</label>
                            <textarea name="symptomes_aigus" rows="2" placeholder="Décrire les symptômes aigus"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Paramètres Cliniques -->
                <div class="bg-[#f8fafc] p-6 rounded-[20px] shadow-sm space-y-6">
                    <h3 class="text-xl font-bold flex items-center gap-2">
                        <svg class="w-6 h-6 text-[#b9ff66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        Paramètres Cliniques
                    </h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Tension artérielle</label>
                            <input type="text" name="tension_arterielle" placeholder="120/80 mmHg"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Saturation O₂ (%)</label>
                            <input type="number" name="saturation_o2" placeholder="98"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Fréquence cardiaque (bpm)</label>
                            <input type="number" name="frequence_cardiaque" placeholder="75"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Température (°C)</label>
                            <input type="number" step="0.1" name="temperature" placeholder="37.0"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Score Glasgow</label>
                            <input type="number" min="3" max="15" name="score_glasgow" placeholder="15"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                        </div>
                    </div>
                </div>

                <!-- Mesures Physiques -->
                <div class="bg-[#f8fafc] p-6 rounded-[20px] shadow-sm space-y-6">
                    <h3 class="text-xl font-bold flex items-center gap-2">
                        <svg class="w-6 h-6 text-[#b9ff66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Mesures Physiques
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Poids (kg)</label>
                            <input type="number" step="0.1" name="poids" id="poids-input" placeholder="70"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Taille (cm)</label>
                            <input type="number" step="0.1" name="taille" id="taille-input" placeholder="175"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">IMC</label>
                            <input type="number" step="0.1" name="imc" id="imc-input" placeholder="Auto"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                        </div>
                    </div>
                </div>

                <!-- Examen/Diagnostic/Traitement/Suivi -->
                <div class="bg-[#f8fafc] p-6 rounded-[20px] shadow-sm space-y-6">
                    <h3 class="text-xl font-bold">Examen, Diagnostic et Suivi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Examen physique</label>
                            <textarea name="examen_physique" rows="3"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]"></textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Diagnostic présumé</label>
                            <textarea name="diagnostic_presume" rows="3"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]"></textarea>
                        </div>
                        <div class="space-y-2 md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Médicaments prescrits</label>
                            <textarea name="medicaments_prescrits" rows="3"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]"></textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Propositions de suivi</label>
                            <textarea name="propositions_suivi" rows="2"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]"></textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Instructions particulières</label>
                            <textarea name="instructions_particulieres" rows="2"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Habitudes de vie / Évolution -->
                <div class="bg-[#f8fafc] p-6 rounded-[20px] shadow-sm space-y-6">
                    <h3 class="text-xl font-bold">Habitudes de vie et Évolution</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Habitudes de vie</label>
                            <textarea name="habitudes_vie" rows="2"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]"></textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Traitement actuel</label>
                            <textarea name="traitement_actuel" rows="2"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]"></textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Évolution des symptômes</label>
                            <textarea name="evolution_symptomes" rows="2"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]"></textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Effets secondaires</label>
                            <textarea name="effets_secondaires" rows="2"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]"></textarea>
                        </div>
                        <div class="space-y-2 md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Examens de contrôle</label>
                            <textarea name="examens_controle" rows="2"
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Enregistrer la consultation
                    </button>
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
                            alert(message);
                            throw new Error(message);
                        }
                        // Success
                        showToast('Consultation créée avec succès');
                        closeConsultationModal();
                        // Recharger pour refléter la nouvelle consultation
                        window.location.reload();
                    }).catch(() => {
                        // already alerted
                    }).finally(() => {
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalBtnHtml;
                        }
                    });
                });
            }

            // Minimal toast
            function showToast(message) {
                const toast = document.createElement('div');
                toast.textContent = message;
                toast.className =
                    'fixed top-4 right-4 z-[60] bg-gray-900 text-white px-4 py-2 rounded-lg shadow-lg';
                document.body.appendChild(toast);
                setTimeout(() => {
                    toast.remove();
                }, 2500);
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
