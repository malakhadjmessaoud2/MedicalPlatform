@extends('dashMedecin.layout')

@section('content')
<div class="p-4 sm:p-8 bg-[#e4e4e4] min-h-screen">
    <!-- En-tête -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-8 gap-4">
        <h1 class="text-2xl sm:text-4xl font-bold">
            AGEND<span class="text-[#b9ff66]">A</span>
        </h1>

        <!-- Actions rapides -->
        <div class="flex flex-wrap gap-3">
            <button onclick="openNewRdvModal()"
                    class="bg-[#b9ff66] hover:bg-[#a8eb5f] text-black rounded-lg px-4 py-2.5 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nouveau RDV
            </button>
            <button onclick="toggleBlockCreneauModal()"
                    class="bg-gray-800 text-white rounded-lg px-4 py-2.5 flex items-center gap-2 hover:bg-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Bloquer créneau
            </button>
        </div>
    </div>

    <!-- Barre d'outils du calendrier -->
    <div class="bg-white p-4 rounded-[20px] shadow-sm mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <!-- Navigation temporelle -->
            <div class="flex items-center gap-3">
                <button class="p-2 hover:bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <h2 class="text-lg font-semibold">Mars 2024</h2>
                <button class="p-2 hover:bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                <button class="px-3 py-1 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">Aujourd'hui</button>
            </div>

            <!-- Vues du calendrier -->
            <div class="flex items-center bg-gray-100 rounded-lg p-1">
                <button class="px-3 py-1 rounded-lg bg-white shadow-sm text-sm">Jour</button>
                <button class="px-3 py-1 rounded-lg text-sm text-gray-600 hover:bg-gray-50">Semaine</button>
                <button class="px-3 py-1 rounded-lg text-sm text-gray-600 hover:bg-gray-50">Mois</button>
            </div>

            <!-- Filtres -->
            <div class="flex gap-2">
                <select class="rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66] text-sm">
                    <option value="">Tous les patients</option>
                    <option value="nouveaux">Nouveaux patients</option>
                    <option value="suivis">Patients suivis</option>
                </select>
                <select class="rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66] text-sm">
                    <option value="">Type de RDV</option>
                    <option value="consultation">Consultation</option>
                    <option value="suivi">Suivi</option>
                    <option value="urgence">Urgence</option>
                </select>
                <select class="rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66] text-sm">
                    <option value="">Tous les statuts</option>
                    <option value="confirme">Confirmé</option>
                    <option value="en_attente">En attente</option>
                    <option value="annule">Annulé</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Calendrier -->
    <div class="bg-white rounded-[20px] shadow-sm overflow-hidden">
        <!-- En-tête des jours -->
        <div class="grid grid-cols-7 border-b text-sm font-medium">
            <div class="p-4 text-center">Lundi</div>
            <div class="p-4 text-center">Mardi</div>
            <div class="p-4 text-center">Mercredi</div>
            <div class="p-4 text-center">Jeudi</div>
            <div class="p-4 text-center">Vendredi</div>
            <div class="p-4 text-center text-gray-500">Samedi</div>
            <div class="p-4 text-center text-gray-500">Dimanche</div>
        </div>

        <!-- Grille du calendrier -->
        <div class="grid grid-cols-7 grid-rows-5 divide-x divide-y" id="calendar-grid">
            <!-- Les cellules seront générées dynamiquement -->
            @for ($i = 0; $i < 35; $i++)
                <div class="min-h-[150px] p-2 relative group" data-date="{{ $i }}">
                    <!-- En-tête de la cellule -->
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm {{ $i % 7 > 4 ? 'text-gray-400' : '' }}">{{ $i + 1 }}</span>
                        <button class="opacity-0 group-hover:opacity-100 p-1 hover:bg-gray-100 rounded transition-opacity">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Rendez-vous (exemples) -->
                    @if ($i == 3)
                        <div class="bg-green-100 text-green-800 rounded p-2 mb-1 text-sm cursor-pointer hover:bg-green-200 transition-colors">
                            <div class="font-medium">9:00 - Consultation</div>
                            <div class="text-xs">M. Dupont</div>
                        </div>
                    @endif

                    @if ($i == 3)
                        <div class="bg-yellow-100 text-yellow-800 rounded p-2 mb-1 text-sm cursor-pointer hover:bg-yellow-200 transition-colors">
                            <div class="font-medium">11:00 - Suivi</div>
                            <div class="text-xs">Mme Martin</div>
                        </div>
                    @endif

                    @if ($i == 4)
                        <div class="bg-red-100 text-red-800 rounded p-2 mb-1 text-sm cursor-pointer hover:bg-red-200 transition-colors">
                            <div class="font-medium">14:30 - Urgence</div>
                            <div class="text-xs">M. Bernard</div>
                        </div>
                    @endif
                </div>
            @endfor
        </div>
    </div>
</div>

