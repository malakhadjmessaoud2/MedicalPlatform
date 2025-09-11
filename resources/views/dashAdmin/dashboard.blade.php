@extends('dashAdmin.layout')

@section('title', 'Dashboard')
@section('page-title', 'Tableau de bord')
@section('page-description', 'Vue d\'ensemble de la plateforme MedicalPlatform')

@section('content')
<div class="space-y-6">
    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-user-md text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Médecins</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_medecins'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-users text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Patients</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_patients'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-calendar-alt text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Rendez-vous aujourd'hui</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['rendez_vous_aujourd_hui'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-euro-sign text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Revenus du mois</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['revenus_mois'], 2) }} TND</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Répartition des rendez-vous aujourd'hui (sans JS) -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Répartition des rendez-vous aujourd'hui</h3>
        @php
            $aujourdhui = \Carbon\Carbon::today();
            $rdvQuery = \App\Models\RendezVous::whereDate('date_debut', $aujourdhui);
            $totalRdvJour = (clone $rdvQuery)->count();
            $countConfirmed = (clone $rdvQuery)->where('statut', 'confirmed')->count();
            $countPending = (clone $rdvQuery)->where('statut', 'pending')->count();
            $countCancelled = (clone $rdvQuery)->where('statut', 'cancelled')->count();
            $countCompleted = (clone $rdvQuery)->where('statut', 'completed')->count();

            $pct = function ($count) use ($totalRdvJour) {
                return $totalRdvJour > 0 ? round(($count / $totalRdvJour) * 100) : 0;
            };
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="p-4 rounded-lg border border-green-200 bg-green-50">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-green-800">Confirmés</span>
                    <span class="text-sm font-semibold text-green-900">{{ $countConfirmed }}</span>
                </div>
                <div class="w-full h-2 bg-green-100 rounded">
                    <div class="h-2 bg-green-500 rounded" style="width: {{ $pct($countConfirmed) }}%"></div>
                </div>
                <div class="mt-1 text-xs text-green-700">{{ $pct($countConfirmed) }}%</div>
            </div>

            <div class="p-4 rounded-lg border border-yellow-200 bg-yellow-50">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-yellow-800">En attente</span>
                    <span class="text-sm font-semibold text-yellow-900">{{ $countPending }}</span>
                </div>
                <div class="w-full h-2 bg-yellow-100 rounded">
                    <div class="h-2 bg-yellow-500 rounded" style="width: {{ $pct($countPending) }}%"></div>
                </div>
                <div class="mt-1 text-xs text-yellow-700">{{ $pct($countPending) }}%</div>
            </div>

            <div class="p-4 rounded-lg border border-red-200 bg-red-50">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-red-800">Annulés</span>
                    <span class="text-sm font-semibold text-red-900">{{ $countCancelled }}</span>
                </div>
                <div class="w-full h-2 bg-red-100 rounded">
                    <div class="h-2 bg-red-500 rounded" style="width: {{ $pct($countCancelled) }}%"></div>
                </div>
                <div class="mt-1 text-xs text-red-700">{{ $pct($countCancelled) }}%</div>
            </div>

            <div class="p-4 rounded-lg border border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-800">Terminés</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $countCompleted }}</span>
                </div>
                <div class="w-full h-2 bg-gray-200 rounded">
                    <div class="h-2 bg-gray-600 rounded" style="width: {{ $pct($countCompleted) }}%"></div>
                </div>
                <div class="mt-1 text-xs text-gray-700">{{ $pct($countCompleted) }}%</div>
            </div>
        </div>

        <div class="mt-4 text-sm text-gray-600">
            Total rendez-vous aujourd'hui: <span class="font-semibold text-gray-900">{{ $totalRdvJour }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Rendez-vous récents -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Rendez-vous récents</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($rendez_vous_recents as $rdv)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-calendar text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $rdv->patient->name ?? 'Patient inconnu' }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    avec Dr. {{ $rdv->medecin->name ?? 'Médecin inconnu' }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">
                                {{ \Carbon\Carbon::parse($rdv->date_debut)->format('d/m H:i') }}
                            </p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($rdv->statut === 'confirmed') bg-green-100 text-green-800
                                @elseif($rdv->statut === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($rdv->statut === 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($rdv->statut) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">Aucun rendez-vous récent</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Médecins les plus actifs -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Médecins les plus actifs</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($medecins_actifs as $medecin)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user-md text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $medecin->name }}</p>
                                <p class="text-xs text-gray-500">{{ $medecin->specialite ?? 'Spécialité non définie' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">{{ $medecin->rendez_vous_count }}</p>
                            <p class="text-xs text-gray-500">rendez-vous</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">Aucun médecin trouvé</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Actions rapides</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.medecins.create') }}"
               class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                <i class="fas fa-plus-circle text-blue-600 text-xl mr-3"></i>
                <div>
                    <p class="font-medium text-blue-900">Ajouter un médecin</p>
                    <p class="text-sm text-blue-700">Créer un nouveau compte médecin</p>
                </div>
            </a>

            <a href="{{ route('admin.utilisateurs') }}"
               class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <i class="fas fa-users text-green-600 text-xl mr-3"></i>
                <div>
                    <p class="font-medium text-green-900">Gérer les utilisateurs</p>
                    <p class="text-sm text-green-700">Voir tous les utilisateurs</p>
                </div>
            </a>

            <a href="{{ route('admin.statistiques') }}"
               class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                <i class="fas fa-chart-bar text-purple-600 text-xl mr-3"></i>
                <div>
                    <p class="font-medium text-purple-900">Voir les statistiques</p>
                    <p class="text-sm text-purple-700">Analyses détaillées</p>
                </div>
            </a>
        </div>
    </div>
</div>

@push('scripts')
{{-- Intentionally left blank: chart removed to prevent looping behavior --}}
@endpush
@endsection
