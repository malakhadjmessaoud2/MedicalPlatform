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

                <!-- Filtres de consultations -->
                <div class="mb-6">
                    <div class="flex flex-wrap gap-2 lg:gap-3 mb-4">
                        <button onclick="filtrerConsultations('aujourdhui')"
                                class="filtre-btn active px-4 py-2 rounded-full text-sm font-medium transition-all bg-[#b9ff66] text-gray-800 hover:bg-[#a8eb5f]">
                            Aujourd'hui
                        </button>
                        <button onclick="filtrerConsultations('demain')"
                                class="filtre-btn px-4 py-2 rounded-full text-sm font-medium transition-all bg-gray-100 text-gray-600 hover:bg-gray-200">
                            Demain
                        </button>
                        <button onclick="filtrerConsultations('semaine')"
                                class="filtre-btn px-4 py-2 rounded-full text-sm font-medium transition-all bg-gray-100 text-gray-600 hover:bg-gray-200">
                            Cette semaine
                        </button>
                        <button onclick="filtrerConsultations('semaine_prochaine')"
                                class="filtre-btn px-4 py-2 rounded-full text-sm font-medium transition-all bg-gray-100 text-gray-600 hover:bg-gray-200">
                            Semaine prochaine
                        </button>
                        <button onclick="filtrerConsultations('mois')"
                                class="filtre-btn px-4 py-2 rounded-full text-sm font-medium transition-all bg-gray-100 text-gray-600 hover:bg-gray-200">
                            Ce mois
                        </button>
                        <button onclick="filtrerConsultations('date')"
                                class="filtre-btn px-4 py-2 rounded-full text-sm font-medium transition-all bg-gray-100 text-gray-600 hover:bg-gray-200">
                            Par date
                        </button>
                        <button onclick="filtrerConsultations('periode')"
                                class="filtre-btn px-4 py-2 rounded-full text-sm font-medium transition-all bg-gray-100 text-gray-600 hover:bg-gray-200">
                            Période
                        </button>
                    </div>

                    <!-- Sélecteur de date (masqué par défaut) -->
                    <div id="dateSelector" class="hidden mb-4">
                        <div class="flex flex-col lg:flex-row gap-3">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sélectionner une date</label>
                                <input type="date" id="dateInput" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#b9ff66] focus:border-transparent">
                            </div>
                            <div class="flex items-end">
                                <button onclick="appliquerFiltreDate()"
                                        class="px-4 py-2 bg-[#b9ff66] text-gray-800 rounded-lg hover:bg-[#a8eb5f] transition-all">
                                    Appliquer
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Sélecteur de période (masqué par défaut) -->
                    <div id="periodeSelector" class="hidden mb-4">
                        <div class="flex flex-col lg:flex-row gap-3">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Période</label>
                                <input type="text" id="periodeInput" placeholder="jj/mm/aaaa - jj/mm/aaaa"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#b9ff66] focus:border-transparent">
                            </div>
                            <div class="flex items-end">
                                <button onclick="appliquerFiltrePeriode()"
                                        class="px-4 py-2 bg-[#b9ff66] text-gray-800 rounded-lg hover:bg-[#a8eb5f] transition-all">
                                    Appliquer
                                </button>
                            </div>
                        </div>
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
                                                    @php
                                                        $maintenant = \Carbon\Carbon::now();
                                                        $dateDebut = \Carbon\Carbon::parse($rdvAujourdhui->date_debut);
                                                        $dateFin = \Carbon\Carbon::parse($rdvAujourdhui->date_fin ?? $rdvAujourdhui->date_debut->copy()->addMinutes(30));
                                                        $cinqMinutesAvant = $dateDebut->copy()->subMinutes(5);

                                                        $indicateur = '';
                                                        if ($rdvAujourdhui->statut === 'completed') {
                                                            $indicateur = '<span class="text-blue-600 font-medium">✅ Consultation terminée</span>';
                                                        } elseif ($rdvAujourdhui->statut === 'cancelled') {
                                                            $indicateur = '<span class="text-red-500 font-medium">❌ Rendez-vous annulé</span>';
                                                        } elseif ($rdvAujourdhui->statut === 'pending') {
                                                            $indicateur = '<span class="text-yellow-600 font-medium">⏳ En attente de confirmation</span>';
                                                        } elseif ($rdvAujourdhui->statut === 'payed') {
                                                            $indicateur = '<span class="text-blue-600 font-medium">💳 Payé - en attente de confirmation</span>';
                                                        } elseif ($rdvAujourdhui->statut === 'confirmé' || $rdvAujourdhui->statut === 'confirmed') {
                                                            if ($maintenant->gte($dateFin)) {
                                                                $indicateur = '<span class="text-blue-600 font-medium">✅ Consultation terminée</span>';
                                                            } elseif ($maintenant->between($dateDebut, $dateFin)) {
                                                                $indicateur = '<span class="text-green-600 font-medium">🔵 Consultation en cours</span>';
                                                            } elseif ($maintenant->between($cinqMinutesAvant, $dateDebut)) {
                                                                $indicateur = '<span class="text-blue-600 font-medium">🟢 Consultation peut commencer (5 min avant)</span>';
                                                            } else {
                                                                $tempsRestant = $dateDebut->diffInMinutes($maintenant);
                                                                if ($tempsRestant > 60) {
                                                                    $heures = floor($tempsRestant / 60);
                                                                    $minutes = $tempsRestant % 60;
                                                                    $indicateur = '<span class="text-gray-500">⏰ Dans ' . $heures . 'h' . $minutes . 'min</span>';
                                                                } else {
                                                                    $indicateur = '<span class="text-gray-500">⏰ Dans ' . $tempsRestant . 'min</span>';
                                                                }
                                                            }
                                                        } else {
                                                            $indicateur = '<span class="text-gray-500">⏰ RDV: ' . $dateDebut->format('H:i') . '</span>';
                                                        }
                                                    @endphp
                                                    {!! $indicateur !!}
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

            <!-- Consultations terminées -->
            <div class="bg-white p-4 lg:p-6 rounded-[20px] shadow-sm">
                <div class="flex items-center justify-between mb-4 lg:mb-6">
                    <h2 class="text-xl font-bold" id="consultationsTermineesTitle">Consultations terminées de ce jour</h2>
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

    <!-- Modal de Confirmation de Changement de Statut -->
    <div id="statusChangeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-auto transform transition-all duration-300 scale-95 opacity-0" id="statusModalContent">
            <!-- Header du Modal -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Confirmation de changement de statut</h3>
                </div>
                <button id="closeStatusModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Contenu du Modal -->
            <div class="p-6">
                <div class="mb-6">
                    <p class="text-gray-700 text-base leading-relaxed">
                        Êtes-vous sûr de vouloir changer le statut de ce rendez-vous vers
                        <span id="newStatusLabel" class="font-semibold text-blue-600"></span> ?
                    </p>
                </div>

                <!-- Informations du rendez-vous -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="flex items-center gap-3">
                        <img id="patientPhoto" src="" alt="Photo patient" class="w-12 h-12 rounded-full object-cover border-2 border-gray-200">
                        <div>
                            <div id="patientName" class="font-medium text-gray-900"></div>
                            <div id="appointmentTime" class="text-sm text-gray-600"></div>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs text-gray-500">Statut actuel :</span>
                                <span id="currentStatusBadge" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Message d'avertissement si nécessaire -->
                <div id="warningMessage" class="hidden mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-yellow-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <p class="text-sm text-yellow-800"></p>
                    </div>
                </div>
            </div>

            <!-- Actions du Modal -->
            <div class="flex gap-3 p-6 pt-0">
                <button id="cancelStatusChange" class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Annuler
                </button>
                <button id="confirmStatusChange" class="flex-1 px-4 py-3 bg-blue-600 text-white rounded-xl font-medium hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <span id="confirmButtonText">Confirmer</span>
                    <span id="loadingSpinner" class="hidden ml-2">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>
            </div>
        </div>
    </div>

