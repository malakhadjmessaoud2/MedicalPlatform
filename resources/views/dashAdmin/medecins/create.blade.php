@extends('dashAdmin.layout')

@section('title', 'Ajouter un Médecin')
@section('page-title', 'Ajouter un Médecin')
@section('page-description', 'Créer un nouveau compte médecin')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Informations du médecin</h3>
            <p class="text-sm text-gray-500">Remplissez les informations pour créer un nouveau compte médecin</p>
        </div>

        <form action="{{ route('admin.medecins.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nom complet <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                           placeholder="Dr. Jean Dupont"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Adresse email <span class="text-red-500">*</span>
                    </label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                           placeholder="jean.dupont@example.com"
                           required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="specialite" class="block text-sm font-medium text-gray-700 mb-2">
                        Spécialité médicale
                    </label>
                    <select id="specialite"
                            name="specialite"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Sélectionner une spécialité</option>
                        <option value="Cardiologie" {{ old('specialite') == 'Cardiologie' ? 'selected' : '' }}>Cardiologie</option>
                        <option value="Dermatologie" {{ old('specialite') == 'Dermatologie' ? 'selected' : '' }}>Dermatologie</option>
                        <option value="Généraliste" {{ old('specialite') == 'Généraliste' ? 'selected' : '' }}>Généraliste</option>
                        <option value="Neurologie" {{ old('specialite') == 'Neurologie' ? 'selected' : '' }}>Neurologie</option>
                        <option value="Ophtalmologie" {{ old('specialite') == 'Ophtalmologie' ? 'selected' : '' }}>Ophtalmologie</option>
                        <option value="Orthopédie" {{ old('specialite') == 'Orthopédie' ? 'selected' : '' }}>Orthopédie</option>
                        <option value="Pédiatrie" {{ old('specialite') == 'Pédiatrie' ? 'selected' : '' }}>Pédiatrie</option>
                        <option value="Psychiatrie" {{ old('specialite') == 'Psychiatrie' ? 'selected' : '' }}>Psychiatrie</option>
                    </select>
                </div>

                <div>
                    <label for="prix_consultation" class="block text-sm font-medium text-gray-700 mb-2">
                        Prix de consultation (TND)
                    </label>
                    <input type="number"
                           id="prix_consultation"
                           name="prix_consultation"
                           value="{{ old('prix_consultation', 50) }}"
                           min="0"
                           step="0.01"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="50.00">
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Mot de passe temporaire <span class="text-red-500">*</span>
                </label>
                <input type="password"
                       id="password"
                       name="password"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror"
                       placeholder="Mot de passe sécurisé"
                       required>
                <p class="mt-1 text-sm text-gray-500">Le médecin devra changer ce mot de passe lors de sa première connexion</p>
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="adresse_cabinet" class="block text-sm font-medium text-gray-700 mb-2">
                    Adresse du cabinet
                </label>
                <textarea id="adresse_cabinet"
                          name="adresse_cabinet"
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="123 Rue de la Santé, 1000 Tunis, Tunisie">{{ old('adresse_cabinet') }}</textarea>
            </div>

            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.medecins.index') }}"
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Annuler
                </a>
                <button type="submit"
                        class="px-6 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-plus mr-2"></i>
                    Créer le médecin
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
