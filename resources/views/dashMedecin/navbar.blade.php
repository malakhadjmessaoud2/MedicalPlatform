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
    <div class="bg-black rounded-full flex items-center p-1.5 mx-20 flex-1">
        <!-- Left Section -->
        <div class="flex items-center space-x-4">
            <span class="text-white text-sm font-medium">Votre horaire</span>
            <button class="flex items-center space-x-2 bg-gray-800/50 rounded-full px-3 py-1">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="text-white text-sm">28 March</span>
            </button>
        </div>

        <!-- Timeline Section -->
        <div class="flex-1 mx-4">
            <div class="bg-[#b9ff66] rounded-full px-6 py-1.5 flex items-center justify-between relative">
                <!-- Left Consultation -->
                <div class="bg-black/5 hover:bg-black/10 transition-colors rounded-full flex items-center p-1.5 cursor-pointer">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" class="w-7 h-7 rounded-full object-cover" alt="Patient">
                    <span class="text-black/80 text-sm mx-2">36 min</span>
                    <button class="p-1 hover:bg-black/5 rounded-full">
                        <svg class="w-4 h-4 text-black/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7-7 7M3 12h18" />
                        </svg>
                    </button>
                </div>

                <!-- Séparation semi-transparente -->
                <div class="h-6 w-px bg-black/5"></div>

                <!-- Center Time -->
                <span class="text-black/90 font-medium">2:00 pm</span>

                <!-- Middle Section with White Background -->
                <div class="bg-white rounded-full py-1.5 px-6 flex items-center gap-4 min-w-[240px]">

                    <!-- Middle Avatars -->
                    <div class=" bg-black/10 transition-colors rounded-full p-1.5 flex -space-x-2 cursor-pointer ">
                        <img src="https://randomuser.me/api/portraits/men/33.jpg" class="w-7 h-7 rounded-full" alt="Patient 1">
                        <img src="https://randomuser.me/api/portraits/men/34.jpg" class="w-7 h-7 rounded-full" alt="Patient 2">
                    </div>
                    <!-- Google Meet Button -->
                    <button class="bg-black/5 transition-colors p-1.5 rounded-full ml-auto">
                        <svg class="w-4 h-4 text-black/70" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </button>
                </div>

                <!-- Séparation semi-transparente -->
                <div class="h-6 w-px bg-black/5"></div>

                <!-- Right Section -->
                <div class="flex items-center space-x-3">
                    <span class="text-black/90 font-medium">3:00 pm</span>
                    <div class="bg-black/10 transition-colors rounded-full p-1.5 flex -space-x-2 cursor-pointer">
                        <img src="https://randomuser.me/api/portraits/women/32.jpg" class="w-7 h-7 rounded-full" alt="Patient 3">
                        <img src="https://randomuser.me/api/portraits/men/35.jpg" class="w-7 h-7 rounded-full" alt="Patient 4">
                    </div>
                </div>

                <!-- Arrow Button -->
                <button class="p-1.5 hover:bg-black/5 rounded-full transition-colors">
                    <svg class="w-4 h-4 text-black/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Right Actions -->
    <div class="flex items-center space-x-4 ml-4 mr-8">
        <!-- Notifications -->
        <div class="relative">
            <button class="w-9 h-9 rounded-full bg-[#f7f7f7] flex items-center justify-center">
                <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </button>
            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white"></span>
        </div>

        <!-- Profile -->
        <div class="w-9 h-9 rounded-full overflow-hidden">
            <img
                src="https://randomuser.me/api/portraits/men/32.jpg"
                alt="Profile"
                class="w-full h-full object-cover"
            >
        </div>
    </div>
</nav>
