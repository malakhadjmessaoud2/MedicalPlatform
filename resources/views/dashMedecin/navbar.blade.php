@vite(['resources/css/app.css', 'resources/js/app.js'])

<nav class="px-4 py-1.5 flex items-center justify-between">
    <!-- Timeline Container -->
    <div class="bg-black rounded-[30px] flex items-center p-3 mx-auto max-w-5xl w-full lg:w-3/4">
        <!-- Left Section - Schedule Title & Date -->
        <div class="flex items-center gap-4">
            <span class="text-white text-base">Votre Horaire</span>
            <button class="flex items-center gap-2 bg-black/40 rounded-full px-3 py-1.5">
                <svg class="w-4 h-4 text-white/70" viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M19,4H17V3a1,1,0,0,0-2,0V4H9V3A1,1,0,0,0,7,3V4H5A2,2,0,0,0,3,6V20a2,2,0,0,0,2,2H19a2,2,0,0,0,2-2V6A2,2,0,0,0,19,4Z" />
                </svg>
                <span class="text-white/90" id="current-date">{{ \Carbon\Carbon::now()->format('d M') }}</span>
            </button>
        </div>

        <!-- Timeline Section -->
        <div class="flex-1 mx-6">
            <div
                class="bg-gradient-to-r from-[#b9ff66] to-[#a8f055] rounded-full flex items-center relative h-12 timeline-container shadow-lg">
                <!-- Scroll Buttons -->
                <button id="scroll-left" class="scroll-btn left-2">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <button id="scroll-right" class="scroll-btn right-2">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <!-- Timeline Content -->
                <div class="timeline-scrollable" id="timeline-content">
                    <!-- Loading state -->
                    <div class="flex items-center justify-center w-full">
                        <div class="animate-spin rounded-full h-4 w-4 border-2 border-black border-t-transparent"></div>
                        <span class="ml-2 text-black/80 text-xs font-medium">Chargement<span
                                class="loading-dots"></span></span>
                    </div>
                </div>
            </div>
            <!-- Bottom tooltip bar -->
            <div id="timeline-tooltip" class="timeline-tooltip">
                <div class="tooltip-inner"></div>
            </div>
        </div>
    </div>

    <!-- Right Actions -->
    <div class="flex items-center space-x-4">
        <!-- Notifications -->
        <div class="relative group">
            <button
                class="w-9 h-9 rounded-full bg-white hover:bg-gray-50 flex items-center justify-center transition-colors duration-200 relative">
                <svg class="w-4 h-4 text-gray-600 group-hover:text-gray-800" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white"></span>
            </button>
            <div class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg py-2 hidden group-hover:block z-50">
                <div class="px-4 py-2 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                </div>
                <div class="max-h-64 overflow-y-auto">
                    <div class="px-4 py-2 hover:bg-gray-50 cursor-pointer">
                        <p class="text-sm text-gray-600">Nouveau rendez-vous confirmé</p>
                        <p class="text-xs text-gray-400">Il y a 5 minutes</p>
                    </div>
                </div>
                <div class="px-4 py-2 border-t border-gray-100">
                    <a href="#" class="text-xs text-blue-600 hover:text-blue-800">Voir toutes les
                        notifications</a>
                </div>
            </div>
        </div>

        <!-- Profile Dropdown -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open"
                class="flex items-center space-x-3 bg-white hover:bg-gray-50 rounded-full py-1.5 px-3 transition-colors duration-200 focus:outline-none">
                <div class="w-8 h-8 rounded-full overflow-hidden ring-2 ring-gray-100">
                    <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}"
                        alt="{{ trim(Auth::user()->prenom . ' ' . Auth::user()->nom) }}"
                        class="w-full h-full object-cover">
                </div>
                <div class="hidden sm:block text-left">
                    <p class="text-sm font-medium text-gray-700">
                        {{ trim(Auth::user()->prenom . ' ' . Auth::user()->nom) }}
                    </p>
                    <p class="text-xs text-gray-500">Médecin</p>
                </div>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg py-2 z-50">
                <div class="px-4 py-2 border-b border-gray-100">
                    <p class="text-xs font-medium text-gray-400">COMPTE</p>
                </div>
                <a href="{{ route('profile.show') }}"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>{{ __('Profil') }}</span>
                </a>
                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <a href="{{ route('api-tokens.index') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center space-x-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                        <span>{{ __('API Tokens') }}</span>
                    </a>
                @endif
                <div class="border-t border-gray-100 my-1"></div>
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <button type="submit"
                        class="w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center space-x-2"
                        @click.prevent="$root.submit();">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>{{ __('Déconnexion') }}</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>


