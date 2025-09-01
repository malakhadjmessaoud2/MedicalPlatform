@extends('dashMedecin.layout')

@section('content')
<div class="p-4 sm:p-8 bg-[#e4e4e4] min-h-screen">
    <!-- En-tête -->
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
        <div class="flex items-center gap-3">
            <h1 class="text-2xl sm:text-4xl font-bold tracking-tight">
                CONSULT<span class="text-[#b9ff66]">A</span>TIONS
            </h1>
            @isset($consultations)
                <span class="text-sm px-2 py-0.5 rounded-full bg-black text-white">{{ method_exists($consultations,'total') ? $consultations->total() : (is_countable($consultations) ? count($consultations) : '-') }}</span>
            @endisset
        </div>

        {{-- <a href="{{ route('consultations.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[#b9ff66] hover:bg-[#a8eb5f] text-black transition-colors duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nouvelle consultation
        </a> --}}
    </div>

    <!-- Tableau des consultations -->
    <div class="bg-white rounded-[20px] shadow-sm border border-gray-100 overflow-hidden">
        @if(isset($consultations) && count($consultations) > 0)
            <!-- En-tête du tableau -->
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Liste des consultations</h3>
                    <div class="flex items-center gap-4">
                        <!-- Recherche directe dans le tableau -->
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.6-4.65a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" id="table-search" placeholder="Rechercher dans le tableau..." class="pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#b9ff66] focus:border-[#b9ff66] transition-all duration-200 w-64">
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span id="visible-count">{{ method_exists($consultations,'total') ? $consultations->total() : count($consultations) }}</span>
                            <span>/</span>
                            <span>{{ method_exists($consultations,'total') ? $consultations->total() : count($consultations) }}</span>
                            <span>consultation(s)</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tableau responsive -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50/80 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Signes vitaux</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motif</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100" id="consultations-tbody">
                        @php
                            // Fonction pour obtenir la couleur selon le type de consultation
                            function getTypeColor($type) {
                                $colors = [
                                    'consultation' => 'bg-blue-100 text-blue-700',
                                    'suivi' => 'bg-green-100 text-green-700',
                                    'urgence' => 'bg-red-100 text-red-700',
                                    'controle' => 'bg-yellow-100 text-yellow-700',
                                    'preventive' => 'bg-purple-100 text-purple-700',
                                    'specialisee' => 'bg-indigo-100 text-indigo-700',
                                    'teleconsultation' => 'bg-cyan-100 text-cyan-700',
                                    'domicile' => 'bg-orange-100 text-orange-700',
                                    'examen' => 'bg-pink-100 text-pink-700',
                                    'vaccination' => 'bg-emerald-100 text-emerald-700',
                                    'chirurgie' => 'bg-gray-100 text-gray-700',
                                    'rehabilitation' => 'bg-teal-100 text-teal-700',
                                    'psychologie' => 'bg-violet-100 text-violet-700',
                                    'nutrition' => 'bg-lime-100 text-lime-700',
                                    'dermatologie' => 'bg-amber-100 text-amber-700',
                                    'cardiologie' => 'bg-rose-100 text-rose-700',
                                    'neurologie' => 'bg-slate-100 text-slate-700',
                                    'orthopedie' => 'bg-stone-100 text-stone-700',
                                    'pediatrie' => 'bg-sky-100 text-sky-700',
                                    'geriatrie' => 'bg-zinc-100 text-zinc-700',
                                    'grossesse' => 'bg-fuchsia-100 text-fuchsia-700',
                                    'contraception' => 'bg-pink-100 text-pink-700',
                                    'depistage' => 'bg-cyan-100 text-cyan-700',
                                    'vaccin' => 'bg-emerald-100 text-emerald-700',
                                    'bilan' => 'bg-blue-100 text-blue-700',
                                    'consultation_preop' => 'bg-orange-100 text-orange-700',
                                    'consultation_postop' => 'bg-purple-100 text-purple-700',
                                    'urgence_vitale' => 'bg-red-100 text-red-700',
                                    'urgence_relative' => 'bg-orange-100 text-orange-700',
                                    'consultation_planifiee' => 'bg-green-100 text-green-700',
                                    'consultation_ponctuelle' => 'bg-blue-100 text-blue-700'
                                ];

                                $typeLower = strtolower($type);
                                return $colors[$typeLower] ?? 'bg-gray-100 text-gray-700';
                            }
                        @endphp

                        @foreach($consultations as $consultation)
                            @php
                                $photoUrl = ($consultation->rendezVous->patient->profile_photo_path ?? null)
                                    ? asset('storage/' . $consultation->rendezVous->patient->profile_photo_path)
                                    : 'https://ui-avatars.com/api/?name=' . urlencode(($consultation->rendezVous->patient->prenom ?? 'P') . ' ' . ($consultation->rendezVous->patient->nom ?? ''));
                                $typeLabel = isset($typesConsultation) && isset($typesConsultation[$consultation->type ?? ''])
                                    ? $typesConsultation[$consultation->type]
                                    : ucfirst($consultation->type ?? '');

                                $typeColor = getTypeColor($consultation->type ?? 'consultation');
                                $dt = \Carbon\Carbon::parse($consultation->date ?? now());
                                $dateDisplay = $dt->isToday() ? "Aujourd'hui" : $dt->format('d/m/Y');
                                $status = $consultation->statut ?? ($dt->isToday() ? 'En cours' : ($dt->isPast() ? 'Terminé' : 'Planifiée'));
                                $mode = $consultation->mode_consultation ?? 'presentiel';
                                $snippet = $consultation->motif ?? ($consultation->diagnostic_presume ?? ($consultation->symptomes ?? null));
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition-colors duration-200 group consultation-row"
                                data-patient="{{ strtolower(($consultation->rendezVous->patient->prenom ?? '') . ' ' . ($consultation->rendezVous->patient->nom ?? '')) }}"
                                data-type="{{ strtolower($typeLabel) }}"
                                data-date="{{ strtolower($dateDisplay) }}"
                                data-motif="{{ strtolower($snippet ?? '') }}"
                                data-signes="{{ strtolower(($consultation->tension_arterielle ?? '') . ' ' . ($consultation->frequence_cardiaque ?? '') . ' ' . ($consultation->temperature ?? '') . ' ' . ($consultation->saturation_o2 ?? '')) }}">
                                <!-- Patient -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="relative">
                                            <img src="{{ $photoUrl }}" class="w-10 h-10 rounded-full ring-2 ring-gray-100 shadow-sm" alt="Patient">
                                            <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-emerald-500 rounded-full ring-2 ring-white"></span>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                {{ ($consultation->rendezVous->patient->prenom ?? 'Patient') . ' ' . ($consultation->rendezVous->patient->nom ?? '') }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                @if(($consultation->rendezVous->patient->dateNaissance ?? null))
                                                    {{ \Carbon\Carbon::parse($consultation->rendezVous->patient->dateNaissance)->age }} ans
                                                @else
                                                    Patient
                                                @endif
                                                @if(($consultation->rendezVous->patient->email ?? null))
                                                    <span class="hidden sm:inline"> • {{ $consultation->rendezVous->patient->email }}</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Date & Heure -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2 text-sm text-gray-600">
                                            <svg class="w-4 h-4 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span class="font-medium">{{ $dateDisplay }}</span>
                                        </div>

                                    </div>
                                </td>

                                <!-- Type -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="space-y-2">
                                        <div class="flex items-center gap-2">
                                            <span class="px-3 py-1 rounded-full text-xs {{ $typeColor }} font-medium">{{ $typeLabel }}</span>
                                        </div>

                                    </div>
                                </td>

                                <!-- Signes vitaux -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if(($consultation->tension_arterielle ?? null) || ($consultation->frequence_cardiaque ?? null) || ($consultation->temperature ?? null) || ($consultation->saturation_o2 ?? null))
                                        <div class="flex flex-wrap gap-1">
                                            @if(($consultation->tension_arterielle ?? null))
                                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs bg-red-50 text-red-700 border border-red-100">TA {{ $consultation->tension_arterielle }}</span>
                                            @endif
                                            @if(($consultation->frequence_cardiaque ?? null))
                                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs bg-gray-100 text-gray-800">FC {{ $consultation->frequence_cardiaque }}</span>
                                            @endif
                                            @if(($consultation->temperature ?? null))
                                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs bg-gray-100 text-gray-800">T° {{ $consultation->temperature }}</span>
                                            @endif
                                            @if(($consultation->saturation_o2 ?? null))
                                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs bg-gray-100 text-gray-800">O₂ {{ $consultation->saturation_o2 }}%</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>

                                <!-- Motif -->
                                <td class="px-6 py-4">
                                    @if($snippet)
                                        <p class="text-sm text-gray-700 line-clamp-2 max-w-xs">
                                            {{ \Illuminate\Support\Str::limit($snippet, 120) }}
                                        </p>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2" x-data="{ open: false }">
                                        <a href="{{ route('medecin.consultations.show', $consultation) }}" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors duration-200" aria-label="Voir la consultation" title="Voir la consultation">
                                            <svg class="w-4 h-4 text-gray-700" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <button @click="open = !open" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors duration-200" aria-label="Plus d'actions" title="Plus d'actions">
                                            <svg class="w-4 h-4 text-gray-700" viewBox="0 0 24 24" fill="currentColor">
                                                <circle cx="5" cy="12" r="2"/>
                                                <circle cx="12" cy="12" r="2"/>
                                                <circle cx="19" cy="12" r="2"/>
                                            </svg>
                                        </button>
                                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-4 mt-2 w-40 bg-white border border-gray-100 rounded-xl shadow-lg py-1 z-20">
                                            <a href="{{ route('medecin.consultations.edit', $consultation) }}" class="block w-full text-left px-3 py-2 text-sm hover:bg-gray-50" aria-label="Modifier">Modifier</a>
                                            <form method="POST" action="{{ route('medecin.consultations.destroy', $consultation) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette consultation ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50" aria-label="Supprimer">Supprimer</button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- État vide pour la recherche -->
            <div id="no-results" class="hidden p-10 text-center">
                <div class="mx-auto w-14 h-14 rounded-full bg-gray-100 mb-3 flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.6-4.65a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold mb-1 text-gray-900">Aucun résultat trouvé</h3>
                <p class="text-gray-600">Essayez de modifier vos critères de recherche</p>
            </div>
        @else
            <!-- État vide -->
            <div class="p-10 text-center">
                <div class="mx-auto w-14 h-14 rounded-full bg-[#b9ff66] mb-3 flex items-center justify-center">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold mb-1">Aucune consultation</h3>
                <p class="text-gray-600 mb-4">Créez votre première consultation pour commencer.</p>
                <a href="{{ route('medecin.consultations.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-black text-white hover:bg-gray-800">
                    Nouvelle consultation
                </a>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if(isset($consultations) && method_exists($consultations, 'links'))
        <div class="mt-6 flex items-center justify-center">
            {{ $consultations->withQueryString()->links() }}
        </div>
    @endif
