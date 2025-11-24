<x-form-section submit="updatePassword">
    <x-slot name="title">
        {{ __('Mettre à jour le mot de passe') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Assurez-vous que votre compte utilise un mot de passe long et sécurisé.') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <x-label for="current_password" value="{{ __('Mot de passe actuel') }}" />
            <x-input id="current_password" type="password" class="mt-1 block w-full" wire:model="state.current_password" autocomplete="current-password" placeholder="Entrez votre mot de passe actuel" />
            <x-input-error for="current_password" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="password" value="{{ __('Nouveau mot de passe') }}" />
            <x-input id="password" type="password" class="mt-1 block w-full" wire:model="state.password" autocomplete="new-password" placeholder="Entrez votre nouveau mot de passe" />
            <x-input-error for="password" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="password_confirmation" value="{{ __('Confirmer le mot de passe') }}" />
            <x-input id="password_confirmation" type="password" class="mt-1 block w-full" wire:model="state.password_confirmation" autocomplete="new-password" placeholder="Confirmez votre nouveau mot de passe" />
            <x-input-error for="password_confirmation" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3 text-emerald-600" on="saved">
            {{ __('Mot de passe mis à jour avec succès.') }}
        </x-action-message>

        <x-button type="submit" wire:loading.attr="disabled" class="inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span>{{ __('Enregistrer') }}</span>
        </x-button>
    </x-slot>
</x-form-section>
