<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-[#E0E0E0]">
        <div class="bg-white p-10 rounded-2xl shadow-2xl w-full max-w-md text-center transform transition-transform hover:scale-105">

            <!-- Titre -->
            <h1 class="text-3xl font-bold mb-6 text-black">Compte en attente d'activation</h1>

            <!-- Message session -->
            @if (session('status'))
                <div class="mb-6 px-4 py-3 bg-green-100 text-[#a8f055] rounded-lg border border-green-200">
                    {{ session('status') }}
                </div>
            @else
                <p class="mb-4 text-gray-700">Votre compte médecin est en attente d'activation par l'administrateur.</p>
            @endif

            <!-- Description -->
            <p class="text-sm text-gray-600 mb-8">
                Vous recevrez une notification dès que votre compte sera activé. Vous pourrez alors accéder à votre tableau de bord.
            </p>

            <!-- Boutons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">

                <!-- Retour à l'accueil -->
                <a href="{{ route('welcome') }}" class="w-full sm:w-auto px-6 py-3 bg-[#a8f055] text-black font-semibold rounded-xl shadow hover:bg-green-500 transition-colors">
                    Retour à l'accueil
                </a>

                <!-- Déconnexion -->
                <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-black text-white font-semibold rounded-xl shadow hover:bg-gray-800 transition-colors">
                        Se déconnecter
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
