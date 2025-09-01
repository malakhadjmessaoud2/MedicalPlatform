<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        <span class="text-xl font-semibold text-gray-800">{{ __('Profile Information') }}</span>
    </x-slot>

    <x-slot name="description">
        <span class="text-gray-600">{{ __('Update your account\'s profile information and email address.') }}</span>
    </x-slot>

    <x-slot name="form">
        <!-- Profile Photo -->
        <div x-data="{ photoName: null, photoPreview: null }" class="col-span-6 sm:col-span-4">
            <!-- Profile Photo File Input -->
            <input type="file" id="photo" class="hidden" wire:model="state.profile_photo" x-ref="photo"
                x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />

            <x-label for="photo" value="{{ __('Photo de profil') }}" />

            <!-- Current Profile Photo -->
            <div class="mt-2" x-show="! photoPreview">
                <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}"
                    alt="{{ $this->user->prenom . ' ' . $this->user->nom }}"
                    class="rounded-full size-24 object-cover ring-2 ring-white shadow">
            </div>

            <!-- New Profile Photo Preview -->
            <div class="mt-2" x-show="photoPreview" style="display: none;">
                <span class="block rounded-full size-24 bg-cover bg-no-repeat bg-center ring-2 ring-white shadow"
                    x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                </span>
            </div>

            <x-secondary-button class="mt-2 me-2" type="button" x-on:click.prevent="$refs.photo.click()">
                {{ __('choisir une nouvelle photo') }}
            </x-secondary-button>

            @if ($this->user->profile_photo_path)
                <x-secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto"
                    wire:loading.attr="disabled">
                    {{ __('supprimer la Photo') }}
                </x-secondary-button>
            @endif

            <x-input-error for="photo" class="mt-2" />
        </div>

        <!-- Nom & Prénom -->
        <div class="col-span-6 sm:col-span-3">
            <x-label for="prenom" value="{{ __('Prénom') }}" />
            <x-input id="prenom" type="text" class="mt-1 block w-full" wire:model="state.prenom" required
                autocomplete="given-name" />
            <x-input-error for="prenom" class="mt-2" />
        </div>
        <div class="col-span-6 sm:col-span-3">
            <x-label for="nom" value="{{ __('Nom') }}" />
            <x-input id="nom" type="text" class="mt-1 block w-full" wire:model="state.nom" required
                autocomplete="family-name" />
            <x-input-error for="nom" class="mt-2" />
        </div>
        <!-- Champ name masqué pour compat Jetstream -->
        <div class="hidden">
            <x-input id="name" type="text" wire:model="state.name" x-data x-init="$watch('$root.__livewire.find($root.closest(' [wire\\: id]
                ')?.getAttribute('
                wire: id ')).get('
                state.prenom ')', v => $wire.set('state.name', (v || '') + ' ' + ($wire.get('state.nom') || '')));
            $watch('$root.__livewire.find($root.closest(' [wire\\: id]
                ')?.getAttribute('
                wire: id ')).get('
                state.nom ')', v => $wire.set('state.name', ($wire.get('state.prenom') || '') + ' ' + (v || '')));" />
        </div>

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="email" value="{{ __('Email') }}" />
            <x-input id="email" type="email" class="mt-1 block w-full" wire:model="state.email" required
                autocomplete="username" />
            <x-input-error for="email" class="mt-2" />

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) &&
                    !$this->user->hasVerifiedEmail())
                <p class="text-sm mt-2">
                    {{ __('Your email address is unverified.') }}

                    <button type="button"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        wire:click.prevent="sendEmailVerification">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                @if ($this->verificationLinkSent)
                    <p class="mt-2 font-medium text-sm text-green-600">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                @endif
            @endif
        </div>

        @if (auth()->user()?->role === 'patient')
            <!-- Champs Patient -->
            <div class="col-span-6 sm:col-span-2">
                <x-label for="dateNaissance" value="{{ __('Date de naissance') }}" />
                <x-input id="dateNaissance" type="date" class="mt-1 block w-full" wire:model="state.dateNaissance" />
                <x-input-error for="dateNaissance" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-2">
                <x-label for="tel" value="{{ __('Téléphone') }}" />
                <x-input id="tel" type="text" class="mt-1 block w-full" wire:model="state.tel"
                    autocomplete="tel" />
                <x-input-error for="tel" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-6">
                <x-label for="adresse" value="{{ __('Adresse') }}" />
                <x-input id="adresse" type="text" class="mt-1 block w-full" wire:model="state.adresse"
                    autocomplete="street-address" />
                <x-input-error for="adresse" class="mt-2" />
            </div>
        @elseif (auth()->user()?->role === 'medecin')
            <!-- Champs Médecin -->
            <div class="col-span-6 sm:col-span-3">
                <x-label for="specialite" value="{{ __('Spécialité') }}" />
                <x-input id="specialite" type="text" class="mt-1 block w-full" wire:model="state.specialite" />
                <x-input-error for="specialite" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <x-label for="adresse_cabinet" value="{{ __('Adresse du cabinet') }}" />
                <x-input id="adresse_cabinet" type="text" class="mt-1 block w-full"
                    wire:model="state.adresse_cabinet" />
                <x-input-error for="adresse_cabinet" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-2">
                <x-label for="experience" value="{{ __('Expérience (années)') }}" />
                <x-input id="experience" type="number" min="0" class="mt-1 block w-full"
                    wire:model="state.experience" />
                <x-input-error for="experience" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-4">
                <x-label for="formation" value="{{ __('Formation') }}" />
                <textarea id="formation" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" wire:model="state.formation"
                    rows="2"></textarea>
                <x-input-error for="formation" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-6">
                <x-label for="langues" value="{{ __('Langues') }}" />
                <x-input id="langues" type="text" class="mt-1 block w-full" wire:model="state.langues"
                    placeholder="ex: Français, Arabe, Anglais" />
                <x-input-error for="langues" class="mt-2" />
            </div>
        @endif
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3 text-emerald-600" on="saved">
            {{ __('Profil mis à jour avec succès.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('enregistrer') }}
        </x-button>
    </x-slot>
</x-form-section>
