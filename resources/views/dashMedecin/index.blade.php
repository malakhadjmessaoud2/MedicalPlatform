@extends('dashMedecin.layout')

@section('content')
<div class="p-4 sm:p-8 bg-[#e4e4e4] min-h-screen">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-8">
        <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-8 mb-4 sm:mb-0">
            <h1 class="text-2xl sm:text-4xl font-bold">
                TABLEAU DE B<span class="text-[#b9ff66]">O</span>RD
            </h1>
            <div class="flex gap-2 sm:gap-4">
                <button class="bg-[#b9ff66] hover:bg-[#a8eb5f] text-black rounded-full px-4 py-2.5 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouveau RDV
                </button>
                <button class="bg-red-500 text-white rounded-full px-4 py-2.5 flex items-center gap-2 hover:bg-red-600">
                    <span>🚨 Mode Urgence</span>
                </button>
            </div>
        </div>

        <!-- Notifications -->
        <div class="relative">
            <button class="p-2 hover:bg-gray-100 rounded-full relative">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">8</span>
            </button>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Quick Actions & Stats -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Accès Rapide -->
            <div class="bg-white p-6 rounded-[20px] shadow-sm">
                <h3 class="font-bold text-lg mb-4">Actions Rapides</h3>
                <div class="space-y-3">
                    <button onclick="window.location.href='/medecin/agenda'"
                            class="w-full text-left px-4 py-3 rounded-lg hover:bg-gray-50 flex items-center gap-3 transition-all">
                        <span class="p-2 bg-blue-100 rounded-full">📅</span>
                        <div>
                            <span class="font-medium">Mon Agenda</span>
                            <p class="text-sm text-gray-500">Gérer les rendez-vous</p>
                        </div>
                    </button>
                    <button onclick="window.location.href='/medecin/patients'"
                            class="w-full text-left px-4 py-3 rounded-lg hover:bg-gray-50 flex items-center gap-3 transition-all">
                        <span class="p-2 bg-purple-100 rounded-full">👥</span>
                        <div>
                            <span class="font-medium">Mes Patients</span>
                            <p class="text-sm text-gray-500">234 patients suivis</p>
                        </div>
                    </button>
                    <button onclick="window.location.href='/medecin/dossiers'"
                            class="w-full text-left px-4 py-3 rounded-lg hover:bg-gray-50 flex items-center gap-3 transition-all">
                        <span class="p-2 bg-green-100 rounded-full">📋</span>
                        <div>
                            <span class="font-medium">Dossiers Médicaux</span>
                            <p class="text-sm text-gray-500">Accès aux dossiers</p>
                        </div>
                    </button>
                </div>
            </div>

            <!-- Tâches Urgentes -->
            <div class="bg-white p-6 rounded-[20px] shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-lg">Tâches Prioritaires</h3>
                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-sm">3</span>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center gap-2 p-2 hover:bg-gray-50 rounded-lg cursor-pointer">
                        <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                        <span class="text-sm">5 ordonnances à renouveler</span>
                    </div>
                    <div class="flex items-center gap-2 p-2 hover:bg-gray-50 rounded-lg cursor-pointer">
                        <span class="w-2 h-2 bg-yellow-500 rounded-full"></span>
                        <span class="text-sm">3 résultats d'analyses à voir</span>
                    </div>
                    <div class="flex items-center gap-2 p-2 hover:bg-gray-50 rounded-lg cursor-pointer">
                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                        <span class="text-sm">2 dossiers à mettre à jour</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Center & Right Columns - Planning & Stats -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Today's Overview -->
            <div class="bg-white p-6 rounded-[20px] shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">Aujourd'hui</h3>
                    <div class="flex gap-2">
                        <span class="bg-[#b9ff66] text-black px-3 py-1 rounded-full text-sm">12 RDV</span>
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">8 Confirmés</span>
                    </div>
                </div>

                <!-- Prochain RDV -->
                <div class="mb-6 bg-blue-50 p-4 rounded-lg border border-blue-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="https://randomuser.me/api/portraits/women/68.jpg" class="w-12 h-12 rounded-full">
                            <div>
                                <h4 class="font-medium">Prochain: Marie Dupont</h4>
                                <p class="text-sm text-gray-600">Dans 15 minutes - Consultation de routine</p>
                            </div>
                        </div>
                        <button class="px-3 py-1 bg-white text-blue-600 rounded-lg text-sm hover:bg-blue-50">
                            Voir dossier
                        </button>
                    </div>
                </div>

                <!-- Timeline des RDV -->
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="text-sm font-medium w-20">10:30</div>
                        <div class="flex-1 bg-yellow-50 p-4 rounded-lg">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-medium">Jean Martin</h4>
                                    <p class="text-sm text-gray-600">Première consultation</p>
                                </div>
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">En attente</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="text-sm font-medium w-20">11:30</div>
                        <div class="flex-1 bg-green-50 p-4 rounded-lg">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-medium">Sophie Bernard</h4>
                                    <p class="text-sm text-gray-600">Suivi traitement</p>
                                </div>
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Confirmé</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 text-center">
                    <button class="text-[#b9ff66] hover:text-[#a8eb5f] font-medium">
                        Voir agenda complet →
                    </button>
                </div>
            </div>

            <!-- Statistics Summary -->
            <div class="bg-white p-6 rounded-[20px] shadow-sm">
                <h3 class="font-bold text-lg mb-4">Aperçu Statistique</h3>
                <div class="grid grid-cols-3 gap-4">
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-gray-600 text-sm">Ce mois</p>
                        <p class="text-2xl font-bold">180</p>
                        <p class="text-sm text-gray-500">Consultations</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-gray-600 text-sm">Nouveaux</p>
                        <p class="text-2xl font-bold text-green-500">+12</p>
                        <p class="text-sm text-gray-500">Patients</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-gray-600 text-sm">Taux</p>
                        <p class="text-2xl font-bold">95%</p>
                        <p class="text-sm text-gray-500">Satisfaction</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
