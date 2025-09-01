@extends('dashPatient.layout')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#e4e4e4] via-white to-[#f8f9fa]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- En-tête moderne et chic -->
        <div class="text-center mb-16">
            <div class="relative">
                <div class="absolute inset-0 bg-gradient-to-r from-[#b9ff66]/20 to-blue-100/30 rounded-full blur-3xl"></div>
                <div class="relative inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-white to-gray-50 rounded-full mb-8 shadow-2xl border border-white/50 backdrop-blur-sm">
                    <div class="absolute inset-0 bg-gradient-to-br from-[#b9ff66]/10 to-transparent rounded-full"></div>
                    <svg class="w-12 h-12 text-gray-700 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
            <h1 class="text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-gray-900 via-gray-800 to-gray-700 mb-4">Mes Dossiers Médicaux</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">Accédez à vos consultations, ordonnances et informations médicales</p>
            <div class="mt-8 flex justify-center">
                <div class="w-24 h-1 bg-gradient-to-r from-[#b9ff66] to-blue-400 rounded-full"></div>
            </div>
        </div>

        <!-- Section des médecins avec design moderne -->
        <div class="relative">
            <!-- Effet de fond décoratif -->
            <div class="absolute inset-0 bg-gradient-to-r from-[#b9ff66]/5 via-white to-blue-50/30 rounded-3xl"></div>
            <div class="relative bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/50 overflow-hidden">
                <div class="bg-gradient-to-r from-[#b9ff66] via-[#b9ff66]/90 to-[#b9ff66]/80 px-8 py-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                <div class="absolute inset-0 bg-white/20 rounded-full blur-lg"></div>
                                <div class="relative w-16 h-16 bg-white/95 rounded-full flex items-center justify-center shadow-xl border border-white/50">
                                    <svg class="w-8 h-8 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900">Mes Médecins</h2>
                                <p class="text-gray-800/80 text-sm mt-1">Gérez vos relations médicales</p>
                            </div>
                        </div>
                        <div class="relative group">
                            <div class="absolute inset-0 bg-white/20 rounded-2xl blur-lg group-hover:blur-xl transition-all duration-300"></div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input type="text" id="searchDoc" placeholder="Rechercher un médecin..."
                                       class="pl-12 pr-6 py-4 w-full sm:w-80 rounded-2xl border-0 focus:ring-2 focus:ring-white/80 focus:outline-none text-sm bg-white/95 shadow-lg backdrop-blur-sm transition-all duration-300" />
                            </div>
                        </div>
                </div>
            </div>
            @if(isset($medecins) && $medecins->count())
                <div class="p-8">
                <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100" id="doctorsTable">
                            <thead>
                                <tr>
                                    <th class="px-8 py-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-gradient-to-br from-[#b9ff66]/20 to-blue-100/30 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                            <span class="text-sm">Médecin</span>
                                        </div>
                                    </th>
                                    <th class="px-8 py-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-gradient-to-br from-[#b9ff66]/20 to-blue-100/30 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                            </div>
                                            <span class="text-sm">Spécialité</span>
                                        </div>
                                    </th>
                                    <th class="px-8 py-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-gradient-to-br from-[#b9ff66]/20 to-blue-100/30 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                            <span class="text-sm">Actions</span>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white/50 divide-y divide-gray-100">
                                @foreach($medecins as $doc)
                                    <tr class="hover:bg-gradient-to-r hover:from-[#b9ff66]/5 hover:to-blue-50/30 transition-all duration-300 group">
                                        <td class="px-8 py-6 whitespace-nowrap">
                                            <div class="flex items-center gap-5">
                                                <div class="relative">
                                                    <div class="absolute inset-0 bg-gradient-to-r from-[#b9ff66]/20 to-blue-100/30 rounded-full blur-lg group-hover:blur-xl transition-all duration-300"></div>
                                                    <img class="relative w-16 h-16 rounded-full object-cover ring-4 ring-white shadow-xl"
                                                         src="{{ $doc->profile_photo_path ? asset('storage/'.$doc->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($doc->prenom.' '.$doc->nom).'&background=3B82F6&color=ffffff&size=128' }}"
                                                         alt="{{ $doc->prenom }}" />
                                                    <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-gradient-to-r from-[#b9ff66] to-green-400 rounded-full border-3 border-white flex items-center justify-center shadow-lg">
                                                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="text-xl font-bold text-gray-900 group-hover:text-gray-800 transition-colors">Dr. {{ $doc->prenom }} {{ $doc->nom }}</div>
                                                    <div class="text-sm text-gray-600 mt-1">{{ $doc->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <span class="inline-flex px-4 py-2 text-sm font-semibold bg-gradient-to-r from-[#b9ff66]/20 to-blue-100/30 text-gray-800 rounded-xl border border-[#b9ff66]/30">
                                                    {{ $doc->specialite ?? 'Généraliste' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6 whitespace-nowrap">
                                            <button data-medecin-id="{{ $doc->id }}"
                                                    data-medecin-name="Dr. {{ $doc->prenom }} {{ $doc->nom }}"
                                                    class="btnVoirConsultations group relative inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#b9ff66] to-[#b9ff66]/90 text-black rounded-xl font-bold hover:from-[#a8eb5f] hover:to-[#a8eb5f]/90 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 hover:-translate-y-0.5">
                                                <div class="absolute inset-0 bg-white/20 rounded-xl blur-sm group-hover:blur-md transition-all duration-300"></div>
                                                <svg class="relative w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                <span class="relative">Voir dossier complet</span>
                                            </button>
                                        </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun médecin associé</h3>
                    <p class="text-gray-600">Vous n'avez pas encore de médecin associé à votre dossier médical.</p>
                </div>
            @endif
        </div>

        <!-- Modal moderne et professionnelle pour les rendez-vous -->
        <div id="rendezVousModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-50">
            <div class="flex items-center justify-center min-h-full p-4">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-7xl max-h-[95vh] overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
                    <!-- En-tête de la modal -->
                    <div class="bg-gradient-to-r from-[#b9ff66] to-green-400 px-8 py-6 relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-white/10 to-transparent"></div>
                        <div class="relative flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 id="modalTitle" class="text-2xl font-bold text-white">Mon Dossier Médical Complet</h3>
                                    <p class="text-white/90 text-sm">Consultez l'historique de vos consultations et téléchargez votre dossier</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <button id="btnTelechargerDossier" class="hidden group relative inline-flex items-center px-4 py-2 bg-white/20 text-white rounded-xl font-semibold hover:bg-white/30 transition-all duration-300 backdrop-blur-sm">
                                    <div class="absolute inset-0 bg-white/10 rounded-xl blur-sm group-hover:blur-md transition-all duration-300"></div>
                                    <svg class="relative w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    <span class="relative">Télécharger le dossier complet</span>
                                </button>
                                <button id="closeModal" class="text-white hover:text-gray-100 text-2xl transition-all duration-300 hover:scale-110">✕</button>
                            </div>
                        </div>
                    </div>

                    <!-- Contenu principal -->
                    <div class="p-8 overflow-y-auto max-h-[calc(95vh-200px)]">
                        <div id="rendezVousContainer" class="space-y-8">
                            <!-- Loading state -->
                            <div class="flex items-center justify-center py-12">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#b9ff66]"></div>
                                <span class="ml-3 text-gray-600">Chargement de votre dossier médical...</span>
                            </div>
                        </div>
                    </div>

                    <!-- Pied de modal -->
                    <div class="px-8 py-6 border-t border-gray-100 bg-gray-50 flex justify-end">
                        <button id="closeModal2" class="px-6 py-3 bg-[#b9ff66] text-black rounded-xl font-semibold hover:bg-[#a8eb5f] transition-all duration-300 shadow-lg hover:shadow-xl">
                            Fermer
                        </button>
                    </div>
                </div>
            </div>
        </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchDoc');
    const table = document.getElementById('doctorsTable');
    if (searchInput && table) {
        searchInput.addEventListener('input', () => {
            const q = searchInput.value.toLowerCase();
            table.querySelectorAll('tbody tr').forEach(tr => {
                const text = tr.innerText.toLowerCase();
                tr.style.display = text.includes(q) ? '' : 'none';
            });
        });
    }

    const modal = document.getElementById('rendezVousModal');
    const closeBtns = [document.getElementById('closeModal'), document.getElementById('closeModal2')];
    const container = document.getElementById('rendezVousContainer');
    const title = document.getElementById('modalTitle');
    const btnTelechargerDossier = document.getElementById('btnTelechargerDossier');

    function openModal() {
        modal.classList.remove('hidden');
        setTimeout(() => {
            const modalContent = document.getElementById('modalContent');
            if (modalContent) {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }
        }, 10);
    }

    function closeModal() {
        const modalContent = document.getElementById('modalContent');
        if (modalContent) {
            modalContent.classList.add('scale-95', 'opacity-0');
            modalContent.classList.remove('scale-100', 'opacity-100');
        }
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    closeBtns.forEach(btn => btn && btn.addEventListener('click', closeModal));
    modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });

    document.querySelectorAll('.btnVoirConsultations').forEach(btn => {
        btn.addEventListener('click', async () => {
            const medecinId = btn.getAttribute('data-medecin-id');
            const medecinName = btn.getAttribute('data-medecin-name');
            title.textContent = `Mon Dossier Médical - ${medecinName}`;
            btnTelechargerDossier.classList.remove('hidden');
            btnTelechargerDossier.setAttribute('data-medecin-id', medecinId);
            btnTelechargerDossier.setAttribute('data-medecin-name', medecinName);

            container.innerHTML = `
                <div class="flex items-center justify-center py-12">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#b9ff66]"></div>
                    <span class="ml-3 text-gray-600">Chargement de votre dossier médical...</span>
                </div>
            `;
            openModal();

            try {
                const url = new URL(`{{ route('patient.dossier') }}`, window.location.origin);
                url.searchParams.set('medecin_id', medecinId);
                const resp = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                if (!resp.ok) throw new Error('HTTP '+resp.status);
                const data = await resp.json();
                if (!data.success) throw new Error('Réponse invalide');
                const rendezVousAvecConsultations = data.rendez_vous_avec_consultations || [];
                container.innerHTML = '';

                if (rendezVousAvecConsultations.length > 0) {
                    let htmlContent = '';

                    // En-tête du dossier
                    htmlContent += `
                        <div class="bg-gradient-to-r from-blue-50 to-green-50 rounded-2xl p-6 mb-8 border border-blue-100">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 bg-[#b9ff66] rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900">Dossier Médical - ${medecinName}</h2>
                                    <p class="text-gray-600">Historique complet de vos consultations et prescriptions</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <div class="bg-white/50 rounded-lg p-3">
                                    <div class="font-semibold text-gray-700">Total des rendez-vous</div>
                                    <div class="text-2xl font-bold text-[#b9ff66]">${rendezVousAvecConsultations.length}</div>
                                </div>
                                <div class="bg-white/50 rounded-lg p-3">
                                    <div class="font-semibold text-gray-700">Consultations effectuées</div>
                                    <div class="text-2xl font-bold text-blue-600">${rendezVousAvecConsultations.filter(item => item.consultations.length > 0).length}</div>
                                </div>
                                <div class="bg-white/50 rounded-lg p-3">
                                    <div class="font-semibold text-gray-700">Ordonnances disponibles</div>
                                    <div class="text-2xl font-bold text-green-600">${rendezVousAvecConsultations.filter(item => item.consultations.some(c => c.ordonnance)).length}</div>
                                </div>
                            </div>
                        </div>
                    `;

                    // Liste des rendez-vous avec consultations détaillées
                    rendezVousAvecConsultations.forEach((item, index) => {
                        const rv = item.rendez_vous;
                        const consultations = item.consultations;
                        const hasOrdonnance = consultations.some(c => c.ordonnance);

                        // Status badge
                        const statusClass = rv.statut === 'confirmed' || rv.statut === 'confirmé' ? 'bg-green-100 text-green-800' :
                                          rv.statut === 'completed' || rv.statut === 'terminé' ? 'bg-blue-100 text-blue-800' :
                                          'bg-gray-100 text-gray-800';

                        htmlContent += `
                            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300">
                                <!-- En-tête du rendez-vous -->
                                <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-6 py-4 border-b border-gray-100">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 bg-[#b9ff66] rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-bold text-gray-900">Rendez-vous du ${rv.date_debut_formatted}</h3>
                                                <div class="text-sm text-gray-600">${rv.date_debut_formatted} - ${rv.date_fin_formatted}</div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full ${statusClass}">
                                                ${rv.type}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contenu du rendez-vous -->
                                <div class="p-6">
                                    ${consultations.length > 0 ? `
                                        <div class="space-y-6">
                                            ${consultations.map((consultation, cIndex) => {
                                                const dateOnly = consultation.date_formatted ? consultation.date_formatted.split(' à ')[0] : '—';

                                                return `
                                                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                                                        <div class="flex items-center justify-between mb-4">
                                                            <h4 class="text-lg font-semibold text-gray-900">Consultation du ${dateOnly}</h4>
                                                            <div class="flex items-center gap-2">
                                                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                                    Consultation médicale
                                                                </span>
                                                                ${consultation.ordonnance && consultation.ordonnance.file ? `
                                                                    <div class="flex gap-2">
                                                                        <button class="btnVoirOrdonnance inline-flex items-center px-3 py-2 bg-[#b9ff66] text-black text-xs font-semibold rounded-lg hover:bg-[#a8eb5f] transition-colors duration-200" data-ordonnance='${JSON.stringify(consultation.ordonnance)}'>
                                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                            </svg>
                                                                            Voir ordonnance
                                                                        </button>
                                                                        <button class="btnTelechargerOrdonnance inline-flex items-center px-3 py-2 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition-colors duration-200" data-ordonnance='${JSON.stringify(consultation.ordonnance)}'>
                                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                                            </svg>
                                                                            Télécharger
                                                                        </button>
                                                                    </div>
                                                                ` : ''}
                                                            </div>
                                                        </div>

                                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                                            <!-- Informations principales -->
                                                            <div class="space-y-4">
                                                                <div>
                                                                    <h5 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                        </svg>
                                                                        Motif de consultation
                                                                    </h5>
                                                                    <p class="text-sm text-gray-600 bg-white p-3 rounded-lg border">${consultation.motif || 'Non spécifié'}</p>
                                                                </div>

                                                                <div>
                                                                    <h5 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                        </svg>
                                                                        Symptômes
                                                                    </h5>
                                                                    <p class="text-sm text-gray-600 bg-white p-3 rounded-lg border">${consultation.symptomes || 'Non spécifiés'}</p>
                                                                </div>

                                                                ${consultation.traitement_actuel ? `
                                                                    <div>
                                                                        <h5 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                            </svg>
                                                                            Traitement actuel
                                                                        </h5>
                                                                        <p class="text-sm text-gray-600 bg-white p-3 rounded-lg border">${consultation.traitement_actuel}</p>
                                                                    </div>
                                                                ` : ''}

                                                                ${consultation.medicaments_prescrits ? `
                                                                    <div>
                                                                        <h5 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                            </svg>
                                                                            Médicaments prescrits
                                                                        </h5>
                                                                        <p class="text-sm text-gray-600 bg-white p-3 rounded-lg border">${consultation.medicaments_prescrits}</p>
                                                                    </div>
                                                                ` : ''}
                                                            </div>

                                                            <!-- Signes vitaux et mesures -->
                                                            <div class="space-y-4">
                                                                <div>
                                                                    <h5 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                        </svg>
                                                                        Signes vitaux
                                                                    </h5>
                                                                    <div class="grid grid-cols-2 gap-3">
                                                                        ${consultation.tension_arterielle ? `
                                                                            <div class="text-center p-3 bg-blue-50 rounded-lg border">
                                                                                <div class="text-xs text-blue-600 font-medium">Tension</div>
                                                                                <div class="text-sm font-bold text-blue-800">${consultation.tension_arterielle}</div>
                                                                            </div>
                                                                        ` : ''}
                                                                        ${consultation.frequence_cardiaque ? `
                                                                            <div class="text-center p-3 bg-green-50 rounded-lg border">
                                                                                <div class="text-xs text-green-600 font-medium">Fréquence cardiaque</div>
                                                                                <div class="text-sm font-bold text-green-800">${consultation.frequence_cardiaque}</div>
                                                                            </div>
                                                                        ` : ''}
                                                                        ${consultation.temperature ? `
                                                                            <div class="text-center p-3 bg-orange-50 rounded-lg border">
                                                                                <div class="text-xs text-orange-600 font-medium">Température</div>
                                                                                <div class="text-sm font-bold text-orange-800">${consultation.temperature}°C</div>
                                                                            </div>
                                                                        ` : ''}
                                                                        ${consultation.saturation_o2 ? `
                                                                            <div class="text-center p-3 bg-purple-50 rounded-lg border">
                                                                                <div class="text-xs text-purple-600 font-medium">Saturation O₂</div>
                                                                                <div class="text-sm font-bold text-purple-800">${consultation.saturation_o2}%</div>
                                                                            </div>
                                                                        ` : ''}
                                                                    </div>
                                                                </div>

                                                                <div>
                                                                    <h5 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                        </svg>
                                                                        Mesures anthropométriques
                                                                    </h5>
                                                                    <div class="grid grid-cols-3 gap-3">
                                                                        ${consultation.poids ? `
                                                                            <div class="text-center p-3 bg-indigo-50 rounded-lg border">
                                                                                <div class="text-xs text-indigo-600 font-medium">Poids</div>
                                                                                <div class="text-sm font-bold text-indigo-800">${consultation.poids} kg</div>
                                                                            </div>
                                                                        ` : ''}
                                                                        ${consultation.taille ? `
                                                                            <div class="text-center p-3 bg-pink-50 rounded-lg border">
                                                                                <div class="text-xs text-pink-600 font-medium">Taille</div>
                                                                                <div class="text-sm font-bold text-pink-800">${consultation.taille} cm</div>
                                                                            </div>
                                                                        ` : ''}
                                                                        ${consultation.imc ? `
                                                                            <div class="text-center p-3 bg-yellow-50 rounded-lg border">
                                                                                <div class="text-xs text-yellow-600 font-medium">IMC</div>
                                                                                <div class="text-sm font-bold text-yellow-800">${consultation.imc}</div>
                                                                            </div>
                                                                        ` : ''}
                                                                    </div>
                                                                </div>

                                                                ${consultation.propositions_suivi ? `
                                                                    <div>
                                                                        <h5 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                            </svg>
                                                                            Propositions de suivi
                                                                        </h5>
                                                                        <p class="text-sm text-gray-600 bg-white p-3 rounded-lg border">${consultation.propositions_suivi}</p>
                                                                    </div>
                                                                ` : ''}

                                                                ${consultation.instructions_particulieres ? `
                                                                    <div>
                                                                        <h5 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                            </svg>
                                                                            Instructions particulières
                                                                        </h5>
                                                                        <p class="text-sm text-gray-600 bg-white p-3 rounded-lg border">${consultation.instructions_particulieres}</p>
                                                                    </div>
                                                                ` : ''}
                                                            </div>
                                                        </div>

                                                        ${consultation.evolution_symptomes || consultation.effets_secondaires || consultation.examens_controle || consultation.orientation_patient ? `
                                                            <div class="mt-6 pt-6 border-t border-gray-200">
                                                                <h5 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                    </svg>
                                                                    Informations complémentaires
                                                                </h5>
                                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                                    ${consultation.evolution_symptomes ? `
                                                                        <div>
                                                                            <h6 class="text-xs font-semibold text-gray-600 mb-1">Évolution des symptômes</h6>
                                                                            <p class="text-sm text-gray-600 bg-white p-3 rounded-lg border">${consultation.evolution_symptomes}</p>
                                                                        </div>
                                                                    ` : ''}
                                                                    ${consultation.effets_secondaires ? `
                                                                        <div>
                                                                            <h6 class="text-xs font-semibold text-gray-600 mb-1">Effets secondaires</h6>
                                                                            <p class="text-sm text-gray-600 bg-white p-3 rounded-lg border">${consultation.effets_secondaires}</p>
                                                                        </div>
                                                                    ` : ''}
                                                                    ${consultation.examens_controle ? `
                                                                        <div>
                                                                            <h6 class="text-xs font-semibold text-gray-600 mb-1">Examens de contrôle</h6>
                                                                            <p class="text-sm text-gray-600 bg-white p-3 rounded-lg border">${consultation.examens_controle}</p>
                                                                        </div>
                                                                    ` : ''}
                                                                    ${consultation.orientation_patient ? `
                                                                        <div>
                                                                            <h6 class="text-xs font-semibold text-gray-600 mb-1">Orientation</h6>
                                                                            <p class="text-sm text-gray-600 bg-white p-3 rounded-lg border">${consultation.orientation_patient}</p>
                                                                        </div>
                                                                    ` : ''}
                                                                </div>
                                                            </div>
                                                        ` : ''}
                                                    </div>
                                                `;
                                            }).join('')}
                                        </div>
                                    ` : `
                                        <div class="text-center py-8">
                                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                            <h4 class="text-lg font-medium text-gray-900 mb-2">Aucune consultation effectuée</h4>
                                            <p class="text-gray-600">Ce rendez-vous n'a pas encore donné lieu à une consultation médicale.</p>
                                        </div>
                                    `}
                                </div>
                            </div>
                        `;
                    });

                    container.innerHTML = htmlContent;

                    // Event listeners pour les boutons d'ordonnance
                    document.querySelectorAll('.btnVoirOrdonnance').forEach(btn => {
                        btn.addEventListener('click', async () => {
                            const ordonnance = JSON.parse(btn.getAttribute('data-ordonnance'));



                            if (ordonnance.file) {
                                try {
                                    // Afficher un indicateur de chargement
                                    const originalContent = btn.innerHTML;
                                    btn.innerHTML = `
                                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-black mr-1"></div>
                                        <span>Ouverture...</span>
                                    `;
                                    btn.disabled = true;

                                    // Appel API pour visualiser l'ordonnance
                                    const url = new URL(`{{ route('patient.ordonnance.view') }}`, window.location.origin);
                                    url.searchParams.set('file', ordonnance.file);

                                    // Créer un lien temporaire pour ouvrir dans un nouvel onglet
                                    const link = document.createElement('a');
                                    link.href = url.toString();
                                    link.target = '_blank';
                                    link.style.display = 'none';

                                    document.body.appendChild(link);
                                    link.click();
                                    document.body.removeChild(link);

                                    // Restaurer le bouton après un délai
                                    setTimeout(() => {
                                        btn.innerHTML = originalContent;
                                        btn.disabled = false;
                                    }, 1000);

                                } catch (error) {
                                    console.error('Erreur lors de l\'ouverture de l\'ordonnance:', error);

                                    // Message d'erreur plus spécifique
                                    let errorMessage = 'Erreur lors de l\'ouverture de l\'ordonnance.';
                                    if (error.message.includes('404')) {
                                        errorMessage = 'Le fichier d\'ordonnance n\'existe plus ou a été supprimé.';
                                    } else if (error.message.includes('403')) {
                                        errorMessage = 'Vous n\'avez pas l\'autorisation d\'accéder à cette ordonnance.';
                                    } else if (error.name === 'TypeError' && error.message.includes('fetch')) {
                                        errorMessage = 'Erreur de connexion. Vérifiez votre connexion internet.';
                                    }

                                    alert(errorMessage);

                                    // Restaurer le bouton en cas d'erreur
                                    btn.innerHTML = `
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Voir ordonnance
                                    `;
                                    btn.disabled = false;
                                }
                            } else {
                                alert('Aucun fichier d\'ordonnance disponible. Veuillez contacter votre médecin.');
                            }
                        });
                    });

                    document.querySelectorAll('.btnTelechargerOrdonnance').forEach(btn => {
                        btn.addEventListener('click', async () => {
                            const ordonnance = JSON.parse(btn.getAttribute('data-ordonnance'));



                            if (ordonnance.file) {
                                try {
                                    // Afficher un indicateur de chargement
                                    const originalContent = btn.innerHTML;
                                    btn.innerHTML = `
                                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-1"></div>
                                        <span>Téléchargement...</span>
                                    `;
                                    btn.disabled = true;

                                    // Appel API pour télécharger l'ordonnance
                                    const url = new URL(`{{ route('patient.ordonnance.download') }}`, window.location.origin);
                                    url.searchParams.set('file', ordonnance.file);

                                    // Créer un lien temporaire pour le téléchargement
                                    const link = document.createElement('a');
                                    link.href = url.toString();
                                    link.download = '';
                                    link.style.display = 'none';

                                    document.body.appendChild(link);
                                    link.click();
                                    document.body.removeChild(link);

                                    // Restaurer le bouton après un délai
                                    setTimeout(() => {
                                        btn.innerHTML = originalContent;
                                        btn.disabled = false;
                                    }, 1000);

                                } catch (error) {
                                    console.error('Erreur lors du téléchargement de l\'ordonnance:', error);
                                    // Message d'erreur plus spécifique
                                    let errorMessage = 'Erreur lors du téléchargement de l\'ordonnance.';
                                    if (error.message.includes('404')) {
                                        errorMessage = 'Le fichier d\'ordonnance n\'existe plus ou a été supprimé.';
                                    } else if (error.message.includes('403')) {
                                        errorMessage = 'Vous n\'avez pas l\'autorisation d\'accéder à cette ordonnance.';
                                    } else if (error.name === 'TypeError' && error.message.includes('fetch')) {
                                        errorMessage = 'Erreur de connexion. Vérifiez votre connexion internet.';
                                    }

                                    alert(errorMessage);

                                    // Restaurer le bouton en cas d'erreur
                                    btn.innerHTML = `
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003-3v1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                        Télécharger
                                    `;
                                    btn.disabled = false;
                                }
                            } else {
                                alert('Aucun fichier d\'ordonnance disponible. Veuillez contacter votre médecin.');
                            }
                        });
                    });

                } else {
                    container.innerHTML = `
                        <div class="text-center py-16">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun rendez-vous trouvé</h3>
                            <p class="text-gray-600">Vous n'avez pas encore de rendez-vous avec ce médecin.</p>
                        </div>
                    `;
                }
            } catch (e) {
                console.error('Erreur lors du chargement des données:', e);
                container.innerHTML = `
                    <div class="text-center py-16">
                        <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Erreur de chargement</h3>
                        <p class="text-gray-600 mb-4">Une erreur s'est produite lors du chargement de votre dossier médical.</p>
                        <button onclick="location.reload()" class="px-4 py-2 bg-[#b9ff66] text-black rounded-lg hover:bg-[#a8eb5f] transition-colors">
                            Réessayer
                        </button>
                    </div>
                `;
            }
        });
    });

    // Event listener pour le bouton de téléchargement du dossier complet
    btnTelechargerDossier.addEventListener('click', async () => {
        const medecinId = btnTelechargerDossier.getAttribute('data-medecin-id');
        const medecinName = btnTelechargerDossier.getAttribute('data-medecin-name');

        try {
            // Afficher un indicateur de chargement
            const originalContent = btnTelechargerDossier.innerHTML;
            btnTelechargerDossier.innerHTML = `
                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                <span>Téléchargement en cours...</span>
            `;
            btnTelechargerDossier.disabled = true;

            // Appel API pour générer le dossier complet
            const url = new URL(`{{ route('patient.dossier') }}`, window.location.origin);
            url.searchParams.set('medecin_id', medecinId);
            url.searchParams.set('download', 'true');

            const resp = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/pdf'
                }
            });

            if (!resp.ok) {
                throw new Error(`Erreur HTTP: ${resp.status} ${resp.statusText}`);
            }

            // Vérifier le type de contenu
            const contentType = resp.headers.get('content-type');
            if (!contentType || !contentType.includes('application/pdf')) {
                // Si ce n'est pas un PDF, essayer de traiter comme JSON pour obtenir un message d'erreur
                const errorData = await resp.json();
                throw new Error(errorData.message || 'Le serveur n\'a pas généré de PDF');
            }

            const blob = await resp.blob();
            const downloadUrl = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = downloadUrl;
            link.download = `dossier_medical_${medecinName.replace(/[^a-zA-Z0-9]/g, '_')}.pdf`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            window.URL.revokeObjectURL(downloadUrl);

            // Restaurer le bouton
            btnTelechargerDossier.innerHTML = originalContent;
            btnTelechargerDossier.disabled = false;

        } catch (error) {
            console.error('Erreur lors du téléchargement du dossier:', error);

            // Message d'erreur plus détaillé
            let errorMessage = 'Erreur lors du téléchargement du dossier.';
            if (error.message.includes('HTTP')) {
                errorMessage = `Erreur serveur: ${error.message}`;
            } else if (error.message.includes('PDF')) {
                errorMessage = 'La génération du PDF a échoué. Veuillez contacter l\'administrateur.';
            } else if (error.name === 'TypeError' && error.message.includes('fetch')) {
                errorMessage = 'Erreur de connexion. Vérifiez votre connexion internet.';
            }

            alert(errorMessage);

            // Restaurer le bouton en cas d'erreur
            btnTelechargerDossier.innerHTML = `
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Télécharger le dossier complet
            `;
            btnTelechargerDossier.disabled = false;
        }
    });
});
</script>

</div>
@endsection
