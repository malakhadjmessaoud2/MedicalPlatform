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
            <a href="{{ route('dashboard.medecin') }}"
               class="flex items-center pl-3">
                <div class="w-10 h-10 rounded-full {{ request()->routeIs('dashboard.medecin') ? 'bg-black text-white' : 'bg-white text-gray-600' }} flex items-center justify-center">
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

            <!-- Patients -->
            <a href="{{ route('medecin.patients') }}"
               class="flex items-center pl-3">
                <div class="w-10 h-10 rounded-full bg-white text-gray-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <span x-show="isOpen"
                      x-transition:enter="transition-opacity duration-300"
                      x-transition:enter-start="opacity-0"
                      x-transition:enter-end="opacity-100"
                      class="ml-3 text-sm font-medium text-gray-900">
                    Patients
                </span>
            </a>

            <!-- Calendar -->
            <a href="{{ route('medecin.agenda') }}"
               class="flex items-center pl-3">
                <div class="w-10 h-10 rounded-full bg-white text-gray-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <span x-show="isOpen"
                      x-transition:enter="transition-opacity duration-300"
                      x-transition:enter-start="opacity-0"
                      x-transition:enter-end="opacity-100"
                      class="ml-3 text-sm font-medium text-gray-900">
                    Agenda
                </span>
            </a>



            <!-- Traitements & Suivis -->
            <a href="{{ route('medecin.traitements') }}"
               class="flex items-center pl-3">
                <div class="w-10 h-10 rounded-full bg-white text-gray-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5h6M9 12h6m-6 7h6M4 6h16v12H4V6z"/>
                    </svg>
                </div>
                <span x-show="isOpen"
                      x-transition:enter="transition-opacity duration-300"
                      x-transition:enter-start="opacity-0"
                      x-transition:enter-end="opacity-100"
                      class="ml-3 text-sm font-medium text-gray-900">
                    Traitements & Suivis
                </span>
            </a>

            <!-- Gestion de prestations -->
            <a href="{{ route('medecin.prestations') }}"
               class="flex items-center pl-3">
                <div class="w-10 h-10 rounded-full bg-white text-gray-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <span x-show="isOpen"
                      x-transition:enter="transition-opacity duration-300"
                      x-transition:enter-start="opacity-0"
                      x-transition:enter-end="opacity-100"
                      class="ml-3 text-sm font-medium text-gray-900">
                    Gestion de prestations
                </span>
            </a>

            <!-- Communication -->
            <a href="{{ route('medecin.communication') }}"
               class="flex items-center pl-3">
                <div class="w-10 h-10 rounded-full bg-white text-gray-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <span x-show="isOpen"
                      x-transition:enter="transition-opacity duration-300"
                      x-transition:enter-start="opacity-0"
                      x-transition:enter-end="opacity-100"
                      class="ml-3 text-sm font-medium text-gray-900">
                    Communication
                </span>
            </a>
        </div>
    </nav>
</div>
