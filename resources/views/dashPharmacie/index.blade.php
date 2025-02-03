@extends('dashPharmacie.layout')

@section('content')
<div class="dashboard-container p-6 bg-gray-100 rounded-lg space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tableau de Bord Pharmacie</h1>
            <p class="text-sm text-gray-500">Dernière mise à jour : {{ now()->format('d/m/Y H:i') }}</p>
        </div>
        <div class="relative w-full md:w-64">
            <input type="text" placeholder="Rechercher..."
                   class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-medium">
            <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
    </div>

    <!-- Statistiques Principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <!-- Carte : Stock Médicaments -->
        <a href="{{ route('dashPharmacie.stock') }}" class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="bg-primary-light p-3 rounded-lg">
                    <svg class="w-8 h-8 text-primary-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-bold text-primary-dark">1,234</p>
                    <p class="text-sm text-gray-500">Stock Médicaments</p>
                </div>
            </div>
        </a>

        <!-- Carte : Commandes de Médicaments -->
        <a href="{{ route('dashPharmacie.commandes') }}" class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="bg-blue-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-bold text-blue-600">56</p>
                    <p class="text-sm text-gray-500">Commandes en cours</p>
                </div>
            </div>
        </a>

        <!-- Carte : Dons Reçus -->
        <a href="{{ route('dashPharmacie.donsReçus') }}" class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="bg-green-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-bold text-green-600">23</p>
                    <p class="text-sm text-gray-500">Dons Reçus</p>
                </div>
            </div>
        </a>

        <!-- Carte : Donateurs -->
        <a href="{{ route('dashPharmacie.donateurs') }}" class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="bg-purple-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-bold text-purple-600">45</p>
                    <p class="text-sm text-gray-500">Donateurs</p>
                </div>
            </div>
        </a>

        <!-- Carte : Demandes de Dons -->
        <a href="{{ route('dashPharmacie.demandesDons') }}" class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="bg-yellow-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-bold text-yellow-600">12</p>
                    <p class="text-sm text-gray-500">Demandes de Dons</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Graphiques et Rappels -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Graphique : Évolution des Stocks -->
        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm">
            <h3 class="text-lg font-semibold mb-4">Évolution des Stocks</h3>
            <div class="h-64">
                <canvas id="stockChart"></canvas>
            </div>
        </div>

        <!-- Rappels -->
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <h3 class="text-lg font-semibold mb-4">Rappels</h3>
            <div class="space-y-4">
                <div class="bg-primary-light p-4 rounded-lg">
                    <p class="font-medium">Contrôle des stocks</p>
                    <p class="text-sm text-gray-600">10:00 - 12:00</p>
                </div>
                <div class="bg-yellow-100 p-4 rounded-lg">
                    <p class="font-medium">Réception de dons</p>
                    <p class="text-sm text-gray-600">14:00 - 16:00</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts pour les graphiques -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const stockChart = new Chart(document.getElementById('stockChart'), {
        type: 'line',
        data: {
            labels: ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4'],
            datasets: [{
                label: 'Unités en stock',
                data: [650, 590, 800, 810],
                borderColor: '#227d53',
                tension: 0.4,
                fill: true,
                backgroundColor: '#5fbd9233'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>

<style>
    .bg-primary-light { background-color: #5fbd92; }
    .bg-primary-medium { background-color: #227d53; }
    .bg-primary-dark { background-color: #13452d; }
    .text-primary-dark { color: #13452d; }
    .text-primary-medium { color: #227d53; }
</style>
@endsection
