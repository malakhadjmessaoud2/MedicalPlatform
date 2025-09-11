<div class="w-64 bg-white shadow-lg">
    <div class="p-6">
        <h1 class="text-2xl font-bold text-gray-800">MedicalPlatform</h1>
        <p class="text-sm text-gray-500">Administration</p>
    </div>

    <nav class="mt-6">
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-600 border-r-4 border-blue-600' : '' }}">
            <i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i>
            Dashboard
        </a>

        <a href="{{ route('admin.medecins.index') }}"
           class="flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('admin.medecins.*') ? 'bg-blue-50 text-blue-600 border-r-4 border-blue-600' : '' }}">
            <i class="fas fa-user-md w-5 h-5 mr-3"></i>
            Médecins
        </a>

        <a href="{{ route('admin.utilisateurs') }}"
           class="flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('admin.utilisateurs') ? 'bg-blue-50 text-blue-600 border-r-4 border-blue-600' : '' }}">
            <i class="fas fa-users w-5 h-5 mr-3"></i>
            Utilisateurs
        </a>

        <a href="{{ route('admin.statistiques') }}"
           class="flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('admin.statistiques') ? 'bg-blue-50 text-blue-600 border-r-4 border-blue-600' : '' }}">
            <i class="fas fa-chart-bar w-5 h-5 mr-3"></i>
            Statistiques
        </a>

        <a href="{{ route('admin.rapports') }}"
           class="flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('admin.rapports') ? 'bg-blue-50 text-blue-600 border-r-4 border-blue-600' : '' }}">
            <i class="fas fa-file-alt w-5 h-5 mr-3"></i>
            Rapports
        </a>
    </nav>
</div>
