@extends('dashMedecin.layout')

@section('styles')
<!-- Utilisation des CDN pour éviter les problèmes d'import -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<style>
    /* Style général du calendrier */
    .fc {
        height: 100% !important;
        min-height: 650px !important;
        max-height: calc(100vh - 100px) !important;
        background: white;
        font-family: 'Inter', sans-serif;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    /* Ajustement des dimensions du conteneur */
    #calendar {
        height: calc(100vh - 120px);
        overflow: hidden;
        padding: 20px;
        transition: all 0.3s ease;
    }

    /* Ajustement de la grille horaire */
    .fc-timegrid-slots {
        min-height: 550px !important;
    }

    /* Style des slots horaires pour éviter la compression */
    .fc-timegrid-slot {
        height: 45px !important;
        min-height: 45px !important;
        transition: background-color 0.2s ease;
    }

    .fc-timegrid-slot:hover {
        background-color: #f8fdf0 !important;
    }

    /* En-tête du calendrier */
    .fc .fc-toolbar.fc-header-toolbar {
        margin-bottom: 1.5em;
        padding: 1.2rem 1.5rem;
        background: #ffffff;
        border-bottom: 1px solid #f0f0f0;
        border-radius: 16px 16px 0 0;
    }

    /* Titre du calendrier */
    .fc .fc-toolbar-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #111827;
        letter-spacing: -0.02em;
    }

    /* Boutons de navigation */
    .fc .fc-button-primary {
        background: white !important;
        border: 1px solid #e5e7eb !important;
        color: #374151 !important;
        padding: 0.6rem 1.2rem !important;
        border-radius: 10px !important;
        font-weight: 600 !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        transition: all 0.2s ease;
    }

    .fc .fc-button-primary:hover {
        background: #f9fafb !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .fc .fc-button-primary:not(:disabled).fc-button-active {
        background: #b9ff66 !important;
        border-color: #b9ff66 !important;
        color: #111827 !important;
        font-weight: 700 !important;
        box-shadow: 0 2px 4px rgba(185, 255, 102, 0.3);
    }

    /* Jours de la semaine */
    .fc-col-header-cell {
        padding: 12px 0 !important;
        background-color: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }

    .fc-col-header-cell-cushion {
        padding: 10px;
        color: #374151;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.05em;
    }

    /* Cellules de temps */
    .fc-timegrid-slot {
        height: 48px !important;
        border-color: #f3f4f6 !important;
    }

    .fc-timegrid-axis {
        padding: 0 12px !important;
        font-weight: 500;
        color: #4b5563;
        font-size: 0.85rem;
    }

    /* Aujourd'hui */
    .fc-day-today {
        background-color: #f0fdf4 !important;
    }

    /* Indicateur "maintenant" */
    .fc-timegrid-now-indicator-line {
        border-color: #b9ff66;
        border-width: 3px;
        box-shadow: 0 0 8px rgba(185, 255, 102, 0.5);
    }

    .fc-timegrid-now-indicator-arrow {
        border-color: #b9ff66;
        border-width: 5px;
        margin-top: -5px;
    }

    /* Style de base des événements */
    .fc-event {
        padding: 5px !important;
        border-radius: 8px !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: none !important;
        margin: 1px 2px !important;
    }

    .fc-event:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.12);
        z-index: 10 !important;
    }

    .fc-event-main {
        padding: 6px 8px !important;
    }

    /* Types d'événements avec meilleure visibilité et texte lisible */
    .event-urgence {
        background-color: #fee2e2 !important;
        color: #7f1d1d !important; /* Couleur de texte plus foncée pour meilleure lisibilité */
        border-left: 4px solid #ef4444 !important;
    }

    .event-urgence:hover {
        background-color: #fecaca !important;
    }

    .event-consultation {
        background-color: #dbeafe !important;
        color: #1e3a8a !important; /* Couleur de texte plus foncée pour meilleure lisibilité */
        border-left: 4px solid #3b82f6 !important;
    }

    .event-consultation:hover {
        background-color: #bfdbfe !important;
    }

    .event-suivi {
        background-color: #fef3c7 !important;
        color: #78350f !important; /* Couleur de texte plus foncée pour meilleure lisibilité */
        border-left: 4px solid #f59e0b !important;
    }

    .event-suivi:hover {
        background-color: #fde68a !important;
    }

    /* Amélioration de la lisibilité du texte dans les événements */
    .fc-event-title {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-weight: 600;
        font-size: 0.9rem;
        text-shadow: 0 0 1px rgba(255, 255, 255, 0.5); /* Léger contour pour améliorer la lisibilité */
    }

    .fc-event-time {
        font-weight: 600;
        color: inherit !important; /* Assure que la couleur du temps correspond au type d'événement */
    }

    /* Statuts */
    .statut-confirmé::after {
        content: "●";
        color: #22c55e;
        position: absolute;
        top: 6px;
        right: 8px;
        font-size: 12px;
        filter: drop-shadow(0 0 2px rgba(34, 197, 94, 0.3));
    }

    .statut-en_attente::after {
        content: "●";
        color: #f59e0b;
        position: absolute;
        top: 6px;
        right: 8px;
        font-size: 12px;
        filter: drop-shadow(0 0 2px rgba(245, 158, 11, 0.3));
    }

    .statut-annulé::after {
        content: "●";
        color: #ef4444;
        position: absolute;
        top: 6px;
        right: 8px;
        font-size: 12px;
        filter: drop-shadow(0 0 2px rgba(239, 68, 68, 0.3));
    }

    .statut-terminé::after {
        content: "●";
        color: #6366f1;
        position: absolute;
        top: 6px;
        right: 8px;
        font-size: 12px;
        filter: drop-shadow(0 0 2px rgba(99, 102, 241, 0.3));
    }

    /* Amélioration du contraste pour les informations patient */
    .patient-info {
        font-size: 0.8rem;
        opacity: 1; /* Augmenté de 0.9 à 1 pour meilleure lisibilité */
        margin-top: 3px;
        font-weight: 600; /* Augmenté de 500 à 600 */
        line-height: 1.2;
        color: inherit !important; /* Assure que la couleur correspond au type d'événement */
    }

    /* Heures non ouvrées */
    .fc-non-business {
        background-color: #f9fafb !important;
        background-image: linear-gradient(45deg, #f3f4f6 25%, transparent 25%, transparent 50%, #f3f4f6 50%, #f3f4f6 75%, transparent 75%, transparent);
        background-size: 10px 10px;
    }

    /* Amélioration des colonnes */
    .fc-timegrid-col.fc-day-today {
        background-color: rgba(240, 253, 244, 0.6) !important;
        box-shadow: inset 0 0 0 1px rgba(185, 255, 102, 0.2);
    }

    /* Notification styles */
    .notification {
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 12px 20px;
        border-radius: 10px;
        color: white;
        font-weight: 500;
        z-index: 1000;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        animation: slideIn 0.3s ease forwards;
        max-width: 350px;
    }

    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    .notification.success {
        background-color: #10b981;
        border-left: 5px solid #059669;
    }

    .notification.error {
        background-color: #ef4444;
        border-left: 5px solid #b91c1c;
    }

    .notification.info {
        background-color: #3b82f6;
        border-left: 5px solid #1d4ed8;
    }

    .notification.warning {
        background-color: #f59e0b;
        border-left: 5px solid #b45309;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .fc {
            height: calc(100vh - 120px);
            padding: 12px;
            border-radius: 12px;
        }

        .fc .fc-toolbar {
            flex-direction: column;
            align-items: flex-start;
            padding: 0.8rem;
        }

        .fc .fc-toolbar-title {
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        .fc-event {
            padding: 4px 6px !important;
        }

        .fc .fc-button-primary {
            padding: 0.4rem 0.8rem !important;
            font-size: 0.9rem;
        }
    }
</style>
@endsection

@section('content')
<div class="p-4 sm:p-8 bg-[#e4e4e4] min-h-screen">
    <!-- En-tête avec navigation et actions -->
    <div class="bg-white rounded-[20px] shadow-sm p-4 mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <!-- Titre et navigation -->
            <div class="flex items-center gap-4">
                <h1 class="text-2xl sm:text-3xl font-bold">
                    AGEND<span class="text-[#b9ff66]">A</span>
                </h1>
                <div class="flex items-center bg-gray-100 p-1 rounded-lg">
                    <button class="px-3 py-1 bg-white shadow-sm rounded-lg text-sm font-medium">Jour</button>
                    <button class="px-3 py-1 hover:bg-white/50 rounded-lg text-sm">Semaine</button>
                    <button class="px-3 py-1 hover:bg-white/50 rounded-lg text-sm">Mois</button>
                </div>
            </div>

            <!-- Navigation temporelle -->
            <div class="flex items-center gap-4">
                <button class="p-2 hover:bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <div class="flex flex-col items-center">
                    <span class="text-lg font-medium">15 Mars 2024</span>
                    <span class="text-sm text-gray-500">Vendredi</span>
                </div>
                <button class="p-2 hover:bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            <!-- Actions rapides -->
            <div class="flex gap-2">
                <button onclick="openNewRdvModal()"
                        class="bg-[#b9ff66] hover:bg-[#a8eb5f] text-black rounded-lg px-4 py-2 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouveau RDV
                </button>
                <button onclick="toggleBlockCreneauModal()"
                        class="bg-gray-800 text-white rounded-lg px-4 py-2 flex items-center gap-2 hover:bg-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Bloquer
                </button>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-[20px] p-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Aujourd'hui</p>
                    <p class="text-xl font-bold">8 RDV</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-[20px] p-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Prochain RDV</p>
                    <p class="text-lg font-medium">10:30</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-[20px] p-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Créneaux libres</p>
                    <p class="text-xl font-bold">5</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-[20px] p-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-red-100 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">En attente</p>
                    <p class="text-xl font-bold">3</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Vue principale -->
    <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-6">
        <!-- Panneau latéral -->
        <div class="hidden lg:block space-y-6">
            <!-- Mini calendrier -->
            <div class="bg-white rounded-[20px] shadow-sm p-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-medium">Mars 2024</h3>
                    <div class="flex gap-1">
                        <button class="p-1.5 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <button class="p-1.5 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Jours de la semaine -->
                <div class="grid grid-cols-7 mb-2">
                    @foreach(['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'] as $day)
                        <div class="text-center text-xs font-medium text-gray-500">{{ $day }}</div>
                    @endforeach
                </div>

                <!-- Grille des jours -->
                <div class="grid grid-cols-7 gap-1">
                    <!-- Jours du mois précédent -->
                    @foreach(range(26, 29) as $day)
                        <button class="aspect-square flex flex-col items-center justify-center p-1 text-gray-400 hover:bg-gray-50 rounded-lg">
                            <span class="text-sm">{{ $day }}</span>
                        </button>
                    @endforeach

                    <!-- Jours du mois actuel -->
                    @foreach(range(1, 31) as $day)
                        <button @class([
                            'aspect-square flex flex-col items-center justify-center p-1 rounded-lg relative hover:bg-gray-50 transition-colors',
                            'bg-[#b9ff66]/20 font-medium' => $day === 15, // Jour actuel
                            'text-gray-900' => true
                        ])>
                            <span class="text-sm">{{ $day }}</span>

                            <!-- Indicateur de rendez-vous -->
                            @if(in_array($day, [4, 8, 15, 22, 29]))
                                <span class="absolute bottom-1 w-1 h-1 rounded-full bg-[#b9ff66]"></span>
                            @endif

                            <!-- Nombre de rendez-vous -->
                            @if(in_array($day, [15]))
                                <span class="absolute top-0 right-0 w-4 h-4 flex items-center justify-center bg-[#b9ff66] text-[10px] rounded-full">
                                    3
                                </span>
                            @endif
                        </button>
                    @endforeach

                    <!-- Jours du mois suivant -->
                    @foreach(range(1, 2) as $day)
                        <button class="aspect-square flex flex-col items-center justify-center p-1 text-gray-400 hover:bg-gray-50 rounded-lg">
                            <span class="text-sm">{{ $day }}</span>
                        </button>
                    @endforeach
                </div>

                <!-- Légende du mini calendrier -->
                <div class="mt-4 pt-4 border-t">
                    <div class="flex items-center justify-between text-xs text-gray-500">
                        <div class="flex items-center gap-1">
                            <span class="w-2 h-2 bg-[#b9ff66] rounded-full"></span>
                            <span>Avec RDV</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <span class="w-2 h-2 bg-gray-200 rounded-full"></span>
                            <span>Sans RDV</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Légende -->
            <div class="bg-white rounded-[20px] shadow-sm p-4">
                <h3 class="font-medium mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Légende
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-2 p-2 hover:bg-gray-50 rounded-lg">
                        <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                        <span class="text-sm">Consultation confirmée</span>
                    </div>
                    <div class="flex items-center gap-2 p-2 hover:bg-gray-50 rounded-lg">
                        <span class="w-3 h-3 bg-yellow-500 rounded-full"></span>
                        <span class="text-sm">En attente</span>
                    </div>
                    <div class="flex items-center gap-2 p-2 hover:bg-gray-50 rounded-lg">
                        <span class="w-3 h-3 bg-red-500 rounded-full"></span>
                        <span class="text-sm">Urgence</span>
                    </div>
                    <div class="flex items-center gap-2 p-2 hover:bg-gray-50 rounded-lg">
                        <span class="w-3 h-3 bg-gray-500 rounded-full"></span>
                        <span class="text-sm">Créneau bloqué</span>
                    </div>
                </div>
            </div>

            <!-- Prochains rendez-vous -->
            <div class="bg-white rounded-[20px] shadow-sm p-4">
                <h3 class="font-medium mb-4">Prochains rendez-vous</h3>
                <div class="space-y-3">
                    <div class="p-3 bg-green-50 rounded-lg border-l-4 border-green-500">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium">10:30 - M. Dupont</p>
                                <p class="text-sm text-gray-600">Suivi diabète</p>
                            </div>
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">Confirmé</span>
                        </div>
                    </div>
                    <div class="p-3 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium">11:00 - Mme Martin</p>
                                <p class="text-sm text-gray-600">Première consultation</p>
                            </div>
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs">En attente</span>
                        </div>
                    </div>
                    <!-- Plus de rendez-vous... -->
                </div>
            </div>
        </div>

        <!-- Menu latéral mobile -->
        <div class="lg:hidden mb-4">
            <button onclick="toggleMobileSidebar()"
                    class="w-full bg-white rounded-lg p-4 flex items-center justify-between shadow-sm">
                <span class="font-medium">Menu latéral</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <!-- Panneau latéral mobile -->
            <div id="mobileSidebar" class="hidden bg-white rounded-lg mt-2 p-4 shadow-sm">
                <!-- ... existing sidebar content ... -->
            </div>
        </div>

        <!-- Calendrier principal avec contrôles responsifs -->
        <div id="calendar" class="bg-white rounded-[20px] shadow-sm p-4 h-[calc(100vh-200px)]"></div>    </div>
</div>

<!-- Modal Nouveau RDV -->
<div id="newRdvModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-[20px] p-8 w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">NOUVEAU RENDEZ-<span class="text-[#b9ff66]">V</span>OUS</h2>
            <button type="button" onclick="closeNewRdvModal()" class="hover:bg-gray-100 p-2 rounded-full">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="rdvForm">
            @csrf
            <input type="hidden" name="id" id="rdvId"> <!-- Champ caché pour l'ID du rendez-vous -->
            <div class="space-y-6">
                <!-- Patient -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Patient <span class="text-red-500">*</span></label>
                    <select id="patient_select" name="patient_id" required class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                        <option value="">Sélectionner un patient</option>
                        <!-- Options de patients -->
                    </select>
                </div>

                <!-- Date et Heure -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Date <span class="text-red-500">*</span></label>
                        <input type="date" name="date_debut" required class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Heure <span class="text-red-500">*</span></label>
                        <input type="time" name="heure_debut" required class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                    </div>
                </div>

                <!-- Type de RDV -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Type de rendez-vous <span class="text-red-500">*</span></label>
                    <select name="type" required class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                        <option value="">Sélectionner un type</option>
                        <option value="consultation">Consultation (30 min)</option>
                        <option value="suivi">Suivi (15 min)</option>
                        <option value="urgence">Urgence (45 min)</option>
                    </select>
                </div>

                <!-- Titre -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Titre <span class="text-red-500">*</span></label>
                    <input type="text" name="titre" required class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                </div>

                <!-- Description -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" rows="3" class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]"></textarea>
                </div>

                <!-- Statut -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Statut <span class="text-red-500">*</span></label>
                    <select name="statut" required class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                        <option value="confirmé">Confirmé</option>
                        <option value="en_attente">En attente</option>
                        <option value="annulé">Annulé</option>
                        <option value="terminé">terminé</option>

                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-4 mt-6">
                <button type="button"
                        onclick="deleteRendezVous(document.querySelector('input[name=\'id\']').value)"
                        class="px-4 py-2 bg-red-500 text-white hover:bg-red-600 rounded-lg">
                    Supprimer
                </button>
                <button type="button" onclick="closeNewRdvModal()"
                        class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                    Annuler
                </button>
                <button type="submit" class="px-4 py-2 bg-[#b9ff66] hover:bg-[#a8eb5f] rounded-lg">
                    <span id="formActionText">Mettre à jour</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Bloquer Créneau -->
<div id="blockCreneauModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-[20px] p-8 w-full max-w-md mx-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">
                BLOQUER UN CRÉNE<span class="text-[#b9ff66]">A</span>U
            </h2>
            <button onclick="toggleBlockCreneauModal()" class="hover:bg-gray-100 p-2 rounded-full">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form class="space-y-6">
            <!-- Période -->
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Date début</label>
                    <input type="date" class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Date fin</label>
                    <input type="date" class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                </div>
            </div>

            <!-- Horaires -->
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Heure début</label>
                    <input type="time" class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Heure fin</label>
                    <input type="time" class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                </div>
            </div>

            <!-- Motif -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Motif</label>
                <select class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                    <option value="pause">Pause déjeuner</option>
                    <option value="conge">Congés</option>
                    <option value="formation">Formation</option>
                    <option value="autre">Autre</option>
                </select>
            </div>

            <!-- Notes -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Notes (optionnel)</label>
                <textarea rows="2" placeholder="Précisions sur le blocage..."
                          class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]"></textarea>
            </div>

            <!-- Boutons d'action -->
            <div class="flex justify-end gap-4 pt-4">
                <button type="button" onclick="toggleBlockCreneauModal()"
                        class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                    Annuler
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-[#b9ff66] hover:bg-[#a8eb5f] rounded-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Bloquer
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<!-- Chargement du bundle complet de FullCalendar -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/fr.js'></script>

<script>

document.addEventListener('DOMContentLoaded', function() {
    // Vérifier que l'élément existe
    var calendarEl = document.getElementById('calendar');
    if (!calendarEl) {
        console.error("Élément calendar non trouvé");
        return;
    }

    try {
        // Initialisation avec le bundle complet
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },
            locale: 'fr',
            slotMinTime: '08:00:00',
            slotMaxTime: '17:30:00',
            slotDuration: '00:30:00',
            snapDuration: '00:15:00',
            selectable: true,
            editable: true,
            nowIndicator: true,
            dayMaxEvents: true,
            businessHours: {
                daysOfWeek: [1, 2, 3, 4, 5],
                startTime: '08:00',
                endTime: '17:30',
            },
            height: '100%',
            expandRows: true,
            stickyHeaderDates: true,
            eventSources: [{
                url: '{{ route("rendez-vous.evenements") }}',
                method: 'GET',
                failure: function(error) {
                    console.error('Error fetching events:', error);
                    showNotification('Erreur lors de la récupération des événements.', 'error');
                }
            }],
            eventDidMount: eventDidMount,
            select: function(info) {
                openAppointmentModal('create', {
                    start: info.start,
                    end: info.end
                });
            },
            eventClick: function(info) {
                openAppointmentModal('edit', info.event);
            },
            eventDrop: function(info) {
                updateAppointmentDates(info.event);
            },
            eventResize: function(info) {
                updateAppointmentDates(info.event);
            },
            slotLabelFormat: {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            },
            eventConstraint: 'businessHours',
            selectConstraint: 'businessHours'
        });

        calendar.render();
        window.calendar = calendar;

    // Initialisation de Select2 avec vérification jQuery
    $(document).ready(function() {
        // Vérifier que jQuery et Select2 sont chargés
        if (typeof $ === 'undefined') {
            console.error('jQuery n\'est pas chargé');
            return;
        }

        if (typeof $.fn.select2 === 'undefined') {
            console.error('Select2 n\'est pas chargé');
            return;
        }

        // S'assurer que l'élément existe
        if ($('#patient_select').length === 0) {
            console.error('Élément #patient_select non trouvé');
            return;
        }

        try {
            $('#patient_select').select2({
                placeholder: 'Rechercher un patient...',
                minimumInputLength: 2,
                language: {
                    inputTooShort: function() {
                        return 'Veuillez saisir au moins 2 caractères';
                    },
                    searching: function() {
                        return 'Recherche en cours...';
                    },
                    noResults: function() {
                        return 'Aucun patient trouvé';
                    }
                },
                ajax: {
                    url: '{{ route("patients.search") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            });

            console.log('Select2 initialisé avec succès');
        } catch (error) {
            console.error('Erreur lors de l\'initialisation de Select2:', error);
        }
    });

        // Écoute des événements en temps réel
        if (window.Echo) {
            console.log('Setting up Echo listener for rendez-vous channel');

            window.Echo.channel('rendez-vous')
                .subscribed(() => {
                    console.log('✅ Successfully subscribed to rendez-vous channel');
                })
                .error((error) => {
                    console.error('❌ Channel error:', error);
                })
                .listen('.RendezVousModifie', (e) => {
                    console.log('📨 Event received:', e);

                    try {
                        switch(e.action) {
                            case 'created':
                                console.log('Creating new event:', e.rendezVous);
                                calendar.addEvent(e.rendezVous);
                                showNotification('Nouveau rendez-vous ajouté', 'info');
                                break;

                            case 'updated':
                                console.log('Updating event:', e.rendezVous);
                                let existingEvent = calendar.getEventById(e.rendezVous.id);
                                if (existingEvent) {
                                    existingEvent.remove();
                                    calendar.addEvent(e.rendezVous);
                                    showNotification('Rendez-vous mis à jour', 'info');
                                }
                                break;

                            case 'deleted':
                                console.log('Deleting event:', e.id);
                                let eventToDelete = calendar.getEventById(e.id);
                                if (eventToDelete) {
                                    eventToDelete.remove();
                                    showNotification('Rendez-vous supprimé', 'warning');
                                }
                                break;
                        }
                    } catch (error) {
                        console.error('Error handling event:', error);
                    }
                });
        }
    } catch (error) {
        console.error('Erreur lors de l\'initialisation du calendrier:', error);
        showNotification('Erreur lors de l\'initialisation du calendrier', 'error');
    }

    // Assurer que le calendrier s'adapte à son conteneur
    window.addEventListener('resize', function() {
        calendar.updateSize();
    });
});

// Fonction pour afficher les notifications
function showNotification(message, type = 'success') {
    console.log(`${type}: ${message}`);

    // Supprimer les notifications existantes
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => {
        notification.remove();
    });

    const notificationDiv = document.createElement('div');
    notificationDiv.className = `notification ${type}`;

    // Ajouter une icône en fonction du type
    let icon = '';
    switch(type) {
        case 'success':
            icon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>';
            break;
        case 'error':
            icon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0v4a1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>';
            break;
        case 'info':
            icon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0v3a1 1 0 112 0V6z" clip-rule="evenodd"/></svg>';
            break;
        case 'warning':
            icon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v4a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>';
            break;
    }

    notificationDiv.innerHTML = icon + message;
    document.body.appendChild(notificationDiv);

    // Animation de sortie
    setTimeout(() => {
        notificationDiv.style.animation = 'slideOut 0.3s ease forwards';
        setTimeout(() => notificationDiv.remove(), 300);
    }, 3000);
}

