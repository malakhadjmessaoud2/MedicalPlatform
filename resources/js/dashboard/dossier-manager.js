/**
 * DossierManager - Module pour gérer les dossiers médicaux
 */
class DossierManager {
    constructor() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.init();
    }

    init() {
        this.setupEventListeners();
    }

    setupEventListeners() {
        // Event delegation pour les boutons de dossier
        document.addEventListener('click', (e) => {
            if (e.target.closest('[data-action="open-dossier"]')) {
                e.preventDefault();
                const button = e.target.closest('[data-action="open-dossier"]');
                const dossierId = button.dataset.dossierId;
                this.openDossierModal(dossierId);
            }

            if (e.target.closest('[data-action="close-dossier"]')) {
                e.preventDefault();
                this.closeDossierModal();
            }
        });

        // Fermer le modal en cliquant en dehors
        document.addEventListener('click', (e) => {
            const modal = document.getElementById('dossierModal');
            if (modal && e.target === modal) {
                this.closeDossierModal();
            }
        });
    }

    openDossierModal(dossierId) {
        if (!dossierId) {
            console.error('ID du dossier non valide');
            return;
        }

        const modal = document.getElementById('dossierModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        // Afficher un loader
        const content = document.getElementById('dossierContent');
        content.innerHTML = `
            <div class="text-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#b9ff66] mx-auto"></div>
                <p class="text-gray-500 mt-2">Chargement du dossier...</p>
            </div>
        `;

        // Charger les données du dossier
        fetch(`/medecin/dossiers/${dossierId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur lors du chargement du dossier');
                }
                return response.json();
            })
            .then(data => {
                if (data.html) {
                    content.innerHTML = data.html;
                } else {
                    throw new Error('Format de données invalide');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                content.innerHTML = `
                    <div class="text-center py-8">
                        <p class="text-red-500">Une erreur est survenue lors du chargement du dossier</p>
                        <p class="text-gray-500 text-sm mt-2">${error.message}</p>
                    </div>
                `;
            });
    }

    closeDossierModal() {
        const modal = document.getElementById('dossierModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.getElementById('dossierContent').innerHTML = '';
    }
}

// Export pour utilisation globale
window.DossierManager = DossierManager;
