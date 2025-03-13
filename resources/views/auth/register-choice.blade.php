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

            <div class="absolute h-3 w-3 rounded-full bg-[#8bc34a]/30 top-[55%] left-[10%] animate-float-medium"></div>
            <div class="absolute h-2 w-2 rounded-full bg-[#ff9800]/30 top-[60%] left-[20%] animate-float-slow"></div>
            <div class="absolute h-4 w-4 rounded-full bg-[#e91e63]/30 top-[65%] left-[30%] animate-float-fast"></div>
            <div class="absolute h-3 w-3 rounded-full bg-[#4dabb4]/30 top-[70%] left-[40%] animate-float-medium"></div>
            <div class="absolute h-2 w-2 rounded-full bg-[#8bc34a]/30 top-[75%] left-[50%] animate-float-slow"></div>
            <div class="absolute h-4 w-4 rounded-full bg-[#ff9800]/30 top-[80%] left-[60%] animate-float-fast"></div>
            <div class="absolute h-3 w-3 rounded-full bg-[#e91e63]/30 top-[85%] left-[70%] animate-float-medium"></div>
            <div class="absolute h-2 w-2 rounded-full bg-[#4dabb4]/30 top-[90%] left-[80%] animate-float-slow"></div>
            <div class="absolute h-4 w-4 rounded-full bg-[#8bc34a]/30 top-[95%] left-[90%] animate-float-fast"></div>

            <!-- Lignes connectées -->
            <div class="absolute h-px w-[200px] bg-gradient-to-r from-transparent via-gray-300/20 to-transparent top-[15%] left-[15%] rotate-[30deg]"></div>
            <div class="absolute h-px w-[300px] bg-gradient-to-r from-transparent via-gray-300/20 to-transparent top-[25%] left-[35%] rotate-[45deg]"></div>
            <div class="absolute h-px w-[250px] bg-gradient-to-r from-transparent via-gray-300/20 to-transparent top-[35%] left-[55%] rotate-[60deg]"></div>
            <div class="absolute h-px w-[200px] bg-gradient-to-r from-transparent via-gray-300/20 to-transparent top-[45%] left-[75%] rotate-[30deg]"></div>
            <div class="absolute h-px w-[300px] bg-gradient-to-r from-transparent via-gray-300/20 to-transparent top-[55%] left-[15%] rotate-[45deg]"></div>
            <div class="absolute h-px w-[250px] bg-gradient-to-r from-transparent via-gray-300/20 to-transparent top-[65%] left-[35%] rotate-[60deg]"></div>
            <div class="absolute h-px w-[200px] bg-gradient-to-r from-transparent via-gray-300/20 to-transparent top-[75%] left-[55%] rotate-[30deg]"></div>
            <div class="absolute h-px w-[300px] bg-gradient-to-r from-transparent via-gray-300/20 to-transparent top-[85%] left-[75%] rotate-[45deg]"></div>

            <!-- Cercles plus grands et plus flous pour l'effet de profondeur -->
            <div class="absolute h-32 w-32 rounded-full bg-[#4dabb4]/5 blur-xl top-[20%] left-[20%] animate-pulse-slow"></div>
            <div class="absolute h-40 w-40 rounded-full bg-[#8bc34a]/5 blur-xl top-[40%] left-[60%] animate-pulse-medium"></div>
            <div class="absolute h-36 w-36 rounded-full bg-[#ff9800]/5 blur-xl top-[70%] left-[30%] animate-pulse-fast"></div>
            <div class="absolute h-48 w-48 rounded-full bg-[#e91e63]/5 blur-xl top-[80%] left-[70%] animate-pulse-slow"></div>
        </div>

        <div class="w-full max-w-4xl relative z-10">
            <div class="text-center mb-10 animate__animated animate__fadeIn">
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">Bienvenue sur MediConnect</h2>
                <p class="mt-3 text-xl text-gray-600">Choisissez votre type de compte pour commencer</p>
            </div>

            <div class="bg-white/90 backdrop-blur-sm shadow-xl rounded-2xl overflow-hidden animate__animated animate__fadeIn animate__delay-1s">
                <div class="p-6 sm:p-10">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <a href="{{ route('register.medecin') }}" class="group relative bg-white border-2 border-gray-100 rounded-xl p-6 hover:border-[#4dabb4] hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-[#4dabb4] focus:ring-offset-2">
                            <div class="absolute top-0 right-0 bg-[#4dabb4]/10 w-24 h-24 rounded-bl-full -mr-6 -mt-6 transition-all duration-300 group-hover:bg-[#4dabb4]/20"></div>
                            <div class="flex flex-col items-center text-center relative z-10">
                                <div class="bg-[#e6f7f9] p-5 rounded-full mb-5 group-hover:bg-[#4dabb4] transition-colors duration-300">
                                    <svg class="w-12 h-12 text-[#4dabb4] group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-800 mb-3">Médecin</h3>
                                <p class="text-gray-600 mb-4">Créez un compte pour les professionnels de santé et gérez vos patients</p>
                                <span class="inline-flex items-center text-[#4dabb4] font-medium group-hover:text-[#4dabb4]/80">
                                    S'inscrire
                                    <svg class="ml-2 w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </span>
                            </div>
                        </a>

                        <a href="{{ route('register.patient') }}" class="group relative bg-white border-2 border-gray-100 rounded-xl p-6 hover:border-[#8bc34a] hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-[#8bc34a] focus:ring-offset-2">
                            <div class="absolute top-0 right-0 bg-[#8bc34a]/10 w-24 h-24 rounded-bl-full -mr-6 -mt-6 transition-all duration-300 group-hover:bg-[#8bc34a]/20"></div>
                            <div class="flex flex-col items-center text-center relative z-10">
                                <div class="bg-[#f2fde8] p-5 rounded-full mb-5 group-hover:bg-[#8bc34a] transition-colors duration-300">
                                    <svg class="w-12 h-12 text-[#8bc34a] group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-800 mb-3">Patient</h3>
                                <p class="text-gray-600 mb-4">Créez un compte pour accéder aux services de santé et prendre rendez-vous</p>
                                <span class="inline-flex items-center text-[#8bc34a] font-medium group-hover:text-[#8bc34a]/80">
                                    S'inscrire
                                    <svg class="ml-2 w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </span>
                            </div>
                        </a>

                        <a href="{{ route('register.pharmacie') }}" class="group relative bg-white border-2 border-gray-100 rounded-xl p-6 hover:border-[#ff9800] hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-[#ff9800] focus:ring-offset-2">
                            <div class="absolute top-0 right-0 bg-[#ff9800]/10 w-24 h-24 rounded-bl-full -mr-6 -mt-6 transition-all duration-300 group-hover:bg-[#ff9800]/20"></div>
                            <div class="flex flex-col items-center text-center relative z-10">
                                <div class="bg-[#fff8e1] p-5 rounded-full mb-5 group-hover:bg-[#ff9800] transition-colors duration-300">
                                    <svg class="w-12 h-12 text-[#ff9800] group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-800 mb-3">Pharmacie</h3>
                                <p class="text-gray-600 mb-4">Créez un compte pour gérer votre pharmacie et vos médicaments</p>
                                <span class="inline-flex items-center text-[#ff9800] font-medium group-hover:text-[#ff9800]/80">
                                    S'inscrire
                                    <svg class="ml-2 w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </span>
                            </div>
                        </a>

                        <a href="{{ route('register.donateur') }}" class="group relative bg-white border-2 border-gray-100 rounded-xl p-6 hover:border-[#e91e63] hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-[#e91e63] focus:ring-offset-2">
                            <div class="absolute top-0 right-0 bg-[#e91e63]/10 w-24 h-24 rounded-bl-full -mr-6 -mt-6 transition-all duration-300 group-hover:bg-[#e91e63]/20"></div>
                            <div class="flex flex-col items-center text-center relative z-10">
                                <div class="bg-[#fce4ec] p-5 rounded-full mb-5 group-hover:bg-[#e91e63] transition-colors duration-300">
                                    <svg class="w-12 h-12 text-[#e91e63] group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-800 mb-3">Donateur</h3>
                                <p class="text-gray-600 mb-4">Créez un compte pour faire des dons et soutenir des causes médicales</p>
                                <span class="inline-flex items-center text-[#e91e63] font-medium group-hover:text-[#e91e63]/80">
                                    S'inscrire
                                    <svg class="ml-2 w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-8 text-center animate__animated animate__fadeIn animate__delay-2s">
                <p class="text-gray-600">Vous avez déjà un compte?</p>
                <a href="{{ route('login') }}" class="mt-2 inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-[#4dabb4] hover:bg-[#3d8a91] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4dabb4] transition-colors duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    Se connecter
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
