@extends('dashMedecin.layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-start mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Agenda des rendez-vous</h1>
            <p class="text-gray-600">Gérez vos rendez-vous</p>
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

            <form action="{{ route('medecin.rendez-vous.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Section 1: Informations de base -->
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2">
                        📋 Informations de base
                    </h4>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                            <span class="text-red-500">*</span> Description du rendez-vous
                        </label>
                        <textarea id="description" name="description" rows="3" required
                                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#b9ff66] focus:ring focus:ring-[#b9ff66] focus:ring-opacity-50"
                                  placeholder="Ex: Consultation de routine, Examen cardiologique, Suivi post-opératoire...">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label for="patient_id" class="block text-sm font-medium text-gray-700 mb-1">
                            <span class="text-red-500">*</span> Patient
                        </label>
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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">
                                <span class="text-red-500">*</span> Type de rendez-vous
                            </label>
                            <select id="type" name="type" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#b9ff66] focus:ring focus:ring-[#b9ff66] focus:ring-opacity-50">
                                <option value="consultation" {{ old('type') == 'consultation' ? 'selected' : '' }}>🔵 Consultation</option>
                                <option value="examen" {{ old('type') == 'examen' ? 'selected' : '' }}>🟢 Examen</option>
                                <option value="intervention" {{ old('type') == 'intervention' ? 'selected' : '' }}>🔴 Intervention</option>
                                <option value="autre" {{ old('type') == 'autre' ? 'selected' : '' }}>🟡 Autre</option>
                            </select>
                        </div>

                        <div>
                            <label for="statut" class="block text-sm font-medium text-gray-700 mb-1">
                                <span class="text-red-500">*</span> Statut
                            </label>
                            <select id="statut" name="statut" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#b9ff66] focus:ring focus:ring-[#b9ff66] focus:ring-opacity-50">
                                <option value="confirmed" {{ old('statut') == 'confirmed' ? 'selected' : '' }}>✅ Confirmé</option>
                                <option value="pending" {{ old('statut') == 'pending' ? 'selected' : '' }}>⏱ En attente</option>
                                <option value="cancelled" {{ old('statut') == 'cancelled' ? 'selected' : '' }}>❌ Annulé</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Planning -->
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2">
                        📅 Planning
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-1">
                                <span class="text-red-500">*</span> Date
                            </label>
                            <input type="date" id="date_debut" name="date_debut" required
                                   value="{{ old('date_debut', $selectedDate->format('Y-m-d')) }}"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#b9ff66] focus:ring focus:ring-[#b9ff66] focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="heure_debut" class="block text-sm font-medium text-gray-700 mb-1">
                                <span class="text-red-500">*</span> Heure de début
                            </label>
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
                            <label for="duree" class="block text-sm font-medium text-gray-700 mb-1">
                                <span class="text-red-500">*</span> Durée
                            </label>
                            <select id="duree" name="duree" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#b9ff66] focus:ring focus:ring-[#b9ff66] focus:ring-opacity-50">
                                <option value="15" {{ old('duree') == 15 ? 'selected' : '' }}>15 min</option>
                                <option value="30" {{ old('duree') == 30 ? 'selected' : '' }}>30 min</option>
                                <option value="45" {{ old('duree') == 45 ? 'selected' : '' }}>45 min</option>
                                <option value="60" {{ old('duree') == 60 ? 'selected' : '' }}>1 heure</option>
                                <option value="90" {{ old('duree') == 90 ? 'selected' : '' }}>1h 30</option>
                                <option value="120" {{ old('duree') == 120 ? 'selected' : '' }}>2 heures</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Consultation en ligne -->
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2">
                        🌐 Consultation en ligne
                    </h4>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h5 class="text-sm font-medium text-blue-800 mb-2">Génération automatique de lien Jitsi</h5>
                                <p class="text-sm text-blue-700 mb-3">
                                    Un lien de consultation Jitsi sera automatiquement généré lors de la création du rendez-vous.
                                </p>

                                <div class="flex items-center space-x-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" id="generer_lien_auto" name="generer_lien_auto" value="1"
                                               {{ old('generer_lien_auto') ? 'checked' : '' }} checked
                                               class="rounded border-gray-300 text-[#b9ff66] focus:ring-[#b9ff66]">
                                        <span class="ml-2 text-sm text-blue-800">Générer automatiquement</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="lien_manuel_section" class="hidden">
                        <label for="lien_en_ligne" class="block text-sm font-medium text-gray-700 mb-1">
                            Lien personnalisé (optionnel)
                        </label>
                        <div class="flex space-x-2">
                            <input type="url" id="lien_en_ligne" name="lien_en_ligne" value="{{ old('lien_en_ligne') }}"
                                   class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-[#b9ff66] focus:ring focus:ring-[#b9ff66] focus:ring-opacity-50"
                                   placeholder="https://meet.google.com/... ou https://jitsi.meet/...">
                            <button type="button" id="generer_lien_jitsi"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                Générer Jitsi
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            Laissez vide pour utiliser le lien automatique, ou saisissez un lien personnalisé
                        </p>
                    </div>
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
@foreach($tousRendezVous as $rdv)
    <!-- Modal pour afficher les détails d'un rendez-vous -->
    <div id="modal-rdv-{{ $rdv->id }}" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-xl font-bold">Rendez-vous - {{ $rdv->patient->nom }} {{ $rdv->patient->prenom }}</h3>
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
                                       ($rdv->type == 'examen' ? 'bg-green-100 text-green-800' :
                                       ($rdv->type == 'intervention' ? 'bg-red-100 text-red-800' :
                                        'bg-gray-100 text-gray-800')) }}">
                            {{ $rdv->type == 'consultation' ? 'Consultation' :
                               ($rdv->type == 'examen' ? 'Examen' :
                               ($rdv->type == 'intervention' ? 'Intervention' : 'Autre')) }}
                        </span>

                        <span class="inline-block px-2 py-1 rounded-full text-xs font-medium ml-2
                                    {{ $rdv->statut == 'confirmed' ? 'bg-green-100 text-green-800' :
                                       ($rdv->statut == 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                        'bg-red-100 text-red-800') }}">
                            {{ $rdv->statut == 'confirmed' ? 'Confirmé' : ($rdv->statut == 'pending' ? 'En attente' : 'Annulé') }}
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
                                {{ Carbon\Carbon::parse($rdv->date_debut)->translatedFormat('d F Y') }}<br>
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

                    @if($rdv->lien_en_ligne)
                        <div>
                            <p class="text-sm text-gray-500">Lien en ligne</p>
                            <a href="{{ $rdv->lien_en_ligne }}" target="_blank"
                               class="text-blue-600 hover:text-blue-800 underline break-all">
                                {{ $rdv->lien_en_ligne }}
                            </a>
                        </div>
                    @endif

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" onclick="openDeleteModal({{ $rdv->id }})"
                                class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                                Supprimer
                            </button>

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

                <form action="{{ route('medecin.rendez-vous.update', $rdv->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Section 1: Informations de base -->
                    <div class="space-y-4">
                        <h4 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2">📋 Informations de base</h4>

                        <div>
                            <label for="edit_description_{{ $rdv->id }}" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea id="edit_description_{{ $rdv->id }}" name="description" rows="3" required
                                      class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#b9ff66] focus:ring focus:ring-[#b9ff66] focus:ring-opacity-50">{{ $rdv->description }}</textarea>
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

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="edit_type_{{ $rdv->id }}" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                                <select id="edit_type_{{ $rdv->id }}" name="type" required
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#b9ff66] focus:ring focus:ring-[#b9ff66] focus:ring-opacity-50">
                                    <option value="consultation" {{ $rdv->type == 'consultation' ? 'selected' : '' }}>🔵 Consultation</option>
                                    <option value="examen" {{ $rdv->type == 'examen' ? 'selected' : '' }}>🟢 Examen</option>
                                    <option value="intervention" {{ $rdv->type == 'intervention' ? 'selected' : '' }}>🔴 Intervention</option>
                                    <option value="autre" {{ $rdv->type == 'autre' ? 'selected' : '' }}>🟡 Autre</option>
                                </select>
                            </div>

                            <div>
                                <label for="edit_statut_{{ $rdv->id }}" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                                <select id="edit_statut_{{ $rdv->id }}" name="statut" required
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#b9ff66] focus:ring focus:ring-[#b9ff66] focus:ring-opacity-50">
                                    <option value="confirmed" {{ $rdv->statut == 'confirmed' ? 'selected' : '' }}>✅ Confirmé</option>
                                    <option value="pending" {{ $rdv->statut == 'pending' ? 'selected' : '' }}>⏱ En attente</option>
                                    <option value="cancelled" {{ $rdv->statut == 'cancelled' ? 'selected' : '' }}>❌ Annulé</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Planning -->
                    <div class="space-y-4">
                        <h4 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2">📅 Planning</h4>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                                <label for="edit_duree_{{ $rdv->id }}" class="block text-sm font-medium text-gray-700 mb-1">Durée</label>
                                <select id="edit_duree_{{ $rdv->id }}" name="duree" required
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#b9ff66] focus:ring focus:ring-[#b9ff66] focus:ring-opacity-50">
                                    @php
                                        $dureeMinutes = Carbon\Carbon::parse($rdv->date_fin)->diffInMinutes(Carbon\Carbon::parse($rdv->date_debut));
                                    @endphp
                                    <option value="15" {{ $dureeMinutes == 15 ? 'selected' : '' }}>15 min</option>
                                    <option value="30" {{ $dureeMinutes == 30 ? 'selected' : '' }}>30 min</option>
                                    <option value="45" {{ $dureeMinutes == 45 ? 'selected' : '' }}>45 min</option>
                                    <option value="60" {{ $dureeMinutes == 60 ? 'selected' : '' }}>1 heure</option>
                                    <option value="90" {{ $dureeMinutes == 90 ? 'selected' : '' }}>1h 30</option>
                                    <option value="120" {{ $dureeMinutes == 120 ? 'selected' : '' }}>2 heures</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Consultation en ligne -->
                    <div class="space-y-3">
                        <h4 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2">🌐 Consultation en ligne</h4>

                        @if($rdv->lien_en_ligne)
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="text-sm text-gray-500">Lien Jitsi actuel</p>

                                </div>

                                <!-- Affichage du lien (par défaut) -->
                                <div id="lien-display-{{ $rdv->id }}">
                                    <a href="{{ $rdv->lien_en_ligne }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline break-all">{{ $rdv->lien_en_ligne }}</a>
                                    <button type="button" class="ml-2 px-2 py-1 text-xs bg-gray-600 text-white rounded hover:bg-gray-700" onclick="navigator.clipboard.writeText('{{ $rdv->lien_en_ligne }}');">Copier</button>
                                </div>

                                <!-- Formulaire de modification du lien (caché par défaut) -->
                                <div id="lien-edit-{{ $rdv->id }}" class="hidden">
                                    <input type="url" name="lien_en_ligne" value="{{ $rdv->lien_en_ligne }}"
                                           data-original-value="{{ $rdv->lien_en_ligne }}"
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#b9ff66] focus:ring focus:ring-[#b9ff66] focus:ring-opacity-50"
                                           placeholder="https://meet.jit.si/...">
                                    <div class="flex gap-2 mt-2">
                                        <button type="button" class="px-3 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700" onclick="toggleLienEdit({{ $rdv->id }})">
                                            Valider
                                        </button>
                                        <button type="button" class="px-3 py-1 text-xs bg-red-600 text-white rounded hover:bg-red-700" onclick="resetLienEdit({{ $rdv->id }})">
                                            Annuler
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                                <p class="text-sm text-gray-500 mb-2">Aucun lien Jitsi associé à ce rendez-vous.</p>
                                <input type="url" name="lien_en_ligne" value=""
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#b9ff66] focus:ring focus:ring-[#b9ff66] focus:ring-opacity-50"
                                       placeholder="https://meet.jit.si/... (optionnel)">
                            </div>
                        @endif
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

