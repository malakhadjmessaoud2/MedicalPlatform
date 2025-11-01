@extends('dashMedecin.layout')

@section('content')
    <div class="p-4 sm:p-8 bg-[#e4e4e4] min-h-screen">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-center mb-8">
            <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-8 mb-4 sm:mb-0">
                <h1 class="text-2xl sm:text-4xl font-bold">
                    GESTI<span class="text-[#b9ff66]">O</span>N DES PATIENTS
                </h1>
                {{-- <div class="flex gap-2 sm:gap-4">
                    <button onclick="openNewPatientModal()"
                        class="bg-black text-white rounded-full px-3 sm:px-4 py-1.5 sm:py-2.5 flex items-center gap-2 hover:bg-black/90 transition-all text-sm sm:text-base">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                            <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        </svg>
                        <span>Nouveau Patient</span>
                    </button>
                </div> --}}
            </div>

            <!-- Search Bar -->
            <div class="relative w-full sm:w-72">
                <input type="text" placeholder="Rechercher un patient..."
                    class="w-full px-4 py-2 rounded-full bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-[#b9ff66]">
                <svg class="w-5 h-5 absolute right-3 top-2.5 text-gray-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        <!-- Main Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Left Column - Quick Actions -->
            <div class="lg:col-span-3 space-y-6">
                <!-- Actions Cards -->
                <div class="bg-white p-6 rounded-[20px] shadow-sm space-y-4">
                    <h2 class="text-xl font-bold mb-4">Actions Rapides</h2>

                    <!-- Dossiers Patients -->
                    <button onclick="showSection('dossiers')"
                        class="w-full bg-[#b9ff66] text-black rounded-full px-4 py-3 flex items-center justify-between hover:bg-[#a8eb5f] transition-all"
                        id="btn-dossiers">
                        <span class="font-medium">Dossiers Patients</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <!-- Rendez vous -->
                    <button onclick="showSection('consultations')"
                        class="w-full bg-gray-100 text-gray-700 rounded-full px-4 py-3 flex items-center justify-between hover:bg-gray-200 transition-all"
                        id="btn-consultations">
                        <span class="font-medium">Rendez vous</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>


                </div>

                <!-- Statistics Card -->
                <div class="bg-white p-6 rounded-[20px] shadow-sm">
                    <h2 class="text-xl font-bold mb-4">Statistiques</h2>
                    @php
                        // Valeurs par défaut pour éviter les erreurs si une clé manque
                        $stats = $stats ?? [];
                        $s = array_merge([
                            'total_patients' => 0,
                            'nouveaux_patients' => 0,
                            'consultations_mois' => 0,
                            'consultations_aujourdhui' => 0,
                            'consultations_semaine' => 0,
                            'rendezvous_payed' => 0,
                            'rendezvous_pending' => 0,
                        ], $stats);
                    @endphp

                    <div class="grid grid-cols-1 gap-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total patients</span>
                            <span class="text-2xl font-bold">{{ $s['total_patients'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Nouveaux patients (30j)</span>
                            <span class="text-lg font-semibold text-green-600">+{{ $s['nouveaux_patients'] }}</span>
                        </div>
                        <hr class="my-1">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Consultations aujourd'hui</span>
                            <span class="text-lg font-semibold">{{ $s['consultations_aujourdhui'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Consultations (semaine)</span>
                            <span class="text-lg font-semibold">{{ $s['consultations_semaine'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Consultations (mois)</span>
                            <span class="text-lg font-semibold">{{ $s['consultations_mois'] }}</span>
                        </div>
                        <hr class="my-1">

                    </div>
                </div>
            </div>

            <!-- Right Column - Content Sections -->
            <div class="lg:col-span-9">
                <!-- Dossiers Section -->
                <div id="section-dossiers" class="bg-white rounded-[20px] p-6 shadow-sm">
                    <h2 class="text-xl font-bold mb-6">Liste des Patients</h2>

                    <!-- Patients Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 rounded-lg">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Patient</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Âge</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Dernière visite</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Statut</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Actions</th>
                                </tr>
                            </thead>
                            @php $patients = $patients ?? collect([]); @endphp
                            @if ($patients instanceof \Illuminate\Pagination\LengthAwarePaginator ? $patients->count() : $patients->count())
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($patients as $patient)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    @php
                                                        $photoUrl = $patient->profile_photo_path
                                                            ? asset('storage/' . $patient->profile_photo_path)
                                                            : 'https://ui-avatars.com/api/?name=' .
                                                                urlencode($patient->prenom . ' ' . $patient->nom) .
                                                                '&color=7F9CF5&background=EBF4FF';
                                                    @endphp
                                                    <img src="{{ $photoUrl }}" alt="Photo de {{ $patient->prenom }}"
                                                        class="w-10 h-10 rounded-full object-cover">
                                                    <div>
                                                        <div class="font-medium">{{ $patient->prenom }} {{ $patient->nom }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">ID: #{{ $patient->id }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">{{ \Carbon\Carbon::parse($patient->dateNaissance)->age }}
                                                ans</td>
                                            <td class="px-6 py-4">{{ $patient->derniere_visite }}</td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                                                    Actif
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-4">
                                                    <a href="{{ route('medecin.dossiermedical', ['patient_id' => $patient->id]) }}"
                                                        class="text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                        Dossier
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                                <div class="flex flex-col items-center">
                                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <h3 class="text-lg font-medium">Aucun patient trouvé</h3>
                                                    <p class="text-sm">Vous n'avez pas encore de patients enregistrés.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            @endif
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($patients && $patients instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="flex justify-between items-center mt-6">
                            {{ $patients->links() }}
                        </div>
                    @endif
                </div>

                <!-- rendez vous Section -->
                <div id="section-consultations" class="bg-white rounded-[20px] p-6 shadow-sm hidden">
                    <h2 class="text-xl font-bold mb-6">Liste des rendez-vous</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 rounded-lg">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Patient</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Date</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Heure</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Statut</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @php $rendezVous = $rendezVous ?? collect([]); @endphp
                                @forelse($rendezVous as $rdv)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                @php
                                                    $photoUrl = $rdv->patient?->profile_photo_path
                                                        ? asset('storage/' . $rdv->patient->profile_photo_path)
                                                        : 'https://ui-avatars.com/api/?name=' .
                                                            urlencode(
                                                                ($rdv->patient->prenom ?? '') .
                                                                    ' ' .
                                                                    ($rdv->patient->nom ?? ''),
                                                            );
                                                @endphp
                                                <img src="{{ $photoUrl }}" class="w-8 h-8 rounded-full object-cover">
                                                <div class="font-medium">{{ $rdv->patient->prenom ?? '' }}
                                                    {{ $rdv->patient->nom ?? '' }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ \Carbon\Carbon::parse($rdv->date_debut)->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($rdv->date_debut)->format('H:i') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $statut = strtolower($rdv->statut ?? '');
                                                $labels = [
                                                    'pending' => 'En attente',
                                                    'confirmed' => 'Confirmé',
                                                    'cancelled' => 'Annulé',
                                                    'rejected' => 'Rejeté',
                                                    'completed' => 'Terminé',
                                                ];
                                            @endphp
                                            <span
                                                class="px-2 py-1 rounded-full text-sm
                                            @if ($statut === 'confirmed') bg-green-100 text-green-800
                                            @elseif($statut === 'cancelled') bg-red-100 text-red-800
                                            @elseif($statut === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($statut === 'completed') bg-gray-100 text-gray-800
                                            @else bg-blue-100 text-blue-800 @endif">
                                                {{ $labels[$statut] ?? ucfirst($statut ?: 'En attente') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <a href="{{ route('medecin.rendezvous.to.consultation', $rdv) }}"
                                                    class="text-blue-600 hover:text-blue-800">Consultation</a>
                                                <a href="{{ route('medecin.dossiermedical', ['patient_id' => $rdv->patient_id]) }}"
                                                    class="text-gray-700 hover:text-gray-900">Dossier</a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">Aucun rendez-vous
                                            trouvé.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Ordonnances Section supprimée -->
            </div>
        </div>
    </div>

    <!-- Modal Nouvelle Ordonnance -->
    <div id="newOrdonnanceModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-[20px] p-6 w-full max-w-4xl mx-4 relative">
            <!-- En-tête Modal -->
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">Nouvelle Ordonnance</h3>
                <button onclick="closeNewOrdonnanceModal()" class="hover:bg-gray-100 p-2 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Formulaire Ordonnance -->
            <form id="ordonnanceForm" class="space-y-6">
                <!-- Informations Patient -->
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Patient</label>
                        <select class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                            <option>Marie Dupont - #12345</option>
                            <option>Jean Martin - #12346</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Date</label>
                        <input type="date"
                            class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                    </div>
                </div>

                <!-- Médicaments -->
                <div class="space-y-4">
                    <label class="block text-sm font-medium text-gray-700">Médicaments</label>
                    <div id="medicaments-list" class="space-y-4">
                        <div class="grid grid-cols-12 gap-4 items-start bg-gray-50 p-4 rounded-lg">
                            <div class="col-span-4">
                                <input type="text" placeholder="Nom du médicament"
                                    class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                            </div>
                            <div class="col-span-3">
                                <input type="text" placeholder="Dosage"
                                    class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                            </div>
                            <div class="col-span-4">
                                <input type="text" placeholder="Posologie"
                                    class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                            </div>
                            <div class="col-span-1">
                                <button type="button" class="text-red-500 hover:text-red-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="addMedicament()"
                        class="text-[#b9ff66] hover:text-[#a8eb5f] flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Ajouter un médicament
                    </button>
                </div>

                <!-- Instructions supplémentaires -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Instructions supplémentaires</label>
                    <textarea rows="3" class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]"
                        placeholder="Ajoutez des instructions spécifiques..."></textarea>
                </div>

                <!-- Signature numérique -->
                <div class="bg-gray-50 p-4 rounded-lg space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Signature numérique</label>
                    <div class="flex items-center gap-4">
                        <div class="flex-1 bg-white p-3 rounded border">
                            Dr. Jean Martin - Certifié #12345MD
                        </div>
                        <button type="button" class="bg-[#b9ff66] hover:bg-[#a8eb5f] text-black rounded-full px-4 py-2">
                            Signer
                        </button>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-4 pt-4 border-t">
                    <button type="button" onclick="closeNewOrdonnanceModal()"
                        class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-full">
                        Annuler
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-[#b9ff66] hover:bg-[#a8eb5f] rounded-full flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Générer l'ordonnance
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Affichage Ordonnance PDF -->
    <div id="viewOrdonnanceModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-[20px] p-6 w-full max-w-5xl h-[95vh] mx-4 relative flex flex-col">
            <!-- En-tête Modal compact -->
            <div class="flex flex-col gap-3 mb-4">
                <!-- Titre et Actions -->
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <h3 class="text-xl font-bold">Ordonnance #ORD-2024-001</h3>
                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                            Signée numériquement
                        </span>
                    </div>
                    <button onclick="closeViewOrdonnanceModal()" class="hover:bg-gray-100 p-2 rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Informations Patient Compactes -->
                <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg">
                    <div class="flex items-center gap-3">
                        <img src="https://randomuser.me/api/portraits/women/68.jpg" class="w-10 h-10 rounded-full">
                        <div>
                            <div class="font-medium">Marie Dupont</div>
                            <div class="text-sm text-gray-500">42 ans - ID: #12345</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Dr. Jean Martin</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Date:</span>
                            <span class="ml-1">15/03/2024</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Barre d'outils -->
            <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg mb-4">
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Dernière modification: Aujourd'hui à 14:30</span>
                </div>
                <div class="flex-1"></div>
                <!-- Nouveau bouton Agrandir -->
                <button onclick="openFullscreenOrdonnance()"
                    class="px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-full flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 4h-4m4 0l-5-5" />
                    </svg>
                    Agrandir
                </button>
                <button onclick="downloadPDF()"
                    class="px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-full flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Télécharger
                </button>
                <button onclick="printPDF()"
                    class="px-4 py-2 bg-[#b9ff66] hover:bg-[#a8eb5f] rounded-full flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Imprimer
                </button>
            </div>

            <!-- PDF Viewer (agrandi) -->
            <div class="flex-1 bg-gray-100 rounded-lg overflow-hidden relative">
                <iframe id="pdfViewer" class="w-full h-full" src="/storage/ordonnances/exemple.pdf#toolbar=0"
                    type="application/pdf">
                </iframe>
            </div>

            <!-- Footer compact -->
            <div class="mt-3 p-3 bg-gray-50 rounded-lg text-sm text-gray-600">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span>Document sécurisé et signé numériquement</span>
                    </div>
                    <div class="text-right">
                        <span>Valide jusqu'au 15/06/2024</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ajouter la modal plein écran -->
    <div id="fullscreenOrdonnanceModal"
        class="fixed inset-0 bg-black bg-opacity-90 hidden items-center justify-center z-[60]">
        <div class="relative w-full h-full p-4">
            <!-- Bouton fermer -->
            <button onclick="closeFullscreenOrdonnance()"
                class="absolute top-4 right-4 text-white hover:text-gray-300 p-2 rounded-full">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- PDF en plein écran -->
            <embed src="/storage/ordonnances/exemple.pdf#toolbar=0" type="application/pdf" class="w-full h-full" />
        </div>
    </div>

    {{-- <!-- Modal Nouveau Patient avec design amélioré -->
    <div id="newPatientModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div
            class="bg-white rounded-[20px] p-8 w-full max-w-4xl mx-4 relative max-h-[90vh] overflow-y-auto shadow-2xl transform transition-all duration-300 ease-in-out">
            <!-- En-tête Modal -->
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold">
                    <span class="text-black">NOUVEAU</span>
                    <span class="text-[#b9ff66]">PATIENT</span>
                </h1>
                <button onclick="closeNewPatientModal()"
                    class="hover:bg-gray-100 p-2 rounded-full transition-all duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Formulaire -->
            <form id="newPatientForm" class="space-y-8">
                <!-- Informations Personnelles -->
                <div class="bg-[#f8fafc] p-6 rounded-[20px] shadow-sm space-y-6">
                    <h2 class="text-xl font-bold flex items-center gap-2">
                        <svg class="w-6 h-6 text-[#b9ff66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Informations Personnelles
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Nom</label>
                            <input type="text" placeholder="Benali"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66] transition-all duration-200">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Prénom</label>
                            <input type="text" placeholder="Mohammed"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66] transition-all duration-200">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Date de naissance</label>
                            <input type="date"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66] transition-all duration-200">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Sexe</label>
                            <div class="flex gap-6 mt-2">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="sexe" value="masculin"
                                        class="text-[#b9ff66] focus:ring-[#b9ff66]">
                                    <span>Masculin</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="sexe" value="feminin"
                                        class="text-[#b9ff66] focus:ring-[#b9ff66]">
                                    <span>Féminin</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="sexe" value="autre"
                                        class="text-[#b9ff66] focus:ring-[#b9ff66]">
                                    <span>Autre</span>
                                </label>
                            </div>
                        </div>
                        <div class="sm:col-span-2 space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Adresse</label>
                            <input type="text" placeholder="15 rue Ibn Sina, Casablanca"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66] transition-all duration-200">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Code postal</label>
                            <input type="text" placeholder="20000"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66] transition-all duration-200">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Ville</label>
                            <input type="text" placeholder="Casablanca"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66] transition-all duration-200">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                            <input type="tel" placeholder="0661234567"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66] transition-all duration-200">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" placeholder="mohammed.benali@email.com"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66] transition-all duration-200">
                        </div>
                    </div>
                </div>

                <!-- Situation Sociale -->
                <div class="bg-[#f8fafc] p-6 rounded-[20px] shadow-sm space-y-6">
                    <h2 class="text-xl font-bold flex items-center gap-2">
                        <svg class="w-6 h-6 text-[#b9ff66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Situation Sociale
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">État civil</label>
                            <select
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66] transition-all duration-200">
                                <option>Célibataire</option>
                                <option>Marié(e)</option>
                                <option>Divorcé(e)</option>
                                <option>Veuf(ve)</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Nombre d'enfants</label>
                            <input type="number" placeholder="3" min="0"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66] transition-all duration-200">
                        </div>
                        <div class="sm:col-span-2 space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Profession</label>
                            <input type="text" placeholder="Commerçant"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66] transition-all duration-200">
                        </div>
                    </div>
                </div>

                <!-- Assurance et Sécurité Sociale -->
                <div class="bg-[#f8fafc] p-6 rounded-[20px] shadow-sm space-y-6">
                    <h2 class="text-xl font-bold flex items-center gap-2">
                        <svg class="w-6 h-6 text-[#b9ff66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        Assurance et Sécurité Sociale
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Numéro de sécurité sociale</label>
                            <input type="text" placeholder="189 45 678 901"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66] transition-all duration-200">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Nom de l'assurance santé</label>
                            <input type="text" placeholder="CNSS"
                                class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66] transition-all duration-200">
                        </div>

                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-end gap-4 pt-6">
                    <button type="button" onclick="closeNewPatientModal()"
                        class="px-6 py-2.5 bg-white text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-200">
                        Annuler
                    </button>
                    <button type="submit"
                        class="px-6 py-2.5 bg-[#b9ff66] hover:bg-[#a8eb5f] rounded-lg flex items-center gap-2 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Créer le patient
                    </button>
                </div>
            </form>
        </div>
    </div> --}}

    <script>
        function showSection(section) {
            // Cache les sections existantes uniquement si elles sont présentes
            var secDossiers = document.getElementById('section-dossiers');
            var secConsult = document.getElementById('section-consultations');
            if (secDossiers) secDossiers.classList.add('hidden');
            if (secConsult) secConsult.classList.add('hidden');

            // Réinitialise les boutons s'ils existent
            var btnD = document.getElementById('btn-dossiers');
            var btnC = document.getElementById('btn-consultations');
            if (btnD) { btnD.classList.remove('bg-[#b9ff66]'); btnD.classList.add('bg-gray-100'); }
            if (btnC) { btnC.classList.remove('bg-[#b9ff66]'); btnC.classList.add('bg-gray-100'); }

            // Affiche la section sélectionnée
            var target = document.getElementById(`section-${section}`);
            if (target) target.classList.remove('hidden');

            // Active le bouton sélectionné
            var btnTarget = document.getElementById(`btn-${section}`);
            if (btnTarget) { btnTarget.classList.remove('bg-gray-100'); btnTarget.classList.add('bg-[#b9ff66]'); }
        }

        // Définir une section par défaut au chargement pour assurer un état cohérent
        document.addEventListener('DOMContentLoaded', function () {
            showSection('dossiers');
        });

        function openNewOrdonnanceModal() {
            document.getElementById('newOrdonnanceModal').classList.remove('hidden');
            document.getElementById('newOrdonnanceModal').classList.add('flex');
        }

        function closeNewOrdonnanceModal() {
            document.getElementById('newOrdonnanceModal').classList.add('hidden');
            document.getElementById('newOrdonnanceModal').classList.remove('flex');
        }

        function addMedicament() {
            const template = `
        <div class="grid grid-cols-12 gap-4 items-start bg-gray-50 p-4 rounded-lg">
            <div class="col-span-4">
                <input type="text"
                       placeholder="Nom du médicament"
                       class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
            </div>
            <div class="col-span-3">
                <input type="text"
                       placeholder="Dosage"
                       class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
            </div>
            <div class="col-span-4">
                <input type="text"
                       placeholder="Posologie"
                       class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
            </div>
            <div class="col-span-1">
                <button type="button" onclick="this.closest('.grid').remove()" class="text-red-500 hover:text-red-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>
        </div>
    `;
            document.getElementById('medicaments-list').insertAdjacentHTML('beforeend', template);
        }

        // Gestion du formulaire
        document.getElementById('ordonnanceForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // Simulation de la génération de l'ordonnance
            alert('Ordonnance générée avec succès ! UUID: ' + crypto.randomUUID());
            closeNewOrdonnanceModal();
        });

        // Fermeture de la modal en cliquant en dehors
        document.getElementById('newOrdonnanceModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeNewOrdonnanceModal();
            }
        });

        // Fonctions pour la modal d'affichage PDF
        function openViewOrdonnanceModal() {
            document.getElementById('viewOrdonnanceModal').classList.remove('hidden');
            document.getElementById('viewOrdonnanceModal').classList.add('flex');
        }

        function closeViewOrdonnanceModal() {
            document.getElementById('viewOrdonnanceModal').classList.add('hidden');
            document.getElementById('viewOrdonnanceModal').classList.remove('flex');
        }

        // Fonctions pour les actions sur le PDF
        function downloadPDF() {
            const link = document.createElement('a');
            link.href = '/storage/ordonnances/exemple.pdf';
            link.download = 'Ordonnance.pdf';
            link.click();
        }

        function printPDF() {
            const viewer = document.getElementById('pdfViewer');
            viewer.contentWindow.print();
        }

        // Fermeture de la modal en cliquant en dehors
        document.getElementById('viewOrdonnanceModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeViewOrdonnanceModal();
            }
        });

        // Fonction pour ouvrir l'ordonnance en plein écran
        function openFullscreenOrdonnance() {
            document.getElementById('fullscreenOrdonnanceModal').classList.remove('hidden');
            document.getElementById('fullscreenOrdonnanceModal').classList.add('flex');
            document.body.style.overflow = 'hidden'; // Empêche le défilement du body
        }

        // Fonction pour fermer l'ordonnance en plein écran
        function closeFullscreenOrdonnance() {
            document.getElementById('fullscreenOrdonnanceModal').classList.add('hidden');
            document.getElementById('fullscreenOrdonnanceModal').classList.remove('flex');
            document.body.style.overflow = 'auto'; // Réactive le défilement du body
        }

        // Fermeture de la modal plein écran en cliquant en dehors
        document.getElementById('fullscreenOrdonnanceModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeFullscreenOrdonnance();
            }
        });

        // Fermeture avec la touche Echap
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeFullscreenOrdonnance();
            }
        });

        function openNewPatientModal() {
            document.getElementById('newPatientModal').classList.remove('hidden');
            document.getElementById('newPatientModal').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeNewPatientModal() {
            document.getElementById('newPatientModal').classList.add('hidden');
            document.getElementById('newPatientModal').classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        // Gestion du formulaire
        document.getElementById('newPatientForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // Logique de soumission du formulaire
            alert('Patient créé avec succès !');
            closeNewPatientModal();
        });

        // Fermeture en cliquant en dehors
        document.getElementById('newPatientModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeNewPatientModal();
            }
        });
    </script>

    <style>
        .pagination-btn {
            @apply flex items-center justify-center w-8 h-8 rounded-full text-sm hover:bg-gray-100 transition-all;
        }

        .pagination-btn.active {
            @apply bg-[#b9ff66] hover:bg-[#a8eb5f];
        }

        .pagination-btn[disabled] {
            @apply opacity-50 cursor-not-allowed hover:bg-transparent;
        }
    </style>
@endsection
