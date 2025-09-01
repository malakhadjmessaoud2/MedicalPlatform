@extends('dashMedecin.layout')

@section('content')
<div class="p-4 sm:p-8 bg-[#e4e4e4] min-h-screen">
    <!-- En-tête -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-8">
        <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-8 mb-4 sm:mb-0">
            <h1 class="text-2xl sm:text-4xl font-bold">
                CONSULT<span class="text-[#b9ff66]">A</span>TION #{{ $consultation->id }}
            </h1>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('medecin.consultations.edit', $consultation) }}"
               class="bg-[#b9ff66] hover:bg-[#a8eb5f] text-black rounded-lg px-4 py-2 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Modifier
            </a>
            <a href="{{ route('medecin.consultations.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white rounded-lg px-4 py-2 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour
            </a>
        </div>
    </div>

    <!-- Informations du patient -->
    <div class="bg-white p-6 rounded-[20px] shadow-sm mb-6">
        <div class="flex items-center gap-4">
            @php
                $patient = $consultation->rendezVous->patient ?? ($consultation->dossierMedical->patient ?? null);
                $medecin = $consultation->rendezVous->medecin ?? null;
                $photoUrl = ($patient && $patient->profile_photo_path)
                    ? asset('storage/' . $patient->profile_photo_path)
                    : 'https://ui-avatars.com/api/?name=' . urlencode(($patient->prenom ?? 'P') . ' ' . ($patient->nom ?? ''));
            @endphp
            <img src="{{ $photoUrl }}" class="w-16 h-16 rounded-full">
            <div>
                <h2 class="text-xl font-bold">{{ ($patient->prenom ?? 'Patient') }} {{ ($patient->nom ?? '') }}</h2>
                <p class="text-gray-600">
                    @if(($patient->dateNaissance ?? null))
                        {{ \Carbon\Carbon::parse($patient->dateNaissance)->age }} ans
                    @endif
                    @if(($patient->email ?? null))
                        • {{ $patient->email }}
                    @endif
                </p>
                <p class="text-sm text-gray-500">Consultation du {{ optional($consultation->date)->format('d/m/Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Colonne gauche -->
        <div class="lg:col-span-8 space-y-6">
            <!-- Informations générales -->
            <div class="bg-white p-6 rounded-[20px] shadow-sm">
                <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#b9ff66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Informations Générales
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Type de consultation</p>
                        <div class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-sm bg-[#e8ffd0] text-[#2a3b0f]">
                                {{ $typesConsultation[$consultation->type_consultation] ?? $consultation->type_consultation }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Médecin</p>
                        <p class="font-medium">Dr. {{ $medecin->prenom ?? '' }} {{ $medecin->nom ?? '' }}</p>
                    </div>
                    @if($consultation->rendezVous)
                        <div>
                            <p class="text-sm text-gray-500">Rendez-vous associé</p>
                            <p class="font-medium">#{{ $consultation->rendezVous->id }}</p>
                        </div>
                    @endif
                    @if($consultation->motif_consultation)
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500">Motif de consultation</p>
                            <p class="font-medium">{{ $consultation->motif_consultation }}</p>
                        </div>
                    @endif
                </div>

                @if($consultation->symptomes_aigus || $consultation->debut_symptomes || $consultation->gravite || $consultation->orientation_patient)
                    <div class="mt-6 p-4 bg-gray-50 rounded-xl">
                        <h4 class="font-semibold text-gray-800 mb-3">Détails d'urgence</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($consultation->symptomes_aigus)
                                <div>
                                    <p class="text-sm text-gray-500">Symptômes aigus</p>
                                    <p class="text-gray-700">{{ $consultation->symptomes_aigus }}</p>
                                </div>
                            @endif
                            @if($consultation->debut_symptomes)
                                <div>
                                    <p class="text-sm text-gray-500">Début des symptômes</p>
                                    <p class="text-gray-700">{{ \Carbon\Carbon::parse($consultation->debut_symptomes)->format('d/m/Y') }}</p>
                                </div>
                            @endif
                            @if($consultation->gravite)
                                <div>
                                    <p class="text-sm text-gray-500">Gravité</p>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-sm bg-gray-100 text-gray-800 capitalize">
                                        {{ $consultation->gravite }}
                                    </span>
                                </div>
                            @endif
                            @if($consultation->orientation_patient)
                                <div>
                                    <p class="text-sm text-gray-500">Orientation</p>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-sm bg-blue-50 text-blue-800 capitalize">
                                        {{ $consultation->orientation_patient }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Symptômes -->
            @if($consultation->symptomes)
                <div class="bg-white p-6 rounded-[20px] shadow-sm">
                    <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#b9ff66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Symptômes
                    </h3>
                    <p class="text-gray-700">{{ $consultation->symptomes }}</p>
                </div>
            @endif

            <!-- Paramètres cliniques -->
            @if($consultation->tension_arterielle || $consultation->frequence_cardiaque || $consultation->temperature || $consultation->saturation_o2 || $consultation->score_glasgow)
                <div class="bg-white p-6 rounded-[20px] shadow-sm">
                    <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#b9ff66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        Paramètres Cliniques
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @if($consultation->tension_arterielle)
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500">Tension artérielle</p>
                                <p class="font-semibold text-gray-800">{{ $consultation->tension_arterielle }}</p>
                            </div>
                        @endif
                        @if($consultation->frequence_cardiaque)
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500">Fréquence cardiaque</p>
                                <p class="font-semibold text-gray-800">{{ $consultation->frequence_cardiaque }} bpm</p>
                            </div>
                        @endif
                        @if($consultation->temperature)
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500">Température</p>
                                <p class="font-semibold text-gray-800">{{ $consultation->temperature }} °C</p>
                            </div>
                        @endif
                        @if($consultation->saturation_o2)
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500">Saturation O2</p>
                                <p class="font-semibold text-gray-800">{{ $consultation->saturation_o2 }} %</p>
                            </div>
                        @endif
                        @if($consultation->score_glasgow)
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500">Score Glasgow</p>
                                <p class="font-semibold text-gray-800">{{ $consultation->score_glasgow }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Mesures physiques -->
            @if($consultation->poids || $consultation->taille || $consultation->imc)
                <div class="bg-white p-6 rounded-[20px] shadow-sm">
                    <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#b9ff66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Mesures Physiques
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @if($consultation->poids)
                            <div>
                                <p class="text-sm text-gray-500">Poids</p>
                                <p class="font-medium">{{ $consultation->poids }} kg</p>
                            </div>
                        @endif
                        @if($consultation->taille)
                            <div>
                                <p class="text-sm text-gray-500">Taille</p>
                                <p class="font-medium">{{ $consultation->taille }} cm</p>
                            </div>
                        @endif
                        @if($consultation->imc)
                            <div>
                                <p class="text-sm text-gray-500">IMC</p>
                                <p class="font-medium">{{ $consultation->imc }} kg/m²</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if($consultation->examen_physique || $consultation->diagnostic_presume || $consultation->medicaments_prescrits || $consultation->propositions_suivi || $consultation->instructions_particulieres)
                <div class="bg-white p-6 rounded-[20px] shadow-sm">
                    <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#b9ff66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Examen, Diagnostic et Plan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($consultation->examen_physique)
                            <div>
                                <p class="text-sm text-gray-500">Examen clinique</p>
                                <p class="text-gray-700">{{ $consultation->examen_physique }}</p>
                            </div>
                        @endif
                        @if($consultation->diagnostic_presume)
                            <div>
                                <p class="text-sm text-gray-500">Diagnostic présumé</p>
                                <p class="text-gray-700">{{ $consultation->diagnostic_presume }}</p>
                            </div>
                        @endif
                    </div>
                    @if($consultation->medicaments_prescrits || $consultation->propositions_suivi || $consultation->instructions_particulieres)
                        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if($consultation->medicaments_prescrits)
                                <div>
                                    <p class="text-sm text-gray-500">Médicaments prescrits</p>
                                    <p class="text-gray-700 whitespace-pre-line">{{ $consultation->medicaments_prescrits }}</p>
                                </div>
                            @endif
                            @if($consultation->propositions_suivi)
                                <div>
                                    <p class="text-sm text-gray-500">Propositions de suivi</p>
                                    <p class="text-gray-700">{{ $consultation->propositions_suivi }}</p>
                                </div>
                            @endif
                            @if($consultation->instructions_particulieres)
                                <div class="md:col-span-2">
                                    <p class="text-sm text-gray-500">Instructions particulières</p>
                                    <p class="text-gray-700 whitespace-pre-line">{{ $consultation->instructions_particulieres }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

            <!-- Habitudes de vie (pour consultations de suivi) -->
            @if($consultation->habitudes_vie || $consultation->traitement_actuel || $consultation->evolution_symptomes || $consultation->effets_secondaires || $consultation->examens_controle)
                <div class="bg-white p-6 rounded-[20px] shadow-sm">
                    <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#b9ff66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        Habitudes de Vie et Suivi
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($consultation->habitudes_vie)
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500">Habitudes de vie</p>
                                <p class="text-gray-700">{{ $consultation->habitudes_vie }}</p>
                            </div>
                        @endif
                        @if($consultation->traitement_actuel)
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500">Traitement actuel</p>
                                <p class="text-gray-700">{{ $consultation->traitement_actuel }}</p>
                            </div>
                        @endif
                        @if($consultation->evolution_symptomes)
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500">Évolution des symptômes</p>
                                <p class="text-gray-700">{{ $consultation->evolution_symptomes }}</p>
                            </div>
                        @endif
                        @if($consultation->effets_secondaires)
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500">Effets secondaires</p>
                                <p class="text-gray-700">{{ $consultation->effets_secondaires }}</p>
                            </div>
                        @endif
                        @if($consultation->examens_controle)
                            <div class="p-3 bg-gray-50 rounded-lg md:col-span-2">
                                <p class="text-xs text-gray-500">Examens de contrôle</p>
                                <p class="text-gray-700">{{ $consultation->examens_controle }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Colonne droite -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Résumé -->
            <div class="bg-white p-6 rounded-[20px] shadow-sm">
                <h3 class="text-lg font-bold mb-4">Résumé</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Date</span>
                        <span class="font-medium">{{ \Carbon\Carbon::parse($consultation->date_consultation)->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Type</span>
                        <span class="font-medium">{{ $typesConsultation[$consultation->type_consultation] ?? $consultation->type_consultation }}</span>
                    </div>
                    @if($consultation->orientation_patient)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Orientation</span>
                            <span class="font-medium">{{ ucfirst($consultation->orientation_patient) }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-white p-6 rounded-[20px] shadow-sm">
                <h3 class="text-lg font-bold mb-4">Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('medecin.consultations.edit', $consultation) }}"
                       class="w-full bg-[#b9ff66] hover:bg-[#a8eb5f] text-black rounded-lg px-4 py-2 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Modifier
                    </a>
                    <button onclick="deleteConsultation({{ $consultation->id }})"
                            class="w-full bg-red-500 hover:bg-red-600 text-white rounded-lg px-4 py-2 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Supprimer
                    </button>
                    <button type="button" onclick="openOrdonnanceModal()"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg px-4 py-2 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        {{ $consultation->ordonnances ? 'Modifier l\'ordonnance' : 'Créer une ordonnance' }}
                    </button>
                    @if($consultation->ordonnances)
                        <button type="button" onclick="openViewOrdonnanceModal()"
                           class="w-full inline-flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg px-4 py-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Voir l'ordonnance
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ordonnance -->
<div id="modal-ordonnance" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-[16px] shadow-xl w-full max-w-2xl mx-4 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold">{{ $consultation->ordonnances ? 'Modifier l\'ordonnance' : 'Créer une ordonnance' }}</h3>
            <button class="p-2 hover:bg-gray-100 rounded-full" onclick="closeOrdonnanceModal()">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('medecin.consultations.ordonnance.upsert', $consultation) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Médicaments (JSON ou texte)</label>
                <textarea name="medicaments" rows="3" class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">{{ $consultation->ordonnances? json_encode($consultation->ordonnances->medicaments) : '' }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" rows="3" class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">{{ $consultation->ordonnances->notes ?? '' }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fichier (PDF/JPG/PNG)</label>
                <input type="file" name="file" class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]" />
                @if($consultation->ordonnances && $consultation->ordonnances->file)
                    <p class="text-sm text-gray-500 mt-1">Fichier actuel: <a class="text-indigo-600 underline" target="_blank" href="{{ asset('storage/' . $consultation->ordonnances->file) }}">ouvrir</a></p>
                @endif
            </div>
            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="closeOrdonnanceModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg">Annuler</button>
                <button type="submit" class="px-4 py-2 bg-[#b9ff66] hover:bg-[#a8eb5f] rounded-lg">Enregistrer</button>
            </div>
        </form>
        @if($consultation->ordonnances)
            <form method="POST" action="{{ route('medecin.consultations.ordonnance.delete', $consultation) }}" class="mt-3" onsubmit="return confirm('Supprimer l\'ordonnance ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 text-sm hover:underline">Supprimer l'ordonnance</button>
            </form>
        @endif
    </div>

</div>

<!-- Modal Voir Ordonnance -->
@if($consultation->ordonnances)
<div id="modal-view-ordonnance" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-[16px] shadow-xl w-full max-w-2xl mx-4 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold">Ordonnance</h3>
            <button class="p-2 hover:bg-gray-100 rounded-full" onclick="closeViewOrdonnanceModal()">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="space-y-4">
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-gray-700 mb-2">Médicaments</h4>
                @php $meds = $consultation->ordonnances->medicaments; @endphp
                @if(is_array($meds) && count($meds))
                    <ul class="list-disc pl-5 space-y-1 text-sm text-gray-800">
                        @foreach($meds as $m)
                            <li>{{ is_array($m) ? json_encode($m, JSON_UNESCAPED_UNICODE) : $m }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-gray-500">Aucun médicament renseigné.</p>
                @endif
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-gray-700 mb-2">Notes</h4>
                <p class="text-sm text-gray-800 whitespace-pre-line">{{ $consultation->ordonnances->notes ?? '—' }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-gray-700 mb-2">Fichier</h4>
                @if($consultation->ordonnances->file)
                    <a class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-700 underline" target="_blank" href="{{ asset('storage/' . $consultation->ordonnances->file) }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M4 12l4 4m0 0l4-4m-4 4V4"/></svg>
                        Ouvrir le fichier
                    </a>
                @else
                    <p class="text-sm text-gray-500">Aucun fichier attaché.</p>
                @endif
            </div>
        </div>
        <div class="flex items-center justify-end pt-4">
            <button type="button" onclick="closeViewOrdonnanceModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg">Fermer</button>
        </div>
    </div>
</div>
@endif

<script>
function deleteConsultation(consultationId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette consultation ?')) {
        fetch(`/medecin/consultations/${consultationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '{{ route("medecin.consultations.index") }}';
            } else {
                alert('Erreur lors de la suppression: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la suppression');
        });
    }
}

function openOrdonnanceModal(){
    const m = document.getElementById('modal-ordonnance');
    if (m){ m.classList.remove('hidden'); m.classList.add('flex'); }
}
function closeOrdonnanceModal(){
    const m = document.getElementById('modal-ordonnance');
    if (m){ m.classList.add('hidden'); m.classList.remove('flex'); }
}
function openViewOrdonnanceModal(){
    const m = document.getElementById('modal-view-ordonnance');
    if (m){ m.classList.remove('hidden'); m.classList.add('flex'); }
}
function closeViewOrdonnanceModal(){
    const m = document.getElementById('modal-view-ordonnance');
    if (m){ m.classList.add('hidden'); m.classList.remove('flex'); }
}
</script>
@endsection
