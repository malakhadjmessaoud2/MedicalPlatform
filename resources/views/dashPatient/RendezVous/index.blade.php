@extends('dashPatient.layout')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <!-- Header avec bouton nouveau rendez-vous -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Mes Rendez-vous</h1>
            <p class="text-gray-500 mt-1">Gérez vos consultations médicales en toute simplicité</p>
        </div>
        <a href="{{ route('patient.rendezvousCreate') }}" class="px-4 py-2.5 bg-[#b9ff66] hover:bg-[#a3e55a] text-gray-800 rounded-lg flex items-center gap-2 shadow-sm transition-all duration-200 hover:shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nouveau Rendez-vous
        </a>
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="relative w-full md:w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" placeholder="Rechercher un rendez-vous..." class="pl-10 pr-4 py-2 w-full rounded-lg border border-gray-200 focus:outline-none focus:ring-1 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto">
                <select class="px-3 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-1 focus:ring-[#b9ff66] focus:border-[#b9ff66] text-gray-600">
                    <option value="">Tous les médecins</option>
                    <option value="cardiologue">Cardiologue</option>
                    <option value="dermatologue">Dermatologue</option>
                    <option value="generaliste">Généraliste</option>
                </select>

                <select class="px-3 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-1 focus:ring-[#b9ff66] focus:border-[#b9ff66] text-gray-600">
                    <option value="">Tous les statuts</option>
                    <option value="en_attente">En attente</option>
                    <option value="confirme">Confirmé</option>
                    <option value="annule">Annulé</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Prochain rendez-vous -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-[#b9ff66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Prochain rendez-vous
        </h2>

        @if(isset($prochainRendezVous) && $prochainRendezVous)
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
            <div class="p-5 border-l-4 border-[#b9ff66]">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="bg-[#b9ff66]/10 p-3 rounded-lg">
                            <svg class="w-8 h-8 text-[#92cc52]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg">Dr. {{ $prochainRendezVous->medecin->nom }} {{ $prochainRendezVous->medecin->prenom }}</h3>
                            <p class="text-gray-500">{{ $prochainRendezVous->medecin->specialite }}</p>
                            <div class="flex items-center mt-1 text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $prochainRendezVous->medecin->adresse_cabinet }}
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col items-end">
                        <div class="bg-[#b9ff66]/10 px-3 py-1 rounded-full text-[#92cc52] text-sm font-medium">
                            {{ ucfirst($prochainRendezVous->statut) }}
                        </div>
                        <p class="font-medium text-lg mt-2">{{ $prochainRendezVous->date_debut->timezone('Africa/Tunis')->translatedFormat('d F Y') }}</p>
                        <p class="text-gray-500">
                            {{ $prochainRendezVous->date_debut->timezone('Africa/Tunis')->format('H:i') }} -
                            {{ $prochainRendezVous->date_fin->timezone('Africa/Tunis')->format('H:i') }}
                        </p>

                        <div class="flex gap-2 mt-3">
                            {{-- <button onclick="window.location.href='{{ route('patient.rendez-vous.edit', $prochainRendezVous->id) }}'"
                                    class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                                Modifier
                            </button> --}}
                            <form action="{{ route('patient.rendez-vous.cancel', $prochainRendezVous->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ?')"
                                        class="px-3 py-1.5 text-sm border border-red-200 text-red-500 rounded-lg hover:bg-red-50 transition-colors flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Annuler
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Countdown et rappels -->
                <div class="mt-4 pt-4 border-t border-gray-100 flex flex-col sm:flex-row justify-between gap-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm text-gray-600">Dans {{ $prochainRendezVous->date_debut->diffForHumans() }}</span>
                    </div>

                    <div class="flex gap-3">

                        <button class="text-sm text-gray-600 flex items-center gap-1 hover:text-gray-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Documents requis
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="bg-gray-50 rounded-xl p-6 text-center">
            <p class="text-gray-500">Aucun rendez-vous à venir</p>
            <a href="{{ route('patient.rendezvousCreate') }}" class="inline-block mt-3 px-4 py-2 bg-[#b9ff66] text-black rounded-lg hover:bg-[#a8eb5f] transition-colors">
                Prendre un rendez-vous
            </a>
        </div>
        @endif
    </div>

    <!-- Rendez-vous en attente -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            En attente de confirmation
        </h2>

        @if($rendezVousEnAttente->count() > 0)
            @foreach($rendezVousEnAttente as $rdv)
            <div class="bg-white rounded-xl shadow-sm mb-4">
                <div class="p-4 border-l-4 border-yellow-400 rounded-xl">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="bg-yellow-50 p-3 rounded-lg">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold">Dr. {{ $rdv->medecin->nom }} {{ $rdv->medecin->prenom }}</h3>
                                <p class="text-sm text-gray-500">{{ $rdv->medecin->specialite }}</p>
                                <div class="flex items-center mt-1 text-xs text-gray-500">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    {{ $rdv->medecin->adresse_cabinet }}
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col items-end">
                            <div class="bg-yellow-50 px-3 py-1 rounded-full text-yellow-600 text-sm font-medium">
                                {{ ucfirst($rdv->statut) }}
                            </div>
                            <p class="font-medium">{{ $rdv->date_debut->timezone('Africa/Tunis')->translatedFormat('d F Y') }}</p>
                            <p class="text-sm text-gray-500">
                                {{ $rdv->date_debut->timezone('Africa/Tunis')->format('H:i') }} -
                                {{ $rdv->date_fin->timezone('Africa/Tunis')->format('H:i') }}
                            </p>

                            <div class="flex gap-2 mt-2">
                                <form action="{{ route('patient.rendez-vous.cancel', $rdv->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ?')"
                                            class="px-3 py-1 text-xs border border-red-200 text-red-500 rounded-lg hover:bg-red-50 transition-colors">
                                        Annuler
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="bg-gray-50 rounded-xl p-6 text-center">
                <p class="text-gray-500">Aucun rendez-vous en attente</p>
            </div>
        @endif
    </div>

    <!-- Historique des rendez-vous -->
    <div>
        <h2 class="text-xl font-semibold mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Historique
        </h2>

        @if($historiqueRendezVous->count() > 0)
            <div class="bg-white rounded-xl shadow-sm divide-y">
                @foreach($historiqueRendezVous as $rdv)
                <div class="p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="bg-gray-100 p-3 rounded-lg">
                                <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold">Dr. {{ $rdv->medecin->nom }} {{ $rdv->medecin->prenom }}</h3>
                                <p class="text-sm text-gray-500">{{ $rdv->medecin->specialite }}</p>
                            </div>
                        </div>
                        <div class="flex flex-col items-end">
                            <p class="font-medium">{{ $rdv->date_debut->timezone('Africa/Tunis')->translatedFormat('d F Y') }}</p>
                            <p class="text-sm text-gray-500">
                                {{ $rdv->date_debut->timezone('Africa/Tunis')->format('H:i') }}
                            </p>
                            <span class="px-2 py-1 text-xs rounded-full {{ $rdv->statut === 'confirmé' ? 'bg-green-100 text-green-800' : ($rdv->statut === 'annulé' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst($rdv->statut) }}
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $historiqueRendezVous->links() }}
            </div>
        @else
            <div class="bg-gray-50 rounded-xl p-6 text-center">
                <p class="text-gray-500">Aucun historique de rendez-vous</p>
            </div>
        @endif
    </div>

</div>
@endsection