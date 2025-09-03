<nav class="px-4 py-2  shadow-sm">
    <div class="flex items-center justify-between">
        <!-- Left Section - Date, Search, Settings -->
        <div class="flex items-center space-x-4">
            <!-- Date du jour -->
            <div class="hidden sm:flex items-center text-gray-600 text-sm">
                <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>{{ now()->format('d M Y') }}</span>
            </div>

            <!-- Barre de recherche -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" placeholder="Rechercher..."
                    class="pl-10 pr-4 py-1.5 w-48 md:w-64 rounded-lg border border-gray-200 focus:outline-none focus:ring-1 focus:ring-[#b9ff66] focus:border-[#b9ff66] text-sm">
            </div>

            <!-- Bouton Paramètres -->
            <button class="p-1.5 rounded-lg hover:bg-gray-100 transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </button>
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
                    <!-- Notification Badge -->
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white"></span>
                </button>
                <!-- Notifications Dropdown -->
                <div
                    class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg py-2 hidden group-hover:block z-50">
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
                        <a href="#" class="text-xs text-blue-600 hover:text-blue-800">Voir toutes les
                            notifications</a>
                    </div>
                </div>
            </div>

            <!-- Profile Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                    class="flex items-center space-x-3 bg-white hover:bg-gray-50 rounded-full py-1.5 px-3 transition-colors duration-200 focus:outline-none">
                    <!-- Profile Photo -->
                    <div class="w-8 h-8 rounded-full overflow-hidden ring-2 ring-gray-100">
                        @if (Auth::user()->profile_photo_path)
                            <img src="{{ Storage::url(Auth::user()->profile_photo_path) }}"
                                alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                        @else
                            <div
                                class="w-full h-full flex items-center justify-center bg-[#b9ff66] text-black font-medium">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <!-- User Info - Visible on larger screens -->
                    <div class="hidden sm:block text-left">
                        <p class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">Patient</p>
                    </div>
                    <!-- Dropdown Arrow -->
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
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

                    <!-- Logout -->
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
    </div>
</nav>

