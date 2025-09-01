<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workspace</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="patient-id" content="{{ Auth::user()->patient->id ?? '' }}">

    @livewireStyles

    @vite(['resources/css/app-patient.css', 'resources/js/app-patient.js'])
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

    @livewireScripts
</body>

</html>
