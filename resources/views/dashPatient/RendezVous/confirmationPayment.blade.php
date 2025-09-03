@extends('dashPatient.layout')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-3xl mx-auto px-4">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Confirmation de paiement</h1>
            <p class="mt-1 text-gray-600">Vérifiez les détails du rendez-vous avant de procéder au paiement.</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-blue-50 text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M3 11h18M5 19h14a2 2 0 002-2v-6H3v6a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Rendez-vous</p>
                        <p class="font-medium text-gray-800">Paiement de la consultation</p>
                    </div>
                </div>
            </div>

            <div class="px-6 py-6">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="flex items-start gap-3">
                        <div class="mt-1 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M3 11h18M5 19h14a2 2 0 002-2v-6H3v6a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Date et heure</dt>
                            <dd class="mt-0.5 font-medium text-gray-900">{{ $rendezVous->date_debut }}</dd>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="mt-1 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Médecin</dt>
                            <dd class="mt-0.5 font-medium text-gray-900">{{ $rendezVous->medecin->nom }} {{ $rendezVous->medecin->prenom }}</dd>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="mt-1 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L8 21l-1.75-4M15 17l-1.75 4L11.5 17M12 3l1.664 5.127L19 9l-4.168 3.026L14.5 17 12 13.973 9.5 17l-.332-4.974L5 9l5.336-.873L12 3z"/>
                            </svg>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Spécialité</dt>
                            <dd class="mt-0.5 font-medium text-gray-900">{{ $rendezVous->medecin->specialite }}</dd>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="mt-1 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-10v10m8-5a8 8 0 11-16 0 8 8 0 0116 0z"/>
                            </svg>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Prix</dt>
                            <dd class="mt-0.5 font-semibold text-gray-900">{{ $rendezVous->medecin->prixConsultation }} DT</dd>
                        </div>
                    </div>
                </dl>

                <div class="mt-8 flex items-center justify-between gap-4">
                    <a href="{{ route('patient.rendez-vous.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Annuler
                    </a>

                    <a href="{{ route('patient.rendez-vous.payment', $rendezVous) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-blue-600 text-white font-medium shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-10v10m8-5a8 8 0 11-16 0 8 8 0 0116 0z"/>
                        </svg>
                        Confirmer le paiement
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>






@endsection
