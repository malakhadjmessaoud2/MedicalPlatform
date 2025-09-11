<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-blue-50 to-teal-50 py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
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
            <div class="absolute h-32 w-32 rounded-full bg-[#4dabb4]/5 blur-xl top-[20%] left-[20%] animate-pulse-slow"></div>
            <div class="absolute h-40 w-40 rounded-full bg-[#8bc34a]/5 blur-xl top-[40%] left-[60%] animate-pulse-medium"></div>
            <div class="absolute h-36 w-36 rounded-full bg-[#ff9800]/5 blur-xl top-[70%] left-[30%] animate-pulse-fast"></div>
            <div class="absolute h-48 w-48 rounded-full bg-[#e91e63]/5 blur-xl top-[80%] left-[70%] animate-pulse-slow"></div>
        </div>

        <div class="w-full max-w-md relative z-10">
            <div class="text-center mb-8 animate__animated animate__fadeIn">
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">Connexion à MedicalPlatform</h2>
                <p class="mt-2 text-sm text-gray-600">Accédez à votre espace personnel</p>
            </div>

            <div class="bg-white/90 backdrop-blur-sm shadow-xl rounded-2xl overflow-hidden animate__animated animate__fadeIn animate__delay-1s">
                <div class="p-8">
                    <x-validation-errors class="mb-4" />

                    @session('status')
                        <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-4 rounded-lg flex items-start">
                            <svg class="w-5 h-5 mr-2 mt-0.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ $value }}</span>
                        </div>
                    @endsession

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="space-y-6">
                            <div>
                                <x-label for="email" value="{{ __('Email') }}" class="text-gray-700 font-medium" />
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                        </svg>
                                    </div>
                                    <x-input id="email" class="block mt-1 w-full pl-10 border-gray-300 focus:border-[#4dabb4] focus:ring focus:ring-[#4dabb4]/20 transition-all duration-200" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="votre@email.com" />
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center justify-between">
                                    <x-label for="password" value="{{ __('Mot de passe') }}" class="text-gray-700 font-medium" />
                                    @if (Route::has('password.request'))
                                        <a class="text-sm text-[#4dabb4] hover:text-[#3d8a91] transition-colors duration-200" href="{{ route('password.request') }}">
                                            {{ __('Mot de passe oublié?') }}
                                        </a>
                                    @endif
                                </div>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <x-input id="password" class="block mt-1 w-full pl-10 border-gray-300 focus:border-[#4dabb4] focus:ring focus:ring-[#4dabb4]/20 transition-all duration-200" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                                </div>
                            </div>

                            <div class="flex items-center">
                                <label for="remember_me" class="flex items-center">
                                    <x-checkbox id="remember_me" name="remember" class="text-[#4dabb4] focus:ring-[#4dabb4]" />
                                    <span class="ms-2 text-sm text-gray-600">{{ __('Se souvenir de moi') }}</span>
                                </label>
                            </div>

                            <div>
                                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-[#4dabb4] hover:bg-[#3d8a91] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4dabb4] transition-colors duration-300">
                                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                        <svg class="h-5 w-5 text-[#3d8a91] group-hover:text-[#2d6a71]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                        </svg>
                                    </span>
                                    {{ __('Se connecter') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-8 text-center animate__animated animate__fadeIn animate__delay-2s">
                <p class="text-gray-600">Vous n'avez pas encore de compte?</p>
                <a href="{{ route('register') }}" class="mt-2 inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-[#4dabb4] bg-white border-[#4dabb4] hover:bg-[#f8fcfc] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4dabb4] transition-colors duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    Créer un compte
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
