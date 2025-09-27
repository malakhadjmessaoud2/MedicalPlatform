@extends('dashPatient.layout')

@section('content')
<div class="p-8 bg-[#e4e4e4] min-h-screen">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-8">
        <div class="flex items-center gap-6">
            <div class="w-16 h-16 bg-[#b9ff66] rounded-2xl flex items-center justify-center">
                <svg class="w-8 h-8 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Bonjour, {{ Auth::user()->prenom }} !</h1>
                <p class="text-gray-600 mt-1">Voici un aperçu de votre santé</p>
            </div>
        </div>
        <a href="{{ route('patient.rendez-vous.create') }}" class="px-4 py-2.5 bg-[#b9ff66] hover:bg-[#a3e55a] text-gray-800 rounded-lg flex items-center gap-2 shadow-sm transition-all duration-200 hover:shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nouveau Rendez-vous
        </a>
    </div>

    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Rendez-vous confirmés -->
        <div class="bg-white rounded-[20px] p-6 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center gap-3 mb-4">
                <span class="p-2 bg-blue-100 rounded-full">📅</span>
                <h3 class="font-semibold">Rendez-vous confirmés</h3>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Total confirmés</span>
                    <span class="text-2xl font-bold text-[#b9ff66]">{{ $stats['rendez_vous_confirmes'] }}</span>
                </div>
            </div>
        </div>

        <!-- Rendez-vous payés -->
        <div class="bg-white rounded-[20px] p-6 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center gap-3 mb-4">
                <span class="p-2 bg-green-100 rounded-full">💳</span>
                <h3 class="font-semibold">Rendez-vous payés</h3>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Total payés</span>
                    <span class="text-2xl font-bold text-[#b9ff66]">{{ $stats['rendez_vous_payes'] }}</span>
                </div>
            </div>
        </div>

        <!-- Consultations terminées -->
        <div class="bg-white rounded-[20px] p-6 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center gap-3 mb-4">
                <span class="p-2 bg-orange-100 rounded-full">✅</span>
                <h3 class="font-semibold">Consultations terminées</h3>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Total terminées</span>
                    <span class="text-2xl font-bold text-[#b9ff66]">{{ $stats['consultations_terminees'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Prochains rendez-vous -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Prochains rendez-vous</h2>
            <a href="{{ route('patient.rendez-vous.create') }}" class="text-[#a8eb5f] font-medium flex items-center gap-2">
                Voir tout
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        @if($prochains_rendez_vous->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($prochains_rendez_vous as $rdv)
                <div class="bg-white rounded-[20px] p-6 shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                @php
                                    $photoUrl = $rdv->medecin->profile_photo_path
                                        ? asset('storage/' . $rdv->medecin->profile_photo_path)
                                        : 'https://ui-avatars.com/api/?name=' . urlencode($rdv->medecin->prenom . ' ' . $rdv->medecin->nom) . '&background=b9ff66&color=000000&size=48';
                                @endphp
                                <img src="{{ $photoUrl }}" alt="Dr. {{ $rdv->medecin->prenom }} {{ $rdv->medecin->nom }}"
                                     class="w-12 h-12 rounded-xl object-cover border-2 border-[#b9ff66] shadow-md">
                                <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 rounded-full border-2 border-white"></span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $rdv->medecin->prenom }} {{ $rdv->medecin->nom }}</h3>
                                <p class="text-sm text-gray-600">{{ $rdv->medecin->specialite }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-[#b9ff66]/10 text-[#b9ff66] rounded-full text-sm font-medium">
                            {{ ucfirst($rdv->statut) }}
                        </span>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $rdv->date_debut->format('d M Y') }}
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $rdv->date_debut->format('H:i') }} - {{ $rdv->date_fin->format('H:i') }}
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $rdv->medecin->adresse_cabinet ?? 'Cabinet médical' }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-[20px] p-8 text-center shadow-sm">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucun rendez-vous à venir</h3>
                <p class="text-gray-600 mb-4">Prenez rendez-vous avec un médecin pour commencer</p>
                <a href="{{ route('patient.rendez-vous.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-[#b9ff66] text-gray-800 rounded-xl hover:bg-[#a8eb5f] transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Prendre un RDV
                </a>
            </div>
        @endif
    </div>

    <!-- Médecins disponibles -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Médecins disponibles</h2>
            <a href="#" class="text-[#a8eb5f] font-medium flex items-center gap-2">
                Voir tout
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-[20px] p-6 shadow-sm mb-6">
            <div id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Filtre par spécialité -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Spécialité</label>
                        <select id="filterSpecialite" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#b9ff66] focus:border-transparent">
                            <option value="">Toutes les spécialités</option>
                            @foreach($specialites as $specialite)
                                <option value="{{ $specialite }}">{{ $specialite }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtre par prix minimum -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Prix min (€)</label>
                        <input type="number" id="filterPrixMin" placeholder="0" min="0" step="5"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#b9ff66] focus:border-transparent">
                    </div>

                    <!-- Filtre par prix maximum -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Prix max (€)</label>
                        <input type="number" id="filterPrixMax" placeholder="1000" min="0" step="5"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#b9ff66] focus:border-transparent">
                    </div>

                    <!-- Filtre par score minimum -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Score min</label>
                        <select id="filterScoreMin" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#b9ff66] focus:border-transparent">
                            <option value="">Tous les scores</option>
                            <option value="4.5">4.5+ ⭐⭐⭐⭐⭐</option>
                            <option value="4.0">4.0+ ⭐⭐⭐⭐</option>
                            <option value="3.5">3.5+ ⭐⭐⭐</option>
                            <option value="3.0">3.0+ ⭐⭐</option>
                        </select>
                    </div>
                </div>

                <!-- Tri et actions -->
                <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200">
                    <div class="flex items-center gap-4">
                        <label class="text-sm font-medium text-gray-700">Trier par:</label>
                        <select id="filterSort" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#b9ff66] focus:border-transparent">
                            <option value="score_desc">Score (décroissant)</option>
                            <option value="score_asc">Score (croissant)</option>
                            <option value="prix_asc">Prix (croissant)</option>
                            <option value="prix_desc">Prix (décroissant)</option>
                            <option value="nom_asc">Nom (A-Z)</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <button id="resetFilters" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all duration-200">
                            Réinitialiser
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Compteur de résultats -->
        <div id="resultCount" class="text-sm text-gray-600 mb-4">
            {{ $medecins->count() }} médecin{{ $medecins->count() > 1 ? 's' : '' }} trouvé{{ $medecins->count() > 1 ? 's' : '' }}
        </div>

        <div id="doctorsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($medecins as $medecin)
            <div class="bg-white rounded-[20px] p-6 shadow-sm hover:shadow-md transition-all doctor-card"
                 data-specialite="{{ $medecin->specialite }}"
                 data-prix="{{ $medecin->prixConsultation }}"
                 data-score="{{ $medecin->score }}"
                 data-nom="{{ $medecin->nom }}"
                 data-prenom="{{ $medecin->prenom }}">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            @php
                                $photoUrl = $medecin->profile_photo_path
                                    ? asset('storage/' . $medecin->profile_photo_path)
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($medecin->prenom . ' ' . $medecin->nom) . '&background=b9ff66&color=000000&size=48';
                            @endphp
                            <img src="{{ $photoUrl }}" alt="Dr. {{ $medecin->prenom }} {{ $medecin->nom }}"
                                 class="w-12 h-12 rounded-xl object-cover border-2 border-[#b9ff66] shadow-md">
                            <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 rounded-full border-2 border-white"></span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Dr. {{ $medecin->prenom }} {{ $medecin->nom }}</h3>
                            <p class="text-sm text-gray-600">{{ $medecin->specialite }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1">
                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.526 1.962l-2.905 2.14a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.905-2.14a1 1 0 00-1.175 0l-2.905 2.14c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.962.526-1.962h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <span class="text-sm font-medium text-gray-700">{{ number_format($medecin->score, 1) }}</span>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ $medecin->adresse_cabinet ?? 'Cabinet médical' }}
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                        {{ number_format($medecin->prixConsultation, 0) }} €/consultation
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $medecin->experience }} ans d'expérience
                    </div>
                </div>

                <div class="mt-4 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                        <span class="text-sm text-gray-500">Disponible</span>
                    </div>
                    <button class="px-4 py-2 bg-[#b9ff66] text-gray-800 rounded-lg hover:bg-[#a8eb5f] transition-all duration-200 text-sm font-medium">
                        Prendre RDV
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    body {
        font-family: 'Inter', sans-serif;
    }

    @layer utilities {
        @keyframes fade-in {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.6s ease-out;
        }
    }

    /* Animation pour les cartes qui apparaissent progressivement */
    .grid > div {
        opacity: 0;
        animation: fade-in 0.6s ease-out forwards;
    }

    .grid > div:nth-child(1) { animation-delay: 0.1s; }
    .grid > div:nth-child(2) { animation-delay: 0.2s; }
    .grid > div:nth-child(3) { animation-delay: 0.3s; }
    .grid > div:nth-child(4) { animation-delay: 0.4s; }
    .grid > div:nth-child(5) { animation-delay: 0.5s; }
    .grid > div:nth-child(6) { animation-delay: 0.6s; }

    /* Animation pour les cartes de médecins */
    .doctor-card {
        transition: all 0.3s ease;
    }

    .doctor-card.hidden {
        opacity: 0;
        transform: scale(0.95);
        pointer-events: none;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Éléments du DOM
    const filterSpecialite = document.getElementById('filterSpecialite');
    const filterPrixMin = document.getElementById('filterPrixMin');
    const filterPrixMax = document.getElementById('filterPrixMax');
    const filterScoreMin = document.getElementById('filterScoreMin');
    const filterSort = document.getElementById('filterSort');
    const resetFilters = document.getElementById('resetFilters');
    const resultCount = document.getElementById('resultCount');
    const doctorsGrid = document.getElementById('doctorsGrid');

    // Toutes les cartes de médecins
    const allDoctorCards = Array.from(document.querySelectorAll('.doctor-card'));

    // Fonction de filtrage
    function filterDoctors() {
        const specialite = filterSpecialite.value.toLowerCase();
        const prixMin = parseFloat(filterPrixMin.value) || 0;
        const prixMax = parseFloat(filterPrixMax.value) || Infinity;
        const scoreMin = parseFloat(filterScoreMin.value) || 0;

        let visibleCards = allDoctorCards.filter(card => {
            const cardSpecialite = card.dataset.specialite.toLowerCase();
            const cardPrix = parseFloat(card.dataset.prix);
            const cardScore = parseFloat(card.dataset.score);

            // Filtres
            const specialiteMatch = !specialite || cardSpecialite.includes(specialite);
            const prixMatch = cardPrix >= prixMin && cardPrix <= prixMax;
            const scoreMatch = cardScore >= scoreMin;

            return specialiteMatch && prixMatch && scoreMatch;
        });

        // Tri
        const sortBy = filterSort.value;
        visibleCards.sort((a, b) => {
            switch(sortBy) {
                case 'score_asc':
                    return parseFloat(a.dataset.score) - parseFloat(b.dataset.score);
                case 'prix_asc':
                    return parseFloat(a.dataset.prix) - parseFloat(b.dataset.prix);
                case 'prix_desc':
                    return parseFloat(b.dataset.prix) - parseFloat(a.dataset.prix);
                case 'nom_asc':
                    return (a.dataset.prenom + ' ' + a.dataset.nom).localeCompare(b.dataset.prenom + ' ' + b.dataset.nom);
                default: // score_desc
                    return parseFloat(b.dataset.score) - parseFloat(a.dataset.score);
            }
        });

        // Masquer toutes les cartes avec animation
        allDoctorCards.forEach(card => {
            card.style.display = 'none';
            card.classList.remove('animate-fade-in');
        });

        // Afficher les cartes filtrées avec animation
        setTimeout(() => {
            visibleCards.forEach((card, index) => {
                card.style.display = 'block';
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('animate-fade-in');
            });

            // Mettre à jour le compteur
            const count = visibleCards.length;
            resultCount.textContent = `${count} médecin${count > 1 ? 's' : ''} trouvé${count > 1 ? 's' : ''}`;

            // Afficher message si aucun résultat
            if (count === 0) {
                showNoResults();
            } else {
                hideNoResults();
            }
        }, 150);
    }

    // Fonction pour afficher le message "aucun résultat"
    function showNoResults() {
        let noResultsDiv = document.getElementById('noResults');
        if (!noResultsDiv) {
            noResultsDiv = document.createElement('div');
            noResultsDiv.id = 'noResults';
            noResultsDiv.className = 'bg-white rounded-[20px] p-8 text-center shadow-sm col-span-full';
            noResultsDiv.innerHTML = `
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-1.009-5.824-2.709M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucun médecin trouvé</h3>
                <p class="text-gray-600 mb-4">Essayez de modifier vos critères de recherche</p>
                <button onclick="resetFilters.click()" class="px-4 py-2 bg-[#b9ff66] text-gray-800 rounded-lg hover:bg-[#a8eb5f] transition-all duration-200">
                    Réinitialiser les filtres
                </button>
            `;
            doctorsGrid.appendChild(noResultsDiv);
        }
    }

    // Fonction pour masquer le message "aucun résultat"
    function hideNoResults() {
        const noResultsDiv = document.getElementById('noResults');
        if (noResultsDiv) {
            noResultsDiv.remove();
        }
    }

    // Fonction de réinitialisation
    function resetAllFilters() {
        filterSpecialite.value = '';
        filterPrixMin.value = '';
        filterPrixMax.value = '';
        filterScoreMin.value = '';
        filterSort.value = 'score_desc';
        filterDoctors();
    }

    // Validation des prix
    function validatePriceRange() {
        const prixMin = parseFloat(filterPrixMin.value) || 0;
        const prixMax = parseFloat(filterPrixMax.value) || Infinity;

        if (prixMin > prixMax && filterPrixMax.value) {
            filterPrixMax.setCustomValidity('Le prix maximum doit être supérieur au prix minimum');
        } else {
            filterPrixMax.setCustomValidity('');
        }
    }

    // Event listeners
    filterSpecialite.addEventListener('change', filterDoctors);
    filterPrixMin.addEventListener('input', () => {
        validatePriceRange();
        filterDoctors();
    });
    filterPrixMax.addEventListener('input', () => {
        validatePriceRange();
        filterDoctors();
    });
    filterScoreMin.addEventListener('change', filterDoctors);
    filterSort.addEventListener('change', filterDoctors);
    resetFilters.addEventListener('click', resetAllFilters);

    // Animation initiale des cartes
    allDoctorCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
        card.classList.add('animate-fade-in');
    });
});
</script>
@endsection