<!-- Modal Nouveau RDV -->
<div id="newRdvModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-[20px] p-8 w-full max-w-2xl mx-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">
                NOUVEAU RENDEZ-<span class="text-[#b9ff66]">V</span>OUS
            </h2>
            <button onclick="closeNewRdvModal()" class="hover:bg-gray-100 p-2 rounded-full">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form class="space-y-6">
            <!-- Patient -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Patient</label>
                <div class="relative">
                    <input type="text" placeholder="Rechercher un patient..."
                           class="w-full pl-10 pr-4 py-2 rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Date et Heure -->
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Date</label>
                    <input type="date" class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Heure</label>
                    <input type="time" class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                </div>
            </div>

            <!-- Type de RDV -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Type de rendez-vous</label>
                <select class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                    <option value="consultation">Consultation</option>
                    <option value="suivi">Suivi</option>
                    <option value="urgence">Urgence</option>
                </select>
            </div>

            <!-- Durée -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Durée</label>
                <select class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                    <option value="15">15 minutes</option>
                    <option value="30" selected>30 minutes</option>
                    <option value="45">45 minutes</option>
                    <option value="60">1 heure</option>
                </select>
            </div>

            <!-- Notes -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Notes</label>
                <textarea rows="3" placeholder="Ajouter des notes pour ce rendez-vous..."
                          class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]"></textarea>
            </div>

            <!-- Boutons d'action -->
            <div class="flex justify-end gap-4 pt-4">
                <button type="button" onclick="closeNewRdvModal()"
                        class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                    Annuler
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-[#b9ff66] hover:bg-[#a8eb5f] rounded-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Bloquer Créneau -->
<div id="blockCreneauModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-[20px] p-8 w-full max-w-md mx-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">
                BLOQUER UN CRÉNE<span class="text-[#b9ff66]">A</span>U
            </h2>
            <button onclick="toggleBlockCreneauModal()" class="hover:bg-gray-100 p-2 rounded-full">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form class="space-y-6">
            <!-- Période -->
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Date début</label>
                    <input type="date" class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Date fin</label>
                    <input type="date" class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                </div>
            </div>

            <!-- Horaires -->
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Heure début</label>
                    <input type="time" class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Heure fin</label>
                    <input type="time" class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                </div>
            </div>

            <!-- Motif -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Motif</label>
                <select class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]">
                    <option value="pause">Pause déjeuner</option>
                    <option value="conge">Congés</option>
                    <option value="formation">Formation</option>
                    <option value="autre">Autre</option>
                </select>
            </div>

            <!-- Notes -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Notes (optionnel)</label>
                <textarea rows="2" placeholder="Précisions sur le blocage..."
                          class="w-full rounded-lg border-gray-300 focus:ring-[#b9ff66] focus:border-[#b9ff66]"></textarea>
            </div>

            <!-- Boutons d'action -->
            <div class="flex justify-end gap-4 pt-4">
                <button type="button" onclick="toggleBlockCreneauModal()"
                        class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                    Annuler
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-[#b9ff66] hover:bg-[#a8eb5f] rounded-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Bloquer
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openNewRdvModal() {
    document.getElementById('newRdvModal').classList.remove('hidden');
    document.getElementById('newRdvModal').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeNewRdvModal() {
    document.getElementById('newRdvModal').classList.add('hidden');
    document.getElementById('newRdvModal').classList.remove('flex');
    document.body.style.overflow = 'auto';
}

function toggleBlockCreneauModal() {
    const modal = document.getElementById('blockCreneauModal');
    if (modal.classList.contains('hidden')) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    } else {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }
}

// Fermeture des modals en cliquant en dehors
document.getElementById('newRdvModal').addEventListener('click', function(e) {
    if (e.target === this) closeNewRdvModal();
});

document.getElementById('blockCreneauModal').addEventListener('click', function(e) {
    if (e.target === this) toggleBlockCreneauModal();
});

// Initialisation du drag & drop pour les rendez-vous
document.addEventListener('DOMContentLoaded', function() {
    const rdvElements = document.querySelectorAll('[data-rdv]');
    rdvElements.forEach(rdv => {
        rdv.setAttribute('draggable', true);
        rdv.addEventListener('dragstart', handleDragStart);
        rdv.addEventListener('dragend', handleDragEnd);
    });

    const calendarCells = document.querySelectorAll('#calendar-grid > div');
    calendarCells.forEach(cell => {
        cell.addEventListener('dragover', handleDragOver);
        cell.addEventListener('drop', handleDrop);
    });
});

let draggedRdv = null;

function handleDragStart(e) {
    draggedRdv = this;
    this.classList.add('opacity-50');
}

function handleDragEnd(e) {
    draggedRdv.classList.remove('opacity-50');
    draggedRdv = null;
}

function handleDragOver(e) {
    e.preventDefault();
}

function handleDrop(e) {
    e.preventDefault();
    if (draggedRdv) {
        this.appendChild(draggedRdv);
        // Ici, vous pouvez ajouter la logique pour mettre à jour la base de données
    }
}
</script>
@endpush
