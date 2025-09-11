@extends('dashPatient.layout')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <!-- Contenu de succès de paiement -->
    <div class="max-w-2xl mx-auto">
        <!-- Icône de succès -->
        <div class="text-center mb-8">
            <div class="mx-auto w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Paiement Réussi !</h1>
            <p class="text-gray-600 text-lg">Votre paiement a été traité avec succès</p>
        </div>

        <!-- Détails du paiement -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Détails du paiement
            </h2>

            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Statut du paiement</span>
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                        ✅ Confirmé
                    </span>
                </div>

                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Date de paiement</span>
                    <span class="font-medium">{{ now()->timezone('Africa/Tunis')->format('d/m/Y à H:i') }}</span>
                </div>

                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Méthode de paiement</span>
                    <span class="font-medium">Carte bancaire</span>
                </div>

                <div class="flex justify-between items-center py-2">
                    <span class="text-gray-600">Montant payé</span>
                    <span class="font-bold text-lg text-green-600">
                        @if(isset($paiement) && $paiement->montant)
                            {{ number_format($paiement->montant, 2) }} TND
                        @else
                            {{ number_format(50.00, 2) }} TND
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- Informations importantes -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-8">
            <h3 class="text-lg font-semibold text-blue-800 mb-3 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Prochaines étapes
            </h3>
            <ul class="space-y-2 text-blue-700">
                <li class="flex items-start">
                    <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                    Votre rendez-vous est maintenant confirmé
                </li>

                <li class="flex items-start">
                    <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                    Vous pouvez consulter vos rendez-vous dans votre tableau de bord
                </li>
            </ul>
        </div>

        <!-- Boutons d'action -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="http://127.0.0.1:8000/patient/rendez-vous"
               class="px-6 py-3 bg-[#b9ff66] hover:bg-[#a3e55a] text-gray-800 rounded-lg font-medium transition-all duration-200 hover:shadow-md flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Voir mes rendez-vous
            </a>

            <a href="http://127.0.0.1:8000/dashboard"
               class="px-6 py-3 border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-lg font-medium transition-all duration-200 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"/>
                </svg>
                Retour au tableau de bord
            </a>
        </div>

        <!-- Message de remerciement -->
        <div class="text-center mt-8">
            <p class="text-gray-500 text-sm">
                Merci pour votre confiance. Nous vous souhaitons une excellente consultation !
            </p>
        </div>
    </div>
</div>
@endsection
