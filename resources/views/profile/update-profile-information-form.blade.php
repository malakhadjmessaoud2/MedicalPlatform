<x-form-section submit="updateProfileInformation">
    {{-- <x-slot name="title">
    </x-slot> --}}

    <x-slot name="description">
    </x-slot>

    <x-slot name="form">
        <!-- Notifications succès/erreur -->
        <div x-data="{show:false,message:'',type:'success'}" x-on:saved.window="type='success';message='{{ __('Profil mis à jour avec succès.') }}';show=true;setTimeout(()=>show=false,3000)" class="col-span-6">
            <template x-if="show">
                <div x-bind:class="type==='success' ? 'bg-emerald-50 text-emerald-700 ring-emerald-200' : 'bg-rose-50 text-rose-700 ring-rose-200'" class="mb-6 px-4 py-3 rounded-lg ring-1 shadow-sm">
                    <span x-text="message"></span>
                </div>
            </template>
        </div>
        @if ($errors->any())
            <div class="col-span-6 mb-6 px-4 py-3 rounded-lg ring-1 ring-rose-200 bg-rose-50 text-rose-700 shadow-sm">
                {{ __('Échec de la mise à jour. Veuillez corriger les champs indiqués en rouge.') }}
            </div>
        @endif
        <!-- Disposition centrée et responsive -->
        <div class="col-span-6">
            <div class="max-w-5xl mx-auto">
                <div class="grid grid-cols-1 {{ auth()->user()?->role === 'medecin' ? 'lg:grid-cols-2' : 'lg:grid-cols-1 max-w-2xl mx-auto' }} gap-8">
            <!-- Colonne gauche: Informations personnelles -->
            <div class="space-y-6">
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-6 border border-indigo-100 shadow-sm">
                    <h3 id="section-infos-personnelles" class="text-lg font-bold text-gray-900 flex items-center gap-3 mb-2">
                        <span class="text-2xl">👤</span>
                        <span>Informations personnelles</span>
                    </h3>
                    <p class="text-sm text-gray-600 ml-11">Gérez vos informations de base et votre photo de profil</p>
                </div>

                <!-- Photo de profil -->
                <div x-data="{
                    photoName: null,
                    photoPreview: null,
                    cacheBuster: Date.now(),
                    isUploading: false,
                    uploadProgress: 0,
                    error: ''
                }"
                x-on:saved.window="console.log('Saved event received'); cacheBuster = Date.now(); window.dispatchEvent(new CustomEvent('profile-photo-updated')); setTimeout(() => { photoPreview = null; photoName = null; }, 100)"
                x-on:livewire-upload-start="isUploading = true; error = ''; uploadProgress = 0; clearTimeout(window.uploadTimeout); console.log('Livewire upload started'); console.log('Event detail:', $event.detail)"
                x-on:livewire-upload-finish="isUploading = false; uploadProgress = 100; cacheBuster = Date.now(); clearTimeout(window.uploadTimeout); console.log('Livewire upload finished'); console.log('Event detail:', $event.detail); setTimeout(() => { uploadProgress = 0; }, 1000)"
                x-on:livewire-upload-error="isUploading = false; error = 'Erreur lors du téléchargement'; uploadProgress = 0; clearTimeout(window.uploadTimeout); console.log('Livewire upload error'); console.log('Error detail:', $event.detail)"
                x-on:livewire-upload-progress="uploadProgress = $event.detail.progress; console.log('Livewire upload progress:', $event.detail.progress + '%'); console.log('Progress event detail:', $event.detail)"
                role="group" aria-labelledby="section-infos-personnelles"
                class="bg-white rounded-xl border border-gray-200 p-6 shadow-md hover:shadow-lg transition-shadow duration-200">

                    <div class="mb-4">
                        <x-label for="photo" value="{{ __('Photo de profil') }}" class="text-sm font-semibold text-gray-900" />
                        <p class="text-xs text-gray-500 mt-1">JPG, JPEG ou PNG. Taille max 1 Mo.</p>
                    </div>

                    <!-- Input fichier caché -->
                    <input type="file" id="photo" class="hidden" wire:model="photo" x-ref="photo" accept="image/*"
                        x-on:change="
                            error='';
                            uploadProgress = 0;
                            const file = $refs.photo.files[0];
                            if (!file) {
                                isUploading = false;
                                photoName = null;
                                photoPreview = null;
                                return;
                            }
                            if (file.size > 1024 * 1024) {
                                error = 'La taille maximale autorisée est 1 Mo.';
                                isUploading = false;
                                uploadProgress = 0;
                                $refs.photo.value = null;
                                photoName = null;
                                photoPreview = null;
                                return;
                            }
                            photoName = file.name;
                            const reader = new FileReader();
                            reader.onload = (e) => { photoPreview = e.target.result };
                            reader.readAsDataURL(file);
                            // Upload via Livewire standard
                            isUploading = true;
                            console.log('Starting Livewire upload for file:', file.name, 'Size:', file.size);

                            // Timeout pour détecter si l'upload reste bloqué
                            window.uploadTimeout = setTimeout(() => {
                                if (isUploading) {
                                    console.error('Upload timeout - upload stuck at', uploadProgress + '%');
                                    isUploading = false;
                                    error = 'Timeout: L\'upload a pris trop de temps';
                                }
                            }, 30000); // 30 secondes timeout

                            $wire.upload('photo', file);
                        " />

                    <!-- Aperçu de la photo -->
                    <div class="flex justify-center">
                        <div class="relative">
                            <template x-if="!photoPreview">
                                <div class="relative">
                                    <img :src="'{{ asset('storage/' . ($this->user->profile_photo_path ?? Auth::user()->profile_photo_path)) }}?v=' + cacheBuster"
                                         alt="{{ $this->user->prenom . ' ' . $this->user->nom }}"
                                         class="rounded-full size-32 sm:size-36 object-cover ring-4 ring-gray-100 shadow-lg border-2 border-white" />
                                    <button type="button" title="Changer la photo" aria-label="Changer la photo" x-on:click.prevent="$refs.photo.click()" class="absolute -bottom-2 -right-2 inline-flex items-center justify-center rounded-full bg-indigo-600 text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500" style="width:36px;height:36px;">
                                        ✏️
                                    </button>
                                </div>
                            </template>
                            <template x-if="photoPreview">
                                <div class="relative">
                                    <span class="rounded-full size-32 sm:size-36 bg-cover bg-no-repeat bg-center ring-4 ring-indigo-100 shadow-lg border-2 border-indigo-200 block"
                                          x-bind:style="'background-image: url(\'' + photoPreview + '\');'"></span>
                                    <button type="button" title="Changer la photo" aria-label="Changer la photo" x-on:click.prevent="$refs.photo.click()" class="absolute -bottom-2 -right-2 inline-flex items-center justify-center rounded-full bg-indigo-600 text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500" style="width:36px;height:36px;">
                                        ✏️
                                    </button>
                                </div>
                            </template>

                            <!-- Indicateur de chargement -->
                            <div x-show="isUploading" class="absolute inset-0 rounded-full bg-black bg-opacity-50 flex items-center justify-center">
                                <div class="flex flex-col items-center gap-2">
                                    <div class="animate-spin rounded-full h-8 w-8 border-2 border-white border-t-transparent"></div>
                                    <span class="text-xs text-white" x-text="uploadProgress + '%' "></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Nom du fichier sélectionné -->
                    <div x-show="photoName" class="mt-4 text-center">
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Fichier sélectionné:</span>
                            <span x-text="photoName" class="text-indigo-600"></span>
                        </p>
                    </div>

                    <x-input-error for="photo" class="mt-3" />
                    <template x-if="error">
                        <div class="mt-3 px-3 py-2 rounded-md bg-rose-50 text-rose-700 ring-1 ring-rose-200 text-sm" role="alert">
                            <span x-text="error"></span>
                        </div>
                    </template>
                    <div x-show="isUploading" class="mt-3">
                        <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-2 bg-indigo-500 transition-all duration-200" :style="`width: ${uploadProgress}%;`"></div>
                        </div>
                    </div>

                    <!-- Actions sous la photo -->
                    <div class="mt-4 flex items-center justify-center gap-3">
                        <button type="button"
                            class="inline-flex items-center gap-2 px-3 py-2 rounded-md bg-white text-gray-700 border border-gray-200 text-sm font-medium shadow-sm hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            x-on:click.prevent="$refs.photo.click()"
                            :disabled="isUploading">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span x-text="isUploading ? 'Téléchargement...' : 'Choisir une photo'"></span>
                        </button>
                        @if ($this->user->profile_photo_path)
                            <button type="button"
                                class="inline-flex items-center gap-2 px-3 py-2 rounded-md bg-rose-50 text-rose-700 border border-rose-200 text-sm font-medium shadow-sm hover:bg-rose-100 focus:outline-none focus:ring-2 focus:ring-rose-500"
                                wire:click="deleteProfilePhoto"
                                wire:loading.attr="disabled"
                                wire:confirm="Êtes-vous sûr de vouloir supprimer votre photo de profil ?">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span>Supprimer</span>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Nom & Prénom -->
                <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-md hover:shadow-lg transition-shadow duration-200">
                    <div>
                        <x-label for="prenom" value="{{ __('Prénom') }}" class="text-sm font-semibold text-gray-900" />
                        <span class="text-rose-600 ml-1" aria-hidden="true">*</span>
                        <div class="relative mt-2">
                            <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">👤</span>
                            <x-input id="prenom" type="text" class="mt-0 block w-full pl-10 pr-4 py-2.5 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200" wire:model="state.prenom" required aria-required="true" autocomplete="given-name" placeholder="Ex: Amine" />
                        </div>
                        <p class="text-xs text-gray-500 mt-1.5">Votre prénom tel qu'il apparaîtra sur votre profil.</p>
                        <x-input-error for="prenom" class="mt-2" />
                    </div>

                    <div class="mt-6">
                        <x-label for="nom" value="{{ __('Nom') }}" class="text-sm font-semibold text-gray-900" />
                        <span class="text-rose-600 ml-1" aria-hidden="true">*</span>
                        <div class="relative mt-2">
                            <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">👤</span>
                            <x-input id="nom" type="text" class="mt-0 block w-full pl-10 pr-4 py-2.5 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200" wire:model="state.nom" required aria-required="true" autocomplete="family-name" placeholder="Ex: Ben Ali" />
                        </div>
                        <p class="text-xs text-gray-500 mt-1.5">Nom de famille.</p>
                        <x-input-error for="nom" class="mt-2" />
                    </div>
                </div>
        <!-- Champ name masqué pour compat Jetstream -->
        <div class="hidden" x-data="{ prenom: @entangle('state.prenom'), nom: @entangle('state.nom') }" x-effect="$wire.set('state.name', ((prenom || '') + ' ' + (nom || '')).trim())">
            <x-input id="name" type="text" wire:model="state.name" />
        </div>

                <!-- Email (HTML5 + pré-remplissage serveur) -->
                <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-md hover:shadow-lg transition-shadow duration-200">
                    <x-label for="email" value="{{ __('Email') }}" class="text-sm font-semibold text-gray-900" />
                    <span class="text-rose-600 ml-1" aria-hidden="true">*</span>
                    <div class="relative mt-2">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">📧</span>
                        <x-input id="email" type="email" class="mt-0 block w-full pl-10 pr-4 py-2.5 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200" wire:model.defer="state.email" required aria-required="true" autocomplete="username" placeholder="exemple@domaine.tn" value="{{ old('email', $this->user->email ?? Auth::user()->email) }}" pattern="^(?!\.)[^\s@]+@[^\s@]+\.[^\s@]+$" title="Email invalide (ex. nom@domaine.tld)" />
                    </div>
                    <p class="text-xs text-gray-500 mt-1.5">Utilisé pour la connexion et les notifications.</p>
                    <x-input-error for="email" class="mt-2" />

                    @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && !$this->user->hasVerifiedEmail())
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
                    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-md hover:shadow-lg transition-shadow duration-200">
                        <h4 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <span>📋</span>
                            <span>Informations complémentaires</span>
                        </h4>
                        <div class="space-y-6">
                            <div>
                                <x-label for="dateNaissance" value="{{ __('Date de naissance') }}" class="text-sm font-semibold text-gray-900" />
                                <div class="relative mt-2">
                                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">📅</span>
                                    <x-input id="dateNaissance" type="date" class="mt-0 block w-full pl-10 pr-4 py-2.5 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200" wire:model="state.dateNaissance" />
                                </div>
                                <x-input-error for="dateNaissance" class="mt-2" />
                            </div>
                            <div>
                                <x-label for="tel" value="{{ __('Téléphone') }}" class="text-sm font-semibold text-gray-900" />
                                <div class="relative mt-2">
                                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">📞</span>
                                    <x-input id="tel" type="text" class="mt-0 block w-full pl-10 pr-4 py-2.5 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200" wire:model="state.tel" autocomplete="tel" placeholder="Ex: +216 12 345 678" />
                                </div>
                                <x-input-error for="tel" class="mt-2" />
                            </div>
                            <div>
                                <x-label for="adresse" value="{{ __('Adresse') }}" class="text-sm font-semibold text-gray-900" />
                                <div class="relative mt-2">
                                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">🏠</span>
                                    <x-input id="adresse" type="text" class="mt-0 block w-full pl-10 pr-4 py-2.5 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200" wire:model="state.adresse" autocomplete="street-address" placeholder="Rue, Ville, Code postal" />
                                </div>
                                <x-input-error for="adresse" class="mt-2" />
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Colonne droite: Informations professionnelles (Médecin) -->
            @if (auth()->user()?->role === 'medecin')
                <div class="space-y-6">
                    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl p-6 border border-emerald-100 shadow-sm">
                        <h3 id="section-infos-professionnelles" class="text-lg font-bold text-gray-900 flex items-center gap-3 mb-2">
                            <span class="text-2xl">🩺</span>
                            <span>Informations professionnelles</span>
                        </h3>
                        <p class="text-sm text-gray-600 ml-11">Complétez vos informations médicales et professionnelles</p>
                    </div>
                    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-md hover:shadow-lg transition-shadow duration-200">
                        <x-label for="specialite" value="{{ __('Spécialité') }}" class="text-sm font-semibold text-gray-900" />
                        @php
                            // Charger dynamiquement les spécialités de médecins pouvant consulter en ligne
                            $specialitesEnLigne = \App\Models\User::query()
                                ->where('role', 'medecin')
                                ->whereNotNull('specialite')
                                ->pluck('specialite')
                                ->unique()
                                ->sort()
                                ->values()
                                ->all();
                        @endphp
                        <div class="relative mt-2">
                            <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">🩺</span>
                            <select id="specialite" class="mt-0 block w-full pl-10 pr-10 py-2.5 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition-all duration-200" wire:model="state.specialite" aria-required="true">
                                <option value="">{{ __('Sélectionner une spécialité') }}</option>
                                @foreach ($specialitesEnLigne as $sp)
                                    <option value="{{ $sp }}">{{ $sp }}</option>
                                @endforeach
                            </select>
                        </div>
                        <p class="text-xs text-gray-500 mt-1.5">Seules les spécialités ci-dessus acceptent la consultation en ligne.</p>
                        <x-input-error for="specialite" class="mt-2" />
                    </div>
                    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-md hover:shadow-lg transition-shadow duration-200">
                        <x-label for="adresse_cabinet" value="{{ __('Adresse du cabinet') }}" class="text-sm font-semibold text-gray-900" />
                        <div class="relative mt-2">
                            <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">🏥</span>
                            <x-input id="adresse_cabinet" type="text" class="mt-0 block w-full pl-10 pr-4 py-2.5 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200" wire:model="state.adresse_cabinet" placeholder="Saisissez votre adresse du cabinet (Rue, Ville, Code postal)" />
                        </div>
                        <p class="text-xs text-gray-500 mt-1.5">Adresse visible par vos patients pour se rendre au cabinet.</p>
                        <x-input-error for="adresse_cabinet" class="mt-2" />
                    </div>
                    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-md hover:shadow-lg transition-shadow duration-200">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <x-label for="experience" value="{{ __('Expérience (années)') }}" class="text-sm font-semibold text-gray-900" />
                                <div class="relative mt-2">
                                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">⏳</span>
                                    <x-input id="experience" type="number" min="0" class="mt-0 block w-full pl-10 pr-4 py-2.5 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200" wire:model="state.experience" placeholder="Nombre d'années" />
                                </div>
                                <p class="text-xs text-gray-500 mt-1.5">Nombre total d'années d'expérience.</p>
                                <x-input-error for="experience" class="mt-2" />
                            </div>
                            <div>
                                <x-label for="prixConsultation" value="{{ __('Prix de consultation (DT)') }}" class="text-sm font-semibold text-gray-900" />
                                <div class="relative mt-2">
                                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">💵</span>
                                    <x-input id="prixConsultation" type="number" min="60" step="1" class="mt-0 block w-full pl-10 pr-4 py-2.5 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200" wire:model="state.prixConsultation" placeholder="Ex: 70" />
                                </div>
                                <p class="text-xs text-gray-500 mt-1.5">Minimum 60 DT.</p>
                                <x-input-error for="prixConsultation" class="mt-2" />
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-md hover:shadow-lg transition-shadow duration-200">
                        <x-label for="formation" value="{{ __('Formation') }}" class="text-sm font-semibold text-gray-900" />
                        <div class="relative mt-2">
                            <span class="pointer-events-none absolute inset-y-0 left-3 flex items-start pt-2.5 text-gray-400">🎓</span>
                            <textarea id="formation" class="mt-0 block w-full border-gray-300 rounded-lg shadow-sm pl-10 pr-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200" wire:model="state.formation" rows="3" placeholder="Ex: Faculté de Médecine de Tunis, Résidence..."></textarea>
                        </div>
                        <p class="text-xs text-gray-500 mt-1.5">Listez vos formations principales et résidences.</p>
                        <x-input-error for="formation" class="mt-2" />
                    </div>
                    <div x-data="{ fileName: '', error: '' }" class="bg-white rounded-xl border border-gray-200 p-6 shadow-md hover:shadow-lg transition-shadow duration-200">
                        <x-label for="DiplômeOrCNOM" value="{{ __('Diplôme ou CNOM (jpg, png, pdf)') }}" class="text-sm font-semibold text-gray-900" />
                        <p class="text-xs text-gray-500 mt-1.5">Formats acceptés: JPG, PNG ou PDF. Taille max 2 Mo.</p>
                        @php $diplomePath = $this->user->DiplômeOrCNOM ?? Auth::user()->DiplômeOrCNOM ?? null; @endphp
                        @if($diplomePath)
                            <div class="mt-3 text-sm flex items-center gap-3">
                                <a href="{{ asset('storage/' . $diplomePath) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 bg-white text-gray-700 shadow-sm hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span>Voir le document</span>
                                </a>
                            </div>
                        @else
                            <p class="mt-3 text-sm text-gray-500">Aucun document enregistré.</p>
                        @endif
                    </div>
                    <div x-data="{ options: ['Français','Arabe','Anglais','Italien','Allemand'], custom:'', selected: (() => { const initial = @js(old('langues', $this->user->langues ?? '')) || ''; return initial.split(',').map(s=>s.trim()).filter(Boolean); })(), add(){ if(this.custom && !this.selected.includes(this.custom)) { this.selected.push(this.custom); this.custom=''; } }, toggle(lang){ const i=this.selected.indexOf(lang); if(i>-1){this.selected.splice(i,1)} else {this.selected.push(lang)} } }" x-init="$watch('selected', v => $wire.set('state.langues', v.join(', ')))" class="bg-white rounded-xl border border-gray-200 p-6 shadow-md hover:shadow-lg transition-shadow duration-200">
                        <x-label value="{{ __('Langues') }}" class="text-sm font-semibold text-gray-900" />
                        <div class="mt-3 flex flex-wrap gap-2">
                            <template x-for="lang in options" :key="lang">
                                <button type="button" class="px-4 py-2 rounded-full text-sm border shadow-sm transition-all duration-200" :class="selected.includes(lang) ? 'bg-emerald-100 text-emerald-700 border-emerald-300 font-medium' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50 hover:border-gray-300'" x-on:click="toggle(lang)" x-text="lang"></button>
                            </template>
                        </div>
                        <div class="mt-4 flex items-center gap-2">
                            <input type="text" x-model="custom" placeholder="Ajouter une langue" class="flex-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm px-4 py-2 transition-all duration-200" />
                            <button type="button" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium shadow-sm hover:bg-emerald-700 transition-all duration-200" x-on:click="add()">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <span>Ajouter</span>
                            </button>
                        </div>
                        <input type="hidden" wire:model="state.langues" />
                        <x-input-error for="langues" class="mt-2" />
                    </div>
                </div>
            @endif
                </div>
            </div>
        </div>


    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3 text-emerald-600" on="saved">
            {{ __('Profil mis à jour avec succès.') }}
        </x-action-message>

        <x-button type="submit" wire:loading.attr="disabled" wire:target="photo" class="inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ __('Enregistrer les modifications') }}</span>
        </x-button>
    </x-slot>
</x-form-section>