function openNewRdvModal() {
    document.getElementById('newRdvModal').classList.remove('hidden');
}

function closeNewRdvModal() {
    document.getElementById('newRdvModal').classList.add('hidden');
}

function openAppointmentModal(type, data) {
    const modal = document.getElementById('newRdvModal');
    const form = document.getElementById('rdvForm');
    const formActionText = document.getElementById('formActionText');
    const deleteButton = modal.querySelector('button[onclick^="deleteRendezVous"]');

    // Réinitialiser le formulaire
    form.reset();

    if (type === 'create') {
        formActionText.textContent = 'Créer le rendez-vous';
        deleteButton.style.display = 'none';

        // Pré-remplir la date et l'heure
        const startDate = data.start.toISOString().split('T')[0];
        const startTime = data.start.toTimeString().slice(0, 5);

        document.querySelector('input[name="date_debut"]').value = startDate;
        document.querySelector('input[name="heure_debut"]').value = startTime;

        form.onsubmit = handleNewRdv;
    } else if (type === 'edit') {
        formActionText.textContent = 'Mettre à jour';
        deleteButton.style.display = 'block';

        // Pré-remplir les champs avec les données existantes
        document.querySelector('input[name="id"]').value = data.id;
        document.querySelector('input[name="date_debut"]').value = data.start.toISOString().split('T')[0];
        document.querySelector('input[name="heure_debut"]').value = data.start.toTimeString().slice(0, 5);
        document.querySelector('select[name="type"]').value = data.extendedProps.type;
        document.querySelector('input[name="titre"]').value = data.title;
        document.querySelector('textarea[name="description"]').value = data.extendedProps.description || '';
        document.querySelector('select[name="statut"]').value = data.extendedProps.statut;

        // Gérer le select2 pour le patient
        const patientSelect = $('#patient_select');
        if (data.extendedProps.patient_id && data.extendedProps.patient_nom) {
            const option = new Option(data.extendedProps.patient_nom, data.extendedProps.patient_id, true, true);
            patientSelect.append(option).trigger('change');
        }

        form.onsubmit = (e) => handleUpdateRdv(e, data.id);
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

async function handleUpdateRdv(event, rdvId) {
    event.preventDefault();
    const formData = new FormData(event.target);

    // Combiner date_debut et heure_debut
    const date = formData.get('date_debut');
    const time = formData.get('heure_debut');
    const dateDebut = new Date(`${date}T${time}`);

    // Calculer la date de fin
    const duration = getDurationByType(formData.get('type'));
    const dateFin = new Date(dateDebut.getTime() + duration * 60000);

    // Créer l'objet de données à envoyer
    const data = {
        patient_id: formData.get('patient_id'),
        date_debut: dateDebut.toISOString(),
        date_fin: dateFin.toISOString(),
        type: formData.get('type'),
        description: formData.get('description'),
        titre: formData.get('titre'),
        statut: formData.get('statut')
    };

    try {
        const response = await fetch(`/dashboard/medecin/rendez-vous/${rdvId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            throw new Error('Erreur lors de la mise à jour');
        }

        window.calendar.refetchEvents();
        closeNewRdvModal();
        showNotification('Rendez-vous mis à jour avec succès', 'success');
    } catch (error) {
        console.error(error);
        showNotification('Erreur lors de la mise à jour du rendez-vous', 'error');
    }
}


function getDurationByType(type) {
    switch(type) {
        case 'consultation': return 30;
        case 'suivi': return 15;
        case 'urgence': return 45;
        default: return 30;
    }
}

function toggleBlockCreneauModal() {
    const modal = document.getElementById('blockCreneauModal');
    modal.classList.toggle('hidden');
}

function updateDuration(type) {
    const durationSelect = document.getElementById('duration');
    switch(type) {
        case 'consultation':
            durationSelect.value = '30';
            break;
        case 'suivi':
            durationSelect.value = '15';
            break;
        case 'urgence':
            durationSelect.value = '45';
            break;
    }
}

function handlePatientSearch(event) {
    console.log('Recherche patient:', event.target.value);
}
async function handleNewRdv(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    // Construire la date de début et de fin
    const date = formData.get('date_debut');
    const time = formData.get('heure_debut');
    const dateDebut = new Date(`${date}T${time}`);

    // Calculer la date de fin en fonction du type de rendez-vous
    const duration = getDurationByType(formData.get('type'));
    const dateFin = new Date(dateDebut.getTime() + duration * 60000);

    // Créer l'objet de données à envoyer
    const data = {
        patient_id: formData.get('patient_id'),
        date_debut: dateDebut.toISOString(),
        date_fin: dateFin.toISOString(),
        type: formData.get('type'),
        description: formData.get('description'),
        titre: formData.get('titre'),
        statut: formData.get('statut')
    };

    try {
        const response = await fetch('/dashboard/medecin/rendez-vous', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            throw new Error('Erreur lors de la création du rendez-vous');
        }

        const responseData = await response.json();
        window.calendar.refetchEvents();
        closeNewRdvModal();
        showNotification('Rendez-vous créé avec succès', 'success');
    } catch (error) {
        console.error(error);
        showNotification(error.message, 'error');
    }
}


function showError(message) {
    showNotification(message, 'error');
}

// Fonction pour mettre à jour les dates d'un rendez-vous (drag & drop)
async function updateAppointmentDates(event) {
    try {
        const response = await fetch(`/dashboard/medecin/rendez-vous/${event.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                start: event.start.toISOString(),
                end: event.end.toISOString()
            })
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Erreur lors de la mise à jour');
        }

        // Rafraîchir les événements du calendrier
        window.calendar.refetchEvents();
        showNotification('Rendez-vous mis à jour avec succès', 'success');
    } catch (error) {
        console.error(error);
        event.revert();
        showNotification('Erreur lors de la mise à jour du rendez-vous', 'error');
    }
}

// Fonction pour supprimer un rendez-vous
async function deleteRendezVous(rdvId) {
    if (!rdvId) {
        showNotification('ID de rendez-vous manquant', 'error');
        return;
    }

    if (!confirm('Êtes-vous sûr de vouloir supprimer ce rendez-vous ?')) {
        return;
    }

    try {
        const response = await fetch(`/dashboard/medecin/rendez-vous/${rdvId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Erreur lors de la suppression');
        }

        // Supprimer l'événement du calendrier
        const event = window.calendar.getEventById(rdvId);
        if (event) {
            event.remove();
        }

        // Forcer le rafraîchissement des événements du calendrier
        window.calendar.refetchEvents();

        // Fermer le modal et afficher une notification
        closeNewRdvModal();
        showNotification('Rendez-vous supprimé avec succès', 'success');
    } catch (error) {
        console.error(error);
        showNotification('Erreur lors de la suppression du rendez-vous', 'error');
    }
}

// Gestion du menu mobile
function toggleMobileSidebar() {
    const sidebar = document.getElementById('mobileSidebar');
    sidebar.classList.toggle('hidden');
}

// Gestion du changement de vue
document.getElementById('calendarView').addEventListener('change', function(e) {
    calendar.changeView(e.target.value);
});

// Optimisation du chargement des événements
let eventsCache = new Map();
calendar.on('dateSet', function(info) {
    const start = info.start.toISOString();
    const end = info.end.toISOString();
    const cacheKey = `${start}-${end}`;

    if (eventsCache.has(cacheKey)) {
        calendar.removeAllEvents();
        calendar.addEventSource(eventsCache.get(cacheKey));
    } else {
        fetch(`/api/events?start=${start}&end=${end}`)
            .then(response => response.json())
            .then(events => {
                eventsCache.set(cacheKey, events);
                calendar.removeAllEvents();
                calendar.addEventSource(events);
            });
    }
});

// Amélioration des performances de rendu
let renderTimeout;
calendar.on('windowResize', function() {
    if (renderTimeout) clearTimeout(renderTimeout);
    renderTimeout = setTimeout(() => calendar.render(), 100);
});

// Amélioration du rendu des événements
function eventDidMount(info) {
    const event = info.event;
    const props = event.extendedProps || {};

    const mainEl = info.el.querySelector('.fc-event-main');
    if (mainEl) {
        mainEl.innerHTML = '';

        // Conteneur principal avec effet de profondeur
        const contentDiv = document.createElement('div');
        contentDiv.className = 'flex flex-col gap-1.5 relative';

        // Ligne du haut : Nom du patient et statut
        const topLine = document.createElement('div');
        topLine.className = 'flex items-center justify-between';

        // Nom du patient avec style amélioré
        const patientName = document.createElement('div');
        patientName.className = 'font-semibold text-sm tracking-tight';
        patientName.textContent = props.patient_nom || event.title || 'Patient';

        // Icône de statut plus visible
        const statusIcon = document.createElement('span');
        statusIcon.style.fontSize = '14px';
        switch(props.statut) {
            case 'confirmé':
                statusIcon.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>';
                break;
            case 'en_attente':
                statusIcon.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>';
                break;
            case 'annulé':
                statusIcon.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>';
                break;
            case 'terminé':
                statusIcon.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>';
                break;
            default:
                statusIcon.textContent = '';
        }

        topLine.appendChild(patientName);
        topLine.appendChild(statusIcon);

        // Ligne du milieu : Type de rendez-vous
        const typeLine = document.createElement('div');
        typeLine.className = 'text-xs font-medium opacity-90';

        let typeText = '';
        switch(props.type) {
            case 'consultation': typeText = 'Consultation'; break;
            case 'suivi': typeText = 'Suivi'; break;
            case 'urgence': typeText = 'Urgence'; break;
            default: typeText = props.type || '';
        }
        typeLine.textContent = typeText;

        // Ligne du bas : Horaires
        const timeLine = document.createElement('div');
        timeLine.className = 'text-xs opacity-85 mt-1';

        // Formatage des heures plus élégant
        const formatTime = (date) => {
            return date ? date.toLocaleTimeString('fr-FR', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            }) : '';
        };

        const startTime = formatTime(event.start);
        const endTime = formatTime(event.end);
        timeLine.textContent = `${startTime} - ${endTime}`;

        // Assemblage final
        contentDiv.appendChild(topLine);
        contentDiv.appendChild(typeLine);
        contentDiv.appendChild(timeLine);
        mainEl.appendChild(contentDiv);

        // Appliquer le style selon le type avec effet de transition
        if (props.type) {
            info.el.classList.add('event-' + props.type);
        } else {
            info.el.classList.add('event-consultation');
        }

        // Ajouter une classe pour le statut
        if (props.statut) {
            info.el.classList.add('statut-' + props.statut);
        }
    }
}
</script>
@endsection
