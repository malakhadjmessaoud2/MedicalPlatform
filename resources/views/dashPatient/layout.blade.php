<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workspace</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

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
