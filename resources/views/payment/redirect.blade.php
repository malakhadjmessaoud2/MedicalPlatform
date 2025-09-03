@extends('dashPatient.layout')

@section('content')
    <div class="min-h-screen bg-gray-50 py-10">
        <div class="max-w-xl mx-auto px-4">
            <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 overflow-hidden">
                <div class="px-6 py-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white text-center">
                    <h2 class="text-2xl font-bold text-gray-800">Paiement Paymee</h2>
                    <p class="mt-1 text-gray-600">Paiement sécurisé via la plateforme Paymee</p>
                </div>

                <div class="px-6 py-6">
                    <div class="text-center">
                        <p class="text-gray-700">Cliquez sur le bouton ci-dessous pour effectuer votre paiement en toute sécurité.</p>
                        <p class="mt-1 text-gray-500">Vous serez redirigé vers Paymee pour compléter votre transaction.</p>
                    </div>

                    <div class="mt-6 flex justify-center">
                        <a href="{{ $paymeeUrl }}"
                           class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-blue-600 text-white font-medium shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                           aria-label="Payer maintenant">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h5M4 7h16a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V9a2 2 0 012-2z"/>
                            </svg>
                            Payer maintenant
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="mt-6 rounded-lg border border-green-200 bg-green-50 text-green-800 px-4 py-3">
                            {{ session('success') }}
                        </div>
                    @elseif (session('error'))
                        <div class="mt-6 rounded-lg border border-red-200 bg-red-50 text-red-800 px-4 py-3">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="mt-6 flex justify-center">
                        <a href="{{ route('patient.rendez-vous.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Revenir aux rendez-vous
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
