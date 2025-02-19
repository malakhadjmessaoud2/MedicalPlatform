@extends('dashPatient.layout')

@section('content')
<div class="p-8 bg-[#e4e4e4] min-h-screen" x-data="{ isModalOpen: null, cart: [] }">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-12">
        <div class="flex items-center gap-8">
            <h1 class="text-4xl font-bold">
                PHAR<span class="text-[#b9ff66]">MA</span>CARE
            </h1>
            <button @click="$dispatch('open-modal', 'upload-ordonnance')"
                    class="bg-[#b9ff66] text-black rounded-full px-4 py-2.5 flex items-center gap-2 hover:bg-[#a5e65c] transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Déposer une ordonnance</span>
            </button>
        </div>

        <!-- Statistiques -->
        <div class="flex gap-12">
            <div class="text-center relative">
                <span class="text-4xl font-bold">15</span>
                <div class="text-gray-500 text-sm mt-1">Pharmacies</div>
            </div>
            <div class="text-center relative">
                <span class="text-4xl font-bold">3</span>
                <span class="absolute -top-1 -right-4 text-xs bg-[#b9ff66] px-1.5 rounded-full">+1</span>
                <div class="text-gray-500 text-sm mt-1">Ordonnances</div>
            </div>
        </div>
    </div>

    <!-- Filtres et Recherche -->
    <div class="flex justify-between items-center mb-8">
        <div class="flex gap-3">
            <button class="px-4 py-2 bg-black text-white rounded-full">Toutes les pharmacies</button>
            <button class="px-4 py-2 rounded-full hover:bg-white/10 transition-all">Les plus proches</button>
            <button class="px-4 py-2 rounded-full hover:bg-white/10 transition-all">Disponibilité 24/7</button>
        </div>
        <div class="relative">
            <input type="text"
                   placeholder="Rechercher une pharmacie ou un médicament..."
                   class="w-96 px-4 py-2 rounded-full bg-white border-none focus:ring-2 focus:ring-[#b9ff66]">
        </div>
    </div>

    <!-- Grid des Pharmacies -->
    <div class="grid grid-cols-3 gap-6 mb-12">
        <!-- Pharmacie 1 -->
        <div class="bg-white rounded-[30px] p-6 shadow-md hover:shadow-lg transition-all duration-300 ease-in-out transform hover:-translate-y-1 cursor-pointer">
            <div class="flex justify-between items-start mb-6">
                <div class="flex gap-4">
                    <div class="relative">
                        <img src="https://placehold.co/100x100" alt="Pharmacie Centrale"
                             class="w-16 h-16 rounded-2xl object-cover">
                        <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 rounded-full border-2 border-white"></span>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold">Pharmacie Centrale</h3>
                        <p class="text-gray-500 text-sm">123 Avenue République</p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="px-2 py-1 bg-green-100 text-green-600 rounded-full text-xs">Ouverte 24/7</span>
                            <span class="text-sm text-gray-500">⭐ 4.8</span>
                        </div>
                    </div>
                </div>
            </div>
            <button @click="isModalOpen = 'voir-medicaments'"
                    class="w-full py-2 bg-black text-white rounded-full hover:bg-black/90 transition-all">
                Voir les médicaments
            </button>
        </div>

        <!-- Pharmacie 2 -->
        <div class="bg-white rounded-[30px] p-6 shadow-md hover:shadow-lg transition-all duration-300 ease-in-out transform hover:-translate-y-1 cursor-pointer">
            <div class="flex justify-between items-start mb-6">
                <div class="flex gap-4">
                    <div class="relative">
                        <img src="https://placehold.co/100x100" alt="Pharmacie du Sud"
                             class="w-16 h-16 rounded-2xl object-cover">
                        <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-red-400 rounded-full border-2 border-white"></span>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold">Pharmacie du Sud</h3>
                        <p class="text-gray-500 text-sm">45 Rue du Commerce</p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="px-2 py-1 bg-red-100 text-red-600 rounded-full text-xs">Fermée</span>
                            <span class="text-sm text-gray-500">⭐ 4.5</span>
                        </div>
                    </div>
                </div>
            </div>
            <button disabled
                    class="w-full py-2 bg-gray-200 text-gray-500 rounded-full cursor-not-allowed">
                Voir les médicaments
            </button>
        </div>
    </div>

    <!-- Panier Flottant -->
    <div class="fixed bottom-8 right-8 bg-white rounded-[30px] p-6 shadow-lg w-96">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold">Mon Panier</h3>
            <span class="px-3 py-1 bg-[#b9ff66] rounded-full text-sm">3 articles</span>
        </div>
        <div class="space-y-4 mb-6">
            <div class="flex justify-between items-center">
                <div class="flex gap-3">
                    <img src="https://placehold.co/50x50" alt="Doliprane"
                         class="w-12 h-12 rounded-xl object-cover">
                    <div>
                        <h4 class="font-medium">Doliprane 1000mg</h4>
                        <p class="text-sm text-gray-500">Boîte de 8 comprimés</p>
                    </div>
                </div>
                <span class="font-medium">12.90 €</span>
            </div>
        </div>
        <div class="border-t pt-4">
            <div class="flex justify-between items-center mb-4">
                <span class="text-gray-500">Total</span>
                <span class="text-xl font-bold">38.70 €</span>
            </div>
            <button @click="isModalOpen = 'paiement'"
                    class="w-full py-3 bg-[#b9ff66] text-black rounded-full hover:bg-[#a5e65c] transition-all font-medium">
                Procéder au paiement
            </button>
        </div>
    </div>

    <!-- Modal Upload Ordonnance -->
    <div x-show="isModalOpen === 'upload-ordonnance'"
         class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-[30px] p-8 w-[500px] relative animate-fade-in">
            <button @click="isModalOpen = null"
                    class="absolute right-6 top-6 text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <h2 class="text-2xl font-bold mb-6">Déposer une ordonnance</h2>

            <div class="space-y-6">
                <div class="border-2 border-dashed border-gray-300 rounded-2xl p-8 text-center">
                    <input type="file" class="hidden" id="ordonnance-upload" accept="image/*,.pdf">
                    <label for="ordonnance-upload" class="cursor-pointer">
                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <span class="text-gray-600">Cliquez ou déposez votre ordonnance ici</span>
                        <p class="text-sm text-gray-500 mt-2">PDF, JPG ou PNG (Max. 5MB)</p>
                    </label>
                </div>

                <button class="w-full py-3 bg-[#b9ff66] text-black rounded-full hover:bg-[#a5e65c] transition-all font-medium">
                    Valider l'ordonnance
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Voir Médicaments -->
    <div x-show="isModalOpen === 'voir-medicaments'"
         class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-[30px] p-8 w-[800px] max-h-[80vh] overflow-y-auto relative animate-fade-in">
            <button @click="isModalOpen = null"
                    class="absolute right-6 top-6 text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="grid grid-cols-2 gap-6">
                <!-- Médicament -->
                <div class="bg-gray-50 rounded-2xl p-4 flex gap-4">
                    <img src="https://placehold.co/100x100" alt="Doliprane"
                         class="w-24 h-24 rounded-xl object-cover">
                    <div class="flex-1">
                        <h3 class="font-bold">Doliprane 1000mg</h3>
                        <p class="text-sm text-gray-500">Boîte de 8 comprimés</p>
                        <div class="flex justify-between items-center mt-4">
                            <span class="font-bold">12.90 €</span>
                            <button @click="cart.push({id: 1, name: 'Doliprane 1000mg', price: 12.90})"
                                    class="px-4 py-2 bg-[#b9ff66] text-black rounded-full hover:bg-[#a5e65c] transition-all text-sm">
                                Ajouter au panier
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Paiement -->
    <div x-show="isModalOpen === 'paiement'"
         class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-[30px] p-8 w-[600px] relative animate-fade-in">
            <button @click="isModalOpen = null"
                    class="absolute right-6 top-6 text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <h2 class="text-2xl font-bold mb-6">Paiement</h2>

            <div class="space-y-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-xl">
                        <span class="text-gray-600">Total à payer</span>
                        <span class="text-2xl font-bold">38.70 €</span>
                    </div>

                    <div class="space-y-4">
                        <input type="text" placeholder="Numéro de carte"
                               class="w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-[#b9ff66]">
                        <div class="grid grid-cols-2 gap-4">
                            <input type="text" placeholder="MM/AA"
                                   class="px-4 py-3 rounded-xl border-gray-200 focus:ring-[#b9ff66]">
                            <input type="text" placeholder="CVC"
                                   class="px-4 py-3 rounded-xl border-gray-200 focus:ring-[#b9ff66]">
                        </div>
                    </div>
                </div>

                <button class="w-full py-3 bg-[#b9ff66] text-black rounded-full hover:bg-[#a5e65c] transition-all font-medium">
                    Confirmer le paiement
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fade-in {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    .animate-fade-in {
        animation: fade-in 0.6s ease-out;
    }

    .grid > div {
        opacity: 0;
        animation: fade-in 0.6s ease-out forwards;
    }

    .grid > div:nth-child(1) { animation-delay: 0.1s; }
    .grid > div:nth-child(2) { animation-delay: 0.2s; }
    .grid > div:nth-child(3) { animation-delay: 0.3s; }
</style>
@endsection
