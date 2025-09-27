<x-guest-layout>
    <div
        class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-blue-50 to-teal-50 py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        <!-- Arrière-plan animé avec Tailwind CSS -->
        <div class="absolute inset-0 z-0 overflow-hidden">
            <!-- Points animés -->
            <div class="absolute h-2 w-2 rounded-full bg-[#8bc34a]/30 top-[10%] left-[10%] animate-float-slow"></div>
            <div class="absolute h-3 w-3 rounded-full bg-[#8bc34a]/30 top-[15%] left-[20%] animate-float-medium"></div>
            <div class="absolute h-2 w-2 rounded-full bg-[#8bc34a]/30 top-[20%] left-[30%] animate-float-fast"></div>
            <div class="absolute h-4 w-4 rounded-full bg-[#8bc34a]/30 top-[25%] left-[40%] animate-float-slow"></div>
            <div class="absolute h-3 w-3 rounded-full bg-[#8bc34a]/30 top-[30%] left-[50%] animate-float-medium"></div>
            <div class="absolute h-2 w-2 rounded-full bg-[#8bc34a]/30 top-[35%] left-[60%] animate-float-fast"></div>
            <div class="absolute h-4 w-4 rounded-full bg-[#8bc34a]/30 top-[40%] left-[70%] animate-float-slow"></div>
            <div class="absolute h-3 w-3 rounded-full bg-[#8bc34a]/30 top-[45%] left-[80%] animate-float-medium"></div>
            <div class="absolute h-2 w-2 rounded-full bg-[#8bc34a]/30 top-[50%] left-[90%] animate-float-fast"></div>

            <!-- Cercles plus grands et plus flous pour l'effet de profondeur -->
            <div class="absolute h-32 w-32 rounded-full bg-[#8bc34a]/5 blur-xl top-[20%] left-[20%] animate-pulse-slow">
            </div>
            <div
                class="absolute h-40 w-40 rounded-full bg-[#8bc34a]/5 blur-xl top-[40%] left-[60%] animate-pulse-medium">
            </div>
            <div class="absolute h-36 w-36 rounded-full bg-[#8bc34a]/5 blur-xl top-[70%] left-[30%] animate-pulse-fast">
            </div>
            <div class="absolute h-48 w-48 rounded-full bg-[#8bc34a]/5 blur-xl top-[80%] left-[70%] animate-pulse-slow">
            </div>
        </div>

        <div class="w-full max-w-2xl relative z-10">
            <div class="text-center mb-6 animate__animated animate__fadeIn">
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">Inscription - Compte Patient</h2>
                <p class="mt-2 text-sm text-gray-600">Créez votre compte pour accéder aux services de santé</p>
            </div>

            <div
                class="bg-white/90 backdrop-blur-sm shadow-xl rounded-2xl overflow-hidden animate__animated animate__fadeIn animate__delay-1s">
                <div class="p-8">
                    <x-validation-errors class="mb-4" />

                    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf

                        <!-- Champ caché pour définir le rôle -->
                        <input type="hidden" name="role" value="patient">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-label for="name" value="{{ __('Nom') }}" class="text-gray-700 font-medium" />
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <x-input id="name"
                                        class="block mt-1 w-full pl-10 border-gray-300 focus:border-[#8bc34a] focus:ring focus:ring-[#8bc34a]/20 transition-all duration-200"
                                        type="text" name="nom" :value="old('nom')" required autofocus
                                        autocomplete="name" placeholder="Votre nom" />
                                </div>
                            </div>

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
                                        class="block mt-1 w-full pl-10 border-gray-300 focus:border-[#8bc34a] focus:ring focus:ring-[#8bc34a]/20 transition-all duration-200"
                                        type="text" name="prenom" :value="old('prenom')" required
                                        placeholder="Votre prénom" />
                                </div>
                            </div>
                        </div>

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
                                    class="block mt-1 w-full pl-10 border-gray-300 focus:border-[#8bc34a] focus:ring focus:ring-[#8bc34a]/20 transition-all duration-200"
                                    type="email" name="email" :value="old('email')" required autocomplete="username"
                                    placeholder="votre@email.com" />
                            </div>
                        </div>

                        <div>
                            <x-label for="dateNaissance" value="{{ __('Date de naissance') }}"
                                class="text-gray-700 font-medium" />
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <x-input id="dateNaissance"
                                    class="block mt-1 w-full pl-10 border-gray-300 focus:border-[#8bc34a] focus:ring focus:ring-[#8bc34a]/20 transition-all duration-200"
                                    type="date" name="dateNaissance" :value="old('dateNaissance')" required />
                            </div>
                        </div>

                        <!-- Photo de profil -->
                        <div>
                            <x-label for="profile_photo" value="{{ __('Photo de profil') }}"
                                class="text-gray-700 font-medium" />
                            <div class="mt-2 flex items-center space-x-4">
                                <div class="relative">
                                    <div
                                        class="h-16 w-16 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center border-2 border-[#8bc34a]/20">
                                        <svg class="h-12 w-12 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    </div>
                                    <div id="preview" class="absolute inset-0 rounded-full overflow-hidden hidden">
                                        <img id="preview-image" class="h-full w-full object-cover" src="#"
                                            alt="Aperçu">
                                    </div>
                                </div>
                                <label for="profile_photo"
                                    class="cursor-pointer bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#8bc34a] transition-all duration-200 flex items-center">
                                    <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Choisir une photo
                                    <input type="file" id="profile_photo" name="profile_photo" class="hidden"
                                        accept="image/*" onchange="previewImage(this)">
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">JPG, PNG ou GIF. Max 1MB.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-label for="password" value="{{ __('Mot de passe') }}"
                                    class="text-gray-700 font-medium" />
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <x-input id="password"
                                        class="block mt-1 w-full pl-10 pr-10 border-gray-300 focus:border-[#8bc34a] focus:ring focus:ring-[#8bc34a]/20 transition-all duration-200"
                                        type="password" name="password" required autocomplete="new-password"
                                        placeholder="••••••••" oninput="validatePassword()" />
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <button type="button" onclick="togglePasswordVisibility('password')"
                                            class="text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600 transition-colors duration-200">
                                            <svg id="password-eye" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Password strength indicator -->
                                <div class="mt-2">
                                    <div class="flex items-center space-x-2">
                                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                                            <div id="password-strength-bar" class="h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                                        </div>
                                        <span id="password-strength-text" class="text-xs text-gray-500">Faible</span>
                                    </div>

                                    <!-- Password criteria -->
                                    <div class="mt-2 space-y-1">
                                        <div class="flex items-center text-xs">
                                            <span id="length-check" class="w-4 h-4 rounded-full mr-2 flex items-center justify-center text-white text-xs">✗</span>
                                            <span class="text-gray-600">Au moins 8 caractères</span>
                                        </div>
                                        <div class="flex items-center text-xs">
                                            <span id="uppercase-check" class="w-4 h-4 rounded-full mr-2 flex items-center justify-center text-white text-xs">✗</span>
                                            <span class="text-gray-600">Une majuscule</span>
                                        </div>
                                        <div class="flex items-center text-xs">
                                            <span id="lowercase-check" class="w-4 h-4 rounded-full mr-2 flex items-center justify-center text-white text-xs">✗</span>
                                            <span class="text-gray-600">Une minuscule</span>
                                        </div>
                                        <div class="flex items-center text-xs">
                                            <span id="number-check" class="w-4 h-4 rounded-full mr-2 flex items-center justify-center text-white text-xs">✗</span>
                                            <span class="text-gray-600">Un chiffre</span>
                                        </div>
                                        <div class="flex items-center text-xs">
                                            <span id="special-check" class="w-4 h-4 rounded-full mr-2 flex items-center justify-center text-white text-xs">✗</span>
                                            <span class="text-gray-600">Un caractère spécial</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <x-label for="password_confirmation" value="{{ __('Confirmer le mot de passe') }}"
                                    class="text-gray-700 font-medium" />
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <x-input id="password_confirmation"
                                        class="block mt-1 w-full pl-10 pr-10 border-gray-300 focus:border-[#8bc34a] focus:ring focus:ring-[#8bc34a]/20 transition-all duration-200"
                                        type="password" name="password_confirmation" required
                                        autocomplete="new-password" placeholder="••••••••" oninput="validatePasswordMatch()" />
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <button type="button" onclick="togglePasswordVisibility('password_confirmation')"
                                            class="text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600 transition-colors duration-200">
                                            <svg id="password_confirmation-eye" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Password match indicator -->
                                <div class="mt-2">
                                    <div id="password-match" class="flex items-center text-xs hidden">
                                        <span class="w-4 h-4 rounded-full mr-2 flex items-center justify-center text-white text-xs">✗</span>
                                        <span class="text-gray-600">Les mots de passe correspondent</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                            <div>
                                <x-label for="terms">
                                    <div class="flex items-center">
                                        <x-checkbox name="terms" id="terms" required
                                            class="text-[#8bc34a] focus:ring-[#8bc34a]" />

                                        <div class="ms-2">
                                            {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                                'terms_of_service' =>
                                                    '<a target="_blank" href="' .
                                                    route('terms.show') .
                                                    '" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#8bc34a]">' .
                                                    __('Terms of Service') .
                                                    '</a>',
                                                'privacy_policy' =>
                                                    '<a target="_blank" href="' .
                                                    route('policy.show') .
                                                    '" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#8bc34a]">' .
                                                    __('Privacy Policy') .
                                                    '</a>',
                                            ]) !!}
                                        </div>
                                    </div>
                                </x-label>
                            </div>
                        @endif

                        <div class="flex items-center justify-between mt-8">
                            <a class="text-sm text-[#8bc34a] hover:text-[#7ab33a] transition-colors duration-200 flex items-center"
                                href="{{ route('login') }}">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                                </svg>
                                {{ __('Déjà inscrit?') }}
                            </a>

                            <button type="submit"
                                class="group relative flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-[#8bc34a] hover:bg-[#7ab33a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#8bc34a] transition-colors duration-300">
                                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                    <svg class="h-5 w-5 text-[#7ab33a] group-hover:text-[#6a9e32]" fill="none"
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
        /* Password validation styles */
        .password-criteria {
            transition: all 0.3s ease;
        }

        .criteria-check {
            transition: all 0.3s ease;
        }

        .criteria-check.valid {
            background-color: #10b981;
            color: white;
        }

        .criteria-check.invalid {
            background-color: #ef4444;
            color: white;
        }

        .criteria-check.neutral {
            background-color: #6b7280;
            color: white;
        }

        #password-strength-bar {
            transition: all 0.3s ease;
        }

        .strength-weak {
            background-color: #ef4444;
        }

        .strength-medium {
            background-color: #f59e0b;
        }

        .strength-strong {
            background-color: #10b981;
        }

        .strength-very-strong {
            background-color: #059669;
        }

        .password-match-valid {
            color: #10b981;
        }

        .password-match-invalid {
            color: #ef4444;
        }
    </style>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('preview');
            const previewImage = document.getElementById('preview-image');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    preview.classList.remove('hidden');
                }

                reader.readAsDataURL(input.files[0]);
            } else {
                previewImage.src = '';
                preview.classList.add('hidden');
            }
        }

        // Password validation functions
        function validatePassword() {
            const password = document.getElementById('password').value;
            const strengthBar = document.getElementById('password-strength-bar');
            const strengthText = document.getElementById('password-strength-text');

            // Check criteria
            const lengthCheck = document.getElementById('length-check');
            const uppercaseCheck = document.getElementById('uppercase-check');
            const lowercaseCheck = document.getElementById('lowercase-check');
            const numberCheck = document.getElementById('number-check');
            const specialCheck = document.getElementById('special-check');

            // Validation rules
            const hasLength = password.length >= 8;
            const hasUppercase = /[A-Z]/.test(password);
            const hasLowercase = /[a-z]/.test(password);
            const hasNumber = /\d/.test(password);
            const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);

            // Update criteria indicators
            updateCriteriaCheck(lengthCheck, hasLength);
            updateCriteriaCheck(uppercaseCheck, hasUppercase);
            updateCriteriaCheck(lowercaseCheck, hasLowercase);
            updateCriteriaCheck(numberCheck, hasNumber);
            updateCriteriaCheck(specialCheck, hasSpecial);

            // Calculate strength
            const criteria = [hasLength, hasUppercase, hasLowercase, hasNumber, hasSpecial];
            const validCriteria = criteria.filter(Boolean).length;

            let strength = 0;
            let strengthClass = '';
            let strengthLabel = '';

            if (validCriteria === 0) {
                strength = 0;
                strengthClass = 'strength-weak';
                strengthLabel = 'Très faible';
            } else if (validCriteria === 1) {
                strength = 20;
                strengthClass = 'strength-weak';
                strengthLabel = 'Faible';
            } else if (validCriteria === 2) {
                strength = 40;
                strengthClass = 'strength-weak';
                strengthLabel = 'Faible';
            } else if (validCriteria === 3) {
                strength = 60;
                strengthClass = 'strength-medium';
                strengthLabel = 'Moyen';
            } else if (validCriteria === 4) {
                strength = 80;
                strengthClass = 'strength-strong';
                strengthLabel = 'Fort';
            } else if (validCriteria === 5) {
                strength = 100;
                strengthClass = 'strength-very-strong';
                strengthLabel = 'Très fort';
            }

            // Update strength bar
            strengthBar.style.width = strength + '%';
            strengthBar.className = 'h-2 rounded-full transition-all duration-300 ' + strengthClass;
            strengthText.textContent = strengthLabel;
            strengthText.className = 'text-xs ' + (strength >= 60 ? 'text-green-600' : strength >= 40 ? 'text-yellow-600' : 'text-red-600');
        }

        function updateCriteriaCheck(element, isValid) {
            element.className = 'w-4 h-4 rounded-full mr-2 flex items-center justify-center text-white text-xs criteria-check ' + (isValid ? 'valid' : 'invalid');
            element.textContent = isValid ? '✓' : '✗';
        }

        function validatePasswordMatch() {
            const password = document.getElementById('password').value;
            const passwordConfirmation = document.getElementById('password_confirmation').value;
            const matchIndicator = document.getElementById('password-match');

            if (passwordConfirmation.length > 0) {
                matchIndicator.classList.remove('hidden');
                const isMatch = password === passwordConfirmation;
                const icon = matchIndicator.querySelector('span');
                const text = matchIndicator.querySelector('span:last-child');

                if (isMatch) {
                    icon.className = 'w-4 h-4 rounded-full mr-2 flex items-center justify-center text-white text-xs bg-green-500';
                    icon.textContent = '✓';
                    text.className = 'text-green-600';
                    text.textContent = 'Les mots de passe correspondent';
                } else {
                    icon.className = 'w-4 h-4 rounded-full mr-2 flex items-center justify-center text-white text-xs bg-red-500';
                    icon.textContent = '✗';
                    text.className = 'text-red-600';
                    text.textContent = 'Les mots de passe ne correspondent pas';
                }
            } else {
                matchIndicator.classList.add('hidden');
            }
        }

        function togglePasswordVisibility(fieldId) {
            const field = document.getElementById(fieldId);
            const eyeIcon = document.getElementById(fieldId + '-eye');

            if (field.type === 'password') {
                field.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                `;
            } else {
                field.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }

        // Form submission validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');

            form.addEventListener('submit', function(e) {
                const password = document.getElementById('password').value;
                const passwordConfirmation = document.getElementById('password_confirmation').value;

                // Check password criteria
                const hasLength = password.length >= 8;
                const hasUppercase = /[A-Z]/.test(password);
                const hasLowercase = /[a-z]/.test(password);
                const hasNumber = /\d/.test(password);
                const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);

                if (!hasLength || !hasUppercase || !hasLowercase || !hasNumber || !hasSpecial) {
                    e.preventDefault();
                    alert('Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.');
                    return false;
                }

                // Check password match
                if (password !== passwordConfirmation) {
                    e.preventDefault();
                    alert('Les mots de passe ne correspondent pas.');
                    return false;
                }
            });
        });
    </script>
</x-guest-layout>
