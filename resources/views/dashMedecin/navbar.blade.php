<meta name="user-id" content="{{ Auth::id() }}">
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- CSS pour le Profile Dropdown -->
<link rel="stylesheet" href="{{ asset('css/profile-dropdown.css') }}">
<!-- CSS dédié au timeline -->
<link rel="stylesheet" href="{{ asset('css/dashMedecin-navbar.css') }}">



<nav class="px-4 py-1.5 flex items-center justify-between">

    <!-- Timeline Container -->
    <div class="bg-black rounded-[30px] overflow-hidden flex items-center p-2 mx-auto max-w-5xl w-full lg:w-3/4">
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
                class="bg-gradient-to-r from-[#b9ff66] to-[#a8f055] rounded-full flex items-center relative h-14 timeline-container shadow-lg mx-1"
                role="region" aria-label="Timeline des rendez-vous">
                <!-- Scroll Buttons -->
                <button id="scroll-left" class="scroll-btn left-2" aria-label="Faire défiler vers la gauche" title="Précédent">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <button id="scroll-right" class="scroll-btn right-2" aria-label="Faire défiler vers la droite" title="Suivant">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <!-- Timeline Content -->
                <div class="timeline-scrollable hide-scrollbar" id="timeline-content" role="list" aria-label="Créneaux horaires">
                    <!-- Loading state -->
                    <div class="flex items-center justify-center w-full">
                        <div class="animate-spin rounded-full h-4 w-4 border-2 border-black border-t-transparent"></div>
                        <span class="ml-2 text-black/80 text-xs font-medium">Chargement<span
                                class="loading-dots"></span></span>
                    </div>
                </div>
            </div>

            <!-- Simple tooltip bar -->
            <div id="timeline-tooltip" class="timeline-tooltip" role="status" aria-live="polite" aria-atomic="true">
                <div class="tooltip-inner"></div>
            </div>
        </div>
    </div>

    <!-- Right Actions -->
    <div class="flex items-center space-x-4">
        <!-- Notifications -->
        <div class="relative" id="notifications">
            <button
                class="w-9 h-9 rounded-full bg-white hover:bg-gray-50 flex items-center justify-center transition-colors duration-200 relative"
                id="notifications-button">
                <svg class="w-4 h-4 text-gray-600 group-hover:text-gray-800" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <span id="notifications-badge" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden">0</span>
            </button>
            <div class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg py-2 z-50 hidden"
                 id="notifications-dropdown">
                <div class="px-4 py-2 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                    <button id="notifications-markread" class="text-xs text-blue-600 hover:text-blue-800">
                        Tout marquer comme lu
                    </button>
                </div>
                <div class="max-h-64 overflow-y-auto" id="notifications-list">
                    <div class="px-4 py-2 text-sm text-gray-500" id="notifications-empty">Aucune notification</div>
                </div>
                <div class="px-4 py-2 border-t border-gray-100">
                    <a href="#" class="text-xs text-blue-600 hover:text-blue-800">Voir toutes les
                        notifications</a>
                </div>
            </div>
        </div>

        <!-- Profile Dropdown -->
        <div class="relative profile-dropdown" x-data="{ open: false }" x-init="
            // Fermer le dropdown avec la touche Escape
            $watch('open', value => {
                if (value) {
                    document.addEventListener('keydown', handleEscape);
                } else {
                    document.removeEventListener('keydown', handleEscape);
                }
            });

            function handleEscape(e) {
                if (e.key === 'Escape') {
                    open = false;
                }
            }
        ">
            <button @click="open = !open"
                class="flex items-center space-x-3 bg-white hover:bg-gray-50 rounded-full py-1.5 px-3 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                :aria-expanded="open"
                aria-haspopup="true">
                <div class="w-8 h-8 rounded-full overflow-hidden ring-2 ring-gray-100">
                    <img data-avatar
                        :src="'{{ asset('storage/' . Auth::user()->profile_photo_path) }}?v=' + (window.__avatarVersion || 0)"
                        alt="{{ trim(Auth::user()->prenom . ' ' . Auth::user()->nom) }}"
                        class="w-full h-full object-cover">
                </div>
                <div class="hidden sm:block text-left">
                    <p class="text-sm font-medium text-gray-700">
                        {{ trim(Auth::user()->prenom . ' ' . Auth::user()->nom) }}
                    </p>
                    <p class="text-xs text-gray-500">Médecin</p>
                </div>
                <svg class="w-4 h-4 text-gray-400 dropdown-arrow"
                     :class="{ 'rotated': open }"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="open"
                 x-cloak
                 @click.outside="open = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="transform opacity-0 scale-95 translate-y-[-10px]"
                 x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="transform opacity-0 scale-95 translate-y-[-10px]"
                 class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50 dropdown-menu"
                 role="menu"
                 aria-orientation="vertical">
                <div class="px-4 py-2 border-b border-gray-100">
                    <p class="text-xs font-medium text-gray-400">COMPTE</p>
                </div>
                <a href="{{ route('profile.show') }}"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center space-x-2 transition-colors duration-150 menu-item"
                    role="menuitem"
                    @click="open = false">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>{{ __('Profil') }}</span>
                </a>
                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <a href="{{ route('api-tokens.index') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center space-x-2 transition-colors duration-150 menu-item"
                        role="menuitem"
                        @click="open = false">
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
                        class="w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center space-x-2 transition-colors duration-150 menu-item"
                        role="menuitem"
                        @click="open = false; $root.submit();">
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

<!-- Script pour les notifications - déjà inclus dans app.js -->

