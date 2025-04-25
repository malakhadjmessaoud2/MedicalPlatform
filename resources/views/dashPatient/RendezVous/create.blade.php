@extends('dashPatient.layout')

@section('content')
<div class="p-8 bg-gray-50 min-h-screen rounded-2xl">
    <!-- Stepper Progress -->
    <div class="max-w-4xl mx-auto mb-8">
        <div class="flex items-center justify-between">
            <!-- Step 1 -->
            <div class="flex flex-col items-center step" data-step="1" onclick="goToStep(1)">
                <div class="w-10 h-10 rounded-full bg-[#b9ff66] flex items-center justify-center text-gray-800 font-semibold step-circle cursor-pointer">
                    1
                </div>
                <span class="text-sm font-medium mt-2 step-title">Spécialité</span>
            </div>
            <!-- Line 1-2 -->
            <div class="flex-1 h-1 bg-gray-200 mx-4 step-line">
                <div class="h-full bg-[#b9ff66] step-progress" id="progress-1-2" style="width: 0%;"></div>
            </div>
            <!-- Step 2 -->
            <div class="flex flex-col items-center step" data-step="2" onclick="goToStep(2)">
                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-semibold step-circle cursor-pointer">
                    2
                </div>
                <span class="text-sm font-medium mt-2 step-title">Médecin</span>
            </div>
            <!-- Line 2-3 -->
            <div class="flex-1 h-1 bg-gray-200 mx-4 step-line">
                <div class="h-full bg-[#b9ff66] step-progress" id="progress-2-3" style="width: 0%;"></div>
            </div>
            <!-- Step 3 -->
            <div class="flex flex-col items-center step" data-step="3" onclick="goToStep(3)">
                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-semibold step-circle cursor-pointer">
                    3
                </div>
                <span class="text-sm font-medium mt-2 step-title">Patient</span>
            </div>
            <!-- Line 3-4 -->
            <div class="flex-1 h-1 bg-gray-200 mx-4 step-line">
                <div class="h-full bg-[#b9ff66] step-progress" id="progress-3-4" style="width: 0%;"></div>
            </div>
            <!-- Step 4 -->
            <div class="flex flex-col items-center step" data-step="4" onclick="goToStep(4)">
                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-semibold step-circle cursor-pointer">
                    4
                </div>
                <span class="text-sm font-medium mt-2 step-title">Dossier</span>
            </div>
        </div>
    </div>

    <!-- Step Content -->
    <div class="max-w-6xl mx-auto">
        <!-- Champs cachés pour stocker les valeurs sélectionnées -->
        <form id="appointment-form" method="POST" action="{{ route('patient.rendez-vous.store') }}">
            @csrf
            <input type="hidden" id="medecin_id" name="medecin_id">
            <input type="hidden" id="date_rdv" name="date_rdv">
            <input type="hidden" id="heure_debut" name="heure_debut">
            <input type="hidden" id="selected_specialite" name="specialite">
            <input type="hidden" name="statut" value="en_attente">
            <input type="hidden" id="type" name="type">
            <input type="hidden" id="description_input" name="description">
        </form>

        <!-- Step 1: Choix de spécialité -->
        <div id="step1" class="space-y-6">
            <h2 class="text-2xl font-bold">Choisissez une spécialité</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Carte Médecin de famille -->
                <div class="group cursor-pointer" data-specialite="Médecin de famille">
                    <div class="relative bg-white rounded-2xl p-6 transition-all duration-300 hover:shadow-xl border border-gray-100 hover:border-[#b9ff66]">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-xl bg-[#b9ff66]/10 flex items-center justify-center">
                                <svg class="w-8 h-8 text-[#92cc52]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold">Médecin de famille</h3>
                                <p class="text-gray-500 text-sm medecin-count" data-specialite="Médecin de famille">Chargement...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Carte Généraliste -->
                <div class="group cursor-pointer" data-specialite="Généraliste">
                    <div class="relative bg-white rounded-2xl p-6 transition-all duration-300 hover:shadow-xl border border-gray-100 hover:border-[#b9ff66]">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-xl bg-[#b9ff66]/10 flex items-center justify-center">
                                <svg class="w-8 h-8 text-[#92cc52]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4.8 2.3A.3.3 0 1 0 5 2H4a2 2 0 0 0-2 2v5a6 6 0 0 0 6 6v0a6 6 0 0 0 6-6V4a2 2 0 0 0-2-2h-1a.2.2 0 1 0 .3.3"></path>
                                    <path d="M8 15v1a6 6 0 0 0 6 6v0a6 6 0 0 0 6-6v-4"></path>
                                    <circle cx="20" cy="10" r="2"></circle>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold">Généraliste</h3>
                                <p class="text-gray-500 text-sm medecin-count" data-specialite="Généraliste">Chargement...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Carte Psychiatrie -->
                <div class="group cursor-pointer" data-specialite="Psychiatrie">
                    <div class="relative bg-white rounded-2xl p-6 transition-all duration-300 hover:shadow-xl border border-gray-100 hover:border-[#b9ff66]">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-xl bg-[#b9ff66]/10 flex items-center justify-center">
                                <svg class="w-8 h-8 text-[#92cc52]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <path d="M12 18.5c-1.2-1.7-2-3.3-2-5.5 0-3.9 2.7-7 6-7 1 0 1.8.6 2.5 1.5"></path>
                                    <path d="M8 19c-2.8-.5-5-3-5-6 0-2.3 1.8-4 4-4 .3 0 .6 0 .9.1"></path>
                                    <path d="M12 18.5c1.2-1.7 2-3.3 2-5.5 0-3.9-2.7-7-6-7-1 0-1.8.6-2.5 1.5"></path>
                                    <path d="M16 19c2.8-.5 5-3 5-6 0-2.3-1.8-4-4-4-.3 0-.6 0-.9.1"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold">Psychiatrie</h3>
                                <p class="text-gray-500 text-sm medecin-count" data-specialite="Psychiatrie">Chargement...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Carte Pédiatrie -->
                <div class="group cursor-pointer" data-specialite="Pédiatrie">
                    <div class="relative bg-white rounded-2xl p-6 transition-all duration-300 hover:shadow-xl border border-gray-100 hover:border-[#b9ff66]">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-xl bg-[#b9ff66]/10 flex items-center justify-center">
                                <svg class="w-8 h-8 text-[#92cc52]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 12h6"></path>
                                    <path d="M12 9v6"></path>
                                    <path d="M10 16c0 2.5-4 2.5-4 0V8.5C6 7.5 7 6 9 6h6c2 0 3 1.5 3 2.5V16c0 2.5-4 2.5-4 0"></path>
                                    <path d="M8 22c0-5 8-5 8 0"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold">Pédiatrie</h3>
                                <p class="text-gray-500 text-sm medecin-count" data-specialite="Pédiatrie">Chargement...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Carte Nutrition -->
                <div class="group cursor-pointer" data-specialite="Nutrition">
                    <div class="relative bg-white rounded-2xl p-6 transition-all duration-300 hover:shadow-xl border border-gray-100 hover:border-[#b9ff66]">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-xl bg-[#b9ff66]/10 flex items-center justify-center">
                                <svg class="w-8 h-8 text-[#92cc52]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 12a8 8 0 1 0 0-16 8 8 0 0 0 0 16Z"></path>
                                    <path d="M12 16a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"></path>
                                    <path d="M12 20v-4"></path>
                                    <path d="M4 20v-4"></path>
                                    <path d="M20 20v-4"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold">Nutrition</h3>
                                <p class="text-gray-500 text-sm medecin-count" data-specialite="Nutrition">Chargement...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 2: Choix du médecin -->
        <div id="step2" class="hidden space-y-6">
            <h2 class="text-2xl font-bold">Choisissez votre médecin</h2>
            <div id="loading-medecins" class="text-center py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-[#b9ff66]"></div>
                <p class="mt-2 text-gray-600">Chargement des médecins...</p>
            </div>
            <div id="medecins-container" class="grid grid-cols-1 lg:grid-cols-2 gap-6 hidden">
                <!-- Les médecins seront chargés ici dynamiquement -->
            </div>
            <div id="no-medecins" class="hidden text-center py-8">
                <p class="text-gray-600">Aucun médecin disponible pour cette spécialité.</p>
            </div>
        </div>

        <!-- Step 3: Information patient -->
        <div id="step3" class="hidden space-y-6">
            <h2 class="text-2xl font-bold">Information patient</h2>
            <div class="bg-white rounded-2xl p-6 border border-gray-100 max-w-2xl">
                <!-- Type de patient -->
                <div class="space-y-4 mb-6">
                    <div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="patient_type" value="self" class="text-[#b9ff66]" checked>
                            <span>Je prends rendez-vous pour moi-même</span>
                        </label>
                    </div>
                    <div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="patient_type" value="family" class="text-[#b9ff66]">
                            <span>Je prends rendez-vous pour un membre de ma famille</span>
                        </label>
                    </div>
                    </div>

                    <!-- Formulaire membre famille (caché par défaut) -->
                <div id="familyForm" class="hidden space-y-4 mb-6 p-4 bg-gray-50 rounded-xl">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Prénom</label>
                                <input type="text" class="mt-1 w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#b9ff66] focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nom</label>
                                <input type="text" class="mt-1 w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#b9ff66] focus:border-transparent">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Lien de parenté</label>
                            <select class="mt-1 w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#b9ff66] focus:border-transparent">
                                <option>Conjoint(e)</option>
                                <option>Enfant</option>
                                <option>Parent</option>
                                <option>Autre</option>
                            </select>
                        </div>
                    </div>

                <!-- Type de rendez-vous -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type de rendez-vous</label>
                    <div class="grid grid-cols-3 gap-4">
                        <label class="relative flex cursor-pointer rounded-lg border border-gray-200 p-4 hover:border-[#b9ff66] focus-within:ring-2 focus-within:ring-[#b9ff66]">
                            <input type="radio" name="appointment_type" value="consultation" class="sr-only" checked>
                            <span class="flex flex-col">
                                <span class="block text-sm font-medium text-gray-900">Consultation</span>
                                <span class="block text-xs text-gray-500">Première visite</span>
                            </span>
                        </label>
                        <label class="relative flex cursor-pointer rounded-lg border border-gray-200 p-4 hover:border-[#b9ff66] focus-within:ring-2 focus-within:ring-[#b9ff66]">
                            <input type="radio" name="appointment_type" value="suivi" class="sr-only">
                            <span class="flex flex-col">
                                <span class="block text-sm font-medium text-gray-900">Suivi</span>
                                <span class="block text-xs text-gray-500">Visite de contrôle</span>
                            </span>
                        </label>
                        <label class="relative flex cursor-pointer rounded-lg border border-gray-200 p-4 hover:border-[#b9ff66] focus-within:ring-2 focus-within:ring-[#b9ff66]">
                            <input type="radio" name="appointment_type" value="urgent" class="sr-only">
                            <span class="flex flex-col">
                                <span class="block text-sm font-medium text-gray-900">Urgent</span>
                                <span class="block text-xs text-gray-500">Consultation prioritaire</span>
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Description / Motif de consultation -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Motif de consultation</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#b9ff66] focus:border-transparent"
                        placeholder="Décrivez brièvement la raison de votre consultation..."></textarea>
                    <p class="mt-1 text-xs text-gray-500">Ces informations aideront le médecin à mieux préparer votre consultation.</p>
                </div>
            </div>
        </div>

        <!-- Step 4: Dossier médical -->
        <div id="step4" class="hidden space-y-6">
            <h2 class="text-2xl font-bold">Dossier médical (optionnel)</h2>
            <div class="bg-white rounded-2xl p-6 border border-gray-100 max-w-2xl">
                <div class="space-y-4">
                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-8 text-center">
                        <div class="space-y-2">
                            <svg class="mx-auto w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3 3m0 0l-3-3m3 3V8"/>
                            </svg>
                            <div class="text-sm text-gray-500">
                                Glissez-déposez vos fichiers ici ou
                                <label class="text-[#b9ff66] hover:text-[#92cc52] cursor-pointer">
                                    <span>parcourez</span>
                                    <input type="file" class="hidden" multiple>
                                </label>
                            </div>
                            <p class="text-xs text-gray-400">PDF, JPG, PNG (max. 10 Mo)</p>
                        </div>
                    </div>

                    <!-- Liste des fichiers -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-sm">analyse.pdf</span>
                            </div>
                            <button class="text-red-500 hover:text-red-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Résumé du rendez-vous -->
        <div id="appointment-summary" class="mt-6 p-4 bg-gray-50 rounded-xl hidden">
            <h3 class="text-lg font-semibold mb-4">Résumé du rendez-vous</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-500">Médecin</p>
                    <p id="selected-medecin-name" class="font-medium"></p>
                    <p id="selected-medecin-specialite" class="text-sm text-[#b9ff66]"></p>
                    <p id="selected-medecin-adresse" class="text-sm text-gray-600"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Date et heure</p>
                    <p id="selected-date" class="font-medium"></p>
                    <p id="selected-time" class="text-sm text-gray-600"></p>
                </div>
            </div>
        </div>

        <!-- Dans l'étape de sélection de la date et heure -->
        <div id="step2" class="hidden">
            <h3 class="text-lg font-semibold mb-4">Choisissez une date et un créneau</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Sélection de la date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date du rendez-vous</label>
                    <input type="date"
                           id="date_rdv"
                           name="date_debut"
                           class="w-full rounded-lg border-gray-300 focus:border-[#b9ff66] focus:ring focus:ring-[#b9ff66] focus:ring-opacity-50"
                           min="{{ date('Y-m-d') }}"
                           onchange="chargerCreneauxDisponibles()">
                </div>

                <!-- Affichage des créneaux disponibles -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Créneaux disponibles</label>
                    <div id="creneaux-container" class="grid grid-cols-2 gap-2">
                        <!-- Les créneaux seront injectés ici dynamiquement -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation buttons -->
        <div class="flex justify-between mt-8">
            <button id="prevBtn" class="px-6 py-2.5 text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors hidden" onclick="goToPreviousStep()">
                Précédent
            </button>
            <button id="nextBtn" class="px-6 py-2.5 bg-black text-white rounded-xl hover:bg-gray-800 transition-colors" onclick="goToNextStep()">
                Suivant
            </button>
        </div>
    </div>

    <!-- Modal Fiche détaillée médecin -->
    <div id="doctorModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-2xl p-6 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-xl font-bold">Fiche détaillée du médecin</h3>
                <button onclick="closeDoctorDetails()" class="p-2 hover:bg-gray-100 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <!-- Contenu de la fiche détaillée -->
            <div class="space-y-6">
                <!-- Info médecin -->
                <div class="flex items-start gap-4">
                    <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="Dr. Foulen" class="w-24 h-24 rounded-xl object-cover">
                    <div>
                        <h4 class="text-xl font-semibold">Dr. Foulen Ben Foulen</h4>
                        <p class="text-[#b9ff66] font-medium">Cardiologue</p>
                        <div class="flex items-center gap-2 mt-1">
                            <div class="flex text-yellow-400">⭐⭐⭐⭐⭐</div>
                            <span class="text-sm text-gray-500">(150 avis)</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-2">15 ans d'expérience</p>
                    </div>
                </div>

                <!-- Détails pratiques -->
                <div class="grid grid-cols-2 gap-4 p-4 bg-gray-50 rounded-xl">
                    <div>
                        <h5 class="font-medium mb-2">Adresse du cabinet</h5>
                        <p class="text-sm text-gray-600">123 Rue Example, La Marsa, Tunis</p>
                    </div>
                    <div>
                        <h5 class="font-medium mb-2">Tarifs</h5>
                        <p class="text-sm text-gray-600">Consultation: 80 TND</p>
                        <p class="text-sm text-gray-600">Consultation en ligne: 60 TND</p>
                    </div>
                </div>

                <!-- Formation et expérience -->
                <div>
                    <h5 class="font-medium mb-2">Formation et expérience</h5>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• Doctorat en médecine - Faculté de Médecine de Tunis (2005)</li>
                        <li>• Spécialisation en cardiologie - Hôpital La Rabta (2010)</li>
                        <li>• Chef de service cardiologie - Clinique Example (2015-2020)</li>
                    </ul>
                </div>

                <!-- Langues parlées -->
                <div>
                    <h5 class="font-medium mb-2">Langues parlées</h5>
                    <div class="flex gap-2">
                        <span class="px-3 py-1 bg-gray-100 rounded-full text-sm">Arabe</span>
                        <span class="px-3 py-1 bg-gray-100 rounded-full text-sm">Français</span>
                        <span class="px-3 py-1 bg-gray-100 rounded-full text-sm">Anglais</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

// Variables globales
let currentStep = 1;
const totalSteps = 4;
let stepValidation = {
    1: false,
    2: false,
    3: true,  // Pour faciliter les tests, mettre à false en production
    4: true   // Dossier (optionnel, donc toujours valide)
};
let selectedSpecialite = '';
let selectedMedecinId = null;
let isInitialLoad = true;

// Attendre que le DOM soit chargé
document.addEventListener('DOMContentLoaded', function() {
    // Configuration initiale
    setupEventListeners();
    goToStep(1);

    // Gestion du formulaire famille
    setupFamilyForm();

    setTimeout(() => {
        isInitialLoad = false;
    }, 500);
});

// Configuration des écouteurs d'événements principaux
function setupEventListeners() {
    // Sélection de spécialité
    const specialtyCards = document.querySelectorAll('#step1 .group.cursor-pointer');
    specialtyCards.forEach(card => {
        card.addEventListener('click', function() {
            handleSpecialitySelection(this, specialtyCards);
        });
    });

    // Gestion des radios pour le type de patient
    const radioButtons = document.querySelectorAll('input[name="patient_type"]');
    radioButtons.forEach(radio => {
        radio.addEventListener('change', handlePatientTypeChange);
    });
}

// Gestion de la sélection de spécialité
function handleSpecialitySelection(selectedCard, allCards) {
    // Désélectionner toutes les cartes
    allCards.forEach(c => {
        const border = c.querySelector('.border');
        if (border) {
            border.classList.remove('border-[#b9ff66]');
            border.classList.add('border-gray-100');
        }
    });

    // Sélectionner la carte cliquée
    const selectedBorder = selectedCard.querySelector('.border');
    if (selectedBorder) {
        selectedBorder.classList.remove('border-gray-100');
        selectedBorder.classList.add('border-[#b9ff66]');
    }

    // Mettre à jour la spécialité sélectionnée
    selectedSpecialite = selectedCard.dataset.specialite;
    stepValidation[1] = true;
}

// Configuration du formulaire famille
function setupFamilyForm() {
    const familyForm = document.getElementById('familyForm');
    if (!familyForm) return;

    document.querySelectorAll('input[name="patient_type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            familyForm.classList.toggle('hidden', this.value !== 'family');
        });
    });
}

// Navigation entre les étapes
function goToStep(step) {
    if (step < 1 || step > totalSteps) return;
    if (step > currentStep + 1 && !stepValidation[currentStep]) {
        alert("Veuillez compléter l'étape actuelle avant de continuer.");
        return;
    }

    hideAllSteps();
    showStep(step);
    updateStepperUI(step);
    currentStep = step;
    updateButtons();

    // Actions spécifiques par étape
    if (step === 2 && selectedSpecialite) {
        loadMedecinsBySpecialite();
    }
}

function goToNextStep() {
    if (currentStep < totalSteps) {
        if (currentStep === 3) {
            if (!validateStep3()) {
                return;
            }
        }

        if (stepValidation[currentStep]) {
            goToStep(currentStep + 1);
        } else {
            alert("Veuillez compléter cette étape avant de continuer.");
        }
    } else {
        handleFinalStep();
    }
}

function goToPreviousStep() {
    if (currentStep > 1) {
        goToStep(currentStep - 1);
    }
}

// Fonctions utilitaires UI
function hideAllSteps() {
    for (let i = 1; i <= totalSteps; i++) {
        const step = document.getElementById(`step${i}`);
        if (step) step.classList.add('hidden');
    }
}

function showStep(step) {
    const stepElement = document.getElementById(`step${step}`);
    if (stepElement) stepElement.classList.remove('hidden');
}

function updateStepperUI(step) {
    // Mise à jour des cercles
    document.querySelectorAll('.step-circle').forEach((circle, index) => {
        if (index + 1 <= step) {
            circle.classList.remove('bg-gray-200', 'text-gray-600');
            circle.classList.add('bg-[#b9ff66]', 'text-gray-800');
        } else {
            circle.classList.remove('bg-[#b9ff66]', 'text-gray-800');
            circle.classList.add('bg-gray-200', 'text-gray-600');
        }
    });

    // Mise à jour des barres de progression
    for (let i = 1; i < totalSteps; i++) {
        const progress = document.getElementById(`progress-${i}-${i+1}`);
        if (progress) {
            progress.style.width = i < step ? '100%' : '0%';
        }
    }
}

function updateButtons() {
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');

    if (prevBtn) prevBtn.classList.toggle('hidden', currentStep === 1);
    if (nextBtn) {
        nextBtn.textContent = currentStep === totalSteps ? 'Confirmer' : 'Suivant';
    }
}

// Fonction de finalisation
function handleFinalStep() {
    // Récupérer le formulaire
    const form = document.getElementById('appointment-form');

    // Récupérer les valeurs
    const description = document.getElementById('description').value.trim();
    const appointmentType = document.querySelector('input[name="appointment_type"]:checked')?.value;

    // Validation des champs requis
    if (!form.medecin_id.value || !form.date_rdv.value || !form.heure_debut.value) {
        alert('Veuillez sélectionner un médecin, une date et une heure');
        return;
    }

    if (!appointmentType) {
        alert('Veuillez sélectionner un type de rendez-vous');
        return;
    }

    if (!description || description.length < 10) {
        alert('Veuillez fournir une description d\'au moins 10 caractères');
        return;
    }

    // Mettre à jour les champs cachés
    document.getElementById('type').value = appointmentType;
    document.getElementById('description_input').value = description;

    // Désactiver le bouton et montrer le chargement
    const nextBtn = document.getElementById('nextBtn');
    nextBtn.disabled = true;
    nextBtn.innerHTML = `
        <span class="inline-block animate-spin mr-2">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </span>
        Création en cours...
    `;

    // Soumettre le formulaire
    form.submit();
}

// Gestion du type de patient
function handlePatientTypeChange(event) {
    const familyForm = document.getElementById('familyForm');
    if (familyForm) {
        familyForm.classList.toggle('hidden', event.target.value !== 'family');
    }
}

// Configuration des écouteurs d'événements
function setupEventListeners() {

    // Sélection de spécialité
    const specialtyCards = document.querySelectorAll('#step1 .group.cursor-pointer');
    specialtyCards.forEach(card => {
        card.addEventListener('click', function() {
            // Désélectionner toutes les cartes
            specialtyCards.forEach(c => {
                c.querySelector('.border').classList.remove('border-[#b9ff66]');
                c.querySelector('.border').classList.add('border-gray-100');
            });

            // Sélectionner cette carte
            this.querySelector('.border').classList.remove('border-gray-100');
            this.querySelector('.border').classList.add('border-[#b9ff66]');

            // Stocker la spécialité sélectionnée
            selectedSpecialite = this.dataset.specialite;

            // Valider l'étape
            stepValidation[1] = true;
        });
    });

    // Charger le nombre de médecins pour chaque spécialité
    loadMedecinCounts();
}

// Fonction pour charger le nombre de médecins par spécialité
function loadMedecinCounts() {
    const specialites = ['Médecin de famille', 'Généraliste', 'Psychiatrie', 'Pédiatrie', 'Nutrition'];

    specialites.forEach(specialite => {
        fetch(`{{ route('patient.medecins.by.specialite') }}?specialite=${encodeURIComponent(specialite)}`)
            .then(response => response.json())
            .then(data => {
                const countElements = document.querySelectorAll(`.medecin-count[data-specialite="${specialite}"]`);
                countElements.forEach(el => {
                    el.textContent = `${data.count} médecin${data.count > 1 ? 's' : ''} disponible${data.count > 1 ? 's' : ''}`;
                });
            })
            .catch(error => {
                console.error('Erreur lors du chargement des médecins:', error);
                const countElements = document.querySelectorAll(`.medecin-count[data-specialite="${specialite}"]`);
                countElements.forEach(el => {
                    el.textContent = 'Erreur de chargement';
                });
            });
    });
}

// Fonction pour charger les médecins selon la spécialité sélectionnée
function loadMedecinsBySpecialite() {
    if (!selectedSpecialite) {
        return;
    }

    // Afficher le loader et cacher les autres conteneurs
    const loadingElement = document.getElementById('loading-medecins');
    const containerElement = document.getElementById('medecins-container');
    const noMedecinsElement = document.getElementById('no-medecins');

    if (loadingElement) loadingElement.classList.remove('hidden');
    if (containerElement) containerElement.classList.add('hidden');
    if (noMedecinsElement) noMedecinsElement.classList.add('hidden');

    // Réinitialiser la sélection du médecin
    selectedMedecinId = null;
    stepValidation[2] = false;

    fetch(`/api/medecins/by-specialite?specialite=${encodeURIComponent(selectedSpecialite)}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (loadingElement) loadingElement.classList.add('hidden');

            if (!data.medecins || data.medecins.length === 0) {
                if (noMedecinsElement) {
                    noMedecinsElement.classList.remove('hidden');
                    noMedecinsElement.innerHTML = '<p class="text-gray-500">Aucun médecin disponible pour cette spécialité.</p>';
                }
                return;
            }

            if (containerElement) {
                containerElement.innerHTML = ''; // Vider le conteneur
                containerElement.classList.remove('hidden');

            // Générer les cartes de médecins
            data.medecins.forEach(medecin => {
                const medecinCard = createMedecinCard(medecin);
                    containerElement.appendChild(medecinCard);
            });

            // Ajouter les écouteurs d'événements pour la sélection des médecins
            setupMedecinSelection();
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement des médecins:', error);

            if (loadingElement) loadingElement.classList.add('hidden');
            if (containerElement) containerElement.classList.add('hidden');

            if (noMedecinsElement) {
                noMedecinsElement.classList.remove('hidden');
                noMedecinsElement.innerHTML = `
                    <div class="text-center">
                        <p class="text-red-500 mb-2">Une erreur est survenue lors du chargement des médecins.</p>
                        <button onclick="retryLoadMedecins()"
                                class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                            Réessayer
                        </button>
                    </div>
                `;
            }
        });
}

// Fonction pour réessayer le chargement des médecins
function retryLoadMedecins() {
    loadMedecinsBySpecialite();
}

// Fonction pour configurer la sélection des médecins
function setupMedecinSelection() {
    // Ajouter les écouteurs d'événements pour les boutons "Voir fiche détaillée"
    document.querySelectorAll('.view-doctor-details').forEach(button => {
        button.addEventListener('click', (e) => {
            const medecinId = e.target.dataset.medecinId;
            if (medecinId) {
                showDoctorDetails(medecinId);
            }
        });
    });

    // Ajouter les écouteurs d'événements pour la sélection des cartes de médecins
    document.querySelectorAll('.medecin-card').forEach(card => {
        card.addEventListener('click', (e) => {
            if (!e.target.closest('.view-doctor-details')) {
                const medecinId = card.dataset.medecinId;
                if (medecinId) {
                    selectMedecin(medecinId);
                }
            }
        });
        });
}

// Fonction pour afficher les détails du médecin
function showDoctorDetails(medecinId) {
    // Afficher l'indicateur de chargement
    const modal = document.getElementById('doctorModal');
    modal.classList.remove('hidden');
    modal.classList.add('animate-fadeIn');

    modal.querySelector('.bg-white').classList.add('animate-scaleIn');

    // Récupérer les détails du médecin
    fetch(`{{ route('patient.medecins.by.specialite') }}?specialite=${encodeURIComponent(selectedSpecialite)}`)
        .then(response => response.json())
        .then(data => {
            const medecin = data.medecins.find(m => m.id == medecinId);

            if (medecin) {
                // Mettre à jour le contenu de la modal avec les données dynamiques

                // Info médecin
                const infoMedecin = modal.querySelector('.flex.items-start.gap-4');
                if (infoMedecin) {
                    // Utiliser la photo de profil du médecin ou une image par défaut
                    const imgSrc = medecin.profile_photo_url || `https://ui-avatars.com/api/?name=${encodeURIComponent(medecin.prenom+' '+medecin.nom)}&color=7F9CF5&background=EBF4FF`;

                    // Calculer le nombre d'années d'expérience
                    const experienceText = medecin.experience
                        ? `${medecin.experience} an${medecin.experience > 1 ? 's' : ''} d'expérience`
                        : 'Expérience non spécifiée';

                    // Générer un nombre aléatoire d'avis (à remplacer par des données réelles)
                    const nbAvis = Math.floor(Math.random() * 200) + 10;

                    infoMedecin.innerHTML = `
                        <img src="${imgSrc}" alt="Dr. ${medecin.prenom} ${medecin.nom}" class="w-24 h-24 rounded-xl object-cover">
                        <div>
                            <h4 class="text-xl font-semibold">Dr. ${medecin.prenom} ${medecin.nom}</h4>
                            <p class="text-[#b9ff66] font-medium">${medecin.specialite}</p>
                            <div class="flex items-center gap-2 mt-1">
                                <div class="flex text-yellow-400">${generateStars(medecin.score || 4)}</div>
                                <span class="text-sm text-gray-500">(${nbAvis} avis)</span>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">${experienceText}</p>
                        </div>
                    `;
                }

                // Détails pratiques
                const detailsPratiques = modal.querySelector('.grid.grid-cols-2.gap-4');
                if (detailsPratiques) {
                    // Générer des tarifs dynamiques (à remplacer par des données réelles)
                    const tarifConsultation = Math.floor(Math.random() * 50) + 50;
                    const tarifConsultationEnLigne = Math.floor(tarifConsultation * 0.8);

                    detailsPratiques.innerHTML = `
                        <div>
                            <h5 class="font-medium mb-2">Adresse du cabinet</h5>
                            <p class="text-sm text-gray-600">${medecin.adresse_cabinet || 'Adresse non spécifiée'}</p>
                        </div>
                        <div>
                            <h5 class="font-medium mb-2">Tarifs</h5>
                            <p class="text-sm text-gray-600">Consultation: ${tarifConsultation} TND</p>
                            <p class="text-sm text-gray-600">Consultation en ligne: ${tarifConsultationEnLigne} TND</p>
                        </div>
                    `;
                }

                // Formation et expérience
                const formationDiv = modal.querySelector('h5.font-medium + ul').parentElement;
                if (formationDiv) {
                    let formationHTML = '<h5 class="font-medium mb-2">Formation et expérience</h5>';

                    if (medecin.formation && medecin.formation.trim() !== '') {
                        // Traiter la formation comme une liste
                        const formationItems = medecin.formation.split('\n').filter(item => item.trim() !== '');

                        if (formationItems.length > 0) {
                            formationHTML += '<ul class="space-y-2 text-sm text-gray-600">';
                            formationItems.forEach(item => {
                                formationHTML += `<li>• ${item}</li>`;
                            });
                            formationHTML += '</ul>';
                        } else {
                            formationHTML += `<p class="text-sm text-gray-600">${medecin.formation}</p>`;
                        }
                    } else {
                        formationHTML += '<p class="text-sm text-gray-600">Information non disponible</p>';
                    }

                    formationDiv.innerHTML = formationHTML;
                }

                // Langues parlées
                const languesDiv = modal.querySelector('h5.font-medium + div.flex').parentElement;
                if (languesDiv) {
                    let languesHTML = '<h5 class="font-medium mb-2">Langues parlées</h5>';

                    if (medecin.langues && medecin.langues.length > 0) {
                        languesHTML += '<div class="flex flex-wrap gap-2">';
                        medecin.langues.forEach(langue => {
                            languesHTML += `<span class="px-3 py-1 bg-gray-100 rounded-full text-sm">${langue}</span>`;
                        });
                        languesHTML += '</div>';
                    } else {
                        languesHTML += '<p class="text-sm text-gray-600">Information non disponible</p>';
                    }

                    languesDiv.innerHTML = languesHTML;
                }
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement des détails du médecin:', error);
            modal.querySelector('.space-y-6').innerHTML = `
                <div class="text-center py-8">
                    <p class="text-red-500">Erreur lors du chargement des détails du médecin.</p>
                    <button onclick="closeDoctorDetails()" class="mt-4 px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                        Fermer
                    </button>
                </div>
            `;
        });
}

