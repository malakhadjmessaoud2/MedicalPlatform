<div class="p-6 bg-gray-100 rounded-lg h-full" style="width: 255px;">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-xl font-semibold text-gray-900">MedicalPlatform</h1>
        <p class="text-sm text-gray-500">Espace Pharmacie</p>

    </div>

    <!-- Menu Principal -->
    <div class="mb-8">
        <p class="text-xs font-medium text-gray-400 mb-4">MENU</p>
        <nav class="space-y-2">
            <!-- Dashboard -->
            <a href="{{ route('dashboard.pharmacie') }}"
               class="flex items-center px-3 py-2 text-gray-600 hover:text-[#1B5E45] rounded-lg group transition-colors">
                <i class="fas fa-th w-5 h-5 mr-3 text-gray-400 group-hover:text-[#1B5E45]"></i>
                <span class="font-medium">Dashboard</span>
            </a>

            <!-- Gestion de Stock -->
            <a href="{{ route('dashPharmacie.stock') }}"
               class="flex items-center px-3 py-2 text-gray-600 hover:text-[#1B5E45] rounded-lg group transition-colors">
                <i class="fas fa-boxes w-5 h-5 mr-3 text-gray-400 group-hover:text-[#1B5E45]"></i>
                <span class="font-medium">Stock Médicaments</span>
            </a>

            <!-- Gestion des Commandes -->
            <a href="{{ route('dashPharmacie.commandes') }}"
               class="flex items-center px-3 py-2 text-gray-600 hover:text-[#1B5E45] rounded-lg group transition-colors">
                <i class="fas fa-shopping-cart w-5 h-5 mr-3 text-gray-400 group-hover:text-[#1B5E45]"></i>
                <span class="font-medium">Commandes de Médicaments</span>
            </a>

            <!-- Gestion des Dons -->
            <a href="{{ route('dashPharmacie.donsReçus') }}"
               class="flex items-center px-3 py-2 text-gray-600 hover:text-[#1B5E45] rounded-lg group transition-colors">
                <i class="fas fa-hand-holding-heart w-5 h-5 mr-3 text-gray-400 group-hover:text-[#1B5E45]"></i>
                <span class="font-medium">Dons Reçus</span>
            </a>

            <!-- Gestion des Donateurs -->
            <a href="{{ route('dashPharmacie.donateurs') }}"
               class="flex items-center px-3 py-2 text-gray-600 hover:text-[#1B5E45] rounded-lg group transition-colors">
                <i class="fas fa-users w-5 h-5 mr-3 text-gray-400 group-hover:text-[#1B5E45]"></i>
                <span class="font-medium">Donateurs</span>
            </a>

            <!-- Demandes de Dons -->
            <a href="{{ route('dashPharmacie.demandesDons') }}"
               class="flex items-center px-3 py-2 text-gray-600 hover:text-[#1B5E45] rounded-lg group transition-colors">
                <i class="fas fa-hands-helping w-5 h-5 mr-3 text-gray-400 group-hover:text-[#1B5E45]"></i>
                <span class="font-medium">Demandes de Dons</span>
            </a>
        </nav>
    </div>

    <!-- Menu Général -->
    <div>
        <p class="text-xs font-medium text-gray-400 mb-4">GENERAL</p>
        <nav class="space-y-2">
            <!-- Paramètres -->
            <a href="#" class="flex items-center px-3 py-2 text-gray-600 hover:text-[#1B5E45] rounded-lg group transition-colors">
                <i class="fas fa-cog w-5 h-5 mr-3 text-gray-400 group-hover:text-[#1B5E45]"></i>
                <span class="font-medium">Paramètres</span>
            </a>

            <!-- Aide -->
            <a href="#" class="flex items-center px-3 py-2 text-gray-600 hover:text-[#1B5E45] rounded-lg group transition-colors">
                <i class="fas fa-question-circle w-5 h-5 mr-3 text-gray-400 group-hover:text-[#1B5E45]"></i>
                <span class="font-medium">Aide</span>
            </a>

            <!-- Déconnexion -->
            <a href="#" class="flex items-center px-3 py-2 text-gray-600 hover:text-[#1B5E45] rounded-lg group transition-colors">
                <i class="fas fa-sign-out-alt w-5 h-5 mr-3 text-gray-400 group-hover:text-[#1B5E45]"></i>
                <span class="font-medium">Déconnexion</span>
            </a>
        </nav>
    </div>
</div>

<style>
.text-[#1B5E45] {
    color: #1B5E45;
}
.hover\:text-[#1B5E45]:hover {
    color: #1B5E45;
}
.group-hover\:text-[#1B5E45]:hover {
    color: #1B5E45;
}
</style>
