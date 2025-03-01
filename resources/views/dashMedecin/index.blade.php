@extends('dashMedecin.layout')

@section('content')
<div class="p-4 sm:p-8 bg-[#e4e4e4] min-h-screen">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-8 sm:mb-12">
        <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-8 mb-4 sm:mb-0">
            <h1 class="text-2xl sm:text-4xl font-bold">
                TABLEAU DE B<span class="text-[#b9ff66]">O</span>RD
            </h1>
            <div class="flex gap-2 sm:gap-4">
                <button class="bg-black text-white rounded-full px-3 sm:px-4 py-1.5 sm:py-2.5 flex items-center gap-2 hover:bg-black/90 transition-all text-sm sm:text-base">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                        <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <span>Nouveau Patient</span>
                </button>
                <button class="bg-red-500 text-white rounded-full px-3 sm:px-4 py-1.5 sm:py-2.5 flex items-center gap-2 hover:bg-red-600 transition-all text-sm sm:text-base">
                    <span>🚨 Mode Urgence</span>
                </button>
            </div>
        </div>

        <!-- Notifications -->
        <div class="relative">
            <button class="p-2 hover:bg-gray-100 rounded-full relative">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">8</span>
            </button>
        </div>
    </div>

    <!-- Main Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-12">
        <!-- Left Column - Statistics and Quick Actions -->
        <div class="lg:col-span-8 space-y-6">
            <!-- Detailed Statistics -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                <!-- Consultations -->
                <div class="bg-white p-4 sm:p-6 rounded-[20px] shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="p-2 bg-blue-100 rounded-full">👥</span>
                        <h3 class="font-semibold">Consultations</h3>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Aujourd'hui</span>
                            <span class="text-2xl font-bold">12</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Cette semaine</span>
                            <span class="text-xl font-semibold">45</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Ce mois</span>
                            <span class="text-xl font-semibold">180</span>
                        </div>
                    </div>
                </div>

                <!-- Patients -->
                <div class="bg-white p-4 sm:p-6 rounded-[20px] shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="p-2 bg-green-100 rounded-full">🏥</span>
                        <h3 class="font-semibold">Patients</h3>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Total suivis</span>
                            <span class="text-2xl font-bold">234</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Nouveaux (mois)</span>
                            <span class="text-lg font-semibold text-green-500">+12</span>
                        </div>
                        <button class="w-full bg-[#b9ff66] text-sm py-2 rounded-full mt-2 hover:bg-[#a8eb5f] transition-all">
                            Voir tous les patients
                        </button>
                    </div>
                </div>

                <!-- Pending Appointments -->
                <div class="bg-white p-4 sm:p-6 rounded-[20px] shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="p-2 bg-orange-100 rounded-full">📅</span>
                        <h3 class="font-semibold">RDV en attente</h3>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">À confirmer</span>
                            <span class="text-2xl font-bold text-orange-500">8</span>
                        </div>
                        <button class="w-full bg-[#b9ff66] text-sm py-2 rounded-full mt-2 hover:bg-[#a8eb5f] transition-all">
                            Gérer les demandes
                        </button>
                    </div>
                </div>
            </div>

            <!-- Today's Patients -->
            <div class="bg-white rounded-[25px] p-4 sm:p-8">
                <!-- Header -->
                <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                    <h2 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-0">Patients du Jour</h2>
                    <div class="flex items-center gap-4">
                        <span class="bg-[#b9ff66] px-3 sm:px-4 py-1.5 sm:py-2 rounded-full text-sm font-medium">12 Patients</span>
                        <button class="p-2 hover:bg-gray-50 rounded-full transition-all">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Patients List -->
                <div class="space-y-4 sm:space-y-6">
                    <!-- Patient Card 1 -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <img src="https://randomuser.me/api/portraits/women/68.jpg" class="w-10 sm:w-14 h-10 sm:h-14 rounded-full">
                            <div>
                                <h4 class="text-lg font-medium">Marie Dupont</h4>
                                <p class="text-sm text-gray-600">09:30 - Consultation</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="px-3 sm:px-4 py-1.5 sm:py-2 bg-blue-50 text-blue-700 rounded-full text-sm">En cours</span>
                            <button class="p-2 hover:bg-gray-50 rounded-full transition-all">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Patient Card 2 -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" class="w-10 sm:w-14 h-10 sm:h-14 rounded-full">
                            <div>
                                <h4 class="text-lg font-medium">Thomas Bernard</h4>
                                <p class="text-sm text-gray-600">10:00 - Suivi traitement</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="px-3 sm:px-4 py-1.5 sm:py-2 bg-yellow-50 text-yellow-700 rounded-full text-sm">En attente</span>
                            <button class="p-2 hover:bg-gray-50 rounded-full transition-all">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Patient Card 3 -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <img src="https://randomuser.me/api/portraits/women/45.jpg" class="w-10 sm:w-14 h-10 sm:h-14 rounded-full">
                            <div>
                                <h4 class="text-lg font-medium">Sophie Martin</h4>
                                <p class="text-sm text-gray-600">10:15 - Première consultation</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="px-3 sm:px-4 py-1.5 sm:py-2 bg-red-50 text-red-700 rounded-full text-sm">Retard</span>
                            <button class="p-2 hover:bg-gray-50 rounded-full transition-all">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Patient Card 4 -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <img src="https://randomuser.me/api/portraits/men/22.jpg" class="w-10 sm:w-14 h-10 sm:h-14 rounded-full">
                            <div>
                                <h4 class="text-lg font-medium">Lucas Petit</h4>
                                <p class="text-sm text-gray-600">11:00 - Contrôle routine</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="px-3 sm:px-4 py-1.5 sm:py-2 bg-green-50 text-green-700 rounded-full text-sm">Confirmé</span>
                            <button class="p-2 hover:bg-gray-50 rounded-full transition-all">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="flex flex-col sm:flex-row items-center justify-between mt-6 sm:mt-8">
                    <span class="text-sm text-gray-600 mb-2 sm:mb-0">
                        Affichage de 1 à 4 sur 12 patients
                    </span>
                    <div class="flex items-center gap-2">
                        <button class="pagination-btn" disabled>
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <button class="pagination-btn active">1</button>
                        <button class="pagination-btn">2</button>
                        <button class="pagination-btn">3</button>
                        <button class="pagination-btn">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Alerts and Notifications -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Alerts -->
            <div class="bg-white p-4 sm:p-6 rounded-[20px] shadow-sm">
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <h2 class="text-xl font-bold">Alertes</h2>
                    <span class="bg-red-100 text-red-800 px-2 sm:px-3 py-1 rounded-full text-sm">3 nouvelles</span>
                </div>
                <div class="space-y-3 sm:space-y-4">
                    <div class="flex items-center gap-3 p-2 sm:p-3 bg-red-50 rounded-lg">
                        <span class="p-2 bg-red-100 rounded-full text-red-600">⚠️</span>
                        <div>
                            <h4 class="font-medium">5 prescriptions à renouveler</h4>
                            <p class="text-sm text-gray-500">Échéance proche</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-2 sm:p-3 bg-blue-50 rounded-lg">
                        <span class="p-2 bg-blue-100 rounded-full text-blue-600">📊</span>
                        <div>
                            <h4 class="font-medium">3 résultats d'analyses reçus</h4>
                            <p class="text-sm text-gray-500">À examiner</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-2 sm:p-3 bg-yellow-50 rounded-lg">
                        <span class="p-2 bg-yellow-100 rounded-full text-yellow-600">⏰</span>
                        <div>
                            <h4 class="font-medium">2 rappels patients</h4>
                            <p class="text-sm text-gray-500">Suivi post-consultation</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Appointments -->
            <div class="bg-white p-4 sm:p-6 rounded-[20px] shadow-sm">
                <h2 class="text-xl font-bold mb-4 sm:mb-6">Prochains Rendez-vous</h2>
                <div class="space-y-3 sm:space-y-4">
                    <div class="p-3 sm:p-4 bg-[#b9ff66] rounded-lg">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-sm font-medium">14:30</span>
                            <span class="px-2 py-1 bg-white/50 rounded-full text-xs">Consultation</span>
                        </div>
                        <h4 class="font-medium">Pierre Martin</h4>
                        <p class="text-sm text-gray-700">Suivi traitement</p>
                    </div>
                    <!-- Repeat for other appointments -->
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-12">
        <div class="bg-white p-4 sm:p-6 rounded-[20px] hover:shadow-lg transition-shadow cursor-pointer">
            <div class="flex items-center gap-4 mb-4">
                <span class="p-3 bg-blue-100 rounded-full">📋</span>
                <h3 class="font-bold">Ordonnances</h3>
            </div>
            <p class="text-sm text-gray-600">Gérer et créer des ordonnances</p>
        </div>
        <div class="bg-white p-6 rounded-[20px] hover:shadow-lg transition-shadow cursor-pointer">
            <div class="flex items-center gap-4 mb-4">
                <span class="p-3 bg-green-100 rounded-full">💊</span>
                <h3 class="font-bold">Prescriptions</h3>
            </div>
            <p class="text-sm text-gray-600">Prescrire des médicaments</p>
        </div>
        <div class="bg-white p-6 rounded-[20px] hover:shadow-lg transition-shadow cursor-pointer">
            <div class="flex items-center gap-4 mb-4">
                <span class="p-3 bg-yellow-100 rounded-full">📊</span>
                <h3 class="font-bold">Analyses</h3>
            </div>
            <p class="text-sm text-gray-600">Résultats et demandes d'analyses</p>
        </div>
        <div class="bg-white p-6 rounded-[20px] hover:shadow-lg transition-shadow cursor-pointer">
            <div class="flex items-center gap-4 mb-4">
                <span class="p-3 bg-purple-100 rounded-full">📅</span>
                <h3 class="font-bold">Planning</h3>
            </div>
            <p class="text-sm text-gray-600">Gérer votre agenda</p>
        </div>
    </div>

    <!-- Upcoming Appointments Section -->
    <div class="mb-12">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-bold">Rendez-vous à venir</h2>
                <span class="bg-white px-3 py-1 rounded-full text-sm">5 RDV</span>
            </div>
        </div>

        <!-- Appointments Grid -->
        <div class="grid gap-4">
            <div class="bg-[#b9ff66] rounded-[30px] p-6">
                <div class="flex justify-between items-start">
                    <div class="flex gap-3">
                        <img src="https://randomuser.me/api/portraits/men/3.jpg" alt="" class="w-12 h-12 rounded-full">
                        <div>
                            <h3 class="font-semibold text-lg">Pierre Martin</h3>
                            <p class="text-sm text-gray-700">Consultation de suivi</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <div class="flex items-center gap-2">
                        <span class="text-xl font-semibold">14:30 - Consultation</span>
                    </div>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="text-sm text-gray-700">Durée: 30 minutes</span>
                    </div>
                </div>
            </div>

            <!-- Repeat for other appointments -->
        </div>
    </div>

    <!-- Calendar Section -->
    <div class="mb-12 bg-white rounded-[30px] p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold">Planning de la semaine</h2>
            <div class="flex gap-4">
                <button class="px-4 py-2 bg-[#b9ff66] rounded-full">Aujourd'hui</button>
                <button class="px-4 py-2 border rounded-full">Vue Semaine</button>
                <button class="px-4 py-2 border rounded-full">Vue Mois</button>
            </div>
        </div>
        <!-- Placeholder for calendar -->
        <div class="h-64 border rounded-lg p-4">
            <!-- Calendar integration here -->
        </div>
    </div>

    <!-- Medical Records Section -->
    <div class="mb-12">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-bold">Dossiers Médicaux</h2>
                <span class="bg-white px-3 py-1 rounded-full text-sm">154 dossiers</span>
            </div>
            <div class="flex gap-2">
                <div class="flex gap-3">
                    <button class="px-4 py-2 bg-white rounded-full hover:bg-white/90 transition-all">Tous</button>
                    <button class="px-4 py-2 rounded-full hover:bg-white/10 transition-all">Actifs</button>
                    <button class="px-4 py-2 rounded-full hover:bg-white/10 transition-all">Archivés</button>
                    <div class="relative">
                        <input type="text"
                               placeholder="Rechercher un dossier..."
                               class="pl-10 pr-4 py-2 border rounded-full w-64 focus:outline-none focus:border-[#b9ff66]">
                        <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" viewBox="0 0 24 24" fill="none">
                            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Records Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Âge</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dernière visite</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y bg-gray-50">
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="https://randomuser.me/api/portraits/women/68.jpg"
                                     class="w-8 h-8 rounded-full">
                                <div>
                                    <div class="font-medium">Marie Dupont</div>
                                    <div class="text-sm text-gray-500">ID: #12345</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">42 ans</td>
                        <td class="px-6 py-4">15/03/2024</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                                Suivi régulier
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <button onclick="openDossierModal('12345')"
                                    class="text-blue-600 hover:text-blue-800 font-medium">
                                Voir le dossier
                            </button>
                        </td>
                    </tr>
                    <!-- Autres lignes similaires -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Dossier Médical -->
    <div id="dossierModal"
         class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-[30px] p-8 w-3/4 max-h-[80vh] overflow-y-auto">
            <div class="flex justify-between items-start mb-6">
                <h3 class="text-2xl font-bold">Dossier Médical</h3>
                <button onclick="closeDossierModal()"
                        class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-3 gap-6">
                <!-- Informations personnelles -->
                <div class="col-span-1 bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-bold mb-4">Informations personnelles</h4>
                    <div class="space-y-2">
                        <div class="flex items-center gap-3">
                            <img src="https://randomuser.me/api/portraits/women/68.jpg"
                                 class="w-16 h-16 rounded-full">
                            <div>
                                <div class="font-medium">Marie Dupont</div>
                                <div class="text-sm text-gray-500">42 ans</div>
                                <div class="text-sm text-gray-500">Née le 15/05/1981</div>
                            </div>
                        </div>
                        <div class="text-sm">
                            <p class="text-gray-600">📱 06 12 34 56 78</p>
                            <p class="text-gray-600">📧 marie.dupont@email.com</p>
                            <p class="text-gray-600">🏠 123 rue de la Santé, 75001 Paris</p>
                        </div>
                    </div>
                </div>

                <!-- Antécédents médicaux -->
                <div class="col-span-2 space-y-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-bold mb-4">Antécédents médicaux</h4>
                        <div class="space-y-2">
                            <div class="flex gap-2 flex-wrap">
                                <span class="px-3 py-1 bg-red-100 rounded-full text-sm">Allergie: Pénicilline</span>
                                <span class="px-3 py-1 bg-yellow-100 rounded-full text-sm">Diabète Type 2</span>
                                <span class="px-3 py-1 bg-blue-100 rounded-full text-sm">Hypertension</span>
                            </div>
                        </div>
                    </div>

                    <!-- Historique des consultations -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-bold mb-4">Historique des consultations</h4>
                        <div class="space-y-4">
                            <div class="border-b pb-4">
                                <div class="flex justify-between">
                                    <span class="font-medium">15/03/2024</span>
                                    <span class="text-gray-500">Consultation de routine</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-2">
                                    Contrôle glycémie, tension artérielle normale.
                                    Renouvellement traitement diabète.
                                </p>
                            </div>
                            <!-- Autres consultations -->
                        </div>
                    </div>

                    <!-- Traitements en cours -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-bold mb-4">Traitements en cours</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="font-medium">Metformine</span>
                                <span class="text-sm text-gray-500">2x/jour - Depuis 01/01/2024</span>
                            </div>
                            <!-- Autres traitements -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Centre de notifications -->
    <div class="mb-12">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold">Centre de notifications</h2>
            <button class="text-sm text-gray-500 hover:text-gray-700">Tout marquer comme lu</button>
        </div>

        <div class="bg-white rounded-[20px] divide-y">
            <!-- Notification item -->
            <div class="p-4 hover:bg-gray-50 transition-colors flex items-center gap-4">
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="font-medium">Nouveaux résultats d'analyses</h4>
                    <p class="text-sm text-gray-500">Patient: Marie Dupont - Analyses sanguines</p>
                </div>
                <span class="text-sm text-gray-400">Il y a 2h</span>
            </div>

            <!-- Notification item -->
            <div class="p-4 hover:bg-gray-50 transition-colors flex items-center gap-4">
                <div class="bg-yellow-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="font-medium">Rappel: Renouvellement ordonnance</h4>
                    <p class="text-sm text-gray-500">Patient: Jean Martin - Traitement diabète</p>
                </div>
                <span class="text-sm text-gray-400">Il y a 5h</span>
            </div>
        </div>
    </div>

    <!-- JavaScript pour le modal -->
    <script>
        function openDossierModal(dossierId) {
            document.getElementById('dossierModal').classList.remove('hidden');
            document.getElementById('dossierModal').classList.add('flex');
            // Ici vous pouvez ajouter la logique pour charger les données du dossier
        }

        function closeDossierModal() {
            document.getElementById('dossierModal').classList.add('hidden');
            document.getElementById('dossierModal').classList.remove('flex');
        }

        // Fermer le modal en cliquant en dehors
        document.getElementById('dossierModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDossierModal();
            }
        });
    </script>
</div>

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
