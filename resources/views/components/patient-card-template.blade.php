<template id="patient-card-template">
    <div class="flex flex-col lg:flex-row items-center justify-between gap-4 p-4 bg-gray-50 rounded-lg shadow-sm hover:shadow-md transition-all mb-2">
        <div class="flex items-center gap-4">
            <img class="patient-photo rounded-full object-cover border-2 border-[#b9ff66] shadow" alt="Photo" width="56" height="56" style="min-width: 56px; min-height: 56px;">
            <div class="flex-1">
                <div class="patient-name text-lg font-bold text-gray-800"></div>
                <div class="patient-meta text-sm text-gray-500 flex gap-2 items-center">
                    <span class="patient-meta-item inline-flex items-center gap-1">
                        <svg class="patient-meta-icon w-4 h-4 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 8v4l3 3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="patient-time"></span>
                    </span>
                    <span class="patient-meta-item inline-flex items-center gap-1">
                        <svg class="patient-meta-icon w-4 h-4 text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="patient-type"></span>
                    </span>
                </div>
                <div class="consultation-indicator text-xs mt-1"></div>
            </div>
        </div>
        <div class="flex flex-col lg:flex-row items-center gap-3">
            <span class="status-label px-4 py-1.5 rounded-full text-sm font-semibold"></span>
            <div class="flex items-center gap-2">
                <button data-action="change-status" class="status-action-btn px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md" title="Changer le statut du rendez-vous">
                    <!-- Le contenu sera défini dynamiquement par JavaScript -->
                </button>
                <a href="#" data-action="open-consultation" class="consultation-link p-2 rounded-full transition-all" title="Rejoindre la consultation">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </a>
                <a href="{{ route('medecin.dossiers.medicaux') }}" class="p-2 bg-[#b9ff66] hover:bg-[#a8eb5f] rounded-full transition-all" title="Voir le rendez-vous" data-action="view-dossier">
                    <svg class="w-5 h-5 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</template>
