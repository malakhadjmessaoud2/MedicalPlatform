@extends('dashPatient.layout')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <h1 class="text-2xl font-bold text-gray-800">Confirmation de paiement</h1>
    <p class="text-gray-500">Vous êtes sur le point de payer la consultation pour le rendez-vous suivant :</p>
        <p class="text-gray-500">date et heure : {{ $rendezVous->date_debut }}</p>
    <p class="text-gray-500">Medecin : {{ $rendezVous->medecin->nom }} {{ $rendezVous->medecin->prenom }}</p>
    <p class="text-gray-500">Specialite : {{ $rendezVous->medecin->specialite }}</p>
    <p class="text-gray-500">Prix : {{ $rendezVous->medecin->prixConsultation }} DT</p>
    <button class="px-3 py-1 text-sm border border-blue-200 text-blue-500 rounded-lg hover:bg-blue-50 transition-colors flex items-center gap-1">
        <a href="{{ route('patient.rendez-vous.payment', $rendezVous) }}">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        confirmer le paiement
        </button>

</div>






@endsection
