@extends('dashPatient.layout')

@section('content')
<div class="container bg-[#e4e4e4] mx-auto px-4 py-6 min-h-screen" x-data="{
    step: 1,
    cart: [],
    selectedMed: null,
    searchQuery: '',
    prescriptionRequired: false,
    prescriptionFile: null,
    orderStatus: 'pending',
    deliveryStatus: 'pending',
    showPrescriptionModal: false,
    showPaymentModal: false,
    trackingNumber: null,
    deliveryAddress: '',
    selectedPharmacy: null
}">
    <!-- Header avec stepper -->
    <div class="mb-8 bg-[#e4e4e4]">
        <h1 class="text-2xl md:text-3xl font-bold mb-6">Commander des Médicaments</h1>

        <!-- Stepper -->
        <div class="bg-white rounded-xl p-4 shadow-sm mb-8">
            <div class="flex overflow-x-auto hide-scrollbar">
                <div class="flex space-x-4 min-w-max px-2">
                    <template x-for="(label, index) in [
                        'Recherche',
                        'Panier',
                        'Ordonnance',
                        'Pharmacie',
                        'Paiement',
                        'Préparation',
                        'Livraison',
                        'Confirmation'
                    ]" :key="index">
                        <div class="flex items-center">
                            <div class="flex flex-col items-center">
                                <div :class="{
                                    'w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium': true,
                                    'bg-[#4CAF50] text-white': step > index,
                                    'bg-[#4CAF50] text-white': step === index + 1,
                                    'bg-gray-200': step < index + 1
                                }">
                                    <span x-text="index + 1"></span>
                                </div>
                                <span class="text-xs mt-1 whitespace-nowrap" x-text="label"></span>
                            </div>
                            <template x-if="index < 7">
                                <div class="w-12 h-[2px] mx-2" :class="{
                                    'bg-[#4CAF50]': step > index + 1,
                                    'bg-gray-200': step <= index + 1
                                }"></div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Étape 1: Recherche de médicaments -->
        <div x-show="step === 1" class="space-y-6">
            <!-- Barre de recherche -->
            <div class="bg-white rounded-xl p-6 shadow-sm">
                <div class="relative">
                    <input type="text"
                           x-model="searchQuery"
                           placeholder="Rechercher un médicament..."
                           class="w-full pl-12 pr-4 py-3 rounded-lg border-gray-200 focus:ring-[#4CAF50] focus:border-[#4CAF50]">
                    <svg class="w-6 h-6 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                <!-- Filtres -->
                <div class="flex gap-3 mt-4">
                    <button class="px-4 py-2 bg-[#4CAF50]/10 text-[#4CAF50] rounded-full">Tous</button>
                    <button class="px-4 py-2 rounded-full hover:bg-gray-100">Sans ordonnance</button>
                    <button class="px-4 py-2 rounded-full hover:bg-gray-100">Avec ordonnance</button>
                </div>
            </div>

            <!-- Grille de médicaments -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Carte médicament -->
                <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-all">
                    <img src="https://placehold.co/300x200" alt="Médicament" class="w-full h-48 object-cover rounded-xl mb-4">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-bold text-lg">Doliprane 1000mg</h3>
                            <p class="text-sm text-gray-500">Boîte de 8 comprimés</p>
                        </div>
                        <span class="px-2 py-1 bg-orange-100 text-orange-600 rounded-full text-xs">Ordonnance requise</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-lg">12.90 €</span>
                        <button @click="cart.push({id: 1, name: 'Doliprane 1000mg', price: 12.90, requiresPrescription: true})"
                                class="px-4 py-2 bg-[#4CAF50] text-white rounded-full hover:bg-[#45a049] transition-all">
                            Ajouter au panier
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panier flottant -->
        <div class="fixed bottom-6 right-6">
            <button @click="step = 2"
                    class="bg-[#4CAF50] text-white p-4 rounded-full shadow-lg hover:bg-[#45a049] transition-all flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span x-text="cart.length" class="font-bold">0</span>
            </button>
        </div>
        <!-- Étape 2: Panier -->
<div x-show="step === 2" class="bg-white rounded-xl p-6 shadow-sm space-y-6">
    <h2 class="text-2xl font-bold">Votre Panier</h2>

    <!-- Liste des médicaments -->
    <div class="space-y-4">
        <template x-for="(item, index) in cart" :key="index">
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center gap-4">
                    <img src="https://placehold.co/100x100" alt="Médicament" class="w-16 h-16 rounded-lg object-cover">
                    <div>
                        <h3 class="font-semibold" x-text="item.name"></h3>
                        <p class="text-sm text-gray-500" x-text="`${item.price} €`"></p>
                        <span x-show="item.requiresPrescription"
                              class="text-xs text-orange-600">Ordonnance requise</span>
                    </div>
                </div>
                <button @click="cart.splice(index, 1)"
                        class="text-red-500 hover:text-red-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>
        </template>

        <!-- Total et Actions -->
        <div class="border-t pt-4 mt-6">
            <div class="flex justify-between items-center mb-4">
                <span class="font-bold text-lg">Total</span>
                <span class="font-bold text-lg" x-text="`${cart.reduce((sum, item) => sum + item.price, 0).toFixed(2)} €`"></span>
            </div>
            <button @click="step = 3"
                    class="w-full py-3 bg-[#4CAF50] text-white rounded-full hover:bg-[#45a049] transition-all">
                Continuer
            </button>
        </div>
    </div>
</div>

