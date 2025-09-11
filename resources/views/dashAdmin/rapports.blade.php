@extends('dashAdmin.layout')

@section('title', 'Rapports')
@section('page-title', 'Rapports')
@section('page-description', 'Générer et consulter les rapports de la plateforme')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-900">Rapports</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-file-pdf text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Rapport des médecins</h3>
                    <p class="text-sm text-gray-500">Liste complète des médecins</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-chart-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Rapport financier</h3>
                    <p class="text-sm text-gray-500">Revenus et statistiques</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-calendar-alt text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Rapport des rendez-vous</h3>
                    <p class="text-sm text-gray-500">Activité et statistiques</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Fonctionnalités à venir</h2>
        <p class="text-gray-600">Les rapports détaillés seront disponibles prochainement.</p>
    </div>
</div>
@endsection
