<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="bg-white p-8 rounded shadow w-full max-w-md text-center">
            <h1 class="text-2xl font-semibold mb-4">Compte en attente d'activation</h1>
            @if (session('status'))
                <div class="mb-4 text-green-700">{{ session('status') }}</div>
            @else
                <p class="mb-4">Votre compte médecin est en attente d'activation par l'administrateur.</p>
            @endif
            <p class="text-sm text-gray-600 mb-6">Vous recevrez une notification dès que votre compte sera activé. Vous pourrez alors accéder à votre tableau de bord.</p>
            <div class="flex items-center justify-center gap-4">
                <a href="{{ route('welcome') }}" class="px-4 py-2 bg-gray-200 rounded">Retour à l'accueil</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Se déconnecter</button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>


