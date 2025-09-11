@extends('dashAdmin.layout')

@section('title', 'Statistiques')
@section('page-title', 'Statistiques')
@section('page-description', 'Analyses détaillées de la plateforme')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-900">Statistiques détaillées</h1>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Médecins par spécialité</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($stats_detaillees['medecins_par_specialite'] as $specialite)
            <div class="bg-blue-50 rounded-lg p-4">
                <h3 class="font-medium text-blue-900">{{ $specialite->specialite ?: 'Non définie' }}</h3>
                <p class="text-2xl font-bold text-blue-600">{{ $specialite->count }}</p>
            </div>
            @empty
            <p class="text-gray-500">Aucune donnée disponible</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Rendez-vous par statut</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @forelse($stats_detaillees['rendez_vous_par_statut'] as $statut)
            <div class="bg-green-50 rounded-lg p-4">
                <h3 class="font-medium text-green-900">{{ ucfirst($statut->statut) }}</h3>
                <p class="text-2xl font-bold text-green-600">{{ $statut->count }}</p>
            </div>
            @empty
            <p class="text-gray-500">Aucune donnée disponible</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Revenus par mois</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($stats_detaillees['revenus_par_mois'] as $revenu)
            <div class="bg-purple-50 rounded-lg p-4">
                <h3 class="font-medium text-purple-900">Mois {{ $revenu->mois }}</h3>
                <p class="text-2xl font-bold text-purple-600">{{ number_format($revenu->total, 2) }} TND</p>
            </div>
            @empty
            <p class="text-gray-500">Aucune donnée disponible</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
