@extends('dashMedecin.layout')

@section('content')
<div class="p-4 sm:p-8 bg-[#e4e4e4] min-h-screen">
    <!-- En-tête -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-8">
        <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-8 mb-4 sm:mb-0">
            <h1 class="text-2xl sm:text-4xl font-bold">
                NOUVELLE CONSULT<span class="text-[#b9ff66]">A</span>TION
            </h1>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('medecin.consultations.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white rounded-lg px-4 py-2 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour
            </a>
        </div>
    </div>

    <!-- Formulaire -->
    <form id="consultationForm" class="space-y-6">
        @csrf

        <!-- Informations générales -->
        <div class="bg-white p-6 rounded-[20px] shadow-sm">
            <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-[#b9ff66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Informations Générales
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Patient (affiché en lecture seule si sélectionné) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Patient *</label>
                    @if($selectedPatient)
                        <input type="hidden" name="patient_id" value="{{ $selectedPatient->id }}">
                        <div class="w-full p-3 bg-gray-50 rounded-lg border border-gray-300">
                            <p class="font-medium">{{ $selectedPatient->prenom }} {{ $selectedPatient->nom }}</p>
                            @if($selectedPatient->dateNaissance)
                                <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($selectedPatient->dateNaissance)->age }} ans</p>
                            @endif
                        </div>
                    @else
                        <select name="patient_id" required class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                            <option value="">Sélectionner un patient</option>
                        </select>
                    @endif
                </div>

                {{-- <!-- Médecin (affiché en lecture seule) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Médecin</label>
                    <div class="w-full p-3 bg-gray-50 rounded-lg border border-gray-300">
                        <p class="font-medium">Dr. {{ $medecinConnecte->prenom }} {{ $medecinConnecte->nom }}</p>
                        @if($medecinConnecte->specialite)
                            <p class="text-sm text-gray-600">{{ $medecinConnecte->specialite }}</p>
                        @endif
                    </div>
                </div> --}}

                <!-- Rendez-vous (optionnel) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rendez-vous</label>
                    @if($selectedPatient && $rendezVous->count() > 0)
                        <select name="rendezvous_id" class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                            <option value="">Aucun rendez-vous</option>
                            @foreach($rendezVous as $rdv)
                                <option value="{{ $rdv->id }}"
                                        {{ $selectedRendezVous && $selectedRendezVous->id == $rdv->id ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::parse($rdv->date_debut)->format('d/m/Y H:i') }} - {{ $rdv->titre }}
                                </option>
                            @endforeach
                        </select>
                    @else
                        <div class="w-full p-3 bg-gray-50 rounded-lg border border-gray-300">
                            <p class="text-sm text-gray-600">
                                @if($selectedPatient)
                                    Aucun rendez-vous payé trouvé pour ce patient
                                @else
                                    Sélectionnez d'abord un patient
                                @endif
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Date de consultation -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date de consultation *</label>
                    <input type="datetime-local" name="date_consultation" required
                           value="{{ now()->format('Y-m-d\TH:i') }}"
                           class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                </div>

                <!-- Type de consultation -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type de consultation *</label>
                    <select name="type_consultation" required id="typeConsultation"
                            class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                        <option value="">Sélectionner le type</option>
                        @foreach($typesConsultation as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Motif et symptômes -->
        <div class="bg-white p-6 rounded-[20px] shadow-sm">
            <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-[#b9ff66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Motif et Symptômes
            </h3>

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Motif de consultation</label>
                    <input type="text" name="motif_consultation" placeholder="Ex: Douleurs abdominales"
                           class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Symptômes actuels</label>
                    <textarea name="symptomes" rows="3" placeholder="Décrivez les symptômes actuels..."
                              class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]"></textarea>
                </div>

                <!-- Champs spécifiques selon le type de consultation -->
                <div id="symptomesSpecifiques" class="hidden">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Symptômes aigus</label>
                        <textarea name="symptomes_aigus" rows="2" placeholder="Symptômes aigus..."
                                  class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Début des symptômes</label>
                            <input type="date" name="debut_symptomes"
                                   class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gravité</label>
                            <select name="gravite" class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                                <option value="">Sélectionner</option>
                                <option value="legere">Légère</option>
                                <option value="moderee">Modérée</option>
                                <option value="severe">Sévère</option>
                                <option value="critique">Critique</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Paramètres cliniques -->
        <div class="bg-white p-6 rounded-[20px] shadow-sm">
            <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-[#b9ff66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                Paramètres Cliniques
            </h3>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tension artérielle</label>
                    <input type="text" name="tension_arterielle" placeholder="120/80 mmHg"
                           class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fréquence cardiaque</label>
                    <input type="number" name="frequence_cardiaque" placeholder="75" min="0"
                           class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                    <span class="text-sm text-gray-500">bpm</span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Température</label>
                    <input type="number" name="temperature" placeholder="37.0" step="0.1" min="30" max="45"
                           class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                    <span class="text-sm text-gray-500">°C</span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Saturation O2</label>
                    <input type="number" name="saturation_o2" placeholder="98" min="0" max="100"
                           class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                    <span class="text-sm text-gray-500">%</span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Score Glasgow</label>
                    <input type="number" name="score_glasgow" placeholder="15" min="3" max="15"
                           class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                </div>
            </div>
        </div>

        <!-- Mesures physiques -->
        <div class="bg-white p-6 rounded-[20px] shadow-sm">
            <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-[#b9ff66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Mesures Physiques
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Poids</label>
                    <input type="number" name="poids" placeholder="70.5" step="0.1" min="0" id="poids"
                           class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                    <span class="text-sm text-gray-500">kg</span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Taille</label>
                    <input type="number" name="taille" placeholder="175" step="0.1" min="0" id="taille"
                           class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                    <span class="text-sm text-gray-500">cm</span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">IMC</label>
                    <input type="number" name="imc" placeholder="22.9" step="0.1" min="0" id="imc" readonly
                           class="w-full rounded-lg border-gray-300 bg-gray-50">
                    <span class="text-sm text-gray-500">kg/m²</span>
                </div>
            </div>
        </div>

        <!-- Examen clinique et diagnostic -->
        <div class="bg-white p-6 rounded-[20px] shadow-sm">
            <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-[#b9ff66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                Examen Clinique et Diagnostic
            </h3>

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Examen physique</label>
                    <textarea name="examen_physique" rows="3" placeholder="Résultats de l'examen physique..."
                              class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Diagnostic présumé</label>
                    <input type="text" name="diagnostic_presume" placeholder="Ex: Gastro-entérite"
                           class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                </div>
            </div>
        </div>

        <!-- Traitement et suivi -->
        <div class="bg-white p-6 rounded-[20px] shadow-sm">
            <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-[#b9ff66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                Traitement et Suivi
            </h3>

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Médicaments prescrits</label>
                    <textarea name="medicaments_prescrits" rows="3"
                              placeholder="Liste des médicaments prescrits avec posologie..."
                              class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Propositions de suivi</label>
                    <textarea name="propositions_suivi" rows="2"
                              placeholder="Propositions de suivi et contrôles..."
                              class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Instructions particulières</label>
                    <textarea name="instructions_particulieres" rows="2"
                              placeholder="Instructions particulières pour le patient..."
                              class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Orientation du patient</label>
                    <select name="orientation_patient" class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                        <option value="">Sélectionner</option>
                        <option value="domicile">Retour à domicile</option>
                        <option value="hospitalisation">Hospitalisation</option>
                        <option value="specialiste">Orientation vers spécialiste</option>
                        <option value="urgence">Service d'urgence</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Habitudes de vie et suivi (pour consultations de suivi) -->
        <div id="habitudesVie" class="bg-white p-6 rounded-[20px] shadow-sm hidden">
            <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-[#b9ff66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                Habitudes de Vie et Suivi
            </h3>

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Habitudes de vie</label>
                    <textarea name="habitudes_vie" rows="2"
                              placeholder="Habitudes alimentaires, activité physique, tabac, alcool..."
                              class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Traitement actuel</label>
                    <textarea name="traitement_actuel" rows="2"
                              placeholder="Traitements en cours..."
                              class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Évolution des symptômes</label>
                    <textarea name="evolution_symptomes" rows="2"
                              placeholder="Évolution depuis la dernière consultation..."
                              class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Effets secondaires</label>
                    <textarea name="effets_secondaires" rows="2"
                              placeholder="Effets secondaires observés..."
                              class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Examens de contrôle</label>
                    <textarea name="examens_controle" rows="2"
                              placeholder="Examens de contrôle prescrits..."
                              class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]"></textarea>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="flex justify-end gap-4 pt-6">
            <a href="{{ route('medecin.consultations.index') }}"
               class="px-6 py-2.5 bg-white text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-200">
                Annuler
            </a>
            <button type="submit"
                    class="px-6 py-2.5 bg-[#b9ff66] hover:bg-[#a8eb5f] rounded-lg flex items-center gap-2 transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Enregistrer la consultation
            </button>
        </div>
    </form>
</div>

<script>
// Gestion du type de consultation
document.getElementById('typeConsultation').addEventListener('change', function() {
    const type = this.value;
    const symptomesSpecifiques = document.getElementById('symptomesSpecifiques');
    const habitudesVie = document.getElementById('habitudesVie');

    // Masquer toutes les sections spécifiques
    symptomesSpecifiques.classList.add('hidden');
    habitudesVie.classList.add('hidden');

    // Afficher les sections selon le type
    if (type === 'urgence') {
        symptomesSpecifiques.classList.remove('hidden');
    } else if (type === 'suivi' || type === 'controle') {
        habitudesVie.classList.remove('hidden');
    }
});

// Calcul automatique de l'IMC
function calculateIMC() {
    const poids = parseFloat(document.getElementById('poids').value) || 0;
    const taille = parseFloat(document.getElementById('taille').value) || 0;
    const imcField = document.getElementById('imc');

    if (poids > 0 && taille > 0) {
        const imc = poids / Math.pow(taille / 100, 2);
        imcField.value = imc.toFixed(1);
    } else {
        imcField.value = '';
    }
}

document.getElementById('poids').addEventListener('input', calculateIMC);
document.getElementById('taille').addEventListener('input', calculateIMC);

// Soumission du formulaire
document.getElementById('consultationForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('{{ route("medecin.consultations.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Consultation créée avec succès !');
            window.location.href = '{{ route("medecin.consultations.index") }}';
        } else {
            alert('Erreur: ' + data.message);
            if (data.errors) {
                console.log('Erreurs de validation:', data.errors);
            }
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la création de la consultation');
    });
});
</script>
@endsection
