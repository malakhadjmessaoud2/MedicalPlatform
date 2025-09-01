@forelse($consultations ?? [] as $consultation)
    @php
        $photoUrl = ($consultation->patient->user ?? null) && ($consultation->patient->user->profile_photo_path ?? null)
            ? asset('storage/' . $consultation->patient->user->profile_photo_path)
            : 'https://ui-avatars.com/api/?name=' . urlencode(($consultation->patient->prenom ?? 'P') . ' ' . ($consultation->patient->nom ?? ''));
        $typeLabel = isset($typesConsultation) && isset($typesConsultation[$consultation->type_consultation ?? ''])
            ? $typesConsultation[$consultation->type_consultation]
            : ucfirst($consultation->type_consultation ?? '');
    @endphp
    <div class="group relative bg-white rounded-[20px] p-6 border border-gray-100 shadow-sm hover:shadow-lg hover:border-[#b9ff66]/30 transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
        <div class="absolute inset-x-0 top-0 h-1 bg-[#b9ff66]"></div>
        <!-- Hover effect overlay -->
        <div class="absolute inset-0 bg-gradient-to-br from-[#b9ff66]/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

        @php
            $dt = \Carbon\Carbon::parse($consultation->date_consultation ?? now());
            $dateDisplay = $dt->isToday() ? "Aujourd'hui" : $dt->format('d/m/Y');
            $timeDisplay = $dt->format('H:i');
            $status = $consultation->statut ?? ($dt->isToday() ? 'En cours' : ($dt->isPast() ? 'Terminé' : 'Planifiée'));
        @endphp

        <!-- Header: avatar + nom + statut -->
        <div class="relative flex items-start justify-between gap-4">
            <div class="flex items-center gap-3 min-w-0">
                <div class="relative">
                    <img src="{{ $photoUrl }}" class="w-12 h-12 rounded-full ring-2 ring-gray-100 shadow-sm" alt="Patient">
                    <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-500 rounded-full ring-2 ring-white"></span>
                </div>
                <div class="min-w-0">
                    <p class="font-semibold truncate text-gray-900">{{ ($consultation->patient->prenom ?? 'Patient') . ' ' . ($consultation->patient->nom ?? '') }}</p>
                    <p class="text-xs text-gray-500 truncate">
                        @if(($consultation->patient->dateNaissance ?? null))
                            {{ \Carbon\Carbon::parse($consultation->patient->dateNaissance)->age }} ans
                        @else
                            Patient
                        @endif
                        @if(($consultation->patient->user->email ?? null))
                            <span class="hidden sm:inline"> • {{ $consultation->patient->user->email }}</span>
                        @endif
                    </p>
                </div>
            </div>
            <span class="shrink-0 px-3 py-1 rounded-full text-xs bg-blue-100 text-blue-700">{{ ucfirst($status) }}</span>
        </div>

        <!-- Bloc infos: type, date, heure -->
        <div class="relative mt-4 p-4 rounded-2xl bg-gray-50/80 backdrop-blur-sm border border-gray-100/50">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-700">Type de consultation</p>
                    <div class="mt-1 flex items-center gap-2 text-gray-800">
                        <svg class="w-4 h-4 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="font-medium">{{ $typeLabel }}</span>
                    </div>
                </div>
                <div class="grid grid-cols-[auto,1fr] items-center gap-x-3 gap-y-2">
                    <div class="flex items-center gap-2 text-gray-700">
                        <svg class="w-4 h-4 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-sm">Date:</span>
                    </div>
                    <span class="text-sm px-2.5 py-1 rounded-full bg-gray-200 text-gray-800">{{ $dateDisplay }}</span>

                    <div class="flex items-center gap-2 text-gray-700">
                        <svg class="w-4 h-4 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm">Heure:</span>
                    </div>
                    <span class="text-sm px-2.5 py-1 rounded-full bg-gray-200 text-gray-800">{{ $timeDisplay }}</span>
                </div>
            </div>
        </div>

        <!-- Tags -->
        <div class="mt-3 flex flex-wrap items-center gap-2">
            @php $typeLower = strtolower($typeLabel); @endphp
            <span class="px-3 py-1 rounded-full text-xs bg-blue-100 text-blue-700">{{ str_contains($typeLower, 'suivi') ? 'Suivi régulier' : $typeLabel }}</span>
            @php $mode = $consultation->mode_consultation ?? 'presentiel'; @endphp
            <span class="px-3 py-1 rounded-full text-xs bg-purple-100 text-purple-700">{{ $mode === 'distanciel' ? 'Distanciel' : 'Présentiel' }}</span>
            @if(($consultation->orientation_patient ?? null))
                <span class="px-3 py-1 rounded-full text-xs bg-emerald-100 text-emerald-700">{{ ucfirst($consultation->orientation_patient) }}</span>
            @endif
        </div>

        @if(($consultation->tension_arterielle ?? null) || ($consultation->frequence_cardiaque ?? null) || ($consultation->temperature ?? null) || ($consultation->saturation_o2 ?? null))
            <div class="mt-3 flex flex-wrap items-center gap-2 text-xs">
                @if(($consultation->tension_arterielle ?? null))
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-red-50 text-red-700 border border-red-100">TA {{ $consultation->tension_arterielle }}</span>
                @endif
                @if(($consultation->frequence_cardiaque ?? null))
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-gray-100 text-gray-800">FC {{ $consultation->frequence_cardiaque }} bpm</span>
                @endif
                @if(($consultation->temperature ?? null))
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-gray-100 text-gray-800">Temp {{ $consultation->temperature }} °C</span>
                @endif
                @if(($consultation->saturation_o2 ?? null))
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-gray-100 text-gray-800">O₂ {{ $consultation->saturation_o2 }}%</span>
                @endif
            </div>
        @endif

        <!-- Motif -->
        @php
            $snippet = $consultation->motif_consultation ?? ($consultation->diagnostic_presume ?? ($consultation->symptomes ?? null));
        @endphp
        @if($snippet)
            <div class="mt-3 flex items-start gap-2 text-sm text-gray-700">
                <svg class="mt-0.5 w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="line-clamp-2"><span class="text-gray-500">Motif:</span> {{ \Illuminate\Support\Str::limit($snippet, 160) }}</p>
            </div>
        @endif

        <!-- Footer: durée + actions -->
        <div class="relative mt-4 flex items-center justify-end">
            <div class="flex items-center gap-2" x-data="{ open:false }">
                <a href="{{ route('consultations.show', $consultation) }}" class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center" aria-label="Voir la consultation" title="Voir la consultation">
                    <svg class="w-4 h-4 text-gray-700" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </a>
                <button @click="open=!open" class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center" aria-label="Plus d'actions" title="Plus d'actions">
                    <svg class="w-4 h-4 text-gray-700" viewBox="0 0 24 24" fill="currentColor"><circle cx="5" cy="12" r="2"/><circle cx="12" cy="12" r="2"/><circle cx="19" cy="12" r="2"/></svg>
                </button>
                <div x-show="open" @click.away="open=false" x-transition class="absolute right-4 bottom-12 w-40 bg-white border border-gray-100 rounded-xl shadow-lg py-1 z-20">
                    <a href="{{ route('consultations.edit', $consultation) }}" class="block px-3 py-2 text-sm hover:bg-gray-50" aria-label="Modifier">Modifier</a>
                    <button type="button" onclick="deleteConsultation({{ $consultation->id }})" class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50" aria-label="Supprimer">Supprimer</button>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="col-span-full">
        <div class="bg-white rounded-[20px] p-10 border border-dashed border-gray-200 text-center">
            <div class="mx-auto w-14 h-14 rounded-full bg-[#b9ff66] mb-3 flex items-center justify-center">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold mb-1">Aucune consultation</h3>
            <p class="text-gray-600 mb-4">Créez votre première consultation pour commencer.</p>
            <a href="{{ route('consultations.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-black text-white hover:bg-gray-800">
                Nouvelle consultation
            </a>
        </div>
    </div>
@endforelse