<!-- Modal de confirmation de suppression -->
<div id="modal-confirm-delete" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4 transform transition-all duration-300 ease-out">
        <div class="p-6">
            <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-100 rounded-full">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>

            <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">
                Confirmer la suppression
            </h3>

            <p class="text-sm text-gray-600 text-center mb-6">
                Êtes-vous sûr de vouloir supprimer ce rendez-vous ?<br>
                <span class="text-red-600 font-medium">Cette action est irréversible.</span>
            </p>

            <div class="flex gap-3 justify-center">
                <button type="button" id="cancel-delete"
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">
                    Annuler
                </button>
                <button type="button" id="confirm-delete"
                        class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                    Supprimer
                </button>
            </div>
        </div>
    </div>
</div>

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
    function openAddRdvModal() {
        const modal = document.getElementById('modal-add-rdv');
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    // Gestion de la génération automatique de lien Jitsi
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🚀 DOM Content Loaded - Initialisation des événements');

        // Vérifier les modals disponibles
        const allEditModals = document.querySelectorAll('[id^="modal-edit-rdv-"]');
        console.log('📋 Modals de modification disponibles:', allEditModals.length);
        allEditModals.forEach(modal => {
            console.log('  -', modal.id);
        });

        // Gestion des boutons onclick pour la modification
        const onclickButtons = document.querySelectorAll('button[onclick*="modal-edit-rdv"]');
        console.log('🔘 Boutons onclick trouvés:', onclickButtons.length);

        onclickButtons.forEach((button, index) => {
            console.log(`📝 Bouton onclick ${index + 1}:`, {
                element: button,
                onclick: button.getAttribute('onclick'),
                classes: button.className
            });

            // Remplacer l'onclick par un gestionnaire d'événements
            const originalOnclick = button.getAttribute('onclick');
            button.removeAttribute('onclick');

            button.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('🎯 Clic sur bouton onclick - Original:', originalOnclick);

                // Extraire l'ID du rendez-vous depuis l'onclick
                const match = originalOnclick.match(/modal-edit-rdv-(\d+)/);
                if (match) {
                    const rdvId = match[1];
                    console.log('🔍 ID extrait:', rdvId);
                    openEditRdvModal(rdvId);
                } else {
                    console.error('❌ Impossible d\'extraire l\'ID du rendez-vous');
                }
            });
        });

        const genererLienAuto = document.getElementById('generer_lien_auto');
        const lienManuelSection = document.getElementById('lien_manuel_section');
        const genererLienJitsi = document.getElementById('generer_lien_jitsi');
        const lienEnLigne = document.getElementById('lien_en_ligne');

        // Gérer l'affichage/masquage de la section lien manuel
        if (genererLienAuto) {
            genererLienAuto.addEventListener('change', function() {
                if (this.checked) {
                    lienManuelSection.classList.add('hidden');
                    lienEnLigne.value = '';
                } else {
                    lienManuelSection.classList.remove('hidden');
                }
            });
        }

        // Générer un lien Jitsi manuellement
        if (genererLienJitsi) {
            genererLienJitsi.addEventListener('click', function() {
                const patientSelect = document.getElementById('patient_id');
                const dateInput = document.getElementById('date_debut');
                const heureInput = document.getElementById('heure_debut');

                if (!patientSelect.value || !dateInput.value || !heureInput.value) {
                    alert('Veuillez d\'abord sélectionner un patient, une date et une heure.');
                    return;
                }

                const patientName = patientSelect.options[patientSelect.selectedIndex].text;
                const date = dateInput.value;
                const heure = heureInput.value;

                // Générer un nom de salle unique
                const roomName = `rdv-${patientName.replace(/\s+/g, '-').toLowerCase()}-${date}-${heure.replace(':', 'h')}`;
                const jitsiUrl = `https://meet.jit.si/${roomName}`;

                lienEnLigne.value = jitsiUrl;

                // Afficher une notification
                showNotification('Lien Jitsi généré avec succès !', 'success');
            });
        }

        // Validation en temps réel du formulaire
        const form = document.querySelector('#modal-add-rdv form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('border-red-500');
                    } else {
                        field.classList.remove('border-red-500');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    showNotification('Veuillez remplir tous les champs obligatoires.', 'error');
                }
            });
        }
    });

    // Fonction pour afficher les notifications
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
            type === 'success' ? 'bg-green-500 text-white' :
            type === 'error' ? 'bg-red-500 text-white' :
            'bg-blue-500 text-white'
        }`;

        notification.innerHTML = `
            <div class="flex items-center gap-2">
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-white hover:text-gray-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        `;

        document.body.appendChild(notification);

        setTimeout(() => notification.classList.remove('translate-x-full'), 100);
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

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

        // Gestion des événements de la modal de suppression
        const cancelDeleteBtn = document.getElementById('cancel-delete');
        const confirmDeleteBtn = document.getElementById('confirm-delete');
        const deleteModal = document.getElementById('modal-confirm-delete');

        console.log('🔧 Initialisation des événements de suppression:', {
            cancelDeleteBtn: !!cancelDeleteBtn,
            confirmDeleteBtn: !!confirmDeleteBtn,
            deleteModal: !!deleteModal
        });

        if (cancelDeleteBtn) {
            cancelDeleteBtn.addEventListener('click', function() {
                console.log('🚫 Annulation de la suppression');
                closeDeleteModal();
            });
        }

        if (confirmDeleteBtn) {
            confirmDeleteBtn.addEventListener('click', function() {
                console.log('✅ Confirmation de la suppression');
                confirmDelete();
            });
        }

        // Fermer la modal en cliquant en dehors
        if (deleteModal) {
            deleteModal.addEventListener('click', function(e) {
                if (e.target === deleteModal) {
                    closeDeleteModal();
                }
            });
        }

        // Fermer la modal avec la touche Échap
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !deleteModal.classList.contains('hidden')) {
                closeDeleteModal();
            }
        });

        // Variables globales pour la suppression
        let currentDeleteForm = null;

        // Fonction pour ouvrir la modal de confirmation de suppression
        window.openDeleteModal = function(rdvId) {
            console.log('🗑️ Ouverture de la modal de suppression pour RDV ID:', rdvId);

            // Stocker l'ID du rendez-vous pour la suppression
            window.currentDeleteRdvId = rdvId;

            // Afficher la modal
            const modal = document.getElementById('modal-confirm-delete');
            if (modal) {
                modal.classList.remove('hidden');

                // Animation d'entrée
                setTimeout(() => {
                    const modalContent = modal.querySelector('div');
                    if (modalContent) {
                        modalContent.style.transform = 'scale(1)';
                        modalContent.style.opacity = '1';
                    }
                }, 10);
            } else {
                console.error('❌ Modal de suppression non trouvée');
            }
        };

        // Fonction pour fermer la modal de suppression
        window.closeDeleteModal = function() {
            const modal = document.getElementById('modal-confirm-delete');
            if (!modal) return;

            const modalContent = modal.querySelector('div');

            // Animation de sortie
            if (modalContent) {
                modalContent.style.transform = 'scale(0.95)';
                modalContent.style.opacity = '0';
            }

            setTimeout(() => {
                modal.classList.add('hidden');
                // Nettoyer la variable globale
                window.currentDeleteRdvId = null;
            }, 300);
        };

        // Fonction pour confirmer la suppression
        window.confirmDelete = function() {
            if (window.currentDeleteRdvId) {
                console.log('✅ Confirmation de suppression pour RDV ID:', window.currentDeleteRdvId);

                // Fermer la modal de confirmation
                closeDeleteModal();

                // Créer un formulaire HTML classique comme dans votre exemple
                const form = document.createElement('form');
                form.action = `/medecin/rendez-vous/${window.currentDeleteRdvId}`;
                form.method = 'POST';
                form.style.display = 'none';

                // Ajouter le token CSRF
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);

                // Ajouter la méthode DELETE
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                // Ajouter le formulaire au DOM et le soumettre
                document.body.appendChild(form);
                form.submit();
            } else {
                console.error('❌ Aucun ID de rendez-vous trouvé pour la suppression');
            }
        };

        // Fonction de test pour la modal de suppression
        window.testDeleteModal = function() {
            console.log('🧪 Test de la modal de suppression');
            openDeleteModal(1); // Test avec ID 1
        };
    });

    // Fonctions pour gérer l'édition du lien de consultation
    window.toggleLienEdit = function(rdvId) {
        const displayDiv = document.getElementById(`lien-display-${rdvId}`);
        const editDiv = document.getElementById(`lien-edit-${rdvId}`);
        const toggleBtn = document.getElementById(`toggle-lien-edit-${rdvId}`);

        if (displayDiv.classList.contains('hidden')) {
            // Passer en mode affichage
            displayDiv.classList.remove('hidden');
            editDiv.classList.add('hidden');
            toggleBtn.textContent = 'Modifier';
        } else {
            // Passer en mode édition
            displayDiv.classList.add('hidden');
            editDiv.classList.remove('hidden');
            toggleBtn.textContent = 'Annuler';
        }
    };

    window.resetLienEdit = function(rdvId) {
        const displayDiv = document.getElementById(`lien-display-${rdvId}`);
        const editDiv = document.getElementById(`lien-edit-${rdvId}`);
        const toggleBtn = document.getElementById(`toggle-lien-edit-${rdvId}`);
        const input = editDiv.querySelector('input[name="lien_en_ligne"]');

        // Réinitialiser la valeur du champ
        const originalValue = input.getAttribute('data-original-value') || '';
        input.value = originalValue;

        // Revenir en mode affichage
        displayDiv.classList.remove('hidden');
        editDiv.classList.add('hidden');
        toggleBtn.textContent = 'Modifier';
    };
</script>
@endpush