<!-- Styles pour les filtres -->
<style>
.filtre-btn {
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.filtre-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.filtre-btn.active {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(185, 255, 102, 0.3);
    border-color: #a8eb5f;
}

.consultation-card {
    transition: all 0.3s ease;
    border: 1px solid #e5e7eb;
}

.consultation-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    border-color: #b9ff66;
}

.status-badge {
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.25rem 0.5rem;
    border-radius: 9999px;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.status-confirmed {
    background-color: #dcfce7;
    color: #166534;
}

.status-pending {
    background-color: #fef3c7;
    color: #92400e;
}

.status-completed {
    background-color: #dbeafe;
    color: #1e40af;
}

.status-cancelled {
    background-color: #fee2e2;
    color: #991b1b;
}

.status-payed {
    background-color: #dbeafe;
    color: #1e40af;
}

.status-unknown {
    background-color: #f3f4f6;
    color: #6b7280;
}

.consultation-indicator {
    font-size: 0.875rem;
    font-weight: 600;
    padding: 0.5rem 1rem;
    border-radius: 1rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.consultation-indicator:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.indicator-active {
    background: linear-gradient(135deg, #dcfce7, #bbf7d0);
    color: #166534;
    border: 2px solid #22c55e;
}

.indicator-in-progress {
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: #1e40af;
    border: 2px solid #3b82f6;
}

/* Styles pour le select de statut */
.status-select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
}

.status-select:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(185, 255, 102, 0.1);
    border-color: #b9ff66;
}

.status-select option {
    padding: 0.5rem;
    font-weight: 500;
}

.status-select option:checked {
    background-color: #b9ff66;
    color: #1f2937;
}

/* Styles pour le modal de confirmation */
#statusChangeModal.show {
    display: flex !important;
}

#statusChangeModal.show #statusModalContent {
    transform: scale(100%);
    opacity: 100;
}

#statusChangeModal.hide #statusModalContent {
    transform: scale(95%);
    opacity: 0;
}

/* Animation d'entrée et sortie du modal */
.modal-enter {
    animation: modalEnter 0.3s ease-out;
}

.modal-exit {
    animation: modalExit 0.2s ease-in;
}

@keyframes modalEnter {
    from {
        opacity: 0;
        transform: scale(95%);
    }
    to {
        opacity: 1;
        transform: scale(100%);
    }
}

@keyframes modalExit {
    from {
        opacity: 1;
        transform: scale(100%);
    }
    to {
        opacity: 0;
        transform: scale(95%);
    }
}
</style>

<!-- Scripts -->
@vite(['resources/css/app.css', 'resources/js/app.js'])
<script>
// Variables globales pour les filtres
let filtreActuel = 'aujourdhui';
let dateActuelle = null;

// Initialisation des modules
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les gestionnaires
    if (window.ConsultationManager) {
        window.consultationManager = new window.ConsultationManager();
    }
    if (window.DossierManager) {
        window.dossierManager = new window.DossierManager();
    }

    // Charger les consultations par défaut (aujourd'hui)
    chargerConsultationsFiltrees('aujourdhui');

    // Vérifier et mettre à jour les statuts toutes les 30 secondes
    setInterval(() => {
        chargerConsultationsFiltrees(filtreActuel, dateActuelle);
    }, 30000);

    // Vérifier périodiquement les statuts (toutes les 5 minutes)
    setInterval(() => {
        console.log('[AUTO-UPDATE] Vérification périodique des statuts...');
        if (filtreActuel) {
            chargerConsultationsFiltrees(filtreActuel, dateActuelle);
        }
    }, 5 * 60 * 1000); // 5 minutes
});

// Fonction pour filtrer les consultations
function filtrerConsultations(filtre) {
    // Mettre à jour l'état des boutons
    document.querySelectorAll('.filtre-btn').forEach(btn => {
        btn.classList.remove('active', 'bg-[#b9ff66]', 'text-gray-800');
        btn.classList.add('bg-gray-100', 'text-gray-600');
    });

    // Activer le bouton sélectionné
    event.target.classList.add('active', 'bg-[#b9ff66]', 'text-gray-800');
    event.target.classList.remove('bg-gray-100', 'text-gray-600');

    // Masquer tous les sélecteurs
    document.getElementById('dateSelector').classList.add('hidden');
    document.getElementById('periodeSelector').classList.add('hidden');

    filtreActuel = filtre;

    // Afficher le sélecteur approprié
    if (filtre === 'date') {
        document.getElementById('dateSelector').classList.remove('hidden');
        document.getElementById('dateInput').value = new Date().toISOString().split('T')[0];
    } else if (filtre === 'periode') {
        document.getElementById('periodeSelector').classList.remove('hidden');
        const aujourdhui = new Date();
        const demain = new Date(aujourdhui);
        demain.setDate(demain.getDate() + 7);
        document.getElementById('periodeInput').value =
            aujourdhui.toLocaleDateString('fr-FR') + ' - ' + demain.toLocaleDateString('fr-FR');
    } else {
        // Appliquer le filtre directement
        chargerConsultationsFiltrees(filtre);
    }
}

// Fonction pour appliquer le filtre par date
function appliquerFiltreDate() {
    const date = document.getElementById('dateInput').value;
    if (date) {
        dateActuelle = date;
        chargerConsultationsFiltrees('date', date);
    }
}

// Fonction pour appliquer le filtre par période
function appliquerFiltrePeriode() {
    const periode = document.getElementById('periodeInput').value;
    if (periode) {
        dateActuelle = periode;
        chargerConsultationsFiltrees('periode', periode);
    }
}

