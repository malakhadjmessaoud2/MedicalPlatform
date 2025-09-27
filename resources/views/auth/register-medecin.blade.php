<x-guest-layout>
    <div
        class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-blue-50 to-teal-50 py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        <!-- Arrière-plan animé avec Tailwind CSS -->
        <div class="absolute inset-0 z-0 overflow-hidden">
            <!-- Points animés -->
            <div class="absolute h-2 w-2 rounded-full bg-[#4dabb4]/30 top-[10%] left-[10%] animate-float-slow"></div>
            <div class="absolute h-3 w-3 rounded-full bg-[#8bc34a]/30 top-[15%] left-[20%] animate-float-medium"></div>
            <div class="absolute h-2 w-2 rounded-full bg-[#ff9800]/30 top-[20%] left-[30%] animate-float-fast"></div>
            <div class="absolute h-4 w-4 rounded-full bg-[#e91e63]/30 top-[25%] left-[40%] animate-float-slow"></div>
            <div class="absolute h-3 w-3 rounded-full bg-[#4dabb4]/30 top-[30%] left-[50%] animate-float-medium"></div>
            <div class="absolute h-2 w-2 rounded-full bg-[#8bc34a]/30 top-[35%] left-[60%] animate-float-fast"></div>
            <div class="absolute h-4 w-4 rounded-full bg-[#ff9800]/30 top-[40%] left-[70%] animate-float-slow"></div>
            <div class="absolute h-3 w-3 rounded-full bg-[#e91e63]/30 top-[45%] left-[80%] animate-float-medium"></div>
            <div class="absolute h-2 w-2 rounded-full bg-[#4dabb4]/30 top-[50%] left-[90%] animate-float-fast"></div>

            <!-- Cercles plus grands et plus flous pour l'effet de profondeur -->
            <div class="absolute h-32 w-32 rounded-full bg-[#4dabb4]/5 blur-xl top-[20%] left-[20%] animate-pulse-slow">
            </div>
            <div
                class="absolute h-40 w-40 rounded-full bg-[#8bc34a]/5 blur-xl top-[40%] left-[60%] animate-pulse-medium">
            </div>
            <div class="absolute h-36 w-36 rounded-full bg-[#ff9800]/5 blur-xl top-[70%] left-[30%] animate-pulse-fast">
            </div>
            <div class="absolute h-48 w-48 rounded-full bg-[#e91e63]/5 blur-xl top-[80%] left-[70%] animate-pulse-slow">
            </div>
        </div>

        <div class="w-full max-w-2xl relative z-10">
            <div class="text-center mb-6 animate__animated animate__fadeIn">
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">Inscription - Compte Médecin</h2>
                <p class="mt-2 text-sm text-gray-600">Créez votre compte professionnel pour accéder à votre espace
                    médecin</p>
            </div>

            <div
                class="bg-white/90 backdrop-blur-sm shadow-xl rounded-2xl overflow-hidden animate__animated animate__fadeIn animate__delay-1s">
                <div class="p-8">
                    <x-validation-errors class="mb-4" />

                    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf

                        <!-- Champ caché pour définir le rôle -->
                        <input type="hidden" name="role" value="medecin">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nom -->
                            <div>
                                <x-label for="name" value="{{ __('Nom') }}" class="text-gray-700 font-medium" />
                            <div class="form-field mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <x-input id="name"
                                        class="block mt-1 w-full pl-10 border-gray-300 focus:border-[#4dabb4] focus:ring focus:ring-[#4dabb4]/20 transition-all duration-200"
                                        type="text" name="nom" :value="old('nom')" required autofocus
                                        autocomplete="name" placeholder="Dupont" />
                                </div>
                            </div>

                            <!-- Prénom -->
                            <div>
                                <x-label for="prenom" value="{{ __('Prénom') }}" class="text-gray-700 font-medium" />
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <x-input id="prenom"
                                        class="block mt-1 w-full pl-10 border-gray-300 focus:border-[#4dabb4] focus:ring focus:ring-[#4dabb4]/20 transition-all duration-200"
                                        type="text" name="prenom" :value="old('prenom')" required placeholder="Jean" />
                                </div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <x-label for="email" value="{{ __('Email') }}" class="text-gray-700 font-medium" />
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                    </svg>
                                </div>
                                <x-input id="email"
                                    class="block mt-1 w-full pl-10 border-gray-300 focus:border-[#4dabb4] focus:ring focus:ring-[#4dabb4]/20 transition-all duration-200"
                                    type="email" name="email" :value="old('email')" required autocomplete="username"
                                    placeholder="docteur@exemple.com" />
                            </div>
                        </div>

                        <!-- Spécialité -->
                        <div>
                            <x-label for="specialite" value="{{ __('Spécialité') }}"
                                class="text-gray-700 font-medium" />
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                    </svg>
                                </div>
                                <select id="specialite" name="specialite"
                                    class="block mt-1 w-full pl-10 border-gray-300 focus:border-[#4dabb4] focus:ring focus:ring-[#4dabb4]/20 transition-all duration-200 rounded-md shadow-sm"
                                    required>
                                    <option value="" disabled selected>Sélectionnez votre spécialité</option>
                                    <option value="Médecin de famille"
                                        {{ old('specialite') == 'Médecin de famille' ? 'selected' : '' }}>Médecin de
                                        famille</option>
                                    <option value="Généraliste"
                                        {{ old('specialite') == 'Généraliste' ? 'selected' : '' }}>Généraliste</option>
                                    <option value="Psychiatrie"
                                        {{ old('specialite') == 'Psychiatrie' ? 'selected' : '' }}>Psychiatrie /
                                        Psychologie</option>
                                    <option value="Pédiatrie" {{ old('specialite') == 'Pédiatrie' ? 'selected' : '' }}>
                                        Pédiatrie</option>
                                    <option value="Nutrition" {{ old('specialite') == 'Nutrition' ? 'selected' : '' }}>
                                        Nutrition</option>
                                </select>
                            </div>
                        </div>

                        <!-- Photo de profil -->
                        <div>
                            <x-label for="profile_photo" value="{{ __('Photo de profil') }}"
                                class="text-gray-700 font-medium" />
                            <div class="mt-2">
                                <!-- Zone de dépôt pour photo de profil -->
                                <div id="drop-zone-profile"
                                    class="drop-zone border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-[#4dabb4] hover:bg-[#4dabb4]/5 transition-all duration-300 cursor-pointer group">

                                    <div class="flex flex-col items-center space-y-4">
                                <div class="relative">
                                            <!-- Icône par défaut -->
                                            <div id="default-icon-profile"
                                                class="h-24 w-24 mx-auto rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center group-hover:from-[#4dabb4]/10 group-hover:to-[#4dabb4]/20 transition-all duration-300">
                                                <svg class="h-12 w-12 text-gray-400 group-hover:text-[#4dabb4] transition-colors duration-300"
                                                    fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    </div>

                                            <!-- Aperçu image -->
                                    <div id="preview" class="absolute inset-0 rounded-full overflow-hidden hidden">
                                        <img id="preview-image" src="#" alt="Aperçu"
                                            class="h-full w-full object-cover">
                                    </div>

                                            <!-- Badge de statut -->
                                            <div id="profile-status" class="absolute -bottom-1 -right-1 hidden">
                                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-green-500 text-white text-xs font-medium">
                                                    ✓
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Texte descriptif -->
                                        <div>
                                            <p class="text-base font-medium text-gray-900 group-hover:text-[#4dabb4] transition-colors duration-300">
                                                Ajoutez votre photo de profil
                                            </p>
                                            <p class="text-sm text-gray-500 mt-1">
                                                ou <span class="text-[#4dabb4] font-medium">cliquez pour parcourir</span>
                                            </p>
                                        </div>

                                        <!-- Types de fichiers acceptés -->
                                        <div class="flex items-center space-x-4 text-xs text-gray-400">
                                            <div class="flex items-center space-x-1">
                                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                                </svg>
                                                <span>JPG, PNG, GIF</span>
                                            </div>
                                        </div>

                                        <!-- Taille maximale -->
                                        <p class="text-xs text-gray-400">Taille maximale: 1MB</p>
                                </div>

                                    <!-- Input file caché -->
                                    <input type="file" id="profile_photo" name="profile_photo" class="sr-only"
                                        accept="image/*" onchange="previewImage()">
                                </div>

                                <!-- Info du fichier sélectionné -->
                                <div id="profile-file-info" class="mt-3 hidden">
                                    <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                                        <div class="flex items-center space-x-3">
                                            <div class="h-10 w-10 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center">
                                                <img id="profile-file-preview" src="#" alt="Aperçu" class="h-full w-full object-cover">
                                            </div>
                                            <div>
                                                <p id="profile-file-name" class="text-sm font-medium text-gray-900"></p>
                                                <p id="profile-file-size" class="text-xs text-gray-500"></p>
                                            </div>
                                        </div>
                                        <button type="button" onclick="removeProfileFile()"
                                            class="text-gray-400 hover:text-red-500 transition-colors duration-200">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <!-- Adresse du cabinet -->
                        <div>
                            <x-label for="adresse_cabinet" value="{{ __('Adresse du cabinet') }}"
                                class="text-gray-700 font-medium" />
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <x-input id="adresse_cabinet"
                                    class="block mt-1 w-full pl-10 border-gray-300 focus:border-[#4dabb4] focus:ring focus:ring-[#4dabb4]/20 transition-all duration-200"
                                    type="text" name="adresse_cabinet" :value="old('adresse_cabinet')" required
                                    placeholder="25 Rue de la Santé, Tunis" />
                            </div>
                        </div>

                        <!-- Années d'expérience -->
                        <div>
                            <x-label for="experience" value="{{ __('Années d expérience') }}"
                                class="text-gray-700 font-medium" />
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <x-input id="experience"
                                    class="block mt-1 w-full pl-10 border-gray-300 focus:border-[#4dabb4] focus:ring focus:ring-[#4dabb4]/20 transition-all duration-200"
                                    type="number" name="experience" :value="old('experience')" required min="0"
                                    max="70" placeholder="10" />
                            </div>
                        </div>

                        <!-- Formation et expérience -->
                        <div>
                            <x-label for="formation" value="{{ __('Formation et parcours professionnel') }}"
                                class="text-gray-700 font-medium" />
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <textarea id="formation" name="formation" rows="3"
                                    class="block w-full border-gray-300 focus:border-[#4dabb4] focus:ring focus:ring-[#4dabb4]/20 transition-all duration-200 rounded-md shadow-sm"
                                    placeholder="Décrivez votre formation et votre parcours professionnel">{{ old('formation') }}</textarea>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Diplômes, certifications, postes occupés, etc.</p>
                        </div>
                       <!-- Diplôme ou CNOM (image ou PDF) -->
                        <div>
                            <x-label for="DiplômeOrCNOM" value="{{ __('Diplôme ou CNOM') }}"
                                class="text-gray-700 font-medium" />
                            <div class="mt-2">
                                <!-- Zone de dépôt améliorée -->
                                <div id="drop-zone-diplome"
                                    class="drop-zone border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-[#4dabb4] hover:bg-[#4dabb4]/5 transition-all duration-300 cursor-pointer group">

                                    <!-- Icône principale -->
                                    <div class="flex flex-col items-center space-y-4">
        <div class="relative">
                                            <!-- Icône par défaut -->
                                            <div id="default-icon-diplome"
                                                class="h-20 w-20 mx-auto rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center group-hover:from-[#4dabb4]/10 group-hover:to-[#4dabb4]/20 transition-all duration-300">
                                                <svg class="h-10 w-10 text-gray-400 group-hover:text-[#4dabb4] transition-colors duration-300"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>

                                            <!-- Aperçu image -->
                                            <div id="preview-diplome" class="absolute inset-0 rounded-xl overflow-hidden hidden">
                                                <img id="preview-image-diplome" src="#" alt="Aperçu Diplôme"
                                                    class="h-full w-full object-cover">
                                            </div>

                                            <!-- Badge PDF -->
                                            <div id="pdf-badge" class="absolute -top-2 -right-2 hidden">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    PDF
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Texte descriptif -->
                                        <div>
                                            <p class="text-lg font-medium text-gray-900 group-hover:text-[#4dabb4] transition-colors duration-300">
                                                Glissez-déposez votre diplôme ici
                                            </p>
                                            <p class="text-sm text-gray-500 mt-1">
                                                ou <span class="text-[#4dabb4] font-medium">cliquez pour parcourir</span>
                                            </p>
                                        </div>

                                        <!-- Types de fichiers acceptés -->
                                        <div class="flex items-center space-x-4 text-xs text-gray-400">
                                            <div class="flex items-center space-x-1">
                                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                                </svg>
                                                <span>JPG, PNG</span>
                                            </div>
                                            <div class="flex items-center space-x-1">
                                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                                </svg>
                                                <span>PDF</span>
            </div>
        </div>

                                        <!-- Taille maximale -->
                                        <p class="text-xs text-gray-400">Taille maximale: 1MB</p>
                                    </div>

                                    <!-- Input file caché -->
                                    <input type="file" id="DiplômeOrCNOM" name="DiplômeOrCNOM" class="sr-only"
                                        accept="image/*,application/pdf" onchange="previewDiplome()">
                                </div>

                                <!-- Nom du fichier sélectionné -->
                                <div id="file-info" class="mt-3 hidden">
                                    <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                                        <div class="flex items-center space-x-3">
                                            <div id="file-icon" class="flex-shrink-0">
                                                <!-- Icône sera ajoutée dynamiquement -->
                                            </div>
                                            <div>
                                                <p id="file-name" class="text-sm font-medium text-gray-900"></p>
                                                <p id="file-size" class="text-xs text-gray-500"></p>
                                            </div>
                                        </div>
                                        <button type="button" onclick="removeFile()"
                                            class="text-gray-400 hover:text-red-500 transition-colors duration-200">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
    </div>
