<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workspace</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Suppression des CDN FullCalendar car nous utilisons les modules npm -->
    @yield('styles')

    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Autres scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Vite doit être chargé avant tout script qui utilise les modules importés -->
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
</body>
</html>
