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
                                        class="block mt-1 w-full pl-10 border-gray-300 focus:border-[#8bc34a] focus:ring focus:ring-[#8bc34a]/20 transition-all duration-200"
                                        type="password" name="password" required autocomplete="new-password"
                                        placeholder="••••••••" />
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
                                        class="block mt-1 w-full pl-10 border-gray-300 focus:border-[#8bc34a] focus:ring focus:ring-[#8bc34a]/20 transition-all duration-200"
                                        type="password" name="password_confirmation" required
                                        autocomplete="new-password" placeholder="••••••••" />
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
    </script>
</x-guest-layout>
