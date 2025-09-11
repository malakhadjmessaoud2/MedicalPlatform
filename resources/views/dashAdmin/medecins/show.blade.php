@extends('dashAdmin.layout')

@section('title', 'Profil Médecin')
@section('page-title', 'Profil du Médecin')
@section('page-description', 'Détails et statut du compte médecin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center gap-4">
            <img class="w-16 h-16 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($medecin->name) }}&background=3b82f6&color=fff" alt="{{ $medecin->name }}">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">{{ $medecin->name }}</h1>
                <p class="text-gray-600">{{ $medecin->specialite ?? 'Spécialité non définie' }}</p>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500">Email</p>
                <p class="font-medium text-gray-900">{{ $medecin->email }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Statut du compte</p>
                @if($medecin->isActive)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-1"></i>
                        Compte actif
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        <i class="fas fa-clock mr-1"></i>
                        Compte en attente d'activation
                    </span>
                @endif
            </div>
            <div>
                <p class="text-sm text-gray-500">Adresse du cabinet</p>
                <p class="font-medium text-gray-900">{{ $medecin->adresse_cabinet ?? '—' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Prix consultation</p>
                <p class="font-medium text-gray-900">{{ $medecin->prixConsultation ? number_format($medecin->prixConsultation, 2) . ' TND' : '—' }}</p>
            </div>
        </div>
 <!-- Diplôme / CNOM - affichage grand format (image ou PDF) -->
 <div class="mt-8">
    <p class="text-sm text-gray-500 mb-2">Diplôme ou CNOM</p>
    @php
        $diplome = $medecin->DiplômeOrCNOM ?? null;
        $url = null;
        $extension = null;
        if ($diplome) {
            $extension = strtolower(pathinfo($diplome, PATHINFO_EXTENSION));
            $isAbsolute = \Illuminate\Support\Str::startsWith($diplome, ['http://', 'https://', '/']);
            $url = $isAbsolute ? $diplome : asset('storage/' . ltrim($diplome, '/'));
        }
        $imageExtensions = ['jpg','jpeg','png','gif','webp','bmp'];
    @endphp
    @if($diplome)
        @if(in_array($extension, $imageExtensions))
            <div class="p-3 rounded-xl border border-gray-200 bg-gray-50">
                <img src="{{ $url }}" alt="Diplôme / CNOM" class="w-full max-h-[700px] object-contain rounded-lg">
            </div>
        @elseif($extension === 'pdf')
            <div class="p-3 rounded-xl border border-gray-200 bg-gray-50">
                <object data="{{ $url }}" type="application/pdf" class="w-full h-[700px] rounded-lg">
                    <div class="text-gray-600">
                        Impossible d'afficher le PDF.
                        <a href="{{ $url }}" target="_blank" class="text-blue-600 underline">Ouvrir le document</a>
                    </div>
                </object>
            </div>
        @else
            <div class="p-6 rounded-xl border border-gray-200 bg-gray-50">
                <div class="text-sm text-gray-600">
                    Format non pris en charge.
                    <a href="{{ $url }}" target="_blank" class="text-blue-600 underline">Télécharger le fichier</a>
                </div>
            </div>
        @endif
    @else
        <div class="p-6 rounded-xl border border-dashed border-gray-300 text-gray-500 bg-white">
            Non renseigné
        </div>
    @endif
</div>
        <div class="mt-8 flex items-center justify-between">
            <a href="{{ route('admin.medecins.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Retour</a>

            <form action="{{ route('admin.medecins.toggle-status', $medecin->id) }}" method="POST" onsubmit="return confirm('Confirmer le changement de statut ?')">
                @csrf
                @method('PATCH')
                @if($medecin->isActive)
                    <button type="submit" class="px-5 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">
                        Désactiver le compte
                    </button>
                @else
                    <button type="submit" class="px-5 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">
                        Activer le compte
                    </button>
                @endif
            </form>
        </div>


    </div>
</div>
@endsection


