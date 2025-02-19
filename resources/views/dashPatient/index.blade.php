@extends('dashPatient.layout')

@section('content')
<div class="p-8 bg-[#e4e4e4] min-h-screen">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-12">
        <div class="flex items-center gap-8">
            <h1 class="text-4xl font-bold">
                PHAR<span class="text-[#b9ff66]">MA</span>CARE
            </h1>

        </div>

        <!-- Statistiques -->
        <div class="flex gap-12">
            <div class="text-center relative">
                <span class="text-4xl font-bold">12</span>
                <span class="absolute -top-1 -right-4 text-xs bg-[#b9ff66] px-1.5 rounded-full">+2</span>
                <div class="text-gray-500 text-sm mt-1">Consultations</div>
            </div>
            <div class="text-center relative">
                <span class="text-4xl font-bold">5</span>
                <span class="absolute -top-1 -right-4 text-xs bg-red-200 px-1.5 rounded-full">+1</span>
                <div class="text-gray-500 text-sm mt-1">Ordonnances</div>
            </div>
            <div class="text-center relative">
                <span class="text-4xl font-bold">3</span>
                <span class="absolute -top-1 -right-4 text-xs bg-orange-200 px-1.5 rounded-full">+1</span>
                <div class="text-gray-500 text-sm mt-1">Dons</div>
            </div>
        </div>
    </div>
