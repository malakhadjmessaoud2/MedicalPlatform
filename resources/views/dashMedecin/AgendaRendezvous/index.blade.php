@extends('dashMedecin.layout')

@section('content')
{{-- <div class="container">
    <h1>Créneaux disponibles pour le Dr. {{ $medecin->nom }} {{ $medecin->prenom }}</h1>
    <form action="{{ route('medecin.creneaux-disponibles', $medecin) }}" method="GET">
        <label for="date">Sélectionnez une date :</label>
        <input type="date" name="date" id="date" value="{{ $selectedDate ?? '' }}">
        <button type="submit">Voir les créneaux</button>
    </form>

    @if(isset($creneauxDisponibles))
        <h2>Créneaux disponibles le {{ $selectedDate }}</h2>
        <ul>
            @foreach($creneauxDisponibles as $creneau)
                <li>{{ $creneau['heure_debut'] }} - {{ $creneau['heure_fin'] }}</li>
            @endforeach
        </ul>
    @else
        <p>Aucun créneau disponible pour cette date.</p>
    @endif
</div> --}}
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-start mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Agenda des rendez-vous</h1>
            <p class="text-gray-600">Gérez vos rendez-vous et consultations</p>
        </div>

        <div class="flex flex-wrap gap-2">
            <button id="btn-mini-calendar" class="px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-gray-700 hover:bg-gray-50 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Calendrier
            </button>

            <button onclick="openAddRdvModal()" class="px-4 py-2 bg-[#b9ff66] text-black rounded-lg hover:bg-[#a8eb5f] transition-colors flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nouveau rendez-vous
            </button>
        </div>
    </div>

    <!-- Statistiques des rendez-vous -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total</p>
                    <p class="text-xl font-semibold">{{ $stats['total'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Confirmés</p>
                    <p class="text-xl font-semibold">{{ $stats['confirmes'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-500 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">En attente</p>
                    <p class="text-xl font-semibold">{{ $stats['en_attente'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-500 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Annulés</p>
                    <p class="text-xl font-semibold">{{ $stats['annules'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres du calendrier -->
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-6">
        <h3 class="text-lg font-medium mb-3">Filtres</h3>
        <form id="calendar-filters" class="flex flex-wrap gap-6">
            <div>
                <p class="text-sm font-medium mb-2">Type</p>
                <div class="flex flex-wrap gap-3">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="type" value="consultation" class="form-checkbox h-4 w-4 text-blue-600" checked>
                        <span class="ml-2 text-sm text-gray-700">Consultation</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="type" value="suivi" class="form-checkbox h-4 w-4 text-green-600" checked>
                        <span class="ml-2 text-sm text-gray-700">Suivi</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="type" value="urgence" class="form-checkbox h-4 w-4 text-red-600" checked>
                        <span class="ml-2 text-sm text-gray-700">Urgence</span>
                    </label>
                </div>
            </div>

            <div>
                <p class="text-sm font-medium mb-2">Statut</p>
                <div class="flex flex-wrap gap-3">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="statut" value="confirmé" class="form-checkbox h-4 w-4 text-green-600" checked>
                        <span class="ml-2 text-sm text-gray-700">Confirmé</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="statut" value="en_attente" class="form-checkbox h-4 w-4 text-yellow-600" checked>
                        <span class="ml-2 text-sm text-gray-700">En attente</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="statut" value="annulé" class="form-checkbox h-4 w-4 text-red-600" checked>
                        <span class="ml-2 text-sm text-gray-700">Annulé</span>
                    </label>
                </div>
            </div>
        </form>
    </div>

    <!-- Calendrier FullCalendar -->
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
        <div id="calendar" class="fc-theme-standard"></div>
    </div>

    <!-- Mini-calendrier pour la navigation rapide -->
    <div id="mini-calendar" class="hidden absolute right-4 top-24 bg-white p-4 rounded-lg shadow-lg border border-gray-200 z-10">
        <!-- Le contenu du mini-calendrier sera généré par JS -->
    </div>
</div>

<!-- Modal pour ajouter un rendez-vous -->
<div id="modal-add-rdv" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-xl font-bold">Nouveau rendez-vous</h3>
                <button onclick="document.getElementById('modal-add-rdv').classList.add('hidden')"
                        class="p-1 hover:bg-gray-100 rounded-full transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form action="{{ route('rendez-vous.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="titre" class="block text-sm font-medium text-gray-700 mb-1">Titre</label>
                    <input type="text" id="titre" name="titre" required value="{{ old('titre') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#b9ff66] focus:ring focus:ring-[#b9ff66] focus:ring-opacity-50">
                </div>

                <div>
                    <label for="patient_id" class="block text-sm font-medium text-gray-700 mb-1">Patient</label>
                    <select id="patient_id" name="patient_id" required
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#b9ff66] focus:ring focus:ring-[#b9ff66] focus:ring-opacity-50">
                        <option value="">Sélectionner un patient</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                {{ $patient->nom }} {{ $patient->prenom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <input type="date" id="date_debut" name="date_debut" required value="{{ old('date_debut', $selectedDate->format('Y-m-d')) }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#b9ff66] focus:ring focus:ring-[#b9ff66] focus:ring-opacity-50">
                </div>

                <div>
                    <label for="heure_debut" class="block text-sm font-medium text-gray-700 mb-1">Heure de début</label>
                    <select id="heure_debut" name="heure_debut" required
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#b9ff66] focus:ring focus:ring-[#b9ff66] focus:ring-opacity-50">
                        @foreach($heuresTravail as $heure)
                            <option value="{{ $heure }}" {{ old('heure_debut') == $heure ? 'selected' : '' }}>
                                {{ $heure }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="duree" class="block text-sm font-medium text-gray-700 mb-1">Durée (minutes)</label>
                    <select id="duree" name="duree" required
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#b9ff66] focus:ring focus:ring-[#b9ff66] focus:ring-opacity-50">
                        <option value="15" {{ old('duree') == 15 ? 'selected' : '' }}>15 minutes</option>
                        <option value="30" {{ old('duree') == 30 ? 'selected' : '' }}>30 minutes</option>
                        <option value="45" {{ old('duree') == 45 ? 'selected' : '' }}>45 minutes</option>
                        <option value="60" {{ old('duree') == 60 ? 'selected' : '' }}>1 heure</option>
                        <option value="90" {{ old('duree') == 90 ? 'selected' : '' }}>1 heure 30</option>
                        <option value="120" {{ old('duree') == 120 ? 'selected' : '' }}>2 heures</option>
                    </select>
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select id="type" name="type" required
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#b9ff66] focus:ring focus:ring-[#b9ff66] focus:ring-opacity-50">
                        <option value="consultation" {{ old('type') == 'consultation' ? 'selected' : '' }}>Consultation</option>
                        <option value="suivi" {{ old('type') == 'suivi' ? 'selected' : '' }}>Suivi</option>
                        <option value="urgence" {{ old('type') == 'urgence' ? 'selected' : '' }}>Urgence</option>
                    </select>
                </div>

                <div>
                    <label for="statut" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select id="statut" name="statut" required
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#b9ff66] focus:ring focus:ring-[#b9ff66] focus:ring-opacity-50">
                        <option value="confirmé" {{ old('statut') == 'confirmé' ? 'selected' : '' }}>Confirmé</option>
                        <option value="en_attente" {{ old('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="annulé" {{ old('statut') == 'annulé' ? 'selected' : '' }}>Annulé</option>
                    </select>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#b9ff66] focus:ring focus:ring-[#b9ff66] focus:ring-opacity-50">{{ old('description') }}</textarea>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('modal-add-rdv').classList.add('hidden')"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">
                        Annuler
                    </button>

                    <button type="submit" class="px-4 py-2 bg-[#b9ff66] text-black rounded-lg hover:bg-[#a8eb5f] transition-colors">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modals pour afficher et modifier les rendez-vous existants -->
@foreach($rendezVous as $rdv)
    <!-- Modal pour afficher les détails d'un rendez-vous -->
    <div id="modal-rdv-{{ $rdv->id }}" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-xl font-bold">{{ $rdv->titre }}</h3>
                    <button onclick="document.getElementById('modal-rdv-{{ $rdv->id }}').classList.add('hidden')"
                            class="p-1 hover:bg-gray-100 rounded-full transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <span class="inline-block px-2 py-1 rounded-full text-xs font-medium
                                    {{ $rdv->type == 'consultation' ? 'bg-blue-100 text-blue-800' :
                                       ($rdv->type == 'suivi' ? 'bg-green-100 text-green-800' :
                                        'bg-red-100 text-red-800') }}">
                            {{ ucfirst($rdv->type) }}
                        </span>

                        <span class="inline-block px-2 py-1 rounded-full text-xs font-medium ml-2
                                    {{ $rdv->statut == 'confirmé' ? 'bg-green-100 text-green-800' :
                                       ($rdv->statut == 'en_attente' ? 'bg-yellow-100 text-yellow-800' :
                                        'bg-red-100 text-red-800') }}">
                            {{ ucfirst($rdv->statut) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Patient</p>
                            <p class="font-medium">{{ $rdv->patient->nom }} {{ $rdv->patient->prenom }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">Date et heure</p>
                            <p class="font-medium">
                                {{ Carbon\Carbon::parse($rdv->date_debut)->format('d/m/Y') }}<br>
                                {{ Carbon\Carbon::parse($rdv->date_debut)->format('H:i') }} -
                                {{ Carbon\Carbon::parse($rdv->date_fin)->format('H:i') }}
                            </p>
                        </div>
                    </div>

                    @if($rdv->description)
                        <div>
                            <p class="text-sm text-gray-500">Description</p>
                            <p class="mt-1">{{ $rdv->description }}</p>
                        </div>
                    @endif

                    <div class="flex justify-end gap-3 pt-4">
                        <form action="{{ route('rendez-vous.destroy', $rdv->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce rendez-vous ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                                Supprimer
                            </button>
                        </form>

                        <button type="button"
                                onclick="document.getElementById('modal-rdv-{{ $rdv->id }}').classList.add('hidden'); document.getElementById('modal-edit-rdv-{{ $rdv->id }}').classList.remove('hidden');"
                                class="px-4 py-2 bg-[#b9ff66] text-black rounded-lg hover:bg-[#a8eb5f] transition-colors">
                            Modifier
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour modifier un rendez-vous -->
    <div id="modal-edit-rdv-{{ $rdv->id }}" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-xl font-bold">Modifier le rendez-vous</h3>
                    <button type="button" onclick="document.getElementById('modal-edit-rdv-{{ $rdv->id }}').classList.add('hidden')"
                            class="p-1 hover:bg-gray-100 rounded-full transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form action="{{ route('medecin.rendez-vous.update', $rdv->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="edit_titre_{{ $rdv->id }}" class="block text-sm font-medium text-gray-700 mb-1">Titre</label>
                        <input type="text" id="edit_titre_{{ $rdv->id }}" name="titre" required value="{{ $rdv->titre }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#b9ff66] focus:ring focus:ring-[#b9ff66] focus:ring-opacity-50">
                    </div>

                    <div>
                        <label for="edit_patient_id_{{ $rdv->id }}" class="block text-sm font-medium text-gray-700 mb-1">Patient</label>
                        <select id="edit_patient_id_{{ $rdv->id }}" name="patient_id" required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#b9ff66] focus:ring focus:ring-[#b9ff66] focus:ring-opacity-50">
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ $rdv->patient_id == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->nom }} {{ $patient->prenom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="edit_date_debut_{{ $rdv->id }}" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                        <input type="date" id="edit_date_debut_{{ $rdv->id }}" name="date_debut" required
                               value="{{ Carbon\Carbon::parse($rdv->date_debut)->format('Y-m-d') }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#b9ff66] focus:ring focus:ring-[#b9ff66] focus:ring-opacity-50">
                    </div>

                    <div>
                        <label for="edit_heure_debut_{{ $rdv->id }}" class="block text-sm font-medium text-gray-700 mb-1">Heure de début</label>
                        <select id="edit_heure_debut_{{ $rdv->id }}" name="heure_debut" required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#b9ff66] focus:ring focus:ring-[#b9ff66] focus:ring-opacity-50">
                            @foreach($heuresTravail as $heure)
                                <option value="{{ $heure }}" {{ Carbon\Carbon::parse($rdv->date_debut)->format('H:i') == $heure ? 'selected' : '' }}>
                                    {{ $heure }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="edit_duree_{{ $rdv->id }}" class="block text-sm font-medium text-gray-700 mb-1">Durée (minutes)</label>
                        <select id="edit_duree_{{ $rdv->id }}" name="duree" required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#b9ff66] focus:ring focus:ring-[#b9ff66] focus:ring-opacity-50">
                            @php
                                $dureeMinutes = Carbon\Carbon::parse($rdv->date_fin)->diffInMinutes(Carbon\Carbon::parse($rdv->date_debut));
                            @endphp
                            <option value="15" {{ $dureeMinutes == 15 ? 'selected' : '' }}>15 minutes</option>
                            <option value="30" {{ $dureeMinutes == 30 ? 'selected' : '' }}>30 minutes</option>
                            <option value="45" {{ $dureeMinutes == 45 ? 'selected' : '' }}>45 minutes</option>
                            <option value="60" {{ $dureeMinutes == 60 ? 'selected' : '' }}>1 heure</option>
                            <option value="90" {{ $dureeMinutes == 90 ? 'selected' : '' }}>1 heure 30</option>
                            <option value="120" {{ $dureeMinutes == 120 ? 'selected' : '' }}>2 heures</option>
                        </select>
                    </div>

                    <div>
                        <label for="edit_type_{{ $rdv->id }}" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select id="edit_type_{{ $rdv->id }}" name="type" required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#b9ff66] focus:ring focus:ring-[#b9ff66] focus:ring-opacity-50">
                            <option value="consultation" {{ $rdv->type == 'consultation' ? 'selected' : '' }}>Consultation</option>
                            <option value="suivi" {{ $rdv->type == 'suivi' ? 'selected' : '' }}>Suivi</option>
                            <option value="urgence" {{ $rdv->type == 'urgence' ? 'selected' : '' }}>Urgence</option>
                        </select>
                    </div>

                    <div>
                        <label for="edit_statut_{{ $rdv->id }}" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                        <select id="edit_statut_{{ $rdv->id }}" name="statut" required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#b9ff66] focus:ring focus:ring-[#b9ff66] focus:ring-opacity-50">
                            <option value="confirmé" {{ $rdv->statut == 'confirmé' ? 'selected' : '' }}>Confirmé</option>
                            <option value="en_attente" {{ $rdv->statut == 'en_attente' ? 'selected' : '' }}>En attente</option>
                            <option value="annulé" {{ $rdv->statut == 'annulé' ? 'selected' : '' }}>Annulé</option>
                        </select>
                    </div>

                    <div>
                        <label for="edit_description_{{ $rdv->id }}" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="edit_description_{{ $rdv->id }}" name="description" rows="3"
                                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#b9ff66] focus:ring focus:ring-[#b9ff66] focus:ring-opacity-50">{{ $rdv->description }}</textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" onclick="document.getElementById('modal-edit-rdv-{{ $rdv->id }}').classList.add('hidden')"
                                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">
                            Annuler
                        </button>

                        <button type="submit" class="px-4 py-2 bg-[#b9ff66] text-black rounded-lg hover:bg-[#a8eb5f] transition-colors">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<!-- Affichage des messages de notification -->
@if(session('success'))
    <div class="fixed bottom-4 right-4 bg-green-100 text-green-800 border-l-4 border-green-500 px-6 py-3 rounded-lg shadow-lg flex items-center"
         x-data="{ show: true }"
         x-init="setTimeout(() => show = false, 5000)"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="fixed bottom-4 right-4 bg-red-100 text-red-800 border-l-4 border-red-500 px-6 py-3 rounded-lg shadow-lg flex items-center"
         x-data="{ show: true }"
         x-init="setTimeout(() => show = false, 5000)"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
        {{ session('error') }}
    </div>
@endif

@endsection

@push('scripts')
<script>
    // Initialisation du mini-calendrier
    document.addEventListener('DOMContentLoaded', function() {
        const miniCalendarEl = document.getElementById('mini-calendar');
        if (!miniCalendarEl) return;

        const miniCalendar = new Calendar(miniCalendarEl, {
            plugins: [dayGridPlugin, interactionPlugin],
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next',
                center: 'title',
                right: ''
            },
            height: 'auto',
            locale: frLocale,
            selectable: true,
            select: function(info) {
                // Rediriger vers la vue jour avec la date sélectionnée
                if (window.calendar) {
                    window.calendar.gotoDate(info.start);
                    window.calendar.changeView('timeGridDay');
                }
                miniCalendarEl.classList.add('hidden');
            }
        });

        miniCalendar.render();

        // Afficher/masquer le mini-calendrier
        const btnMiniCalendar = document.getElementById('btn-mini-calendar');
        if (btnMiniCalendar) {
            btnMiniCalendar.addEventListener('click', function() {
                miniCalendarEl.classList.toggle('hidden');
            });
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            timeZone: 'Africa/Tunis',
            locale: frLocale,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            slotMinTime: '08:00:00',
            slotMaxTime: '19:00:00',
            allDaySlot: false,
            slotDuration: '00:30:00',
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            },
            events: '/api/evenements',
            eventDidMount: function(info) {
                info.el.title = info.event.title + '\n' +
                               info.event.extendedProps.heure_debut + ' - ' +
                               info.event.extendedProps.heure_fin;
            }
        });

        calendar.render();
    });
</script>
@endpush