// Fonction pour créer une carte de médecin
function createMedecinCard(medecin) {
    const card = document.createElement('div');
    card.className = 'bg-white rounded-2xl p-6 border border-gray-100 medecin-card transition-all duration-300 hover:shadow-lg';
    card.dataset.medecinId = medecin.id;

    // Utiliser la photo de profil du médecin ou une image par défaut
    const imgSrc = medecin.profile_photo_url || `https://ui-avatars.com/api/?name=${encodeURIComponent(medecin.prenom+' '+medecin.nom)}&color=7F9CF5&background=EBF4FF`;

    // Générer un nombre d'avis dynamique (à remplacer par des données réelles)
    const nbAvis = Math.floor(Math.random() * 200);

    card.innerHTML = `
        <div class="flex items-start gap-4 mb-4">
            <img src="${imgSrc}" alt="Dr. ${medecin.nom}" class="w-16 h-16 rounded-xl object-cover">
            <div>
                <h3 class="text-lg font-semibold">Dr. ${medecin.prenom} ${medecin.nom}</h3>
                <p class="text-[#b9ff66] text-sm font-medium">${medecin.specialite}</p>
                <div class="flex items-center gap-2 mt-1">
                    <div class="flex text-yellow-400 text-sm">${generateStars(medecin.score || 4)}</div>
                    <span class="text-sm text-gray-500">(${nbAvis} avis)</span>
                </div>
            </div>
            <button class="ml-auto text-sm text-[#b9ff66] hover:text-[#92cc52] view-doctor-details transition-colors duration-200" data-medecin-id="${medecin.id}">
                Voir fiche détaillée
            </button>
        </div>

        <!-- Sélecteur de date -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Date du rendez-vous</label>
            <input type="date"
                   class="w-full rounded-lg border-gray-300 focus:border-[#b9ff66] focus:ring focus:ring-[#b9ff66] focus:ring-opacity-50"
                   min="${new Date().toISOString().split('T')[0]}"
                   onchange="loadCreneauxForMedecin(${medecin.id}, this.value)"
                   data-medecin-id="${medecin.id}">
        </div>

        <!-- Créneaux disponibles -->
        <div class="space-y-3">
            <h4 class="font-medium">Créneaux disponibles</h4>
            <div id="creneaux-medecin-${medecin.id}" class="grid grid-cols-3 gap-2">
                <p class="text-center text-gray-500 col-span-3">Sélectionnez une date pour voir les créneaux disponibles</p>
            </div>
        </div>
    `;

    return card;
}

