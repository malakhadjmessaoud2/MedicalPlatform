<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workspace</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="patient-id" content="{{ Auth::user()->patient->id ?? '' }}">
    <meta name="pusher-key" content="{{ config('broadcasting.connections.pusher.key') }}">
    <meta name="pusher-cluster" content="{{ config('broadcasting.connections.pusher.options.cluster') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/realtime.js'])
</head>
<body class="bg-[#e4e4e4] font-sans">
    <div class="flex h-screen">
        @include('dashPatient.sidebar')

        <div class="flex flex-col flex-1">
            @include('dashPatient.navbar')

            <main class="p-6 flex-1 overflow-auto">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
