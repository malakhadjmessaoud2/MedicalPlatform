<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workspace</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="medecin-id" content="{{ Auth::user()->medecin->id ?? '' }}">
    @livewireStyles

    @yield('styles')

    <!-- CDN nécessaires -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" defer></script>


    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Vite pour tous les assets CSS et JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-[#e4e4e4] font-sans">
    <div class="flex h-screen">
        @include('dashMedecin.sidebar')

        <div class="flex flex-col flex-1">
            @include('dashMedecin.navbar')

            <main class="p-6 flex-1 overflow-auto">
                @yield('content')
            </main>
        </div>
    </div>
    @yield('scripts')




    <script type="module">
        console.log('Listening for new appointments...');
        console.log('Echo:', window.Echo);

        if (window.Echo && typeof window.Echo.channel === 'function') {
            window.Echo.channel('rendez-vous')
                .listen('.create', (data) => {
                    console.log('New appointment created: ');
                    console.log('Order status updated: ', data);
                });
        } else {
            console.warn('Laravel Echo is not initialized; skipping channel subscription.');
        }
    </script>
</body>

</html>
