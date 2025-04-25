<div x-data="{ isOpen: false }"
     class="flex flex-col h-screen bg-[#e4e4e4] transition-all duration-300"
     :class="{ 'w-64': isOpen, 'w-16': !isOpen }">

    <!-- Logo -->
    <div class="flex justify-start pl-4 py-4">
        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <circle cx="12" cy="12" r="3" stroke-width="2"/>
            <circle cx="12" cy="4" r="3" stroke-width="2"/>
            <circle cx="20" cy="12" r="3" stroke-width="2"/>
            <circle cx="4" cy="12" r="3" stroke-width="2"/>
        </svg>
    </div>

    <!-- Navigation Links -->
    <nav class="flex flex-col space-y-4 mt-4">
        <!-- Toggle Button -->
        <div class="pl-3">
            <button @click="isOpen = !isOpen"
                    class="w-10 h-10 rounded-full bg-white flex items-center justify-center">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          :d="isOpen ? 'M9 5l7 7-7 7' : 'M15 19l-7-7 7-7'"/>
                </svg>
            </button>
        </div>

        <!-- Navigation Items -->
        <div class="space-y-4">
            <!-- Dashboard -->
            <a href="{{ route('dashboard.patient') }}"
               class="flex items-center pl-3">
                <div class="w-10 h-10 rounded-full {{ request()->routeIs('dashboard.patient') ? 'bg-black text-white' : 'bg-white text-gray-600' }} flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <span x-show="isOpen"
                      x-transition:enter="transition-opacity duration-300"
                      x-transition:enter-start="opacity-0"
                      x-transition:enter-end="opacity-100"
                      class="ml-3 text-sm font-medium text-gray-900">
                    Tableau de bord
                </span>
            </a>

            <!-- Dossier Médical -->
            <a href="#"
               class="flex items-center pl-3">
                <div class="w-10 h-10 rounded-full {{ request()->routeIs('patient.dossier') ? 'bg-black text-white' : 'bg-white text-gray-600' }} flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <span x-show="isOpen" class="ml-3 text-sm font-medium text-gray-900">
                    Dossier Médical
                </span>
            </a>

            <!-- Rendez-vous -->
            <a href="{{ route('patient.rendezvous') }}"
               class="flex items-center pl-3">
                <div class="w-10 h-10 rounded-full {{ request()->routeIs('patient.rendez-vous') ? 'bg-black text-white' : 'bg-white text-gray-600' }} flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <span x-show="isOpen" class="ml-3 text-sm font-medium text-gray-900">
                    Rendez-vous
                </span>
            </a>

            <!-- Médicaments -->
            <a href="{{ route('patient.medicaments') }}"
               class="flex items-center pl-3">
                <div class="w-10 h-10 rounded-full {{ request()->routeIs('patient.medicaments') ? 'bg-black text-white' : 'bg-white text-gray-600' }} flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <span x-show="isOpen" class="ml-3 text-sm font-medium text-gray-900">
                    Médicaments
                </span>
            </a>

            <!-- Commandes -->
            <a href="{{ route('patient.commandes') }}"
               class="flex items-center pl-3">
                <div class="w-10 h-10 rounded-full {{ request()->routeIs('patient.commandes') ? 'bg-black text-white' : 'bg-white text-gray-600' }} flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <span x-show="isOpen" class="ml-3 text-sm font-medium text-gray-900">
                    Commandes
                </span>
            </a>

            <!-- Dons -->
            <a href="{{ route('patient.dons') }}"
               class="flex items-center pl-3">
                <div class="w-10 h-10 rounded-full {{ request()->routeIs('patient.dons') ? 'bg-black text-white' : 'bg-white text-gray-600' }} flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <span x-show="isOpen" class="ml-3 text-sm font-medium text-gray-900">
                    Dons Médicaux
                </span>
            </a>

            <!-- Messages -->
            <a href="#"
               class="flex items-center pl-3">
                <div class="w-10 h-10 rounded-full {{ request()->routeIs('patient.messages') ? 'bg-black text-white' : 'bg-white text-gray-600' }} flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                </div>
                <span x-show="isOpen" class="ml-3 text-sm font-medium text-gray-900">
                    Messages
                </span>
            </a>

            <!-- Urgence -->
            <a href="#"
               class="flex items-center pl-3">
                <div class="w-10 h-10 rounded-full bg-red-500 text-white hover:bg-red-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <span x-show="isOpen" class="ml-3 text-sm font-medium text-gray-900">
                    Urgence
                </span>
            </a>
        </div>
    </nav>
</div>
