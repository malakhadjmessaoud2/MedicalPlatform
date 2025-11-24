@extends('dashMedecin.layout')

@section('content')
@php
    $patient = $consultation->rendezVous->patient ?? ($consultation->dossierMedical->patient ?? null);
    $medecin = $consultation->rendezVous->medecin ?? null;
    $photoUrl = ($patient && $patient->profile_photo_path)
        ? asset('storage/' . $patient->profile_photo_path)
        : 'https://ui-avatars.com/api/?name=' . urlencode(($patient->prenom ?? 'P') . ' ' . ($patient->nom ?? ''));

@endphp
<div class="p-4 sm:p-8 bg-[#e4e4e4] min-h-screen">
    <nav class="text-sm text-gray-500 flex items-center gap-2 mb-6">
        <a href="{{ route('medecin.consultations.index') }}" class="hover:text-gray-700">Consultations</a>
        <span>/</span>
        <span class="text-gray-900 font-semibold">Fiche #{{ $consultation->id }}</span>
    </nav>
    <!-- En-tête -->
    <div class="flex flex-col gap-4 mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <p class="uppercase text-xs tracking-[0.3em] text-gray-400">Fiche consultation</p>
                <h1 class="text-2xl sm:text-4xl font-bold mt-1">
                    CONSULT<span class="text-[#b9ff66]">A</span>TION #{{ $consultation->id }}
                </h1>
            </div>
            <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                <a href="{{ route('medecin.consultations.edit', $consultation) }}"
                   class="bg-[#b9ff66] hover:bg-[#a8eb5f] text-black rounded-lg px-4 py-2 flex items-center gap-2 transition-colors duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Modifier
                </a>
                <a href="{{ route('medecin.consultations.index') }}"
                   class="bg-white hover:bg-gray-50 text-gray-800 border border-gray-200 rounded-lg px-4 py-2 flex items-center gap-2 transition-colors duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour
                </a>
            </div>
        </div>

    </div>

    <!-- Informations du patient -->
    <div class="bg-white p-6 rounded-[20px] shadow-sm mb-6 border border-gray-100">
        <div class="flex flex-col lg:flex-row lg:items-center gap-6">
            <div class="flex items-center gap-4">
                <img src="{{ $photoUrl }}" class="w-16 h-16 rounded-full ring-4 ring-[#f5ffe6]" alt="Photo patient">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ ($patient->prenom ?? 'Patient') }} {{ ($patient->nom ?? '') }}</h2>
                    <p class="text-sm text-gray-500">Consultation du {{ optional($consultation->date)->format('d/m/Y') }}</p>
                </div>
            </div>
            <dl class="grid grid-cols-1 sm:grid-cols-3 gap-4 flex-1">
                @if(($patient->dateNaissance ?? null))
                    <div class="p-4 rounded-2xl bg-gray-50">
                        <dt class="text-xs uppercase tracking-widest text-gray-400">Âge</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ \Carbon\Carbon::parse($patient->dateNaissance)->age }} ans</dd>
                    </div>
                @endif
                @if(($patient->email ?? null))
                    <div class="p-4 rounded-2xl bg-gray-50">
                        <dt class="text-xs uppercase tracking-widest text-gray-400">Email</dt>
                        <dd class="text-sm font-semibold text-gray-900 break-all">{{ $patient->email }}</dd>
                    </div>
                @endif
                @if(($patient->telephone ?? null))
                    <div class="p-4 rounded-2xl bg-gray-50">
                        <dt class="text-xs uppercase tracking-widest text-gray-400">Téléphone</dt>
                        <dd class="text-sm font-semibold text-gray-900">{{ $patient->telephone }}</dd>
                    </div>
                @endif
            </dl>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Colonne gauche -->
        <div class="lg:col-span-8 space-y-6">


            <!-- Symptômes -->
            @if($consultation->symptomes)
                <div class="bg-white p-6 rounded-[20px] shadow-sm border border-gray-100">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="inline-flex items-center justify-center p-2 rounded-full bg-[#e8ffd0] text-[#3b6314]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </span>
                        <h3 class="text-lg font-bold">Symptômes</h3>
                    </div>
                    <p class="text-gray-700 leading-relaxed">{{ $consultation->symptomes }}</p>
                </div>
            @endif

            <!-- Paramètres cliniques -->
            @if($consultation->tension_arterielle || $consultation->frequence_cardiaque || $consultation->temperature || $consultation->saturation_o2 || $consultation->score_glasgow)
                @php
                    $clinicalMetrics = collect([
                        ['label' => 'Tension artérielle', 'value' => $consultation->tension_arterielle, 'unit' => null],
                        ['label' => 'Fréquence cardiaque', 'value' => $consultation->frequence_cardiaque, 'unit' => 'bpm'],
                        ['label' => 'Température', 'value' => $consultation->temperature, 'unit' => '°C'],
                        ['label' => 'Saturation O₂', 'value' => $consultation->saturation_o2, 'unit' => '%'],
                        ['label' => 'Score Glasgow', 'value' => $consultation->score_glasgow, 'unit' => null],
                    ])->filter(fn($metric) => filled($metric['value']));
                @endphp
                <div class="bg-white p-6 rounded-[20px] shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold flex items-center gap-2">
                            <span class="inline-flex items-center justify-center p-2 rounded-full bg-[#e8ffd0] text-[#3b6314]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                </svg>
                            </span>
                            Paramètres cliniques
                        </h3>
                        <span class="text-xs text-gray-400 uppercase tracking-widest">{{ $clinicalMetrics->count() }} valeurs</span>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($clinicalMetrics as $metric)
                            <div class="p-4 rounded-2xl border border-gray-100 bg-gradient-to-b from-white to-gray-50">
                                <p class="text-xs uppercase tracking-widest text-gray-400">{{ $metric['label'] }}</p>
                                <p class="mt-2 text-xl font-semibold text-gray-900">
                                    {{ $metric['value'] }}
                                    @if($metric['unit'])
                                        <span class="text-sm font-normal text-gray-500">{{ $metric['unit'] }}</span>
                                    @endif
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Mesures physiques -->
            @if($consultation->poids || $consultation->taille || $consultation->imc)
                <div class="bg-white p-6 rounded-[20px] shadow-sm border border-gray-100">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="inline-flex items-center justify-center p-2 rounded-full bg-[#e8ffd0] text-[#3b6314]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </span>
                        <h3 class="text-lg font-bold">Mesures physiques</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @if($consultation->poids)
                            <div class="p-4 rounded-2xl bg-gray-50">
                                <p class="text-xs uppercase tracking-widest text-gray-400">Poids</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $consultation->poids }} kg</p>
                            </div>
                        @endif
                        @if($consultation->taille)
                            <div class="p-4 rounded-2xl bg-gray-50">
                                <p class="text-xs uppercase tracking-widest text-gray-400">Taille</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $consultation->taille }} cm</p>
                            </div>
                        @endif
                        @if($consultation->imc)
                            <div class="p-4 rounded-2xl bg-gray-50">
                                <p class="text-xs uppercase tracking-widest text-gray-400">IMC</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $consultation->imc }} kg/m²</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if($consultation->examen_physique || $consultation->diagnostic_presume || $consultation->medicaments_prescrits || $consultation->propositions_suivi || $consultation->instructions_particulieres)
                <div class="bg-white p-6 rounded-[20px] shadow-sm border border-gray-100">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="inline-flex items-center justify-center p-2 rounded-full bg-[#e8ffd0] text-[#3b6314]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </span>
                        <h3 class="text-lg font-bold">Examen, diagnostic & plan</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        @if($consultation->examen_physique)
                            <div class="p-4 rounded-2xl bg-gray-50">
                                <p class="text-xs uppercase tracking-widest text-gray-400">Examen clinique</p>
                                <p class="mt-2 text-gray-800 leading-relaxed">{{ $consultation->examen_physique }}</p>
                            </div>
                        @endif
                        @if($consultation->diagnostic_presume)
                            <div class="p-4 rounded-2xl bg-gray-50">
                                <p class="text-xs uppercase tracking-widest text-gray-400">Diagnostic présumé</p>
                                <p class="mt-2 text-gray-800 leading-relaxed">{{ $consultation->diagnostic_presume }}</p>
                            </div>
                        @endif
                    </div>
                    @if($consultation->medicaments_prescrits || $consultation->propositions_suivi || $consultation->instructions_particulieres)
                        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                            @if($consultation->medicaments_prescrits)
                                <div class="p-4 rounded-2xl border border-dashed border-gray-200">
                                    <p class="text-xs uppercase tracking-widest text-gray-400">Médicaments prescrits</p>
                                    <p class="mt-2 text-gray-800 whitespace-pre-line leading-relaxed">{{ $consultation->medicaments_prescrits }}</p>
                                </div>
                            @endif
                            @if($consultation->propositions_suivi)
                                <div class="p-4 rounded-2xl border border-dashed border-gray-200">
                                    <p class="text-xs uppercase tracking-widest text-gray-400">Propositions de suivi</p>
                                    <p class="mt-2 text-gray-800 leading-relaxed">{{ $consultation->propositions_suivi }}</p>
                                </div>
                            @endif
                            @if($consultation->instructions_particulieres)
                                <div class="md:col-span-2 p-4 rounded-2xl bg-gray-50">
                                    <p class="text-xs uppercase tracking-widest text-gray-400">Instructions particulières</p>
                                    <p class="mt-2 text-gray-800 whitespace-pre-line leading-relaxed">{{ $consultation->instructions_particulieres }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

            <!-- Habitudes de vie (pour consultations de suivi) -->
            @if($consultation->habitudes_vie || $consultation->traitement_actuel || $consultation->evolution_symptomes || $consultation->effets_secondaires || $consultation->examens_controle)
                <div class="bg-white p-6 rounded-[20px] shadow-sm border border-gray-100">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="inline-flex items-center justify-center p-2 rounded-full bg-[#e8ffd0] text-[#3b6314]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </span>
                        <h3 class="text-lg font-bold">Habitudes de vie & suivi</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($consultation->habitudes_vie)
                            <div class="p-4 bg-gray-50 rounded-2xl">
                                <p class="text-xs uppercase tracking-widest text-gray-400">Habitudes de vie</p>
                                <p class="mt-2 text-gray-800">{{ $consultation->habitudes_vie }}</p>
                            </div>
                        @endif
                        @if($consultation->traitement_actuel)
                            <div class="p-4 bg-gray-50 rounded-2xl">
                                <p class="text-xs uppercase tracking-widest text-gray-400">Traitement actuel</p>
                                <p class="mt-2 text-gray-800">{{ $consultation->traitement_actuel }}</p>
                            </div>
                        @endif
                        @if($consultation->evolution_symptomes)
                            <div class="p-4 bg-gray-50 rounded-2xl">
                                <p class="text-xs uppercase tracking-widest text-gray-400">Évolution des symptômes</p>
                                <p class="mt-2 text-gray-800">{{ $consultation->evolution_symptomes }}</p>
                            </div>
                        @endif
                        @if($consultation->effets_secondaires)
                            <div class="p-4 bg-gray-50 rounded-2xl">
                                <p class="text-xs uppercase tracking-widest text-gray-400">Effets secondaires</p>
                                <p class="mt-2 text-gray-800">{{ $consultation->effets_secondaires }}</p>
                            </div>
                        @endif
                        @if($consultation->examens_controle)
                            <div class="p-4 bg-gray-50 rounded-2xl md:col-span-2">
                                <p class="text-xs uppercase tracking-widest text-gray-400">Examens de contrôle</p>
                                <p class="mt-2 text-gray-800">{{ $consultation->examens_controle }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Colonne droite -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Résumé -->
            <div class="bg-gradient-to-b from-white to-gray-50 p-6 rounded-[20px] shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold">Résumé</h3>
                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-gray-500">
                        <span class="w-2 h-2 rounded-full bg-[#b9ff66]"></span>
                        À jour
                    </span>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-widest text-gray-400">Date</p>
                            <p class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($consultation->date_consultation)->format('d/m/Y') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs uppercase tracking-widest text-gray-400">Type</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $typesConsultation[$consultation->type_consultation] ?? $consultation->type_consultation }}</p>
                        </div>
                    </div>
                    <div class="border-t border-gray-100 pt-4">
                        <p class="text-xs uppercase tracking-widest text-gray-400 mb-1">Orientation</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $consultation->orientation_patient ? ucfirst($consultation->orientation_patient) : 'Non définie' }}</p>
                    </div>
                    @if($consultation->rendezVous)
                        <div class="border-t border-gray-100 pt-4">
                            <p class="text-xs uppercase tracking-widest text-gray-400 mb-1">Rendez-vous</p>
                            <p class="text-sm font-semibold text-gray-900">#{{ $consultation->rendezVous->id }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-white p-6 rounded-[20px] shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold">Actions</h3>
                    <span class="text-xs uppercase tracking-widest text-gray-400">Gestion</span>
                </div>
                <div class="space-y-3">
                    <a href="{{ route('medecin.consultations.edit', $consultation) }}"
                       class="w-full bg-[#b9ff66] hover:bg-[#a8eb5f] text-black rounded-xl px-4 py-3 flex items-center justify-between transition-colors duration-150">
                        <span class="flex items-center gap-2 font-semibold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Modifier la fiche
                        </span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    {{-- mettre a jour ordonnance action rapide --}}
                    {{-- <button type="button" onclick="openOrdonnanceModal()"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl px-4 py-3 flex items-center justify-between transition-colors duration-150">
                        <span class="flex items-center gap-2 font-semibold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            {{ $consultation->ordonnances ? 'Mettre à jour l\'ordonnance' : 'Créer une ordonnance' }}
                        </span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button> --}}
                    @if($consultation->ordonnances)
                        <button type="button" onclick="openViewOrdonnanceModal()"
                                class="w-full inline-flex items-center justify-between gap-2 bg-gray-50 hover:bg-gray-100 text-gray-800 rounded-xl px-4 py-3 transition-colors duration-150">
                            <span class="flex items-center gap-2 font-semibold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Voir l'ordonnance
                            </span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    @endif
                    <button onclick="deleteConsultation({{ $consultation->id }})"
                            class="w-full bg-red-500 hover:bg-red-600 text-white rounded-xl px-4 py-3 flex items-center justify-between transition-colors duration-150">
                        <span class="flex items-center gap-2 font-semibold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Supprimer
                        </span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Ordonnance liée -->
            <div class="bg-white p-6 rounded-[20px] shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center justify-center p-2 rounded-full bg-[#e8ffd0] text-[#3b6314]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </span>
                        <h3 class="text-lg font-bold">Ordonnance associée</h3>
                    </div>
                    <span class="text-xs uppercase tracking-widest text-gray-400">
                        {{ $consultation->ordonnances ? 'Dernière mise à jour' : 'Aucune donnée' }}
                    </span>
                </div>

                @if($consultation->ordonnances)
                    <div class="space-y-4">
                        <div class="p-4 rounded-2xl bg-gray-50">
                            <p class="text-xs uppercase tracking-widest text-gray-400 mb-2">Médicaments</p>
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
                        <div class="p-4 rounded-2xl border border-dashed border-gray-200">
                            <p class="text-xs uppercase tracking-widest text-gray-400 mb-2">Notes</p>
                            <p class="text-sm text-gray-700 whitespace-pre-line">{{ $consultation->ordonnances->notes ?? '—' }}</p>
                        </div>
                        <div class="flex items-center justify-between bg-gray-50 p-4 rounded-2xl">
                            <div>
                                <p class="text-xs uppercase tracking-widest text-gray-400">Fichier joint</p>
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ $consultation->ordonnances->file ? 'Disponible' : 'Aucun fichier' }}
                                </p>
                            </div>
                            @if($consultation->ordonnances->file)
                                <a
    class="inline-flex items-center gap-2 px-3 py-2 bg-indigo-600 text-white rounded-lg shadow-sm
           hover:bg-indigo-700 transition-all duration-200 font-medium"
    target="_blank"
    href="{{ asset('storage/' . $consultation->ordonnances->file) }}"