// Fonction pour charger les créneaux d'un médecin
function loadCreneauxForMedecin(medecinId, date) {
    const creneauxContainer = document.getElementById(`creneaux-medecin-${medecinId}`);
    if (!creneauxContainer) return;

    creneauxContainer.innerHTML = '<p class="text-center text-gray-500 col-span-3">Chargement des créneaux...</p>';

    // Mettre à jour la date dans le résumé si elle existe déjà
    updateDateInSummary(date);

    fetch(`/api/medecins/${medecinId}/creneaux-disponibles?date=${date}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                creneauxContainer.innerHTML = `<p class="text-red-500 col-span-3 text-center">${data.error}</p>`;
                return;
            }

            if (!data.creneaux_disponibles || data.creneaux_disponibles.length === 0) {
                creneauxContainer.innerHTML = '<p class="text-gray-500 col-span-3 text-center">Aucun créneau disponible pour cette date</p>';
                return;
            }

            creneauxContainer.innerHTML = data.creneaux_disponibles
                .map(creneau => `
                    <button type="button"
                            onclick="selectCreneauAndMedecin(${medecinId}, '${date}', '${creneau.heure_debut}')"
                            class="p-2 text-sm text-center border rounded-lg hover:bg-[#b9ff66]/10 hover:border-[#b9ff66] transition-colors creneau-btn">
                        ${creneau.heure_debut} - ${creneau.heure_fin}
                    </button>
                `)
                .join('');
        })
        .catch(error => {
            console.error('Erreur:', error);
            creneauxContainer.innerHTML = '<p class="text-red-500 col-span-3 text-center">Erreur lors du chargement des créneaux</p>';
        });
}

// Fonction pour mettre à jour la date dans le résumé
function updateDateInSummary(date) {
    const selectedDate = document.getElementById('selected-date');
    if (selectedDate) {
        const dateFormatee = new Date(date).toLocaleDateString('fr-FR', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        selectedDate.textContent = dateFormatee;
    }
}

// Fonction pour mettre à jour l'heure dans le résumé
function updateTimeInSummary(heure) {
    const selectedTime = document.getElementById('selected-time');
    if (selectedTime) {
        selectedTime.textContent = `${heure} - ${getEndTime(heure, 30)}`;
    }
}

// Fonction pour sélectionner un créneau et un médecin
function selectCreneauAndMedecin(medecinId, date, heure) {
    // Mettre à jour les champs cachés
    const medecinIdInput = document.getElementById('medecin_id');
    const dateRdvInput = document.getElementById('date_rdv');
    const heureDebutInput = document.getElementById('heure_debut');

    if (!medecinIdInput || !dateRdvInput || !heureDebutInput) {
        console.error('Les champs de formulaire requis sont manquants');
        return;
    }

    // Mettre à jour les champs cachés
    medecinIdInput.value = medecinId;
    dateRdvInput.value = date;
    heureDebutInput.value = heure;

    // Mettre à jour l'UI des créneaux
    const allCreneauBtns = document.querySelectorAll('.creneau-btn');
    allCreneauBtns.forEach(btn => {
        btn.classList.remove('bg-[#b9ff66]/10', 'border-[#b9ff66]', 'font-medium');
    });

    if (event && event.target) {
        event.target.classList.add('bg-[#b9ff66]/10', 'border-[#b9ff66]', 'font-medium');
    }

    // Mettre à jour le résumé
    updateAppointmentSummary(medecinId, date, heure);
}

// Fonction pour mettre à jour le résumé complet du rendez-vous
function updateAppointmentSummary(medecinId, date, heure) {
    const summaryDiv = document.getElementById('appointment-summary');

    if (!summaryDiv) {
        console.error('Le conteneur du résumé est manquant');
        return;
    }

    // Afficher le conteneur du résumé s'il était caché
    summaryDiv.classList.remove('hidden');

    // Mettre à jour la date et l'heure immédiatement
    updateDateInSummary(date);
    updateTimeInSummary(heure);

    // Charger les informations du médecin
    fetch(`/api/medecins/${medecinId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Erreur réseau');
        return response.json();
    })
    .then(medecin => {
        // Mettre à jour les informations du médecin dans le résumé
        const selectedMedecinName = document.getElementById('selected-medecin-name');
        const selectedMedecinSpecialite = document.getElementById('selected-medecin-specialite');
        const selectedMedecinAdresse = document.getElementById('selected-medecin-adresse');

        if (selectedMedecinName) selectedMedecinName.textContent = `Dr. ${medecin.prenom} ${medecin.nom}`;
        if (selectedMedecinSpecialite) selectedMedecinSpecialite.textContent = medecin.specialite;
        if (selectedMedecinAdresse) selectedMedecinAdresse.textContent = medecin.adresse_cabinet || 'Adresse non spécifiée';

                // Valider l'étape
                stepValidation[2] = true;
    })
    .catch(error => {
        console.error('Erreur:', error);
        summaryDiv.innerHTML = `
            <div class="text-red-500 p-4 text-center">
                <p>Erreur lors du chargement des informations du médecin</p>
                <button onclick="retryLoadMedecinInfo(${medecinId}, '${date}', '${heure}')"
                        class="mt-2 px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                    Réessayer
                </button>
            </div>
        `;
    });
}

