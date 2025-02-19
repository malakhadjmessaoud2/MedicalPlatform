<div x-data="{ isOpen: false }"
     :class="{ 'w-64': isOpen, 'w-16': !isOpen }"
     class="flex flex-col items-center py-10 space-y-8 transition-all duration-300">
    <!-- Toggle Button (Open/Close Sidebar) -->
    <button id="sidebar-toggle" class="w-10 h-10 rounded-full bg-white hover:bg-gray-100 flex items-center justify-center shadow-md absolute top-4 right-4">
        <svg class="w-5 h-5 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9l6 6 6-6"/>
        </svg>
    </button>

    <!-- Logo -->
    <div class="mb-6">
        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4c1.657 0 3 1.343 3 3s-1.343 3-3 3-3-1.343-3-3 1.343-3 3-3zM12 14c1.657 0 3 1.343 3 3s-1.343 3-3 3-3-1.343-3-3 1.343-3 3-3z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 10c1.657 0 3 1.343 3 3s-1.343 3-3 3-3-1.343-3-3 1.343-3 3-3zM5 10c1.657 0 3 1.343 3 3s-1.343 3-3 3-3-1.343-3-3 1.343-3 3-3z"/>
        </svg>
    </div>

    <!-- Back Button -->
    <button @click="isOpen = !isOpen"
            class="w-10 h-10 rounded-full bg-white hover:bg-gray-100 flex items-center justify-center shadow-md">
        <svg :class="{ 'rotate-180': isOpen }"
             class="w-5 h-5 transform transition-transform duration-300"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </button>

    <!-- Navigation Buttons -->
    <div class="flex flex-col space-y-4">
        <!-- Tableau de bord -->
        <a href="{{ route('dashboard.medecin') }}"
           class="w-10 h-10 rounded-full {{ request()->routeIs('dashboard.medecin') ? 'bg-black text-white' : 'bg-white hover:bg-gray-100' }} flex items-center justify-center"
           title="Tableau de bord">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
        </a>
        <!-- Gestion des patients -->
        <a href="{{ route('medecin.patients') }}"
           class="w-10 h-10 rounded-full bg-white hover:bg-gray-100 flex items-center justify-center relative group">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <span x-cloak
                  :class="{ 'opacity-100': isOpen, 'opacity-0 group-hover:opacity-100': !isOpen }"
                  class="absolute left-12 whitespace-nowrap text-sm text-gray-700 transition-opacity duration-300">
                Gestion des patients
            </span>
        </a>

        <!-- Agenda & Rendez-vous -->
        <a href="{{ route('medecin.agenda') }}"
           class="w-10 h-10 rounded-full bg-white hover:bg-gray-100 flex items-center justify-center relative group">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span x-cloak
                  :class="{ 'opacity-100': isOpen, 'opacity-0 group-hover:opacity-100': !isOpen }"
                  class="absolute left-12 whitespace-nowrap text-sm text-gray-700 transition-opacity duration-300">
                Agenda & Rendez-vous
            </span>
        </a>

        <!-- Traitements & Suivis -->
        <a href="{{ route('medecin.traitements') }}"
           class="w-10 h-10 rounded-full bg-white hover:bg-gray-100 flex items-center justify-center relative group">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5h6M9 12h6m-6 7h6M4 6h16v12H4V6z"/>
            </svg>
            <span x-cloak
                  :class="{ 'opacity-100': isOpen, 'opacity-0 group-hover:opacity-100': !isOpen }"
                  class="absolute left-12 whitespace-nowrap text-sm text-gray-700 transition-opacity duration-300">
                Traitements & Suivis
            </span>
        </a>

        <!-- Gestion de prestations -->
        <a href="{{ route('medecin.prestations') }}"
           class="w-10 h-10 rounded-full bg-white hover:bg-gray-100 flex items-center justify-center relative group">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 9V7a2 2 0 00-2-2H3a2 2 0 00-2 2v12a2 2 0 002 2h2a2 2 0 002-2v-2m2-4h4l4 4m-4-4v4h-4v-4m-4 0v8h12V7a2 2 0 00-2-2h-2"/>
            </svg>
            <span x-cloak
                  :class="{ 'opacity-100': isOpen, 'opacity-0 group-hover:opacity-100': !isOpen }"
                  class="absolute left-12 whitespace-nowrap text-sm text-gray-700 transition-opacity duration-300">
                Gestion des prestations
            </span>
        </a>

        <!-- Communication & Assistance -->
        <a href="{{ route('medecin.communication') }}"
           class="w-10 h-10 rounded-full bg-white hover:bg-gray-100 flex items-center justify-center relative group">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/>
            </svg>
            <span x-cloak
                  :class="{ 'opacity-100': isOpen, 'opacity-0 group-hover:opacity-100': !isOpen }"
                  class="absolute left-12 whitespace-nowrap text-sm text-gray-700 transition-opacity duration-300">
                Communication & Assistance
            </span>
        </a>
    </div>
</div>
