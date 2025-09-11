@extends('dashAdmin.layout')

@section('title', 'Modifier le Médecin')
@section('page-title', 'Modifier le Médecin')
@section('page-description', 'Modifier les informations du médecin')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Modifier les informations</h3>
            <p class="text-sm text-gray-500">Mettre à jour les informations du médecin {{ $medecin->name }}</p>
        </div>

        <form action="{{ route('admin.medecins.update', $medecin->id) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nom complet <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name', $medecin->name) }}"
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
                           value="{{ old('email', $medecin->email) }}"
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
                        <option value="Cardiologie" {{ old('specialite', $medecin->specialite) == 'Cardiologie' ? 'selected' : '' }}>Cardiologie</option>
                        <option value="Dermatologie" {{ old('specialite', $medecin->specialite) == 'Dermatologie' ? 'selected' : '' }}>Dermatologie</option>
                        <option value="Généraliste" {{ old('specialite', $medecin->specialite) == 'Généraliste' ? 'selected' : '' }}>Généraliste</option>
                        <option value="Neurologie" {{ old('specialite', $medecin->specialite) == 'Neurologie' ? 'selected' : '' }}>Neurologie</option>
                        <option value="Ophtalmologie" {{ old('specialite', $medecin->specialite) == 'Ophtalmologie' ? 'selected' : '' }}>Ophtalmologie</option>
                        <option value="Orthopédie" {{ old('specialite', $medecin->specialite) == 'Orthopédie' ? 'selected' : '' }}>Orthopédie</option>
                        <option value="Pédiatrie" {{ old('specialite', $medecin->specialite) == 'Pédiatrie' ? 'selected' : '' }}>Pédiatrie</option>
                        <option value="Psychiatrie" {{ old('specialite', $medecin->specialite) == 'Psychiatrie' ? 'selected' : '' }}>Psychiatrie</option>
                    </select>
                </div>

                <div>
                    <label for="prix_consultation" class="block text-sm font-medium text-gray-700 mb-2">
                        Prix de consultation (TND)
                    </label>
                    <input type="number"
                           id="prix_consultation"
                           name="prix_consultation"
                           value="{{ old('prix_consultation', $medecin->prix_consultation) }}"
                           min="0"
                           step="0.01"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="50.00">
                </div>
            </div>

            <div>
                <label for="adresse_cabinet" class="block text-sm font-medium text-gray-700 mb-2">
                    Adresse du cabinet
                </label>
                <textarea id="adresse_cabinet"
                          name="adresse_cabinet"
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="123 Rue de la Santé, 1000 Tunis, Tunisie">{{ old('adresse_cabinet', $medecin->adresse_cabinet) }}</textarea>
            </div>

            <!-- Informations de compte -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-900 mb-3">Informations de compte</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Inscrit le :</span>
                        <span class="font-medium">{{ $medecin->created_at->format('d/m/Y à H:i') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Dernière connexion :</span>
                        <span class="font-medium">
                            {{ $medecin->last_login_at ? $medecin->last_login_at->format('d/m/Y à H:i') : 'Jamais' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-600">Email vérifié :</span>
                        <span class="font-medium">
                            @if($medecin->email_verified_at)
                                <span class="text-green-600">Oui</span>
                            @else
                                <span class="text-yellow-600">Non</span>
                            @endif
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-600">Statut :</span>
                        <span class="font-medium">
                            @if($medecin->email_verified_at)
                                <span class="text-green-600">Actif</span>
                            @else
                                <span class="text-yellow-600">En attente</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.medecins.index') }}"
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Annuler
                </a>
                <button type="submit"
                        class="px-6 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-save mr-2"></i>
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