// Fonction pour réessayer le chargement des informations
function retryLoadMedecinInfo(medecinId, date, heure) {
    updateAppointmentSummary(medecinId, date, heure);
}

// Fonction utilitaire pour calculer l'heure de fin
function getEndTime(startTime, durationMinutes) {
    const [hours, minutes] = startTime.split(':').map(Number);
    const startDate = new Date();
    startDate.setHours(hours, minutes, 0);

    const endDate = new Date(startDate.getTime() + durationMinutes * 60000);
    return endDate.toLocaleTimeString('fr-FR', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: false
    });
}

// Ajouter cette fonction avant createMedecinCard
function generateStars(score) {
    // S'assurer que le score est entre 0 et 5
    const normalizedScore = Math.min(Math.max(score, 0), 5);

    // SVG pour les différents types d'étoiles
    const fullStar = `<svg class="w-4 h-4 text-yellow-400 inline" fill="currentColor" viewBox="0 0 20 20">
        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
    </svg>`;

    const emptyStar = `<svg class="w-4 h-4 text-gray-300 inline" fill="currentColor" viewBox="0 0 20 20">
        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
    </svg>`;

    let starsHTML = '';

    // Générer les étoiles
    for (let i = 1; i <= 5; i++) {
        if (i <= normalizedScore) {
            starsHTML += fullStar;
        } else {
            starsHTML += emptyStar;
        }
    }

    return starsHTML;
}

