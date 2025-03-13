<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
            @csrf

            <div>
                <x-label for="name" value="{{ __('Nom') }}" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <x-label for="prenom" value="{{ __('Prénom') }}" />
                <x-input id="prenom" class="block mt-1 w-full" type="text" name="prenom" :value="old('prenom')" required />
            </div>

            <div class="mt-4">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="role" value="{{ __('Rôle') }}" />
                <select id="role" name="role" class="block mt-1 w-full border-gray-300 focus:border-[#4dabb4] focus:ring focus:ring-[#4dabb4] focus:ring-opacity-50 rounded-md shadow-sm" required>
                    <option value="">Sélectionnez un rôle</option>
                    <option value="medecin" {{ old('role') == 'medecin' ? 'selected' : '' }}>Médecin</option>
                    <option value="patient" {{ old('role') == 'patient' ? 'selected' : '' }}>Patient</option>
                </select>
            </div>

            <!-- Champ spécifique au médecin -->
            <div class="mt-4">
                <x-label for="specialite" value="{{ __('Spécialité (pour médecin)') }}" />
                <x-input id="specialite" class="block mt-1 w-full" type="text" name="specialite" :value="old('specialite')" />
                <p class="text-sm text-gray-500 mt-1">Requis uniquement pour les médecins</p>
            </div>

            <!-- Champ spécifique au patient -->
            <div class="mt-4">
                <x-label for="dateNaissance" value="{{ __('Date de naissance (pour patient)') }}" />
                <x-input id="dateNaissance" class="block mt-1 w-full" type="date" name="dateNaissance" :value="old('dateNaissance')" />
                <p class="text-sm text-gray-500 mt-1">Requis uniquement pour les patients</p>
            </div>

            <!-- Photo de profil -->
            <div class="mt-4">
                <x-label for="profile_photo" value="{{ __('Photo de profil') }}" />
                <div class="flex items-center space-x-4">
                    <label for="profile_photo" class="flex flex-col items-center justify-center w-32 h-32 border-2 border-dashed border-gray-300 rounded-full cursor-pointer hover:bg-gray-50 transition-colors">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </label>
                    <div>
                        <input id="profile_photo" type="file" class="hidden" name="profile_photo" accept="image/*" />
                        <div class="text-sm text-gray-500">
                            <p>Cliquez pour sélectionner une photo</p>
                            <p class="mt-1">JPG, PNG ou GIF. Max 1MB.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Mot de passe') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Confirmer le mot de passe') }}" />
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" required />

                            <div class="ms-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4dabb4]">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4dabb4]">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4dabb4]" href="{{ route('login') }}">
                    {{ __('Déjà inscrit?') }}
                </a>

                <x-button class="ms-4">
                    {{ __('S\'inscrire') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