<!-- Étape 3: Téléchargement Ordonnance -->
<div x-show="step === 3" class="bg-white rounded-xl p-6 shadow-sm space-y-6">
    <h2 class="text-2xl font-bold">Ordonnance Médicale</h2>

    <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center">
        <input type="file"
               @change="prescriptionFile = $event.target.files[0]"
               class="hidden"
               id="prescription"
               accept="image/*,.pdf">
        <label for="prescription" class="cursor-pointer">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
            </svg>
            <p class="mt-4 text-gray-600">Cliquez pour télécharger ou glissez votre ordonnance ici</p>
            <p class="mt-2 text-sm text-gray-500">PDF, JPG, PNG (Max. 5MB)</p>
        </label>
    </div>

    <div x-show="prescriptionFile" class="bg-green-50 p-4 rounded-lg">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span x-text="prescriptionFile?.name" class="text-green-700"></span>
        </div>
    </div>

    <button @click="step = 4"
            :disabled="!prescriptionFile"
            :class="{
                'w-full py-3 rounded-full transition-all': true,
                'bg-[#4CAF50] text-white hover:bg-[#45a049]': prescriptionFile,
                'bg-gray-200 text-gray-500 cursor-not-allowed': !prescriptionFile
            }">
        Continuer
    </button>
</div>

<!-- Étape 4: Sélection Pharmacie -->
<div x-show="step === 4" class="bg-white rounded-xl p-6 shadow-sm space-y-6">
    <h2 class="text-2xl font-bold">Choisir une Pharmacie</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <template x-for="pharmacy in [
            {id: 1, name: 'Pharmacie Centrale', address: '123 Rue Principal', distance: '0.5km', rating: 4.5},
            {id: 2, name: 'Pharmacie du Marché', address: '45 Avenue Commerce', distance: '1.2km', rating: 4.8}
        ]">
            <div class="border rounded-xl p-4 hover:border-[#4CAF50] cursor-pointer"
                 @click="selectedPharmacy = pharmacy">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-semibold" x-text="pharmacy.name"></h3>
                        <p class="text-sm text-gray-500" x-text="pharmacy.address"></p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="text-sm text-gray-500" x-text="pharmacy.distance"></span>
                            <span class="flex items-center text-sm text-yellow-500">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                </svg>
                                <span x-text="pharmacy.rating"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <button @click="step = 5"
            :disabled="!selectedPharmacy"
            :class="{
                'w-full py-3 rounded-full transition-all': true,
                'bg-[#4CAF50] text-white hover:bg-[#45a049]': selectedPharmacy,
                'bg-gray-200 text-gray-500 cursor-not-allowed': !selectedPharmacy
            }">
        Continuer
    </button>
</div>

<!-- Étape 5: Paiement -->
<div x-show="step === 5" class="bg-white rounded-xl p-6 shadow-sm space-y-6">
    <h2 class="text-2xl font-bold">Paiement</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Récapitulatif -->
        <div class="bg-gray-50 rounded-xl p-6">
            <h3 class="font-semibold mb-4">Récapitulatif de la commande</h3>
            <div class="space-y-3">
                <template x-for="item in cart">
                    <div class="flex justify-between text-sm">
                        <span x-text="item.name"></span>
                        <span x-text="`${item.price} €`"></span>
                    </div>
                </template>
                <div class="pt-3 border-t">
                    <div class="flex justify-between font-semibold">
                        <span>Total</span>
                        <span x-text="`${cart.reduce((sum, item) => sum + item.price, 0).toFixed(2)} €`"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulaire de paiement -->
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Numéro de carte</label>
                <input type="text" class="w-full px-4 py-2 border rounded-lg focus:ring-[#4CAF50] focus:border-[#4CAF50]">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date d'expiration</label>
                    <input type="text" placeholder="MM/AA" class="w-full px-4 py-2 border rounded-lg focus:ring-[#4CAF50] focus:border-[#4CAF50]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">CVC</label>
                    <input type="text" class="w-full px-4 py-2 border rounded-lg focus:ring-[#4CAF50] focus:border-[#4CAF50]">
                </div>
            </div>
            <button @click="step = 6" class="w-full py-3 bg-[#4CAF50] text-white rounded-full hover:bg-[#45a049] transition-all">
                Payer
            </button>
        </div>
    </div>
</div>

<!-- Étapes 6-8: Suivi de commande -->
<div x-show="step >= 6" class="bg-white rounded-xl p-6 shadow-sm space-y-6">
    <div class="text-center">
        <template x-if="step === 6">
            <div>
                <svg class="mx-auto w-16 h-16 text-[#4CAF50]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h2 class="text-2xl font-bold mt-4">Commande confirmée !</h2>
                <p class="text-gray-600 mt-2">Votre commande est en cours de préparation</p>
            </div>
        </template>

        <!-- Barre de progression -->
        <div class="max-w-xl mx-auto mt-8">
            <div class="relative">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm" :class="{'text-[#4CAF50] font-medium': step >= 6}">Préparation</span>
                    <span class="text-sm" :class="{'text-[#4CAF50] font-medium': step >= 7}">En livraison</span>
                    <span class="text-sm" :class="{'text-[#4CAF50] font-medium': step >= 8}">Livrée</span>
                </div>
                <div class="h-2 bg-gray-200 rounded-full">
                    <div class="h-full bg-[#4CAF50] rounded-full transition-all duration-500"
                         :style="`width: ${((step - 5) / 3) * 100}%`"></div>
                </div>
            </div>
        </div>

        <button @click="step = Math.min(step + 1, 8)"
                x-show="step < 8"
                class="mt-8 px-6 py-2 bg-[#4CAF50] text-white rounded-full hover:bg-[#45a049] transition-all">
            Suivant
        </button>
    </div>
</div>

<!-- Styles et Scripts -->
<style>
    @keyframes fade-in {
        0% { opacity: 0; transform: translateY(10px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }

    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }
</style>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('orderManagement', () => ({
            // ... vos données Alpine.js
        }))
    })
</script>

@endsection