<!-- Section Dossier Médical -->
<div class="mb-12">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <h2 class="text-2xl font-bold">Mon Dossier Médical</h2>
            <span class="bg-white px-3 py-1 rounded-full text-sm">5 documents</span>
        </div>
        <div class="flex gap-2">
            <button class="p-2 hover:bg-white/10 rounded-full transition-all">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Filtres -->
    <div class="flex gap-3 mb-6">
        <button class="px-4 py-2 bg-white rounded-full hover:bg-white/90 transition-all">Tous</button>
        <button class="px-4 py-2 rounded-full flex items-center gap-2 hover:bg-white/10 transition-all">
            <span class="text-red-500">📄</span>
            <span>Ordonnances</span>
        </button>
        <button class="px-4 py-2 rounded-full hover:bg-white/10 transition-all">Consultations</button>
        <button class="px-4 py-2 rounded-full hover:bg-white/10 transition-all">Analyses</button>
    </div>

    <!-- Grid des Dossiers Médicaux -->
    <div class="grid grid-cols-3 gap-5">
        <!-- Carte Ordonnance Améliorée -->
        <div class="bg-[#FFFFFF] rounded-[30px] p-6 shadow-md hover:shadow-lg transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-[1.02] cursor-pointer group border border-[#D2D2D2]/20">
            <!-- En-tête avec statut -->
            <div class="flex justify-between items-start mb-4">
                <div class="flex gap-4">
                    <div class="relative">
                        <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="Dr. Martin" class="w-12 h-12 rounded-full object-cover ">
                        <span class="absolute -bottom-1 -right-1 w-4 h-4  rounded-full "></span>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-xl font-bold text-[#000000]  transition-colors">Dr. Martin</h3>
                        <div class="flex items-center gap-2">
                            <span class="text-[#D2D2D2] text-sm">Cardiologue</span>
                            <span class="w-1.5 h-1.5 rounded-full"></span>
                            <span class="text-[#D2D2D2] text-sm">Hôpital Central</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 bg-[#B9FF66]/10 text-[#B9FF66] rounded-full text-sm font-medium">Nouveau</span>
                    <button class="p-2 rounded-full hover:bg-gray-100 transition-colors group-hover:bg-blue-50">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500" viewBox="0 0 24 24" fill="none">
                            <path d="M7 17l9.2-9.2M17 17V8h-9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Informations principales -->
            <div class="mt-4 p-4 bg-[#000000]/5 rounded-2xl space-y-4">
                <div class="flex items-center justify-between">
                    <p class="text-[#000000] font-medium">Type de consultation</p>
                    <span class="flex items-center gap-2">
                        <span class="text-[#F04949]">📄</span>
                        <span class="text-[#000000] font-medium">Ordonnance</span>
                    </span>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-gray-600">Date:</span>
                    </div>
                    <span class="px-3 py-1 bg-gray-200 rounded-full text-sm font-medium">15 Mars 2024</span>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-gray-600">Heure:</span>
                    </div>
                    <span class="px-3 py-1 bg-gray-200 rounded-full text-sm font-medium">14:30</span>
                </div>
            </div>

            <!-- Détails supplémentaires -->
            <div class="mt-4 space-y-3">
                <div class="flex items-center gap-3">
                    <span class="px-2 py-1 bg-[#66FFED]/10 text-[#66FFED] rounded-lg text-sm">Traitement en cours</span>
                    <span class="px-2 py-1 bg-[#B9FF66]/10 text-[#B9FF66] rounded-lg text-sm">Suivi régulier</span>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="text-gray-500">3 médicaments prescrits</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-gray-500">Prochain RDV: 15 Avril</span>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="mt-6 flex items-center justify-between">
                <div class="flex -space-x-2">
                    <img src="path/to/medicine1.jpg" alt="Médicament 1" class="w-8 h-8 rounded-full border-2 border-white">
                    <img src="path/to/medicine2.jpg" alt="Médicament 2" class="w-8 h-8 rounded-full border-2 border-white">
                    <img src="path/to/medicine3.jpg" alt="Médicament 3" class="w-8 h-8 rounded-full border-2 border-white">
                    <span class="w-8 h-8 rounded-full bg-gray-100 border-2 border-white flex items-center justify-center text-sm text-gray-500">+2</span>
                </div>
                <div class="flex items-center gap-2">
                    <button class="p-2 hover:bg-[#66FFED]/10 rounded-full transition-colors">
                        <svg class="w-5 h-5 text-[#D2D2D2] hover:text-[#66FFED]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                    </button>
                    <button class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Carte Consultation -->
        <div class="bg-[#FFFFFF] rounded-[30px] p-6 shadow-md hover:shadow-lg transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-[1.02] cursor-pointer animate-fade-in">
            <!-- En-tête avec photo et statut -->
            <div class="flex justify-between items-start">
                <div class="flex gap-4">
                    <div class="relative">
                        <img src="https://randomuser.me/api/portraits/women/3.jpg" alt="Dr. Emma" class="w-12 h-12 rounded-full object-cover ring-2 ring-[#4A90E2]">
                        <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 rounded-full border-2 border-white"></span>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-xl font-bold">Dr. Emma</h3>
                        <div class="flex items-center gap-2">
                            <p class="text-gray-500 text-sm">Généraliste</p>
                            <span class="w-1.5 h-1.5 bg-gray-300 rounded-full"></span>
                            <span class="text-gray-500 text-sm">Cabinet Médical Central</span>
                        </div>
                    </div>
                </div>
                <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-sm">En cours</span>
            </div>

            <!-- Informations principales -->
            <div class="mt-6 p-4 bg-gray-50 rounded-2xl space-y-4">
                <div class="flex items-center justify-between">
                    <p class="text-gray-700 font-medium">Type de consultation</p>
                    <span class="flex items-center gap-2">
                        <span class="text-[#4A90E2]">👥</span>
                        <span class="text-gray-600">Consultation de suivi</span>
                    </span>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#4A90E2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-gray-600">Date:</span>
                    </div>
                    <span class="px-3 py-1 bg-gray-200 rounded-full text-sm">Aujourd'hui</span>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#4A90E2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-gray-600">Heure:</span>
                    </div>
                    <span class="px-3 py-1 bg-gray-200 rounded-full text-sm">16:00</span>
                </div>
            </div>

            <!-- Détails supplémentaires -->
            <div class="mt-4 space-y-3">
                <div class="flex flex-wrap gap-2">
                    <span class="px-3 py-1 bg-[#4A90E2]/10 text-[#4A90E2] rounded-full text-sm">Suivi régulier</span>
                    <span class="px-3 py-1 bg-purple-100 rounded-full text-sm">Présentiel</span>
                    <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-sm">Mutuelle acceptée</span>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="text-gray-500">Motif: Contrôle tension artérielle</span>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="mt-6 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                    <span class="text-sm text-gray-500">Durée estimée: 30 min</span>
                </div>
                <div class="flex items-center gap-2">
                    <button class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                        <svg class="w-5 h-5 text-[#4A90E2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </button>
                    <button class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Carte Analyse -->
        <div class="bg-[#FFFFFF] rounded-[30px] p-6 shadow-md hover:shadow-lg transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-[1.02] cursor-pointer group border border-[#D2D2D2]/20">
            <!-- En-tête avec statut -->
            <div class="flex justify-between items-start mb-4">
                <div class="flex gap-4">
                    <div class="relative">
                        <div class="w-12 h-12 rounded-full bg-[#66FFED]/10 flex items-center justify-center group-hover:bg-[#66FFED]/20 transition-colors">
                            <span class="text-2xl">🔬</span>
                        </div>
                        <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-[#B9FF66] rounded-full border-2 border-white"></span>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-xl font-bold  transition-colors">Laboratoire Central</h3>
                        <div class="flex items-center gap-2">
                            <span class="text-gray-500 text-sm">Analyse Sanguine</span>
                            <span class="w-1.5 h-1.5 bg-gray-300 rounded-full"></span>
                            <span class="text-gray-500 text-sm">Ref: #AN-2024-123</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-sm font-medium">Terminé</span>
                </div>
            </div>

            <!-- Informations principales -->
            <div class="mt-4 p-4 bg-gray-50 rounded-2xl space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span class="text-gray-700 font-medium">Type d'analyse:</span>
                    </div>
                    <span class="text-gray-600">Bilan sanguin complet</span>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-gray-700 font-medium">Date du prélèvement:</span>
                    </div>
                    <span class="px-3 py-1 bg-gray-200 rounded-full text-sm font-medium">12 Mars 2024</span>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-gray-700 font-medium">Heure:</span>
                    </div>
                    <span class="px-3 py-1 bg-gray-200 rounded-full text-sm font-medium">09:30</span>
                </div>
            </div>

            <!-- Résultats -->
            <div class="mt-4 space-y-3">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-medium text-gray-700">Paramètres analysés:</h4>
                    <span class="text-sm text-purple-600">6 paramètres</span>
                </div>

                <div class="grid grid-cols-3 gap-2">
                    <div class="px-3 py-2 bg-[#66FFED]/10 rounded-lg hover:bg-[#66FFED]/20 transition-colors">
                        <span class="text-xs text-[#000000] font-medium">Glycémie</span>
                        <div class="flex items-center gap-1 mt-1">
                            <span class="text-sm font-semibold">5.2</span>
                            <span class="text-xs text-[#D2D2D2]">mmol/L</span>
                        </div>
                    </div>
                    <div class="px-3 py-2 bg-[#66FFED]/10 rounded-lg hover:bg-[#66FFED]/20 transition-colors">
                        <span class="text-xs text-[#000000] font-medium">Cholestérol</span>
                        <div class="flex items-center gap-1 mt-1">
                            <span class="text-sm font-semibold">4.8</span>
                            <span class="text-xs text-[#D2D2D2]">mmol/L</span>
                        </div>
                    </div>
                    <div class="px-3 py-2 bg-[#66FFED]/10 rounded-lg hover:bg-[#66FFED]/20 transition-colors">
                        <span class="text-xs text-[#000000] font-medium">Fer</span>
                        <div class="flex items-center gap-1 mt-1">
                            <span class="text-sm font-semibold">15.2</span>
                            <span class="text-xs text-[#D2D2D2]">µmol/L</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="mt-6 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500">Dr. Sophie Martin</span>
                    <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                    <span class="text-sm text-gray-500">Biologiste</span>
                </div>
                <div class="flex items-center gap-2">
                    <button class="p-2 hover:bg-purple-50 rounded-full transition-colors group-hover:text-purple-600" title="Télécharger les résultats">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                    </button>
                    <button class="p-2 hover:bg-purple-50 rounded-full transition-colors group-hover:text-purple-600" title="Voir les détails">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- Section Demandes de Don de Médicaments -->
    <div class="mb-12">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-bold">Mes Demandes de Don</h2>
                <span class="bg-white px-3 py-1 rounded-full text-sm">3 en cours</span>
            </div>
            <div class="flex gap-2">
                <button class="p-2 hover:bg-white/10 rounded-full transition-all">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Filtres -->
        <div class="flex gap-3 mb-6">
            <button class="px-4 py-2 bg-white rounded-full hover:bg-white/90 transition-all">Toutes</button>
            <button class="px-4 py-2 rounded-full flex items-center gap-2 hover:bg-white/10 transition-all">
                <span class="text-red-500">🏥</span>
                <span>Urgentes</span>
            </button>
            <button class="px-4 py-2 rounded-full hover:bg-white/10 transition-all">En cours</button>
            <button class="px-4 py-2 rounded-full hover:bg-white/10 transition-all">Terminées</button>
        </div>

        <!-- Grid des Demandes de Don -->
        <div class="grid grid-cols-3 gap-5">
            <!-- Demande Urgente -->
            <div class="bg-[#FF6B6B] rounded-[30px] p-6 transform transition-all duration-300 ease-in-out hover:scale-[1.02] hover:rotate-1 hover:shadow-xl cursor-pointer group border border-[#F04949]/20">
                <div class="flex justify-between items-start">
                    <div class="flex gap-3">
                        <div>
                            <h3 class="font-semibold text-lg">Paracétamol</h3>
                            <p class="text-sm text-gray-700">Demande urgente</p>
                        </div>
                    </div>
                    <button class="p-3 bg-black rounded-full hover:bg-black/90 transition-all">
                        <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none">
                            <path d="M12 4v16m8-8H4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>

                <div class="mt-6">
                    <div class="flex items-center gap-2">
                        <span class="text-xl font-semibold">Status: non traité </span>
                    </div>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="text-sm text-gray-700">Date limite: 20.03.2025</span>
                    </div>
                </div>
            </div>

            <!-- Demande En Cours -->
            <div class="bg-[#b9ff66] rounded-[30px] p-6 transform transition-all duration-300 ease-in-out hover:scale-[1.02] hover:-rotate-1 hover:shadow-xl cursor-pointer group border border-[#B9FF66]/20">
                <div class="flex justify-between items-start">
                    <div class="flex gap-3">
                        <div>
                            <h3 class="font-semibold text-lg">Insuline</h3>
                            <p class="text-sm text-gray-700">Demande en cours</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 bg-[#B9FF66]/10 text-[#B9FF66] rounded-full text-sm">Complété</span>
                </div>

                <div class="mt-6">
                    <div class="flex items-center gap-2">
                        <span class="text-xl font-semibold">Status: En attente</span>
                    </div>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="text-sm text-gray-700">Date limite: 20.03.2024</span>
                    </div>
                </div>
            </div>

            <!-- Demande Terminée -->
            <div class="bg-[#E2E8F0] rounded-[30px] p-6 transform transition-all duration-300 ease-in-out hover:scale-[1.02] hover:rotate-1 hover:shadow-xl cursor-pointer group border border-[#F04949]/20">
                <div class="flex justify-between items-start">
                    <div class="flex gap-3">
                        <div>
                            <h3 class="font-semibold text-lg">Insuline</h3>
                            <p class="text-sm text-gray-700">Demande terminée</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 bg-[#F04949]/10 text-[#F04949] rounded-full text-sm">Complété</span>
                </div>

                <div class="mt-6">
                    <div class="flex items-center gap-2">
                        <span class="text-xl font-semibold">Don reçu</span>
                    </div>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="text-sm text-gray-700">Reçu le: 15.03.2024</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Commandes de Médicaments -->
    <div class="mb-12">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-bold">Mes Commandes</h2>
                <span class="bg-white px-3 py-1 rounded-full text-sm">2 en cours</span>
            </div>
            <div class="flex gap-2">
                <button class="p-2 hover:bg-white/10 rounded-full transition-all">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Filtres -->
        <div class="flex gap-3 mb-6">
            <button class="px-4 py-2 bg-white rounded-full hover:bg-white/90 transition-all">Toutes</button>
            <button class="px-4 py-2 rounded-full flex items-center gap-2 hover:bg-white/10 transition-all">
                <span class="text-red-500">🏥</span>
                <span>Urgentes</span>
            </button>
            <button class="px-4 py-2 rounded-full hover:bg-white/10 transition-all">En cours</button>
            <button class="px-4 py-2 rounded-full hover:bg-white/10 transition-all">Terminées</button>
        </div>

        <!-- Grid des Commandes -->
        <div class="grid grid-cols-3 gap-5">
            <!-- Commande En Cours -->
            <div class="bg-white rounded-[30px] p-6 transform transition-all duration-300 ease-in-out hover:-translate-y-2 hover:shadow-2xl cursor-pointer group border border-[#D2D2D2]/20">
                <div class="flex justify-between items-start group-hover:scale-[1.02] transition-transform duration-300">
                    <div class="flex gap-3">
                        <div>
                            <h3 class="font-semibold text-lg">Commande #123</h3>
                            <p class="text-sm text-gray-500">Pharmacie Centrale</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-sm">Payé</span>
                </div>

                <div class="mt-6">
                    <h4 class="text-xl font-semibold">Total: 125.00 €</h4>
                    <p class="text-sm text-gray-500 mt-2">3 médicaments</p>
                </div>

                <div class="mt-6 flex items-center gap-2">
                    <span class="text-sm text-gray-500">Livraison prévue: 18.03.2024</span>
                    <button class="ml-auto p-2 hover:bg-gray-100 rounded-full transition-all">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Commande En Préparation -->
            <div class="bg-white rounded-[30px] p-6 transform transition-all duration-300 ease-in-out hover:-translate-y-2 hover:shadow-2xl cursor-pointer group border border-[#D2D2D2]/20">
                <div class="flex justify-between items-start group-hover:scale-[1.02] transition-transform duration-300">
                    <div class="flex gap-3">
                        <div>
                            <h3 class="font-semibold text-lg">Commande #124</h3>
                            <p class="text-sm text-gray-500">Pharmacie du Sud</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-600 rounded-full text-sm">En préparation</span>
                </div>

                <div class="mt-6">
                    <h4 class="text-xl font-semibold">Total: 75.50 €</h4>
                    <p class="text-sm text-gray-500 mt-2">2 médicaments</p>
                </div>

                <div class="mt-6 flex items-center gap-2">
                    <span class="text-sm text-gray-500">Préparation en cours</span>
                </div>
            </div>

            <!-- Commande Livrée -->
            <div class="bg-white rounded-[30px] p-6 transform transition-all duration-300 ease-in-out hover:-translate-y-2 hover:shadow-2xl cursor-pointer group border border-[#D2D2D2]/20">
                <div class="flex justify-between items-start group-hover:scale-[1.02] transition-transform duration-300">
                    <div class="flex gap-3">
                        <div>
                            <h3 class="font-semibold text-lg">Commande #122</h3>
                            <p class="text-sm text-gray-500">Pharmacie de l'Est</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-sm">Livrée</span>
                </div>

                <div class="mt-6">
                    <h4 class="text-xl font-semibold">Total: 95.20 €</h4>
                    <p class="text-sm text-gray-500 mt-2">4 médicaments</p>
                </div>

                <div class="mt-6 flex items-center gap-2">
                    <span class="text-sm text-gray-500">Livrée le: 16.03.2024</span>
                    <button class="ml-auto p-2 hover:bg-gray-100 rounded-full transition-all">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                            <path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Panel -->
    <div x-data="{ open: false }"
         class="fixed bottom-0 right-8 w-96">

        <!-- Summary Button - État fermé -->
        <button @click="open = !open"
                x-show="!open"
                class="bg-black text-white w-full rounded-t-[30px] px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                          stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <span class="font-medium text-[15px]">Summary</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-400">3:15</span>
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                    <path d="M19 9l-7 7-7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>
        </button>

        <!-- Summary Panel - État ouvert -->
        <div x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-y-full"
             x-transition:enter-end="translate-y-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="translate-y-0"
             x-transition:leave-end="translate-y-full"
             class="bg-black text-white rounded-t-[30px] absolute bottom-0 w-full"
             style="box-shadow: 0 -10px 30px rgba(0,0,0,0.2);">

            <!-- Header -->
            <div class="px-6 py-4 flex items-center justify-between border-b border-white/10">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                              stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <span class="font-medium text-[15px]">Summary</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-400">3:15</span>
                    <button @click="open = false">
                        <svg class="w-5 h-5 rotate-180" viewBox="0 0 24 24" fill="none">
                            <path d="M19 9l-7 7-7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6 space-y-8">
                <!-- Documents Section -->
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-[15px] font-medium">Documents:</h3>
                        <button class="hover:opacity-75 transition-opacity">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                                <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                                      stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </button>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white/10 rounded-lg aspect-[3/4]"></div>
                        <div class="bg-white/10 rounded-lg aspect-[3/4]"></div>
                    </div>
                </div>

                <!-- Goal Section -->
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-[15px] font-medium">Goal:</h3>
                        <button class="hover:opacity-75 transition-opacity">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                      stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </button>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Reduce the number of security incidents by 50%<br>
                        This goal is quantitative and measurable
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700&display=swap');

    body {
        font-family: 'Urbanist', sans-serif;
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
</style>
@endsection