// Fonction pour mettre à jour la section des consultations terminées
function updateConsultationsTerminees(consultationsTerminees) {
    const container = document.getElementById('consultationsTermineesList');
    const titleElement = document.getElementById('consultationsTermineesTitle');

    // Mettre à jour le titre selon le filtre actuel
    const titreParFiltre = {
        'aujourdhui': 'Consultations terminées d\'aujourd\'hui',
        'demain': 'Consultations terminées de demain',
        'semaine': 'Consultations terminées de cette semaine',
        'semaine_prochaine': 'Consultations terminées de la semaine prochaine',
        'mois': 'Consultations terminées de ce mois',
        'date': 'Consultations terminées de la date sélectionnée',
        'periode': 'Consultations terminées de la période sélectionnée'
    };

    if (titleElement) {
        titleElement.textContent = titreParFiltre[filtreActuel] || 'Consultations terminées';
    }

    if (consultationsTerminees.length === 0) {
        const messageParFiltre = {
            'aujourdhui': 'Aucune consultation terminée aujourd\'hui',
            'demain': 'Aucune consultation terminée demain',
            'semaine': 'Aucune consultation terminée cette semaine',
            'semaine_prochaine': 'Aucune consultation terminée la semaine prochaine',
            'mois': 'Aucune consultation terminée ce mois',
            'date': 'Aucune consultation terminée à la date sélectionnée',
            'periode': 'Aucune consultation terminée dans la période sélectionnée'
        };

        container.innerHTML = `
            <div class="text-center py-4">
                <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-gray-500 text-sm">${messageParFiltre[filtreActuel] || 'Aucune consultation terminée'}</p>
            </div>
        `;
    } else {
        // Filtrer pour ne garder que les consultations terminées (pas les annulées)
        const consultationsTermineesFiltrees = consultationsTerminees.filter(consultation =>
            consultation.statut === 'completed'
        );

        if (consultationsTermineesFiltrees.length === 0) {
            container.innerHTML = `
                <div class="text-center py-4">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-gray-500 text-sm">Aucune consultation terminée</p>
                </div>
            `;
        } else {
            container.innerHTML = consultationsTermineesFiltrees.map(consultation => `
                <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex items-center gap-3">
                        <img src="${consultation.photo}" alt="Photo" width="40" height="40"
                             class="rounded-full object-cover border-2 border-gray-300"
                             style="min-width:40px;min-height:40px;">
                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-gray-800 truncate">${consultation.prenom} ${consultation.nom}</div>
                            <div class="text-xs text-gray-500">${consultation.heure} - ${consultation.heure_fin}</div>
                            <div class="text-xs text-gray-600 mt-1">
                                🏁 Consultation terminée
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        }
    }
}

// Fonction pour charger les consultations avec filtres
async function chargerConsultationsFiltrees(filtre, date = null) {
    const patientsList = document.getElementById('patientsList');
    const patientsCount = document.getElementById('patientsCount');

    // Afficher le loader
    patientsList.innerHTML = `
        <div class="text-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#b9ff66] mx-auto"></div>
            <p class="text-gray-500 mt-2">Chargement des consultations...</p>
        </div>
    `;

    try {
        const params = new URLSearchParams({ filtre: filtre });
        if (date) {
            params.append('date', date);
        }

        const response = await fetch(`/medecin/consultations-filtrees?${params}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });

        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }

        const consultations = await response.json();

        // Debug: Afficher les consultations reçues
        console.log('[DEBUG] Consultations reçues:', consultations);
        console.log('[DEBUG] Filtre actuel:', filtre);
        console.log('[DEBUG] Date actuelle:', date);

        // Debug spécifique pour les consultations completed
        const consultationsCompleted = consultations.filter(c => c.statut === 'completed');
        const consultationsCancelled = consultations.filter(c => c.statut === 'cancelled');
        console.log('[DEBUG] Consultations avec statut completed:', consultationsCompleted);
        console.log('[DEBUG] Consultations avec statut cancelled:', consultationsCancelled);

        // Vérifier et mettre à jour automatiquement les statuts "payed" vers "completed"
        await verifierEtMettreAJourStatuts(consultations);

        // Séparer les consultations à venir des consultations terminées
        const maintenant = new Date();
        const consultationsAVenir = consultations.filter(consultation => {
            // Si le statut est completed ou cancelled, ne pas l'afficher dans "à venir"
            if (consultation.statut === 'completed' || consultation.statut === 'cancelled') {
                console.log(`[DEBUG] Consultation ${consultation.id} exclue des "à venir" - Statut: ${consultation.statut}`);
                return false;
            }

            // Pour les autres statuts, vérifier si la consultation est à venir
            const dateDebut = new Date(consultation.date_debut);
            const dateFin = new Date(consultation.date_fin || new Date(dateDebut.getTime() + 30 * 60000));

            // Une consultation est à venir si :
            // 1. Elle n'est pas terminée (statut != completed/cancelled)
            // 2. ET l'heure actuelle est avant l'heure de fin
            const estAVenir = maintenant <= dateFin;

            console.log(`[DEBUG] Consultation ${consultation.id} - À venir: ${estAVenir} (maintenant: ${maintenant.toISOString()}, fin: ${dateFin.toISOString()})`);
            return estAVenir;
        });

        const consultationsTerminees = consultations.filter(consultation => {
            // Afficher les consultations terminées selon le filtre actuel
            const dateDebut = new Date(consultation.date_debut);
            const dateFin = new Date(consultation.date_fin || new Date(dateDebut.getTime() + 30 * 60000));
            const maintenant = new Date();

            // Vérifier si la consultation est terminée
            // Une consultation est terminée si :
            // 1. Le statut est 'completed' (mais PAS 'cancelled')
            // 2. OU si l'heure actuelle dépasse l'heure de fin
            const estTerminee = consultation.statut === 'completed' ||
                               maintenant > dateFin;

            // Debug pour chaque consultation
            console.log(`[DEBUG] Consultation ${consultation.id}:`, {
                statut: consultation.statut,
                dateDebut: dateDebut.toDateString(),
                dateFin: dateFin.toDateString(),
                maintenant: maintenant.toDateString(),
                estTerminee: estTerminee,
                filtre: filtre,
                raison: consultation.statut === 'completed' ? 'statut completed' :
                       maintenant > dateFin ? 'heure dépassée' : 'non terminée'
            });

            if (!estTerminee) {
                console.log(`[DEBUG] Consultation ${consultation.id} exclue - Non terminée`);
                return false;
            }

            // Appliquer le filtre selon la période sélectionnée
            const aujourdhui = new Date();
            aujourdhui.setHours(0, 0, 0, 0);

            let correspondAuFiltre = false;

            if (filtre === 'aujourdhui') {
                // Pour le filtre "aujourd'hui", afficher les consultations terminées d'aujourd'hui
                // Vérifier si la date de début est d'aujourd'hui (pas la date de fin)
                correspondAuFiltre = dateDebut.toDateString() === aujourdhui.toDateString();
                console.log(`[DEBUG] Filtre aujourd'hui - ${consultation.id}:`, {
                    dateDebut: dateDebut.toDateString(),
                    aujourdhui: aujourdhui.toDateString(),
                    correspond: correspondAuFiltre
                });
            } else if (filtre === 'demain') {
                // Pour le filtre "demain", afficher les consultations terminées de demain
                const demain = new Date(aujourdhui);
                demain.setDate(demain.getDate() + 1);
                correspondAuFiltre = dateDebut.toDateString() === demain.toDateString();
            } else if (filtre === 'semaine') {
                // Pour le filtre "semaine", afficher les consultations terminées de cette semaine
                const debutSemaine = new Date(aujourdhui);
                debutSemaine.setDate(aujourdhui.getDate() - aujourdhui.getDay() + 1);
                const finSemaine = new Date(debutSemaine);
                finSemaine.setDate(debutSemaine.getDate() + 6);
                correspondAuFiltre = dateDebut >= debutSemaine && dateDebut <= finSemaine;
            } else if (filtre === 'semaine_prochaine') {
                // Pour le filtre "semaine prochaine", afficher les consultations terminées de la semaine prochaine
                const debutSemaineProchaine = new Date(aujourdhui);
                debutSemaineProchaine.setDate(aujourdhui.getDate() - aujourdhui.getDay() + 8);
                const finSemaineProchaine = new Date(debutSemaineProchaine);
                finSemaineProchaine.setDate(debutSemaineProchaine.getDate() + 6);
                correspondAuFiltre = dateDebut >= debutSemaineProchaine && dateDebut <= finSemaineProchaine;
            } else if (filtre === 'mois') {
                // Pour le filtre "mois", afficher les consultations terminées de ce mois
                const debutMois = new Date(aujourdhui.getFullYear(), aujourdhui.getMonth(), 1);
                const finMois = new Date(aujourdhui.getFullYear(), aujourdhui.getMonth() + 1, 0);
                correspondAuFiltre = dateDebut >= debutMois && dateDebut <= finMois;
            } else if (filtre === 'date' && date) {
                // Pour le filtre "date", afficher les consultations terminées de la date sélectionnée
                const dateSelectionnee = new Date(date);
                correspondAuFiltre = dateDebut.toDateString() === dateSelectionnee.toDateString();
            } else if (filtre === 'periode' && date) {
                // Pour le filtre "période", afficher les consultations terminées dans la période sélectionnée
                const dates = date.split(' - ');
                if (dates.length === 2) {
                    const dateDebutPeriode = new Date(dates[0]);
                    const dateFinPeriode = new Date(dates[1]);
                    correspondAuFiltre = dateDebut >= dateDebutPeriode && dateDebut <= dateFinPeriode;
                }
            } else {
                // Par défaut, afficher les consultations terminées d'aujourd'hui
                correspondAuFiltre = dateDebut.toDateString() === aujourdhui.toDateString();
            }

            console.log(`[DEBUG] Consultation ${consultation.id} - Résultat final:`, correspondAuFiltre);
            if (correspondAuFiltre) {
                console.log(`[DEBUG] ✅ Consultation ${consultation.id} incluse dans les terminées`);
            } else {
                console.log(`[DEBUG] ❌ Consultation ${consultation.id} exclue des terminées`);
            }
            return correspondAuFiltre;
        });

        // Mettre à jour le compteur avec les consultations à venir
        patientsCount.textContent = `${consultationsAVenir.length} Consultations`;

        // Afficher les consultations à venir
        if (consultationsAVenir.length === 0) {
            patientsList.innerHTML = `
                <div class="text-center py-8">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-gray-500 text-lg mb-2">Aucune consultation trouvée</p>
                    <p class="text-gray-400 text-sm">Aucune consultation ne correspond aux critères sélectionnés</p>
                </div>
            `;
        } else {
            patientsList.innerHTML = consultationsAVenir.map(consultation => `
                <div class="consultation-card bg-white rounded-2xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300"
                     data-date-debut="${consultation.date_debut}"
                     data-date-fin="${consultation.date_fin}">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-5">
                            <div class="relative">
                                <img src="${consultation.photo}" alt="${consultation.prenom} ${consultation.nom}"
                                     class="w-20 h-20 rounded-full object-cover border-3 border-[#b9ff66] shadow-md">
                                ${consultation.consultation_active || consultation.consultation_en_cours ? `
                                    <div class="absolute -top-1 -right-1 w-5 h-5 bg-green-500 rounded-full border-3 border-white animate-pulse"></div>
                                ` : ''}
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-xl text-gray-900 mb-3">${consultation.prenom} ${consultation.nom}</h3>

                                <!-- Informations de base -->
                                <div class="space-y-2 mb-4">
                                    <div class="flex items-center gap-3 text-sm text-gray-600">
                                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="font-semibold text-base">${consultation.heure} - ${consultation.heure_fin}</span>
                                    </div>

                                    ${consultation.date ? `
                                        <div class="flex items-center gap-3 text-sm text-gray-600">
                                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span class="font-semibold text-base">${consultation.date}</span>
                                        </div>
                                    ` : ''}

                                    <div class="flex items-center gap-3 text-sm text-gray-600">
                                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <span class="font-semibold text-base">${consultation.type || 'Consultation'}</span>
                                    </div>
                                </div>

                                <!-- Badge de statut unique -->
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="status-badge ${
                                        consultation.statut === 'confirmed' || consultation.statut === 'confirmé' ? 'status-confirmed' :
                                        consultation.statut === 'pending' ? 'status-pending' :
                                        consultation.statut === 'payed' ? 'status-payed' :
                                        consultation.statut === 'completed' ? 'status-completed' :
                                        consultation.statut === 'cancelled' ? 'status-cancelled' :
                                        'status-unknown'
                                    }">
                                        ${consultation.statut === 'confirmed' || consultation.statut === 'confirmé' ? '✅ Confirmé' :
                                          consultation.statut === 'pending' ? '⏳ En attente' :
                                          consultation.statut === 'payed' ? '💳 Payé' :
                                          consultation.statut === 'completed' ? '✅ Terminé' :
                                          consultation.statut === 'cancelled' ? '❌ Annulé' :
                                          '❓ ' + consultation.statut}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col items-end gap-3">
                            ${consultation.consultation_active || consultation.consultation_en_cours ? `
                                <a href="${consultation.lien_meet}" target="_blank"
                                   class="px-6 py-3 bg-[#b9ff66] text-gray-800 rounded-xl hover:bg-[#a8eb5f] transition-all font-bold shadow-lg hover:shadow-xl flex items-center gap-3 text-base">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    Rejoindre
                                </a>
                            ` : ''}

                            <!-- Select pour changer le statut -->
                            <div class="relative">
                                <select onchange="changeRendezVousStatus(${consultation.id}, this.value, this)"
                                        class="status-select appearance-none bg-white border-2 border-gray-300 rounded-xl px-5 py-3 pr-10 text-sm font-bold focus:ring-2 focus:ring-[#b9ff66] focus:border-transparent transition-all cursor-pointer min-w-[160px] ${
                                            consultation.statut === 'confirmed' || consultation.statut === 'confirmé' ? 'text-green-700 bg-green-50 border-green-300' :
                                            consultation.statut === 'pending' ? 'text-yellow-700 bg-yellow-50 border-yellow-300' :
                                            consultation.statut === 'payed' ? 'text-blue-700 bg-blue-50 border-blue-300' :
                                            consultation.statut === 'completed' ? 'text-blue-700 bg-blue-50 border-blue-300' :
                                            consultation.statut === 'cancelled' ? 'text-red-700 bg-red-50 border-red-300' :
                                            'text-gray-700 bg-gray-50 border-gray-300'
                                        }" data-current-status="${consultation.statut}">
                                    <option value="pending" ${consultation.statut === 'pending' ? 'selected' : ''}>⏳ En attente</option>
                                    <option value="confirmed" ${consultation.statut === 'confirmed' || consultation.statut === 'confirmé' ? 'selected' : ''}>✅ Confirmé</option>
                                    <option value="payed" ${consultation.statut === 'payed' ? 'selected' : ''}>💳 Payé</option>
                                    <option value="completed" ${consultation.statut === 'completed' ? 'selected' : ''}>✅ Terminé</option>
                                    <option value="cancelled" ${consultation.statut === 'cancelled' ? 'selected' : ''}>❌ Annulé</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- Boutons d'action -->
                            <div class="flex items-center gap-2">
                                <button onclick="voirDetails(${consultation.id})"
                                        class="p-3 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 transition-all shadow-md hover:shadow-lg"
                                        title="Voir les détails">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>

                                <button onclick="voirDetails(${consultation.id})"
                                        class="p-3 bg-[#b9ff66] text-gray-800 rounded-xl hover:bg-[#a8eb5f] transition-all shadow-md hover:shadow-lg"
                                        title="Accéder au dossier">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Debug: Afficher les consultations terminées filtrées
        console.log('[DEBUG] Consultations terminées filtrées:', consultationsTerminees);
        console.log('[DEBUG] Nombre de consultations terminées:', consultationsTerminees.length);
        console.log('[DEBUG] Résumé des filtres:');
        console.log(`  - Consultations à venir: ${consultationsAVenir.length}`);
        console.log(`  - Consultations terminées: ${consultationsTerminees.length}`);
        console.log(`  - Total consultations: ${consultations.length}`);

        // Mettre à jour la section des consultations terminées
        updateConsultationsTerminees(consultationsTerminees);

    } catch (error) {
        console.error('Erreur lors du chargement des consultations:', error);
        patientsList.innerHTML = `
            <div class="text-center py-8">
                <svg class="w-16 h-16 mx-auto text-red-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-red-500 text-lg mb-2">Erreur de chargement</p>
                <p class="text-gray-400 text-sm">Impossible de charger les consultations. Veuillez réessayer.</p>
                <button onclick="chargerConsultationsFiltrees('${filtre}', '${date}')"
                        class="mt-4 px-4 py-2 bg-[#b9ff66] text-gray-800 rounded-lg hover:bg-[#a8eb5f] transition-all">
                    Réessayer
                </button>
            </div>
        `;
    }
}

// Fonction pour vérifier et mettre à jour automatiquement les statuts "payed" vers "completed"
async function verifierEtMettreAJourStatuts(consultations) {
    const maintenant = new Date();
    const consultationsAMettreAJour = [];

    consultations.forEach(consultation => {
        // Vérifier si le statut est "payed" et si la date de fin est dépassée
        if (consultation.statut === 'payed' && consultation.date_fin) {
            const dateFin = new Date(consultation.date_fin);

            if (maintenant > dateFin) {
                consultationsAMettreAJour.push(consultation.id);
            }
        }
    });

    // Mettre à jour les statuts en lot
    if (consultationsAMettreAJour.length > 0) {
        console.log(`[AUTO-UPDATE] Mise à jour automatique de ${consultationsAMettreAJour.length} consultations de "payed" vers "completed"`);

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            // Mettre à jour chaque consultation individuellement
            for (const consultationId of consultationsAMettreAJour) {
                await fetch(`/api/medecin/rendez-vous/${consultationId}/statut`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ statut: 'completed' })
                });
            }

            // Mettre à jour les statuts dans l'array des consultations
            consultations.forEach(consultation => {
                if (consultationsAMettreAJour.includes(consultation.id)) {
                    consultation.statut = 'completed';
                    consultation.consultation_terminee = true;
                }
            });

            console.log('[AUTO-UPDATE] Mise à jour automatique terminée avec succès');

        } catch (error) {
            console.error('[AUTO-UPDATE] Erreur lors de la mise à jour automatique:', error);
        }
    }
}

// Fonction pour mettre à jour l'affichage d'une consultation après changement de statut
function updateConsultationDisplay(rendezVousId, newStatus) {
    console.log('[DEBUG] updateConsultationDisplay appelé', { rendezVousId, newStatus });

    try {
        // Trouver la carte de consultation correspondante
        const consultationCards = document.querySelectorAll('.consultation-card');
        let cardFound = false;

        consultationCards.forEach(card => {
        // Vérifier si cette carte contient le bon ID (en cherchant dans les boutons ou liens)
        const selectElement = card.querySelector(`select[onchange*="${rendezVousId}"]`);
        if (selectElement) {
            cardFound = true;
            console.log('[DEBUG] Carte trouvée, mise à jour en cours...');

            // Mettre à jour le select
            selectElement.value = newStatus;
            selectElement.setAttribute('data-current-status', newStatus);

            // Mettre à jour les classes CSS du select selon le nouveau statut
            selectElement.className = selectElement.className.replace(/text-\w+-\d+ bg-\w+-\d+ border-\w+-\d+/g, '');

            // Définir les classes selon le statut
            let statusClasses = [];
            if (newStatus === 'confirmed' || newStatus === 'confirmé') {
                statusClasses = ['text-green-700', 'bg-green-50', 'border-green-200'];
            } else if (newStatus === 'pending') {
                statusClasses = ['text-yellow-700', 'bg-yellow-50', 'border-yellow-200'];
            } else if (newStatus === 'payed') {
                statusClasses = ['text-blue-700', 'bg-blue-50', 'border-blue-200'];
            } else if (newStatus === 'completed') {
                statusClasses = ['text-blue-700', 'bg-blue-50', 'border-blue-200'];
            } else if (newStatus === 'cancelled') {
                statusClasses = ['text-red-700', 'bg-red-50', 'border-red-200'];
            } else {
                statusClasses = ['text-gray-700', 'bg-gray-50', 'border-gray-200'];
            }

            // Ajouter les classes une par une
            statusClasses.forEach(className => {
                selectElement.classList.add(className);
            });

            // Mettre à jour les badges de statut dans la carte
            const statusBadges = card.querySelectorAll('.status-badge');
            statusBadges.forEach(badge => {
                const statusText = newStatus === 'confirmed' || newStatus === 'confirmé' ? '✅ Confirmé' :
                                 newStatus === 'pending' ? '⏳ En attente' :
                                 newStatus === 'payed' ? '💳 Payé' :
                                 newStatus === 'completed' ? '✅ Terminé' :
                                 newStatus === 'cancelled' ? '❌ Annulé' :
                                 '❓ ' + newStatus;
                badge.textContent = statusText;

                // Mettre à jour les classes CSS du badge
                badge.className = badge.className.replace(/status-\w+/g, '');
                badge.classList.add(
                    newStatus === 'confirmed' || newStatus === 'confirmé' ? 'status-confirmed' :
                    newStatus === 'pending' ? 'status-pending' :
                    newStatus === 'payed' ? 'status-payed' :
                    newStatus === 'completed' ? 'status-completed' :
                    newStatus === 'cancelled' ? 'status-cancelled' :
                    'status-unknown'
                );
            });

            // L'indicateur principal a été supprimé, seul le badge de statut est conservé
            // La mise à jour se fait déjà via les statusBadges ci-dessus

            // Ajouter une animation de mise à jour
            try {
                card.style.transition = 'all 0.3s ease';
                card.style.transform = 'scale(1.02)';
                card.style.boxShadow = '0 8px 25px rgba(34, 197, 94, 0.3)';

                setTimeout(() => {
                    card.style.transform = 'scale(1)';
                    card.style.boxShadow = '';
                }, 300);

                console.log('[DEBUG] Carte mise à jour avec succès');
            } catch (animationError) {
                console.warn('[DEBUG] Erreur lors de l\'animation:', animationError);
                // L'animation a échoué mais ce n'est pas critique
            }
        }
    });

        if (!cardFound) {
            console.warn('[DEBUG] Aucune carte trouvée pour le rendez-vous', rendezVousId);
            // Recharger les consultations si la carte n'est pas trouvée
            chargerConsultationsFiltrees(filtreActuel, dateActuelle);
        }
    } catch (error) {
        console.error('[DEBUG] Erreur lors de la mise à jour de l\'affichage:', error);
        // En cas d'erreur, recharger les consultations
        chargerConsultationsFiltrees(filtreActuel, dateActuelle);
    }
}

// Fonction pour générer les indicateurs de statut appropriés
function getStatusIndicator(consultation) {
    const maintenant = new Date();
    const dateDebut = new Date(consultation.date_debut);
    const dateFin = new Date(consultation.date_fin || new Date(dateDebut.getTime() + 30 * 60000)); // +30 min par défaut

    // Priorité aux statuts finaux et explicites d'abord
    if (consultation.statut === 'completed') {
        return '<span class="consultation-indicator" style="background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #1e40af; border: 2px solid #3b82f6;">✅ Consultation terminée</span>';
    }

    if (consultation.statut === 'cancelled') {
        return '<span class="consultation-indicator" style="background: linear-gradient(135deg, #fee2e2, #fecaca); color: #991b1b; border: 2px solid #ef4444;">❌ Rendez-vous annulé</span>';
    }

    if (consultation.statut === 'pending') {
        return '<span class="consultation-indicator" style="background: linear-gradient(135deg, #fef3c7, #fde68a); color: #92400e; border: 2px solid #f59e0b;">⏳ En attente de confirmation</span>';
    }

    if (consultation.statut === 'payed') {
        return '<span class="consultation-indicator" style="background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #1e40af; border: 2px solid #3b82f6;">💳 Payé - en attente de confirmation</span>';
    }

    // Si le rendez-vous est confirmé (gérer les variantes confirmed/confirmé)
    if (consultation.statut === 'confirmé' || consultation.statut === 'confirmed') {
        // Vérifier si la consultation peut commencer (5 min avant)
        const cinqMinutesAvant = new Date(dateDebut.getTime() - 5 * 60000);
        const consultationPeutCommencer = maintenant >= cinqMinutesAvant && maintenant < dateFin;

        // Vérifier si la consultation est en cours
        const consultationEnCours = maintenant >= dateDebut && maintenant < dateFin;

        // Vérifier si la consultation est terminée
        const consultationTerminee = maintenant >= dateFin;

        if (consultationTerminee) {
            return '<span class="consultation-indicator" style="background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #1e40af; border: 2px solid #3b82f6;">✅ Consultation terminée</span>';
        } else if (consultationEnCours) {
            return '<span class="consultation-indicator indicator-in-progress">🔵 Consultation en cours</span>';
        } else if (consultationPeutCommencer) {
            return '<span class="consultation-indicator indicator-active">🟢 Consultation peut commencer (5 min avant)</span>';
        } else {
            // Consultation confirmée mais pas encore active
            const tempsRestant = Math.ceil((dateDebut - maintenant) / (1000 * 60));
            if (tempsRestant > 60) {
                const heures = Math.floor(tempsRestant / 60);
                return `<span class="consultation-indicator" style="background: linear-gradient(135deg, #f3f4f6, #e5e7eb); color: #374151; border: 2px solid #9ca3af;">⏰ Dans ${heures}h${tempsRestant % 60}min</span>`;
            } else {
                return `<span class="consultation-indicator" style="background: linear-gradient(135deg, #f3f4f6, #e5e7eb); color: #374151; border: 2px solid #9ca3af;">⏰ Dans ${tempsRestant}min</span>`;
            }
        }
    }

    // Statut inconnu
    return '<span class="consultation-indicator" style="background: linear-gradient(135deg, #f3f4f6, #e5e7eb); color: #6b7280; border: 2px solid #9ca3af;">❓ Statut inconnu: ' + consultation.statut + '</span>';
}

// Fonction pour voir les détails d'une consultation
function voirDetails(consultationId) {
    // Rediriger vers la page de détails de la consultation
    window.location.href = `/medecin/rendez-vous/${consultationId}`;
}

// Variables globales pour le modal de confirmation
let currentRendezVousId = null;
let currentNewStatus = null;
let currentSelectElement = null;

// Fonction pour afficher le modal de confirmation
function showStatusChangeModal(rendezVousId, newStatus, selectEl, consultationData) {
    const statusLabels = {
        'pending': 'En attente',
        'confirmed': 'Confirmé',
        'payed': 'Payé',
        'cancelled': 'Annulé',
        'completed': 'Terminé'
    };

    const statusColors = {
        'pending': 'bg-yellow-100 text-yellow-800',
        'confirmed': 'bg-green-100 text-green-800',
        'payed': 'bg-blue-100 text-blue-800',
        'cancelled': 'bg-red-100 text-red-800',
        'completed': 'bg-gray-100 text-gray-800'
    };

    // Stocker les données pour la confirmation
    currentRendezVousId = rendezVousId;
    currentNewStatus = newStatus;
    currentSelectElement = selectEl;

    // Remplir les informations du modal
    document.getElementById('newStatusLabel').textContent = statusLabels[newStatus] || newStatus;
    document.getElementById('patientPhoto').src = consultationData.photo || 'https://ui-avatars.com/api/?name=Patient';
    document.getElementById('patientName').textContent = `${consultationData.prenom} ${consultationData.nom}`;
    document.getElementById('appointmentTime').textContent = `${consultationData.heure} - ${consultationData.heure_fin}`;

    // Badge du statut actuel
    const currentStatusBadge = document.getElementById('currentStatusBadge');
    const currentStatus = selectEl.getAttribute('data-current-status') || consultationData.statut;
    currentStatusBadge.textContent = statusLabels[currentStatus] || currentStatus;
    currentStatusBadge.className = `inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${statusColors[currentStatus] || 'bg-gray-100 text-gray-800'}`;

    // Afficher un message d'avertissement si nécessaire
    const warningMessage = document.getElementById('warningMessage');
    const warningText = warningMessage.querySelector('p');

    if (newStatus === 'cancelled') {
        warningText.textContent = 'Cette action annulera définitivement le rendez-vous. Le patient sera notifié de l\'annulation.';
        warningMessage.classList.remove('hidden');
    } else if (newStatus === 'completed') {
        warningText.textContent = 'Marquer comme terminé fermera définitivement ce rendez-vous. Assurez-vous que la consultation est bien terminée.';
        warningMessage.classList.remove('hidden');
    } else {
        warningMessage.classList.add('hidden');
    }

    // Afficher le modal avec animation
    const modal = document.getElementById('statusChangeModal');
    modal.classList.remove('hidden');
    modal.classList.add('show');

    // Forcer le reflow pour l'animation
    setTimeout(() => {
        modal.classList.add('modal-enter');
    }, 10);
}

// Fonction pour masquer le modal
function hideStatusChangeModal() {
    const modal = document.getElementById('statusChangeModal');
    const modalContent = document.getElementById('statusModalContent');

    // Animation de sortie
    modalContent.classList.add('modal-exit');

    setTimeout(() => {
        modal.classList.remove('show', 'modal-enter', 'modal-exit');
        modal.classList.add('hidden');
        modalContent.classList.remove('modal-exit');

        // Réinitialiser les variables globales
        currentRendezVousId = null;
        currentNewStatus = null;
        currentSelectElement = null;
    }, 200);
}

// Fonction pour vérifier la connectivité réseau
function checkNetworkConnectivity() {
    return navigator.onLine;
}

// Fonction pour gérer les erreurs de réseau de manière intelligente
function handleNetworkError(error) {
    console.error('[NETWORK_ERROR]', error);

    // Si c'est une erreur DOM, ne pas la traiter comme une erreur réseau
    if (error.name === 'InvalidCharacterError' || error.message.includes('DOMTokenList')) {
        console.warn('[DOM_ERROR] Erreur DOM détectée, ignorée pour la gestion réseau');
        return null; // Ne pas afficher de notification d'erreur réseau
    }

    if (!checkNetworkConnectivity()) {
        return 'Connexion internet perdue. Vérifiez votre connexion et réessayez.';
    }

    if (error.name === 'AbortError') {
        return 'Délai d\'attente dépassé. Le serveur met trop de temps à répondre.';
    }

    if (error.message.includes('Failed to fetch')) {
        return 'Impossible de contacter le serveur. Vérifiez votre connexion internet.';
    }

    if (error.message.includes('NetworkError')) {
        return 'Erreur de réseau. Vérifiez votre connexion internet.';
    }

    if (error.message.includes('CORS')) {
        return 'Erreur de configuration serveur. Contactez l\'administrateur.';
    }

    return 'Erreur de connexion. Veuillez réessayer.';
}

// Fonction pour changer le statut d'un rendez-vous
async function changeRendezVousStatus(rendezVousId, newStatus, selectEl = null) {
    const statusLabels = {
        'pending': 'En attente',
        'confirmed': 'Confirmé',
        'payed': 'Payé',
        'cancelled': 'Annulé',
        'completed': 'Terminé'
    };

    console.log('[DEBUG] changeRendezVousStatus appelé', {
        rendezVousId,
        newStatus,
        currentUrl: window.location.href,
        csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    });

    // Récupérer les données de la consultation depuis la carte
    const consultationCard = selectEl.closest('.consultation-card');
    const consultationData = {
        prenom: consultationCard.querySelector('h3')?.textContent?.split(' ')[0] || 'Patient',
        nom: consultationCard.querySelector('h3')?.textContent?.split(' ').slice(1).join(' ') || '',
        photo: consultationCard.querySelector('img')?.src || 'https://ui-avatars.com/api/?name=Patient',
        heure: consultationCard.querySelector('.text-base')?.textContent?.split(' - ')[0] || '',
        heure_fin: consultationCard.querySelector('.text-base')?.textContent?.split(' - ')[1] || '',
        statut: selectEl.getAttribute('data-current-status') || 'pending'
    };

    // Afficher le modal de confirmation
    showStatusChangeModal(rendezVousId, newStatus, selectEl, consultationData);
}

// Fonction pour confirmer le changement de statut
async function confirmStatusChange() {
    if (!currentRendezVousId || !currentNewStatus || !currentSelectElement) {
        console.error('[DEBUG] Données manquantes pour la confirmation');
        showNotification('Erreur: Données manquantes pour la confirmation', 'error');
        return;
    }

    // Afficher l'état de chargement
    const confirmButton = document.getElementById('confirmStatusChange');
    const confirmText = document.getElementById('confirmButtonText');
    const loadingSpinner = document.getElementById('loadingSpinner');

    confirmButton.disabled = true;
    confirmText.classList.add('hidden');
    loadingSpinner.classList.remove('hidden');

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            console.error('[DEBUG] Token CSRF manquant');
            showNotification('Erreur: Token CSRF manquant. Veuillez recharger la page.', 'error');
            return;
        }

        console.log('[DEBUG] Envoi de la requête', {
            url: `/api/medecin/rendez-vous/${currentRendezVousId}/statut`,
            method: 'PUT',
            body: { statut: currentNewStatus },
            csrfToken
        });

        // Ajouter un timeout pour éviter les requêtes qui traînent
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 10000); // 10 secondes timeout

        const response = await fetch(`/api/medecin/rendez-vous/${currentRendezVousId}/statut`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify({ statut: currentNewStatus }),
            signal: controller.signal
        });

        clearTimeout(timeoutId);

        console.log('[DEBUG] Réponse reçue', {
            status: response.status,
            statusText: response.statusText,
            ok: response.ok
        });

        // Vérifier si la réponse est OK
        if (!response.ok) {
            let errorMessage = 'Erreur lors de la mise à jour du statut';

            if (response.status === 401) {
                errorMessage = 'Session expirée. Veuillez vous reconnecter.';
            } else if (response.status === 403) {
                errorMessage = 'Accès refusé. Vous n\'êtes pas autorisé à effectuer cette action.';
            } else if (response.status === 404) {
                errorMessage = 'Rendez-vous non trouvé.';
            } else if (response.status === 422) {
                errorMessage = 'Données invalides. Veuillez réessayer.';
            } else if (response.status >= 500) {
                errorMessage = 'Erreur serveur. Veuillez réessayer dans quelques instants.';
            }

            throw new Error(`${response.status}: ${errorMessage}`);
        }

        const data = await response.json();
        console.log('[DEBUG] Données de réponse', data);

        if (data.success) {
            const statutFromDb = data?.data?.statut || currentNewStatus;

            // Afficher le message de succès
            showNotification('✅ Statut mis à jour avec succès', 'success');

            // Mettre à jour l'affichage selon la valeur retournée par l'API
            updateConsultationDisplay(currentRendezVousId, statutFromDb);

            // Mémoriser le nouveau statut comme valeur actuelle du select
            if (currentSelectElement) {
                currentSelectElement.setAttribute('data-current-status', statutFromDb);
                currentSelectElement.value = statutFromDb;
            }

            // Masquer le modal avec un petit délai pour que l'utilisateur voie le succès
            setTimeout(() => {
                hideStatusChangeModal();
            }, 500);

        } else {
            throw new Error(data.message || 'Erreur lors de la mise à jour du statut');
        }

    } catch (error) {
        console.error('[DEBUG] Erreur lors du changement de statut:', error);

        // Utiliser la fonction de gestion d'erreurs intelligente
        const errorMessage = handleNetworkError(error);

        // Afficher la notification d'erreur seulement si ce n'est pas une erreur DOM
        if (errorMessage) {
            showNotification(`❌ ${errorMessage}`, 'error');
        }

        // Revenir à la valeur précédente en cas d'erreur réseau (pas pour les erreurs DOM)
        if (errorMessage && currentSelectElement) {
            const previous = currentSelectElement.getAttribute('data-current-status');
            if (previous) {
                currentSelectElement.value = previous;
            }
        }

    } finally {
        // Restaurer l'état du bouton
        confirmButton.disabled = false;
        confirmText.classList.remove('hidden');
        loadingSpinner.classList.add('hidden');
    }
}

// Gestionnaires d'événements pour le modal
document.addEventListener('DOMContentLoaded', function() {
    // Bouton de fermeture du modal
    document.getElementById('closeStatusModal').addEventListener('click', function() {
        hideStatusChangeModal();
    });

    // Bouton d'annulation
    document.getElementById('cancelStatusChange').addEventListener('click', function() {
        hideStatusChangeModal();
    });

    // Bouton de confirmation
    document.getElementById('confirmStatusChange').addEventListener('click', function() {
        confirmStatusChange();
    });

    // Fermer le modal en cliquant sur l'arrière-plan
    document.getElementById('statusChangeModal').addEventListener('click', function(e) {
        if (e.target === this) {
            hideStatusChangeModal();
        }
    });

    // Fermer le modal avec la touche Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('statusChangeModal');
            if (!modal.classList.contains('hidden')) {
                hideStatusChangeModal();
            }
        }
    });

    // Gestionnaire pour les changements de connectivité réseau
    window.addEventListener('online', function() {
        showNotification('🟢 Connexion internet rétablie', 'success');
    });

    window.addEventListener('offline', function() {
        showNotification('🔴 Connexion internet perdue', 'error');
    });
});

// Fonction pour afficher les notifications
function showNotification(message, type = 'info') {
    // Supprimer les notifications existantes du même type
    const existingNotifications = document.querySelectorAll('.notification-toast');
    existingNotifications.forEach(notif => notif.remove());

    const notification = document.createElement('div');
    notification.className = `notification-toast fixed top-4 right-4 z-50 p-4 rounded-xl shadow-2xl transition-all duration-500 transform translate-x-full max-w-sm ${
        type === 'success' ? 'bg-gradient-to-r from-green-500 to-green-600 text-white border-l-4 border-green-400' :
        type === 'error' ? 'bg-gradient-to-r from-red-500 to-red-600 text-white border-l-4 border-red-400' :
        'bg-gradient-to-r from-blue-500 to-blue-600 text-white border-l-4 border-blue-400'
    }`;

    const icon = type === 'success' ?
        '<svg class="w-6 h-6 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>' :
        type === 'error' ?
        '<svg class="w-6 h-6 text-red-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>' :
        '<svg class="w-6 h-6 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';

    notification.innerHTML = `
        <div class="flex items-start gap-3">
            <div class="flex-shrink-0">
                ${icon}
            </div>
            <div class="flex-1">
                <p class="font-medium text-sm leading-relaxed">${message}</p>
            </div>
            <button onclick="this.closest('.notification-toast').remove()"
                    class="flex-shrink-0 text-white hover:text-gray-200 transition-colors p-1 rounded-full hover:bg-white hover:bg-opacity-20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    `;

    document.body.appendChild(notification);

    // Animation d'entrée
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
        notification.classList.add('translate-x-0');
    }, 100);

    // Auto-suppression après 4 secondes pour les succès, 6 secondes pour les erreurs
    const duration = type === 'success' ? 4000 : type === 'error' ? 6000 : 5000;
    setTimeout(() => {
        notification.classList.remove('translate-x-0');
        notification.classList.add('translate-x-full');
        setTimeout(() => notification.remove(), 300);
    }, duration);
}
</script>
@endsection
