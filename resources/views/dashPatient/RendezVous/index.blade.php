@extends('dashPatient.layout')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <!-- Header avec bouton nouveau rendez-vous -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Mes Rendez-vous</h1>
            <p class="text-gray-500 mt-1">Gérez vos consultations médicales en toute simplicité</p>
        </div>
        <a href="{{ route('patient.rendez-vous.create') }}" class="px-4 py-2.5 bg-[#b9ff66] hover:bg-[#a3e55a] text-gray-800 rounded-lg flex items-center gap-2 shadow-sm transition-all duration-200 hover:shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nouveau Rendez-vous
        </a>
    </div>

    <!-- Indicateur de rafraîchissement automatique -->
    {{-- <div id="refresh-indicator" class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4 text-center">
        <div class="flex items-center justify-center gap-2 text-blue-700">
            <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            <span class="text-sm font-medium">Mise à jour automatique activée - </span>
            <span class="refresh-time text-sm font-medium">Prochain rafraîchissement dans 30s</span>
        </div>
    </div> --}}

    <!-- Filtres et recherche -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="relative w-full md:w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" placeholder="Rechercher un rendez-vous..." class="pl-10 pr-4 py-2 w-full rounded-lg border border-gray-200 focus:outline-none focus:ring-1 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto">
                <select class="px-3 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-1 focus:ring-[#b9ff66] focus:border-[#b9ff66] text-gray-600">
                    <option value="">Tous les médecins</option>
                    <option value="cardiologue">Cardiologue</option>
                    <option value="dermatologue">Dermatologue</option>
                    <option value="generaliste">Généraliste</option>
                </select>

                <select class="px-3 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-1 focus:ring-[#b9ff66] focus:border-[#b9ff66] text-gray-600">
                    <option value="">Tous les statuts</option>
                    <option value="en_attente">En attente</option>
                    <option value="confirme">Confirmé</option>
                    <option value="annule">Annulé</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Prochains rendez-vous -->
    <!-- Cette section affiche les rendez-vous confirmés jusqu'à leur date de début -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-[#b9ff66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Prochains rendez-vous
        </h2>

        @php
            // Filtrer les prochains rendez-vous (jusqu'à la date de début)
            $prochainsRendezVousFiltres = $prochainsRendezVous->filter(function($rdv) {
                $heureDebut = \Carbon\Carbon::parse($rdv->date_debut);
                $maintenant = \Carbon\Carbon::now();
                return $maintenant <= $heureDebut;
            });
        @endphp

        @forelse($prochainsRendezVousFiltres as $rdv)
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 mb-4">
            <div class="p-5 border-l-4 border-[#b9ff66]">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        @php
                            $photo = $rdv->medecin?->profile_photo_path;
                            $photoUrl = $photo ? asset('storage/' . $photo) : 'https://ui-avatars.com/api/?name=' . urlencode($rdv->medecin->prenom . ' ' . $rdv->medecin->nom);
                        @endphp
                        <img src="{{ $photoUrl }}" alt="Photo de Dr. {{ $rdv->medecin->nom }}" class="w-14 h-14 rounded-full object-cover border-2 border-gray-100">
                        <div>
                            <h3 class="font-semibold text-lg">Dr. {{ $rdv->medecin->nom }} {{ $rdv->medecin->prenom }}</h3>
                            <p class="text-gray-500">{{ $rdv->medecin->specialite }}</p>
                            <div class="flex items-center mt-1 text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $rdv->medecin->adresse_cabinet }}
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col items-end">
                        <div class="bg-[#b9ff66]/10 px-3 py-1 rounded-full text-[#92cc52] text-sm font-medium">
                            @php
                                $labels = [
                                    'pending' => 'En attente',
                                    'confirmed' => 'Confirmé',
                                    'cancelled' => 'Annulé',
                                    'rejected' => 'Rejeté',
                                    'completed' => 'Terminé',
                                ];
                            @endphp
                            {{ $labels[$rdv->statut] ?? ucfirst($rdv->statut) }}
                        </div>
                        <p class="font-medium text-lg mt-2">{{ $rdv->date_debut->timezone('Africa/Tunis')->translatedFormat('d F Y') }}</p>
                        <p class="text-gray-500">
                            {{ $rdv->date_debut->timezone('Africa/Tunis')->format('H:i') }} -
                            {{ $rdv->date_fin->timezone('Africa/Tunis')->format('H:i') }}
                        </p>

                        <div class="flex gap-2 mt-3">
                            {{-- <button onclick="window.location.href='{{ route('patient.rendez-vous.edit', $rdv->id) }}'"
                                    class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                                Modifier
                            </button> --}}
                            <form action="{{ route('patient.rendez-vous.cancel', $rdv->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ?')"
                                        class="px-3 py-1.5 text-sm border border-red-200 text-red-500 rounded-lg hover:bg-red-50 transition-colors flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Annuler
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Countdown et rappels -->
                <div class="mt-4 pt-4 border-t border-gray-100 flex flex-col sm:flex-row justify-between gap-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm text-gray-600">Dans <span class="countdown-timer" data-date="{{ $rdv->date_debut->toISOString() }}">{{ $rdv->date_debut->diffForHumans() }}</span></span>
                    </div>

                    <div class="flex gap-3">

                        <button class="text-sm text-gray-600 flex items-center gap-1 hover:text-gray-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Documents requis
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-gray-50 rounded-xl p-6 text-center">
            <p class="text-gray-500">Aucun rendez-vous à venir</p>
            <a href="{{ route('patient.rendez-vous.create') }}" class="inline-block mt-3 px-4 py-2 bg-[#b9ff66] text-black rounded-lg hover:bg-[#a8eb5f] transition-colors">
                Prendre un rendez-vous
            </a>
        </div>
        @endforelse
    </div>

    <!-- Consultations en ligne -->
    <!-- Cette section affiche les consultations confirmées jusqu'à leur date de fin -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            Consultations en ligne
        </h2>

        @php
            // Filtrer seulement les consultations qui doivent être affichées dans "Consultations en ligne"
            // IMPORTANT: Les consultations restent affichées jusqu'à la date de fin du rendez-vous
            $consultationsEnLigne = $prochainsRendezVous->filter(function($rdv) {
                $heureFin = \Carbon\Carbon::parse($rdv->date_fin ?? $rdv->date_debut->addMinutes(30));
                $maintenant = \Carbon\Carbon::now();

                // Afficher dès que le statut est confirmé ET jusqu'à la fin du rendez-vous
                // Cela permet aux consultations de rester visibles pendant toute leur durée
                return $rdv->statut === 'confirmed' &&
                       $rdv->lien_en_ligne &&
                       $maintenant <= $heureFin;
            });
        @endphp

        @if($consultationsEnLigne->count() > 0)
            @foreach($consultationsEnLigne as $rdv)
                @php
                    $heureDebut = \Carbon\Carbon::parse($rdv->date_debut);
                    $heureFin = \Carbon\Carbon::parse($rdv->date_fin ?? $rdv->date_debut->addMinutes(30));
                    $maintenant = \Carbon\Carbon::now();
                    $consultationActive = $maintenant->between($heureDebut->copy()->subMinutes(5), $heureFin);
                    $consultationEnCours = $maintenant->between($heureDebut, $heureFin);
                @endphp

                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-blue-200 mb-4 hover:shadow-md transition-shadow" data-rdv="{{ $rdv->id }}" data-debut="{{ $rdv->date_debut->toISOString() }}" data-fin="{{ ($rdv->date_fin ?? $rdv->date_debut->addMinutes(30))->toISOString() }}">
                    <div class="p-5 border-l-4 border-blue-500">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                @php
                                    $photo = $rdv->medecin?->profile_photo_path;
                                    $photoUrl = $photo ? asset('storage/' . $photo) : 'https://ui-avatars.com/api/?name=' . urlencode($rdv->medecin->prenom . ' ' . $rdv->medecin->nom);
                                @endphp
                                <img src="{{ $photoUrl }}" alt="Photo de Dr. {{ $rdv->medecin->nom }}" class="w-14 h-14 rounded-full object-cover border-2 border-blue-100">
                                <div>
                                    <h3 class="font-semibold text-lg">Dr. {{ $rdv->medecin->nom }} {{ $rdv->medecin->prenom }}</h3>
                                    <p class="text-gray-500">{{ $rdv->medecin->specialite }}</p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ $rdv->date_debut->timezone('Africa/Tunis')->format('H:i') }} -
                                        {{ $rdv->date_fin->timezone('Africa/Tunis')->format('H:i') }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex flex-col items-end">
                                <a href="{{ route('patient.rendez-vous.payer', $rdv->id) }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm rounded-lg bg-blue-600 text-white font-medium shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h5M4 7h16a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V9a2 2 0 012-2z"/>
                                    </svg>
                                    Payer la consultation
                                </a>
                                <div class="flex items-center gap-2 mb-3 mt-3">
                                        @if($consultationEnCours)
                                            <span class="px-3 py-1 bg-green-50 text-green-700 border border-green-200 rounded-full text-sm font-medium">
                                                🟢 Consultation en cours
                                            </span>
                                    @elseif($consultationActive)
                                            <span class="px-3 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-full text-sm font-medium">
                                            🔵 Consultation peut commencer (5 min avant)
                                            </span>
                                    @else
                                            <span class="px-3 py-1 bg-gray-50 text-gray-600 border border-gray-200 rounded-full text-sm font-medium">
                                            ⏰ Consultation programmée
                                            </span>
                                    @endif
                                </div>

                                @if($consultationActive)
                                    <a href="{{ $rdv->lien_en_ligne }}"
                                       target="_blank"
                                       class="inline-flex items-center gap-2 px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium transition-colors shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                        Rejoindre la consultation
                                    </a>
                                    <!-- Indicateur du type de lien -->
                                    <div class="text-xs text-gray-500 mt-1">
                                        @if(str_contains($rdv->lien_en_ligne, 'meet.jit.si'))
                                            <span class="inline-flex items-center gap-1">
                                                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                                </svg>
                                                Jitsi Meet
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1">
                                                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                                </svg>
                                                Consultation en ligne
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <div class="text-center">
                                        <div class="text-sm text-gray-500 mb-2">
                                            @if($consultationEnCours)
                                                Consultation en cours
                                            @else
                                                Le lien sera actif 5 minutes avant le début
                                            @endif
                                        </div>
                                        @if(!$consultationEnCours)
                                        <div class="text-xs text-gray-400">
                                            {{ $heureDebut->diffForHumans() }}
                                        </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="bg-gray-50 rounded-xl p-6 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                <p class="text-gray-500 text-lg mb-2">Aucune consultation en ligne programmée</p>
                <p class="text-gray-400 text-sm">Les consultations en ligne apparaîtront ici dès confirmation et resteront visibles jusqu'à la fin du rendez-vous</p>
            </div>
        @endif
    </div>

    <!-- Rendez-vous en attente -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            En attente de confirmation
        </h2>

        @if($rendezVousEnAttente->count() > 0)
            @foreach($rendezVousEnAttente as $rdv)
            <div class="bg-white rounded-xl shadow-sm mb-4">
                <div class="p-4 border-l-4 border-yellow-400 rounded-xl">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            @php
                                $photo = $rdv->medecin?->profile_photo_path;
                                $photoUrl = $photo ? asset('storage/' . $photo) : 'https://ui-avatars.com/api/?name=' . urlencode($rdv->medecin->prenom . ' ' . $rdv->medecin->nom);
                            @endphp
                            <img src="{{ $photoUrl }}" alt="Photo de Dr. {{ $rdv->medecin->nom }}" class="w-14 h-14 rounded-full object-cover border-2 border-gray-100">
                            <div>
                                <h3 class="font-semibold">Dr. {{ $rdv->medecin->nom }} {{ $rdv->medecin->prenom }}</h3>
                                <p class="text-sm text-gray-500">{{ $rdv->medecin->specialite }}</p>
                                <div class="flex items-center mt-1 text-xs text-gray-500">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    {{ $rdv->medecin->adresse_cabinet }}
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col items-end">
                            <div class="bg-yellow-50 px-3 py-1 rounded-full text-yellow-600 text-sm font-medium">
                                {{ ucfirst($rdv->statut) }}
                            </div>
                            <p class="font-medium">{{ $rdv->date_debut->timezone('Africa/Tunis')->translatedFormat('d F Y') }}</p>
                            <p class="text-sm text-gray-500">
                                {{ $rdv->date_debut->timezone('Africa/Tunis')->format('H:i') }} -
                                {{ $rdv->date_fin->timezone('Africa/Tunis')->format('H:i') }}
                            </p>

                            <div class="flex gap-2 mt-2">
                                <form action="{{ route('patient.rendez-vous.cancel', $rdv->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ?')"
                                            class="px-3 py-1 text-xs border border-red-200 text-red-500 rounded-lg hover:bg-red-50 transition-colors">
                                        Annuler
                                    </button>
                                </form>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="bg-gray-50 rounded-xl p-6 text-center">
                <p class="text-gray-500">Aucun rendez-vous en attente</p>
            </div>
        @endif
    </div>

            <!-- Rendez-vous terminés de ce jour -->
    <!-- Cette section affiche les rendez-vous confirmés qui se sont terminés aujourd'hui -->
    @php
        $rendezVousTerminesAujourdhui = $prochainsRendezVous->filter(function($rdv) {
            $heureFin = \Carbon\Carbon::parse($rdv->date_fin ?? $rdv->date_debut->addMinutes(30));
            $maintenant = \Carbon\Carbon::now();
            $aujourdhui = \Carbon\Carbon::today();

            // Inclure seulement les rendez-vous confirmés terminés aujourd'hui
            // Logique: statut confirmé + heure actuelle > heure de fin + même jour
            return $rdv->statut === 'confirmed' &&
                   $maintenant > $heureFin &&
                   $heureFin->isSameDay($aujourdhui);
        });
    @endphp

    @if($rendezVousTerminesAujourdhui->count() > 0)
    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Rendez-vous terminés de ce jour
        </h2>

        @foreach($rendezVousTerminesAujourdhui as $rdv)
            <div class="bg-gray-50 rounded-xl shadow-sm overflow-hidden border border-gray-200 mb-4">
                <div class="p-5 border-l-4 border-gray-400">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            @php
                                $photo = $rdv->medecin->user?->profile_photo_path;
                                $photoUrl = $photo ? asset('storage/' . $photo) : 'https://ui-avatars.com/api/?name=' . urlencode($rdv->medecin->prenom . ' ' . $rdv->medecin->nom);
                            @endphp
                            <img src="{{ $photoUrl }}" alt="Photo de Dr. {{ $rdv->medecin->nom }}" class="w-14 h-14 rounded-full object-cover border-2 border-gray-200">
                            <div>
                                <h3 class="font-semibold text-lg text-gray-700">Dr. {{ $rdv->medecin->nom }} {{ $rdv->medecin->prenom }}</h3>
                                <p class="text-gray-500">{{ $rdv->medecin->specialite }}</p>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $rdv->date_debut->timezone('Africa/Tunis')->format('H:i') }} -
                                    {{ $rdv->date_fin->timezone('Africa/Tunis')->format('H:i') }}
                                </p>
                                @if($rdv->lien_en_ligne)
                                    <div class="text-xs text-blue-600 mt-1">
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                            Consultation en ligne terminée
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-col items-end">
                            <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-sm font-medium">
                                ✅ Rendez-vous terminé
                            </span>
                            <div class="text-xs text-gray-500 mt-2">
                                Terminé à {{ \Carbon\Carbon::parse($rdv->date_fin ?? $rdv->date_debut->addMinutes(30))->format('H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @else
        <div class="bg-gray-50 rounded-xl p-6 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-gray-500 text-lg mb-2">Aucun rendez-vous terminé aujourd'hui</p>
            <p class="text-gray-400 text-sm">Les rendez-vous terminés de ce jour apparaîtront ici</p>
        </div>
    @endif

    <!-- Historique des rendez-vous -->
    <div>
        <h2 class="text-xl font-semibold mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Historique
        </h2>

        @if($historiqueRendezVous->count() > 0)
            <div class="bg-white rounded-xl shadow-sm divide-y">
                @foreach($historiqueRendezVous as $rdv)
                <div class="p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            @php
                                $photo = $rdv->medecin->user?->profile_photo_path;
                                $photoUrl = $photo ? asset('storage/' . $photo) : 'https://ui-avatars.com/api/?name=' . urlencode($rdv->medecin->prenom . ' ' . $rdv->medecin->nom);
                            @endphp
                            <img src="{{ $photoUrl }}" alt="Photo de Dr. {{ $rdv->medecin->nom }}" class="w-14 h-14 rounded-full object-cover border-2 border-gray-100">
                            <div>
                                <h3 class="font-semibold">Dr. {{ $rdv->medecin->nom }} {{ $rdv->medecin->prenom }}</h3>
                                <p class="text-sm text-gray-500">{{ $rdv->medecin->specialite }}</p>
                            </div>
                        </div>
                        <div class="flex flex-col items-end">
                            <p class="font-medium">{{ $rdv->date_debut->timezone('Africa/Tunis')->translatedFormat('d F Y') }}</p>
                            <p class="text-sm text-gray-500">
                                {{ $rdv->date_debut->timezone('Africa/Tunis')->format('H:i') }}
                            </p>
                            @php
                                $cls = match($rdv->statut) {
                                    'confirmed' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                    'completed' => 'bg-gray-100 text-gray-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                                $labels = [
                                    'pending' => 'En attente',
                                    'confirmed' => 'Confirmé',
                                    'cancelled' => 'Annulé',
                                    'rejected' => 'Rejeté',
                                    'completed' => 'Terminé',
                                ];
                            @endphp
                            <span class="px-2 py-1 text-xs rounded-full {{ $cls }}">
                                {{ $labels[$rdv->statut] ?? ucfirst($rdv->statut) }}
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $historiqueRendezVous->links() }}
            </div>
        @else
            <div class="bg-gray-50 rounded-xl p-6 text-center">
                <p class="text-gray-500">Aucun rendez-vous terminé aujourd'hui</p>
                <p class="text-gray-400 text-sm mt-2">Les rendez-vous terminés de ce jour apparaîtront ici</p>
            </div>
        @endif
    </div>

</div>

<script>
    // Fonction pour rafraîchir automatiquement la page
    function rafraichirPage() {
        // Rafraîchir la page toutes les 30 secondes
        setInterval(() => {
            // Afficher un indicateur de rafraîchissement
            const indicator = document.getElementById('refresh-indicator');
            if (indicator) {
                indicator.innerHTML = `
                    <div class="flex items-center justify-center gap-2 text-blue-700">
                        <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span class="text-sm font-medium">Rafraîchissement en cours...</span>
                    </div>
                `;
            }

            // Rafraîchir la page après un court délai
            setTimeout(() => {
                location.reload();
            }, 1000);
        }, 30000); // 30 secondes
    }

    // Fonction pour mettre à jour le compteur de rafraîchissement
    function updateRefreshCountdown() {
        const now = new Date();
        const nextRefresh = new Date(now.getTime() + (30 - (now.getSeconds() % 30)) * 1000);
        const timeLeft = Math.ceil((nextRefresh - now) / 1000);

        const indicator = document.getElementById('refresh-indicator');
        if (indicator) {
            const timeSpan = indicator.querySelector('.refresh-time');
            if (timeSpan) {
                timeSpan.textContent = `Prochain rafraîchissement dans ${timeLeft}s`;
            }
        }
    }

    // Fonction pour mettre à jour les indicateurs de consultation en temps réel
    function mettreAJourIndicateurs() {
        // Mettre à jour les indicateurs toutes les 10 secondes
        setInterval(() => {
            // Mettre à jour le compteur de rafraîchissement
            updateRefreshCountdown();

            // Mettre à jour les compteurs de temps
            // Mettre à jour les compteurs de temps
            const countdowns = document.querySelectorAll('.countdown-timer');
            countdowns.forEach(countdown => {
                const dateRdv = new Date(countdown.dataset.date);
                const maintenant = new Date();
                const diff = dateRdv - maintenant;

                if (diff > 0) {
                    const heures = Math.floor(diff / (1000 * 60 * 60));
                    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                    const secondes = Math.floor((diff % (1000 * 60)) / 1000);

                    countdown.textContent = `${heures}h ${minutes}m ${secondes}s`;
                } else {
                    countdown.textContent = 'Maintenant';
                    // Recharger la page quand le rendez-vous commence
                    setTimeout(() => location.reload(), 1000);
                }
            });

            // Mettre à jour les indicateurs de consultation
            const consultationsEnLigne = document.querySelectorAll('.consultation-status');
            consultationsEnLigne.forEach(consultation => {
                const dateDebut = new Date(consultation.dataset.debut);
                const dateFin = new Date(consultation.dataset.fin);
                const maintenant = new Date();

                // Vérifier si la consultation peut commencer (5 min avant)
                const peutCommencer = maintenant >= new Date(dateDebut.getTime() - 5 * 60000);
                const enCours = maintenant >= dateDebut && maintenant <= dateFin;

                if (enCours) {
                    consultation.innerHTML = '<span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">🟢 Consultation en cours</span>';
                } else if (peutCommencer) {
                    consultation.innerHTML = '<span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">🔵 Consultation peut commencer (5 min avant)</span>';
                } else {
                    consultation.innerHTML = '<span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">⏰ Consultation programmée</span>';
                }
            });
        }, 10000); // 10 secondes
    }

    // Fonction pour ajouter des classes CSS pour le rafraîchissement automatique
    function preparerRafraichissement() {
        // Ajouter des classes aux éléments qui doivent être mis à jour
        const countdowns = document.querySelectorAll('[data-date]');
        countdowns.forEach(countdown => {
            countdown.classList.add('countdown-timer');
        });

        const consultations = document.querySelectorAll('.consultation-indicator');
        consultations.forEach(consultation => {
            const rdv = consultation.closest('[data-rdv]');
            if (rdv) {
                consultation.classList.add('consultation-status');
                consultation.dataset.debut = rdv.dataset.debut;
                consultation.dataset.fin = rdv.dataset.fin;
            }
        });
    }

    // Initialiser le rafraîchissement automatique au chargement de la page
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Initialisation du rafraîchissement automatique...');

        // Préparer les éléments pour le rafraîchissement
        preparerRafraichissement();

        // Démarrer la mise à jour des indicateurs
        mettreAJourIndicateurs();

        // Rafraîchir complètement la page toutes les 30 secondes
        rafraichirPage();

        console.log('Rafraîchissement automatique activé');
    });

    // Debug: Vérifier les liens de consultation
        console.log('=== Debug: Liens de consultation ===');

        // Vérifier les liens dans les prochains rendez-vous
        const prochainsRDV = @json($prochainsRendezVous);
        console.log('Prochains rendez-vous:', prochainsRDV);

        prochainsRDV.forEach((rdv, index) => {
            const dateDebut = new Date(rdv.date_debut);
            const dateFin = new Date(rdv.date_fin || new Date(dateDebut.getTime() + 30 * 60000));
            const maintenant = new Date();
            const estTermine = maintenant > dateFin;
            const estActif = maintenant >= dateDebut && maintenant <= dateFin;
        const peutCommencer = maintenant >= new Date(dateDebut.getTime() - 5 * 60000) && maintenant <= dateFin;

            console.log(`RDV ${index + 1}:`, {
                id: rdv.id,
                medecin: rdv.medecin?.nom + ' ' + rdv.medecin?.prenom,
                date_debut: rdv.date_debut,
                date_fin: rdv.date_fin,
                lien_en_ligne: rdv.lien_en_ligne,
                has_lien: !!rdv.lien_en_ligne,
                est_termine: estTermine,
                est_actif: estActif,
                peut_commencer: peutCommencer,
                maintenant: maintenant.toISOString()
            });
        });

        // Vérifier les consultations en ligne
        const consultationsEnLigne = prochainsRDV.filter(rdv => {
            const dateFin = new Date(rdv.date_fin || new Date(new Date(rdv.date_debut).getTime() + 30 * 60000));
            const maintenant = new Date();
            return rdv.lien_en_ligne && maintenant <= dateFin;
        });

        console.log('Consultations avec lien (non terminées):', consultationsEnLigne.length);

        if (consultationsEnLigne.length === 0) {
            console.warn('⚠️ Aucun lien de consultation actif trouvé !');
        } else {
            console.log('✅ Liens de consultation actifs trouvés:', consultationsEnLigne.map(rdv => rdv.lien_en_ligne));
        }
</script>

@endsection