>
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" >
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M14 3h7m0 0v7m0-7L10 14m-7 7h8a2 2 0 002-2v-3m-6 5H5a2 2 0 01-2-2v-8" />
    </svg>
    Ouvrir le document
</a>

                            @else
                                <button type="button" onclick="openOrdonnanceModal()"
                                        class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-700 font-semibold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Ajouter un fichier
                                </button>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="openOrdonnanceModal()"
                                    class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl px-4 py-2.5 text-sm font-semibold transition-colors">
                                Modifier l'ordonnance
                            </button>
                            <button type="button" onclick="openViewOrdonnanceModal()"
                                    class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-xl px-4 py-2.5 text-sm font-semibold transition-colors">
                                Voir en plein écran
                            </button>
                        </div>
                    </div>
                @else
                    <div class="rounded-2xl border border-dashed border-gray-200 p-6 text-center">
                        <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gray-50 text-gray-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-gray-900 mb-1">Aucune ordonnance enregistrée</p>
                        <p class="text-sm text-gray-500 mb-4">Créez une ordonnance dès maintenant pour suivre le traitement du patient.</p>
                        <button type="button" onclick="openOrdonnanceModal()"
                                class="inline-flex items-center gap-2 bg-[#b9ff66] hover:bg-[#a8eb5f] text-black rounded-xl px-4 py-2 text-sm font-semibold transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Créer une ordonnance
                        </button>
                    </div>
                @endif
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