function validateStep3() {
    const description = document.getElementById('description').value.trim();
    const appointmentType = document.querySelector('input[name="appointment_type"]:checked');

    if (!appointmentType) {
        alert("Veuillez sélectionner un type de rendez-vous");
        return false;
    }

    if (description.length < 10) {
        alert("Veuillez fournir une description d'au moins 10 caractères");
        return false;
    }

    // Si c'est pour un membre de la famille, vérifier les champs supplémentaires
    const patientType = document.querySelector('input[name="patient_type"]:checked').value;
    if (patientType === 'family') {
        const familyForm = document.getElementById('familyForm');
        const prenom = familyForm.querySelector('input[type="text"]').value.trim();
        const nom = familyForm.querySelector('input[type="text"]:last-of-type').value.trim();

        if (!prenom || !nom) {
            alert("Veuillez remplir tous les champs pour le membre de la famille");
            return false;
        }
    }

    return true;
}
</script>

<style>
    body {
        font-family: 'Inter', sans-serif;
    }

    .grid > div {
        opacity: 0;
        animation: fadeUp 0.6s ease-out forwards;
    }

    @keyframes fadeUp {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .grid > div:nth-child(1) { animation-delay: 0.1s; }
    .grid > div:nth-child(2) { animation-delay: 0.2s; }
    .grid > div:nth-child(3) { animation-delay: 0.3s; }

    .spinner {
        border: 3px solid #f3f3f3;
        border-radius: 50%;
        border-top: 3px solid #b9ff66;
        width: 24px;
        height: 24px;
        animation: spin 1s linear infinite;
        margin: 0 auto;
        margin-bottom: 8px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endsection
