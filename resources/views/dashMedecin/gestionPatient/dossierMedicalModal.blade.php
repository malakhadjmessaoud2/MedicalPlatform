<div class="space-y-6">
    <!-- Informations du patient -->
    <div class="bg-gray-50 rounded-lg p-4">
        <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center gap-2">
            <svg class="w-5 h-5 text-[#b9ff66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Informations du patient
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex items-center gap-3">
                @php
                    $user = $dossier->patient->user ?? null;
                    $photoUrl = $user && $user->profile_photo_path
                        ? asset('storage/' . $user->profile_photo_path)
                        : 'https://ui-avatars.com/api/?name=' . urlencode($dossier->patient->prenom . ' ' . $dossier->patient->nom);
                @endphp
                <img src="{{ $photoUrl }}" class="w-16 h-16 rounded-full object-cover border-4 border-[#b9ff66]">
                <div>
                    <div class="text-xl font-bold text-gray-900">{{ $dossier->patient->prenom }} {{ $dossier->patient->nom }}</div>
                    <div class="text-sm text-gray-600">ID: #{{ $dossier->patient->id }}</div>
                    @if($dossier->patient->dateNaissance)
                        <div class="text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($dossier->patient->dateNaissance)->age }} ans
                            ({{ \Carbon\Carbon::parse($dossier->patient->dateNaissance)->format('d/m/Y') }})
                        </div>
                    @endif
                </div>
            </div>
            <div class="space-y-2">
                @if($dossier->tel)
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span class="text-gray-700">{{ $dossier->tel }}</span>
                    </div>
                @endif
                @if($dossier->adresse)
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-gray-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314-11.314z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="text-gray-700">{{ $dossier->adresse }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Informations médicales -->
    <div class="space-y-4">
        <!-- Groupe sanguin -->
        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center gap-2">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                </svg>
                Groupe sanguin
            </h4>
            @if($dossier->groupe_sanguin)
                <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                    {{ $dossier->groupe_sanguin }}
                </div>
            @else
                <span class="text-gray-500 italic">Non renseigné</span>
            @endif
        </div>

        <!-- Allergies -->
        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center gap-2">
                <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                Allergies
            </h4>
            @if($dossier->allergies && $dossier->allergies !== 'Aucune')
                <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                    {{ $dossier->allergies }}
                </div>
            @else
                <span class="text-green-600 font-medium">✓ Aucune allergie connue</span>
            @endif
        </div>

        <!-- Antécédents médicaux -->
        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Antécédents médicaux
            </h4>
            @if($dossier->antecedents_medicaux && $dossier->antecedents_medicaux !== 'Aucun')
                <div class="bg-gray-50 rounded-lg p-3">
                    <p class="text-gray-700 leading-relaxed">{{ $dossier->antecedents_medicaux }}</p>
                </div>
            @else
                <span class="text-gray-500 italic">Aucun antécédent médical renseigné</span>
            @endif
        </div>
    </div>

    <!-- Actions -->
    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
        <a href="{{ route('medecin.dossiermedical', ['patient_id' => $dossier->patient->id]) }}"
           class="px-4 py-8 bg-[#b9ff66] text-gray-800 rounded-lg hover:bg-[#a8eb5f] transition-colors font-medium">
            Voir le dossier complet
        </a>
        <button onclick="closeDossierModal()"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
            Fermer
        </button>
    </div>
</div>
