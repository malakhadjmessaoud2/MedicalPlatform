<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Laravel\Jetstream\Http\Livewire\UpdateProfileInformationForm as JetstreamUpdateProfileInformationForm;

class UpdateProfileInformationForm extends JetstreamUpdateProfileInformationForm
{
    /**
     * Update the user's profile information.
     *
     * @param  \Laravel\Fortify\Contracts\UpdatesUserProfileInformation  $updater
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function updateProfileInformation(UpdatesUserProfileInformation $updater)
    {
        $this->resetErrorBag();

        // Log détaillé pour déboguer l'upload
        Log::info('[Livewire] updateProfileInformation called', [
            'user_id' => Auth::id(),
            'has_photo' => !is_null($this->photo),
            'photo_type' => gettype($this->photo),
            'photo_class' => is_object($this->photo) ? get_class($this->photo) : 'not_object',
            'photo_size' => is_object($this->photo) && method_exists($this->photo, 'getSize') ? $this->photo->getSize() : 'unknown',
            'photo_name' => is_object($this->photo) && method_exists($this->photo, 'getClientOriginalName') ? $this->photo->getClientOriginalName() : 'unknown',
        ]);

        $input = $this->photo
            ? array_merge($this->state, ['photo' => $this->photo])
            : $this->state;

        Log::info('[Livewire] Input to updater', [
            'user_id' => Auth::id(),
            'input_keys' => array_keys($input),
            'has_photo_key' => array_key_exists('photo', $input),
            'photo_is_file' => isset($input['photo']) && $input['photo'] instanceof \Illuminate\Http\UploadedFile,
            'photo_class' => isset($input['photo']) ? get_class($input['photo']) : 'not_set',
        ]);

        $updater->update(Auth::user(), $input);

        if (isset($this->photo)) {
            Log::info('[Livewire] Photo uploaded, redirecting to profile.show');
            return redirect()->route('profile.show');
        }

        Log::info('[Livewire] No photo uploaded, dispatching saved event');
        $this->dispatch('saved');

        $this->dispatch('refresh-navigation-menu');
    }
}
