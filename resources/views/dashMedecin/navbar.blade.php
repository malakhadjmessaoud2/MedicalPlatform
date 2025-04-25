<style>
    /* Hide scrollbar but keep functionality */
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    /* Gradient fade on edges */
    .timeline-container {
        position: relative;
    }
    .timeline-container::before,
    .timeline-container::after {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        width: 30px;
        z-index: 1;
        pointer-events: none;
    }
    .timeline-container::before {
        left: 0;
        background: linear-gradient(to right, rgba(0,0,0,0.8), transparent);
    }
    .timeline-container::after {
        right: 0;
        background: linear-gradient(to left, rgba(0,0,0,0.8), transparent);
    }

    /* Active time indicator animation */
    .time-indicator {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0% { opacity: 0.8; }
        50% { opacity: 1; }
        100% { opacity: 0.8; }
    }
</style>

<nav class="px-4 py-1.5 flex items-center justify-between">
    <!-- Timeline Container -->
    <div class="bg-black rounded-[30px] flex items-center p-3 mx-auto max-w-5xl w-full lg:w-3/4">
        <!-- Left Section - Schedule Title & Date -->
        <div class="flex items-center gap-4">
            <span class="text-white text-base">Votre Horaire</span>
            <button class="flex items-center gap-2 bg-black/40 rounded-full px-3 py-1.5">
                <svg class="w-4 h-4 text-white/70" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M19,4H17V3a1,1,0,0,0-2,0V4H9V3A1,1,0,0,0,7,3V4H5A2,2,0,0,0,3,6V20a2,2,0,0,0,2,2H19a2,2,0,0,0,2-2V6A2,2,0,0,0,19,4Z"/>
                </svg>
                <span class="text-white/90">28 March</span>
            </button>
        </div>

        <!-- Timeline Section -->
        <div class="flex-1 mx-6">
            <div class="bg-[#b9ff66] rounded-full flex items-center relative h-10">
                <!-- Left Event -->
                <div class="flex items-center gap-3 px-4">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" class="w-7 h-7 rounded-full" alt="Patient">
                    <span class="text-black/70">36 min</span>
                    <button class="hover:bg-black/5 rounded-full p-1">
                        <svg class="w-4 h-4 text-black/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                </div>

                <!-- Center Event -->
                <div class="absolute left-1/2 -translate-x-1/2 flex items-center gap-3">
                    <span class="text-black font-medium">2:00 pm</span>
                    <div class="bg-white rounded-full py-1.5 px-4 flex items-center gap-4">
                        <div class="flex -space-x-2">
                            <img src="https://randomuser.me/api/portraits/men/33.jpg" class="w-7 h-7 rounded-full border-2 border-white" alt="Patient 1">
                            <img src="https://randomuser.me/api/portraits/men/34.jpg" class="w-7 h-7 rounded-full border-2 border-white" alt="Patient 2">
                        </div>
                        <img src="https://upload.wikimedia.org/wikipedia/commons/9/9b/Google_Meet_icon_%282020%29.svg" class="w-5 h-5" alt="Meet">
                    </div>
                    <!-- Current Time Indicator -->
                    <div class="absolute -top-5 left-1/2 -translate-x-1/2 flex flex-col items-center">
                        <span class="text-xs text-white bg-black/90 rounded-full px-2 py-0.5">2:15 pm</span>
                        <div class="w-0.5 h-2 bg-black/90 mt-0.5"></div>
                    </div>
                </div>

                <!-- Right Event -->
                <div class="flex items-center gap-3 px-4 ml-auto">
                    <span class="text-black font-medium">3:00 pm</span>
                    <div class="flex -space-x-2">
                        <img src="https://randomuser.me/api/portraits/women/32.jpg" class="w-7 h-7 rounded-full" alt="Patient 3">
                        <img src="https://randomuser.me/api/portraits/men/35.jpg" class="w-7 h-7 rounded-full" alt="Patient 4">
                    </div>
                    <button class="hover:bg-black/5 rounded-full p-1">
                        <svg class="w-4 h-4 text-black/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Actions -->
    <div class="flex items-center space-x-4">
        <!-- Notifications -->
        <div class="relative group">
            <button class="w-9 h-9 rounded-full bg-white hover:bg-gray-50 flex items-center justify-center transition-colors duration-200 relative">
                <svg class="w-4 h-4 text-gray-600 group-hover:text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <!-- Notification Badge -->
                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white"></span>
            </button>
            <!-- Notifications Dropdown -->
            <div class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg py-2 hidden group-hover:block z-50">
                <div class="px-4 py-2 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                </div>
                <div class="max-h-64 overflow-y-auto">
                    <!-- Notifications List -->
                    <div class="px-4 py-2 hover:bg-gray-50 cursor-pointer">
                        <p class="text-sm text-gray-600">Nouveau rendez-vous confirmé</p>
                        <p class="text-xs text-gray-400">Il y a 5 minutes</p>
                    </div>
                </div>
                <div class="px-4 py-2 border-t border-gray-100">
                    <a href="#" class="text-xs text-blue-600 hover:text-blue-800">Voir toutes les notifications</a>
                </div>
            </div>
        </div>

        <!-- Profile Dropdown -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open"
                    class="flex items-center space-x-3 bg-white hover:bg-gray-50 rounded-full py-1.5 px-3 transition-colors duration-200 focus:outline-none">
                <!-- Profile Photo -->
                <div class="w-8 h-8 rounded-full overflow-hidden ring-2 ring-gray-100">
                    @if(Auth::user()->profile_photo_path)
                        <img src="{{ Storage::url(Auth::user()->profile_photo_path) }}"
                             alt="{{ Auth::user()->name }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-[#b9ff66] text-black font-medium">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <!-- User Info - Visible on larger screens -->
                <div class="hidden sm:block text-left">
                    <p class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500">Médecin</p>
                </div>
                <!-- Dropdown Arrow -->
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open"
                 @click.away="open = false"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg py-2 z-50">

                <!-- Account Management -->
                <div class="px-4 py-2 border-b border-gray-100">
                    <p class="text-xs font-medium text-gray-400">COMPTE</p>
                </div>

                <a href="{{ route('profile.show') }}"
                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>{{ __('Profil') }}</span>
                </a>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <a href="{{ route('api-tokens.index') }}"
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center space-x-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                        <span>{{ __('API Tokens') }}</span>
                    </a>
                @endif

                <div class="border-t border-gray-100 my-1"></div>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <button type="submit"
                            class="w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center space-x-2"
                            @click.prevent="$root.submit();">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>{{ __('Déconnexion') }}</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
