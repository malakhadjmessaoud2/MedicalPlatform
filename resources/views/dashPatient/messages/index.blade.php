@extends('dashPatient.layout')

@section('content')
<div class="p-8 bg-gray-50 min-h-screen rounded-2xl">
    <div class="max-w-6xl mx-auto">
        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Messages</h1>
            <p class="text-gray-600 mt-2">Communiquez avec votre équipe médicale</p>
        </div>

        <!-- Interface de messages -->
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
            <!-- En-tête des messages -->
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900">Conversations</h2>
                    <button class="px-4 py-2 bg-[#b9ff66] text-black rounded-lg hover:bg-[#92cc52] transition-colors font-medium">
                        Nouveau message
                    </button>
                </div>
            </div>

            <!-- Liste des conversations -->
            <div class="divide-y divide-gray-100">
                <!-- Conversation exemple -->
                <div class="p-6 hover:bg-gray-50 cursor-pointer transition-colors">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-[#b9ff66] rounded-full flex items-center justify-center">
                            <span class="text-black font-semibold">Dr</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900">Dr. Ahmed Ben Salem</h3>
                            <p class="text-gray-600">Dernier message: Consultation de suivi programmée</p>
                            <p class="text-sm text-gray-500">Il y a 2 heures</p>
                        </div>
                        <div class="w-3 h-3 bg-[#b9ff66] rounded-full"></div>
                    </div>
                </div>

                <!-- Autre conversation -->
                <div class="p-6 hover:bg-gray-50 cursor-pointer transition-colors">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center">
                            <span class="text-gray-600 font-semibold">Ph</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900">Pharmacie Centrale</h3>
                            <p class="text-gray-600">Votre commande de médicaments est prête</p>
                            <p class="text-sm text-gray-500">Hier</p>
                        </div>
                    </div>
                </div>

                <!-- Message système -->
                <div class="p-6 hover:bg-gray-50 cursor-pointer transition-colors">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900">Système</h3>
                            <p class="text-gray-600">Votre rendez-vous de demain a été confirmé</p>
                            <p class="text-sm text-gray-500">Il y a 1 jour</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Message d'information -->
            <div class="p-6 bg-gray-50">
                <div class="text-center">
                    <p class="text-gray-600">Aucune nouvelle conversation pour le moment.</p>
                    <p class="text-sm text-gray-500 mt-1">Les messages de votre équipe médicale apparaîtront ici.</p>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl p-6 border border-gray-100 text-center">
                <div class="w-16 h-16 bg-[#b9ff66]/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-[#b9ff66]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Contacter mon médecin</h3>
                <p class="text-gray-600 text-sm">Envoyez un message à votre médecin traitant</p>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-gray-100 text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Demander un document</h3>
                <p class="text-gray-600 text-sm">Solicitez un document médical</p>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-gray-100 text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Prendre rendez-vous</h3>
                <p class="text-gray-600 text-sm">Planifiez votre prochaine consultation</p>
            </div>
        </div>
    </div>
</div>
@endsection
