@extends('dashDonateur.layout')

@section('content')
<div class="dashboard-container p-6 bg-gray-100 rounded-lg space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tableau de Bord Donateur</h1>
            <p class="text-sm text-gray-500">Dernière mise à jour : {{ now()->format('d/m/Y H:i') }}</p>
        </div>
        <div class="relative w-full md:w-64">
            <input type="text" placeholder="Rechercher un don..."
                   class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500">
            <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
    </div>

    <!-- Statistiques Principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total des Dons -->
        <a href="{{ route('donateur.dons') }}" class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="bg-teal-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-bold text-teal-600">157</p>
                    <p class="text-sm text-gray-500">Total des Dons</p>
                </div>
            </div>
        </a>

        <!-- Dons en Cours -->
        <a href="{{ route('donateur.suivi') }}" class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="bg-blue-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-bold text-blue-600">5</p>
                    <p class="text-sm text-gray-500">Dons en Cours</p>
                </div>
            </div>
        </a>

        <!-- Impact Social -->
        <a href="{{ route('donateur.rapports') }}" class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="bg-green-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-bold text-green-600">1,234</p>
                    <p class="text-sm text-gray-500">Patients Aidés</p>
                </div>
            </div>
        </a>

        <!-- Pharmacies Partenaires -->
        <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="bg-purple-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-bold text-purple-600">12</p>
                    <p class="text-sm text-gray-500">Pharmacies Partenaires</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Principale -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Graphique : Historique des Dons -->
        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm">
            <h3 class="text-lg font-semibold mb-4">Historique des Dons</h3>
            <div class="h-64">
                <canvas id="donationsChart"></canvas>
            </div>
        </div>

        <!-- Dernières Activités -->
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <h3 class="text-lg font-semibold mb-4">Activités Récentes</h3>
            <div class="space-y-4">
                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                    <div class="bg-blue-100 p-2 rounded-full">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium">Don accepté - Pharmacie Centrale</p>
                        <p class="text-xs text-gray-500">Il y a 2 heures</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                    <div class="bg-green-100 p-2 rounded-full">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium">Don distribué aux patients</p>
                        <p class="text-xs text-gray-500">Il y a 1 jour</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Inférieure -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Prochains Dons Planifiés -->
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <h3 class="text-lg font-semibold mb-4">Prochains Dons Planifiés</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="bg-teal-100 p-2 rounded-full">
                            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium">Pharmacie Saint-Louis</p>
                            <p class="text-sm text-gray-500">15 Juin 2024</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 bg-teal-100 text-teal-600 rounded-full text-sm">Planifié</span>
                </div>
            </div>
        </div>

        <!-- Besoins Urgents -->
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <h3 class="text-lg font-semibold mb-4">Besoins Urgents</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-4 bg-red-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="bg-red-100 p-2 rounded-full">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium">Antibiotiques</p>
                            <p class="text-sm text-gray-500">Pharmacie Centrale</p>
                        </div>
                    </div>
                    <button class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Faire un don
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts pour les graphiques -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const donationsChart = new Chart(document.getElementById('donationsChart'), {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
            datasets: [{
                label: 'Dons effectués',
                data: [12, 19, 15, 25, 22, 30],
                borderColor: '#0d9488',
                tension: 0.4,
                fill: true,
                backgroundColor: 'rgba(13, 148, 136, 0.1)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        display: true,
                        color: 'rgba(0,0,0,0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>

<style>
    .bg-teal-100 { background-color: rgba(13, 148, 136, 0.1); }
    .text-teal-600 { color: #0d9488; }
    .hover\:bg-teal-700:hover { background-color: #0f766e; }
</style>
@endsection