</div>


                        <!-- Langues parlées -->
                        <div>
                            <x-label for="langues" value="{{ __('Langues parlées') }}"
                                class="text-gray-700 font-medium" />
                            <div class="mt-1 flex flex-wrap gap-4">
                                <div class="checkbox-custom">
                                    <input id="langue_francais" name="langues[]" type="checkbox" value="Français"
                                        {{ is_array(old('langues')) && in_array('Français', old('langues')) ? 'checked' : '' }}>
                                    <label for="langue_francais" class="text-sm text-gray-700 font-medium">Français</label>
                                </div>
                                <div class="checkbox-custom">
                                    <input id="langue_arabe" name="langues[]" type="checkbox" value="Arabe"
                                        {{ is_array(old('langues')) && in_array('Arabe', old('langues')) ? 'checked' : '' }}>
                                    <label for="langue_arabe" class="text-sm text-gray-700 font-medium">Arabe</label>
                                </div>
                                <div class="checkbox-custom">
                                    <input id="langue_anglais" name="langues[]" type="checkbox" value="Anglais"
                                        {{ is_array(old('langues')) && in_array('Anglais', old('langues')) ? 'checked' : '' }}>
                                    <label for="langue_anglais" class="text-sm text-gray-700 font-medium">Anglais</label>
                                </div>
                                <div class="checkbox-custom flex items-center">
                                    <input id="langue_autre" name="langues_autre" type="checkbox"
                                        {{ old('langues_autre') ? 'checked' : '' }}>
                                    <label for="langue_autre" class="text-sm text-gray-700 font-medium">Autre:</label>
                                    <input type="text" name="langues_autre_texte"
                                        class="ml-3 text-sm border-gray-300 focus:border-[#4dabb4] focus:ring focus:ring-[#4dabb4]/20 rounded-md px-3 py-1"
                                        placeholder="Précisez" value="{{ old('langues_autre_texte') }}">
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Mot de passe -->
                            <div>
                                <x-label for="password" value="{{ __('Mot de passe') }}"
                                    class="text-gray-700 font-medium" />
                                <div class="form-field mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <x-input id="password"
                                        class="block mt-1 w-full pl-10 pr-10 border-gray-300 focus:border-[#4dabb4] focus:ring focus:ring-[#4dabb4]/20 transition-all duration-200"
                                        type="password" name="password" required autocomplete="new-password"
                                        placeholder="••••••••" oninput="validatePassword()" />
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <button type="button" id="toggle-password" onclick="togglePasswordVisibility()"
                                            class="password-toggle text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                            <svg id="eye-icon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Indicateurs de force du mot de passe -->
                                <div class="mt-2">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-medium text-gray-600">Force du mot de passe</span>
                                        <span id="password-strength-text" class="text-xs font-medium text-gray-400">Faible</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div id="password-strength-bar" class="h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                                    </div>
                                </div>

                                <!-- Critères de validation -->
                                <div class="mt-3 space-y-1">
                                    <div class="flex items-center text-xs">
                                        <div id="length-check" class="password-check w-4 h-4 rounded-full border-2 border-gray-300 mr-2 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white hidden" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <span id="length-text" class="text-gray-500">Au moins 8 caractères</span>
                                    </div>
                                    <div class="flex items-center text-xs">
                                        <div id="uppercase-check" class="password-check w-4 h-4 rounded-full border-2 border-gray-300 mr-2 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white hidden" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <span id="uppercase-text" class="text-gray-500">Une majuscule</span>
                                    </div>
                                    <div class="flex items-center text-xs">
                                        <div id="lowercase-check" class="password-check w-4 h-4 rounded-full border-2 border-gray-300 mr-2 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white hidden" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <span id="lowercase-text" class="text-gray-500">Une minuscule</span>
                                    </div>
                                    <div class="flex items-center text-xs">
                                        <div id="number-check" class="password-check w-4 h-4 rounded-full border-2 border-gray-300 mr-2 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white hidden" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <span id="number-text" class="text-gray-500">Un chiffre</span>
                                    </div>
                                    <div class="flex items-center text-xs">
                                        <div id="special-check" class="password-check w-4 h-4 rounded-full border-2 border-gray-300 mr-2 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white hidden" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <span id="special-text" class="text-gray-500">Un caractère spécial (!@#$%^&*)</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Confirmation mot de passe -->
                            <div>
                                <x-label for="password_confirmation" value="{{ __('Confirmer le mot de passe') }}"
                                    class="text-gray-700 font-medium" />
                                <div class="form-field mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <x-input id="password_confirmation"
                                        class="block mt-1 w-full pl-10 pr-10 border-gray-300 focus:border-[#4dabb4] focus:ring focus:ring-[#4dabb4]/20 transition-all duration-200"
                                        type="password" name="password_confirmation" required
                                        autocomplete="new-password" placeholder="••••••••" oninput="validatePasswordConfirmation()" />
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <button type="button" id="toggle-password-confirmation" onclick="togglePasswordConfirmationVisibility()"
                                            class="password-toggle text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                            <svg id="eye-icon-confirmation" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Indicateur de correspondance -->
                                <div class="mt-2">
                                    <div class="flex items-center text-xs">
                                        <div id="match-check" class="password-check w-4 h-4 rounded-full border-2 border-gray-300 mr-2 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white hidden" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <span id="match-text" class="text-gray-500">Les mots de passe correspondent</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                            <div>
                                <x-label for="terms">
                                    <div class="flex items-center">
                                        <x-checkbox name="terms" id="terms" required
                                            class="text-[#4dabb4] focus:ring-[#4dabb4]" />

                                        <div class="ms-2">
                                            {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                                'terms_of_service' =>
                                                    '<a target="_blank" href="' .
                                                    route('terms.show') .
                                                    '" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4dabb4]">' .
                                                    __('Terms of Service') .
                                                    '</a>',
                                                'privacy_policy' =>
                                                    '<a target="_blank" href="' .
                                                    route('policy.show') .
                                                    '" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4dabb4]">' .
                                                    __('Privacy Policy') .
                                                    '</a>',
                                            ]) !!}
                                        </div>
                                    </div>
                                </x-label>
                            </div>
                        @endif

                        <div class="flex items-center justify-between mt-8">
                            <a class="text-sm text-[#4dabb4] hover:text-[#3d8a91] transition-colors duration-200 flex items-center"
                                href="{{ route('login') }}">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                                </svg>
                                {{ __('Déjà inscrit?') }}
                            </a>

                            <button type="submit"
                                class="btn-primary group relative flex justify-center py-3 px-6 border border-transparent text-sm font-medium rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4dabb4] transition-all duration-300 shadow-lg">
                                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                    <svg class="h-5 w-5 text-[#3d8a91] group-hover:text-[#2d6a71]" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </span>
                                {{ __('S\'inscrire') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Animations personnalisées */
        @keyframes float-slow {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }

        @keyframes float-medium {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(-3deg); }
        }

        @keyframes float-fast {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(2deg); }
        }

        @keyframes pulse-slow {
            0%, 100% { opacity: 0.3; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(1.05); }
        }

        @keyframes pulse-medium {
            0%, 100% { opacity: 0.4; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.03); }
        }

        @keyframes pulse-fast {
            0%, 100% { opacity: 0.5; transform: scale(1); }
            50% { opacity: 0.8; transform: scale(1.02); }
        }

        .animate-float-slow { animation: float-slow 6s ease-in-out infinite; }
        .animate-float-medium { animation: float-medium 4s ease-in-out infinite; }
        .animate-float-fast { animation: float-fast 3s ease-in-out infinite; }
        .animate-pulse-slow { animation: pulse-slow 8s ease-in-out infinite; }
        .animate-pulse-medium { animation: pulse-medium 6s ease-in-out infinite; }
        .animate-pulse-fast { animation: pulse-fast 4s ease-in-out infinite; }

        /* Styles pour les zones de dépôt */
        .drop-zone {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .drop-zone:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .drop-zone.drag-over {
            transform: scale(1.02);
            box-shadow: 0 15px 35px rgba(77, 171, 180, 0.2);
        }

        /* Styles pour les icônes de fichier */
        .file-icon {
            transition: all 0.2s ease;
        }

        .file-icon:hover {
            transform: scale(1.1);
        }

        /* Styles pour les badges */
        .badge {
            animation: pulse 2s infinite;
        }

        /* Amélioration des champs de formulaire */
        .form-field {
            transition: all 0.3s ease;
        }

        .form-field:focus-within {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(77, 171, 180, 0.15);
        }

        /* Styles pour les boutons */
        .btn-primary {
            background: linear-gradient(135deg, #4dabb4 0%, #3d8a91 100%);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #3d8a91 0%, #2d6a71 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(77, 171, 180, 0.3);
        }

        /* Styles pour les checkboxes */
        .checkbox-custom {
            position: relative;
        }

        .checkbox-custom input[type="checkbox"] {
            opacity: 0;
            position: absolute;
        }

        .checkbox-custom label {
            position: relative;
            padding-left: 30px;
            cursor: pointer;
        }

        .checkbox-custom label:before {
            content: '';
            position: absolute;
            left: 0;
            top: 2px;
            width: 18px;
            height: 18px;
            border: 2px solid #d1d5db;
            border-radius: 4px;
            background: white;
            transition: all 0.3s ease;
        }

        .checkbox-custom input[type="checkbox"]:checked + label:before {
            background: #4dabb4;
            border-color: #4dabb4;
        }

        .checkbox-custom input[type="checkbox"]:checked + label:after {
            content: '✓';
            position: absolute;
            left: 3px;
            top: -1px;
            color: white;
            font-size: 12px;
            font-weight: bold;
        }

        /* Styles pour les indicateurs de mot de passe */
        .password-strength-weak {
            background: linear-gradient(90deg, #ef4444 0%, #f87171 100%);
        }

        .password-strength-medium {
            background: linear-gradient(90deg, #f59e0b 0%, #fbbf24 100%);
        }

        .password-strength-strong {
            background: linear-gradient(90deg, #10b981 0%, #34d399 100%);
        }

        .password-strength-very-strong {
            background: linear-gradient(90deg, #059669 0%, #10b981 100%);
        }

        .password-check {
            transition: all 0.3s ease;
        }

        .password-check.valid {
            background-color: #10b981;
            border-color: #10b981;
        }

        .password-check.valid svg {
            display: block !important;
        }

        .password-check.valid + span {
            color: #10b981;
            font-weight: 500;
        }

        .password-check.invalid {
            background-color: #f3f4f6;
            border-color: #d1d5db;
        }

        .password-check.invalid svg {
            display: none !important;
        }

        .password-check.invalid + span {
            color: #6b7280;
        }

        /* Styles pour les boutons de visibilité */
        .password-toggle {
            transition: all 0.2s ease;
        }

        .password-toggle:hover {
            transform: scale(1.1);
        }

        /* Animation pour la barre de force */
        .password-strength-bar {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Styles pour les messages de validation */
        .validation-message {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <script>
        // Fonction pour prévisualiser l'image de profil
        function previewImage() {
            const preview = document.getElementById('preview');
            const previewImage = document.getElementById('preview-image');
            const fileInput = document.getElementById('profile_photo');
            const file = fileInput.files[0];
            const defaultIcon = document.getElementById('default-icon-profile');
            const profileStatus = document.getElementById('profile-status');
            const profileFileInfo = document.getElementById('profile-file-info');
            const profileFilePreview = document.getElementById('profile-file-preview');
            const profileFileName = document.getElementById('profile-file-name');
            const profileFileSize = document.getElementById('profile-file-size');

            if (file) {
                // Validation de la taille
                if (file.size > 1024 * 1024) { // 1MB
                    alert('Le fichier est trop volumineux. Taille maximale: 1MB');
                    fileInput.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    // Aperçu principal
                    previewImage.src = e.target.result;
                    preview.classList.remove('hidden');
                    defaultIcon.classList.add('hidden');
                    profileStatus.classList.remove('hidden');

                    // Info du fichier
                    profileFilePreview.src = e.target.result;
                    profileFileName.textContent = file.name;
                    profileFileSize.textContent = formatFileSize(file.size);
                    profileFileInfo.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            } else {
                preview.classList.add('hidden');
                defaultIcon.classList.remove('hidden');
                profileStatus.classList.add('hidden');
                profileFileInfo.classList.add('hidden');
            }
        }

        // Fonction pour prévisualiser le diplôme
        function previewDiplome() {
    const fileInput = document.getElementById('DiplômeOrCNOM');
    const file = fileInput.files[0];
    const previewImageContainer = document.getElementById('preview-diplome');
    const previewImage = document.getElementById('preview-image-diplome');
            const defaultIcon = document.getElementById('default-icon-diplome');
            const pdfBadge = document.getElementById('pdf-badge');
            const fileInfo = document.getElementById('file-info');
            const fileIcon = document.getElementById('file-icon');
            const fileName = document.getElementById('file-name');
            const fileSize = document.getElementById('file-size');

            if (file) {
                // Validation de la taille
                if (file.size > 1024 * 1024) { // 1MB
                    alert('Le fichier est trop volumineux. Taille maximale: 1MB');
                    fileInput.value = '';
                    return;
                }

        const fileType = file.type;
                if (fileType.startsWith('image/')) {
            // Afficher l'image
            const reader = new FileReader();
                    reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewImageContainer.classList.remove('hidden');
                        defaultIcon.classList.add('hidden');
                        pdfBadge.classList.add('hidden');

                        // Info du fichier
                        fileIcon.innerHTML = '<svg class="h-6 w-6 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" /></svg>';
                        fileName.textContent = file.name;
                        fileSize.textContent = formatFileSize(file.size);
                        fileInfo.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
                } else if (fileType === 'application/pdf') {
                    // Afficher le badge PDF
                    defaultIcon.classList.add('hidden');
            previewImageContainer.classList.add('hidden');
                    pdfBadge.classList.remove('hidden');

                    // Info du fichier
                    fileIcon.innerHTML = '<svg class="h-6 w-6 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" /></svg>';
                    fileName.textContent = file.name;
                    fileSize.textContent = formatFileSize(file.size);
                    fileInfo.classList.remove('hidden');
        } else {
            // Fichier non supporté
            alert('Type de fichier non supporté. Veuillez sélectionner une image ou un PDF.');
            fileInput.value = '';
            previewImageContainer.classList.add('hidden');
                    pdfBadge.classList.add('hidden');
                    fileInfo.classList.add('hidden');
        }
    } else {
        previewImageContainer.classList.add('hidden');
                defaultIcon.classList.remove('hidden');
                pdfBadge.classList.add('hidden');
                fileInfo.classList.add('hidden');
            }
        }

        // Fonction pour formater la taille du fichier
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Fonction pour supprimer le fichier de profil
        function removeProfileFile() {
            const fileInput = document.getElementById('profile_photo');
            const preview = document.getElementById('preview');
            const defaultIcon = document.getElementById('default-icon-profile');
            const profileStatus = document.getElementById('profile-status');
            const profileFileInfo = document.getElementById('profile-file-info');

            fileInput.value = '';
            preview.classList.add('hidden');
            defaultIcon.classList.remove('hidden');
            profileStatus.classList.add('hidden');
            profileFileInfo.classList.add('hidden');
        }

        // Fonction pour supprimer le fichier diplôme
        function removeFile() {
            const fileInput = document.getElementById('DiplômeOrCNOM');
            const previewImageContainer = document.getElementById('preview-diplome');
            const defaultIcon = document.getElementById('default-icon-diplome');
            const pdfBadge = document.getElementById('pdf-badge');
            const fileInfo = document.getElementById('file-info');

            fileInput.value = '';
            previewImageContainer.classList.add('hidden');
            defaultIcon.classList.remove('hidden');
            pdfBadge.classList.add('hidden');
            fileInfo.classList.add('hidden');
        }

        // Gestion du drag & drop pour la photo de profil
        document.addEventListener('DOMContentLoaded', function() {
            const dropZoneProfile = document.getElementById('drop-zone-profile');
            const fileInputProfile = document.getElementById('profile_photo');

            if (dropZoneProfile) {
                // Gestion du clic
                dropZoneProfile.addEventListener('click', () => fileInputProfile.click());

                // Gestion du drag & drop
                dropZoneProfile.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    dropZoneProfile.classList.add('border-[#4dabb4]', 'bg-[#4dabb4]/10');
                });

                dropZoneProfile.addEventListener('dragleave', (e) => {
                    e.preventDefault();
                    dropZoneProfile.classList.remove('border-[#4dabb4]', 'bg-[#4dabb4]/10');
                });

                dropZoneProfile.addEventListener('drop', (e) => {
                    e.preventDefault();
                    dropZoneProfile.classList.remove('border-[#4dabb4]', 'bg-[#4dabb4]/10');

                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        fileInputProfile.files = files;
                        previewImage();
                    }
                });
            }

            // Gestion du drag & drop pour le diplôme
            const dropZoneDiplome = document.getElementById('drop-zone-diplome');
            const fileInputDiplome = document.getElementById('DiplômeOrCNOM');

            if (dropZoneDiplome) {
                // Gestion du clic
                dropZoneDiplome.addEventListener('click', () => fileInputDiplome.click());

                // Gestion du drag & drop
                dropZoneDiplome.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    dropZoneDiplome.classList.add('border-[#4dabb4]', 'bg-[#4dabb4]/10');
                });

                dropZoneDiplome.addEventListener('dragleave', (e) => {
                    e.preventDefault();
                    dropZoneDiplome.classList.remove('border-[#4dabb4]', 'bg-[#4dabb4]/10');
                });

                dropZoneDiplome.addEventListener('drop', (e) => {
                    e.preventDefault();
                    dropZoneDiplome.classList.remove('border-[#4dabb4]', 'bg-[#4dabb4]/10');

                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        fileInputDiplome.files = files;
                        previewDiplome();
                    }
                });
            }
        });

        // Event listeners
document.getElementById('DiplômeOrCNOM').addEventListener('change', previewDiplome);

        // ===== FONCTIONS DE VALIDATION DU MOT DE PASSE =====

        // Fonction pour basculer la visibilité du mot de passe
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }

        // Fonction pour basculer la visibilité de la confirmation du mot de passe
        function togglePasswordConfirmationVisibility() {
            const passwordInput = document.getElementById('password_confirmation');
            const eyeIcon = document.getElementById('eye-icon-confirmation');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }

        // Fonction pour valider le mot de passe
        function validatePassword() {
            const password = document.getElementById('password').value;
            const strengthBar = document.getElementById('password-strength-bar');
            const strengthText = document.getElementById('password-strength-text');

            // Critères de validation
            const hasLength = password.length >= 8;
            const hasUppercase = /[A-Z]/.test(password);
            const hasLowercase = /[a-z]/.test(password);
            const hasNumber = /\d/.test(password);
            const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);

            // Mettre à jour les indicateurs visuels
            updatePasswordCheck('length-check', 'length-text', hasLength);
            updatePasswordCheck('uppercase-check', 'uppercase-text', hasUppercase);
            updatePasswordCheck('lowercase-check', 'lowercase-text', hasLowercase);
            updatePasswordCheck('number-check', 'number-text', hasNumber);
            updatePasswordCheck('special-check', 'special-text', hasSpecial);

            // Calculer la force du mot de passe
            let score = 0;
            if (hasLength) score += 20;
            if (hasUppercase) score += 20;
            if (hasLowercase) score += 20;
            if (hasNumber) score += 20;
            if (hasSpecial) score += 20;

            // Mettre à jour la barre de force
            strengthBar.style.width = score + '%';

            // Mettre à jour le texte et la couleur
            if (score < 40) {
                strengthBar.className = 'h-2 rounded-full transition-all duration-300 password-strength-weak';
                strengthText.textContent = 'Très faible';
                strengthText.className = 'text-xs font-medium text-red-500';
            } else if (score < 60) {
                strengthBar.className = 'h-2 rounded-full transition-all duration-300 password-strength-weak';
                strengthText.textContent = 'Faible';
                strengthText.className = 'text-xs font-medium text-red-500';
            } else if (score < 80) {
                strengthBar.className = 'h-2 rounded-full transition-all duration-300 password-strength-medium';
                strengthText.textContent = 'Moyen';
                strengthText.className = 'text-xs font-medium text-yellow-500';
            } else if (score < 100) {
                strengthBar.className = 'h-2 rounded-full transition-all duration-300 password-strength-strong';
                strengthText.textContent = 'Fort';
                strengthText.className = 'text-xs font-medium text-green-500';
            } else {
                strengthBar.className = 'h-2 rounded-full transition-all duration-300 password-strength-very-strong';
                strengthText.textContent = 'Très fort';
                strengthText.className = 'text-xs font-medium text-green-600';
            }

            // Valider la confirmation si elle existe
            if (document.getElementById('password_confirmation').value) {
                validatePasswordConfirmation();
            }
        }

        // Fonction pour valider la confirmation du mot de passe
        function validatePasswordConfirmation() {
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;
            const matchCheck = document.getElementById('match-check');
            const matchText = document.getElementById('match-text');

            const isMatch = password === confirmation && password.length > 0;
            updatePasswordCheck('match-check', 'match-text', isMatch);
        }

        // Fonction utilitaire pour mettre à jour les indicateurs
        function updatePasswordCheck(checkId, textId, isValid) {
            const checkElement = document.getElementById(checkId);
            const textElement = document.getElementById(textId);

            if (isValid) {
                checkElement.classList.remove('invalid');
                checkElement.classList.add('valid');
                textElement.classList.add('validation-message');
            } else {
                checkElement.classList.remove('valid');
                checkElement.classList.add('invalid');
                textElement.classList.remove('validation-message');
            }
        }

        // Validation du formulaire avant soumission
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const password = document.getElementById('password').value;
                    const confirmation = document.getElementById('password_confirmation').value;

                    // Vérifier la force du mot de passe
                    const hasLength = password.length >= 8;
                    const hasUppercase = /[A-Z]/.test(password);
                    const hasLowercase = /[a-z]/.test(password);
                    const hasNumber = /\d/.test(password);
                    const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);

                    if (!hasLength || !hasUppercase || !hasLowercase || !hasNumber || !hasSpecial) {
                        e.preventDefault();
                        alert('Le mot de passe doit respecter tous les critères de sécurité.');
                        return false;
                    }

                    // Vérifier la correspondance
                    if (password !== confirmation) {
                        e.preventDefault();
                        alert('Les mots de passe ne correspondent pas.');
                        return false;
                    }
                });
            }
        });
        </script>

</x-guest-layout>