</div>

<!-- Auto-submit form on change (minimal JavaScript) -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when select elements change
    const autoSubmitElements = document.querySelectorAll('select[name="sort"], select[name="per"]');
    autoSubmitElements.forEach(element => {
        element.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });

    // Auto-submit form when date inputs change
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        input.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });

    // Recherche directe dans le tableau
    const tableSearch = document.getElementById('table-search');
    const consultationRows = document.querySelectorAll('.consultation-row');
    const visibleCount = document.getElementById('visible-count');
    const noResults = document.getElementById('no-results');
    const tbody = document.getElementById('consultations-tbody');
    const totalCount = consultationRows.length;

    if (tableSearch) {
        tableSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            let visibleRows = 0;

            consultationRows.forEach(row => {
                const patient = row.getAttribute('data-patient') || '';
                const type = row.getAttribute('data-type') || '';
                const date = row.getAttribute('data-date') || '';
                const time = row.getAttribute('data-time') || '';
                const motif = row.getAttribute('data-motif') || '';
                const signes = row.getAttribute('data-signes') || '';

                const searchableText = `${patient} ${type} ${date} ${time} ${motif} ${signes}`;

                if (searchTerm === '' || searchableText.includes(searchTerm)) {
                    row.style.display = '';
                    visibleRows++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Mettre à jour le compteur
            if (visibleCount) {
                visibleCount.textContent = visibleRows;
            }

            // Afficher/masquer le message "aucun résultat"
            if (noResults && tbody) {
                if (visibleRows === 0 && searchTerm !== '') {
                    tbody.style.display = 'none';
                    noResults.classList.remove('hidden');
                } else {
                    tbody.style.display = '';
                    noResults.classList.add('hidden');
                }
            }
        });

        // Effacer la recherche avec Escape
        tableSearch.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                this.value = '';
                this.dispatchEvent(new Event('input'));
            }
        });
    }
});
</script>
@endsection

