<div class="p-6 bg-gray-100 rounded-lg h-full" style="width: 255px;">
    <div class="mb-8">
        <h1 class="text-xl font-semibold text-gray-900">MediConnect</h1>
        <p class="text-sm text-gray-500">Espace Donateur</p>

    </div>

    <!-- Menu principal -->
    <nav class="flex-1 space-y-1">
        <!-- Tableau de bord -->
        <a href="{{ route('donateur.dashboard') }}"
           class="nav-link flex items-center px-4 py-3 text-gray-700 hover:bg-teal-50 hover:text-teal-600 rounded-lg group transition-colors relative {{ request()->routeIs('donateur.dashboard') ? 'active bg-teal-50 text-teal-600 font-medium' : '' }}">
            <div class="nav-indicator"></div>
            <i class="fas fa-home w-5 h-5 mr-3"></i>
            <span>Tableau de bord</span>
        </a>

        <!-- Mes Dons -->
        <a href="{{ route('donateur.dons') }}"
           class="nav-link flex items-center px-4 py-3 text-gray-700 hover:bg-teal-50 hover:text-teal-600 rounded-lg group transition-colors relative {{ request()->routeIs('donateur.dons') ? 'active bg-teal-50 text-teal-600 font-medium' : '' }}">
            <div class="nav-indicator"></div>
            <i class="fas fa-hand-holding-medical w-5 h-5 mr-3"></i>
            <span>Mes Dons</span>
        </a>

        <!-- Faire un Don -->
        <a href="{{ route('donateur.nouveau-don') }}"
           class="nav-link flex items-center px-4 py-3 text-gray-700 hover:bg-teal-50 hover:text-teal-600 rounded-lg group transition-colors relative {{ request()->routeIs('donateur.nouveau-don') ? 'active bg-teal-50 text-teal-600 font-medium' : '' }}">
            <div class="nav-indicator"></div>
            <i class="fas fa-plus-circle w-5 h-5 mr-3"></i>
            <span>Faire un Don</span>
        </a>

        <!-- Suivi des Dons -->
        <a href="{{ route('donateur.suivi') }}"
           class="nav-link flex items-center px-4 py-3 text-gray-700 hover:bg-teal-50 hover:text-teal-600 rounded-lg group transition-colors relative {{ request()->routeIs('donateur.suivi') ? 'active bg-teal-50 text-teal-600 font-medium' : '' }}">
            <div class="nav-indicator"></div>
            <i class="fas fa-chart-line w-5 h-5 mr-3"></i>
            <span>Suivi des Dons</span>
        </a>

        <!-- Rapports -->
        <a href="{{ route('donateur.rapports') }}"
           class="nav-link flex items-center px-4 py-3 text-gray-700 hover:bg-teal-50 hover:text-teal-600 rounded-lg group transition-colors relative {{ request()->routeIs('donateur.rapports') ? 'active bg-teal-50 text-teal-600 font-medium' : '' }}">
            <div class="nav-indicator"></div>
            <i class="fas fa-file-alt w-5 h-5 mr-3"></i>
            <span>Rapports & Statistiques</span>
        </a>

        <!-- Notifications -->
        <a href="{{ route('donateur.notifications') }}"
           class="nav-link flex items-center px-4 py-3 text-gray-700 hover:bg-teal-50 hover:text-teal-600 rounded-lg group transition-colors relative {{ request()->routeIs('donateur.notifications') ? 'active bg-teal-50 text-teal-600 font-medium' : '' }}">
            <div class="nav-indicator"></div>
            <i class="fas fa-bell w-5 h-5 mr-3"></i>
            <span>Notifications</span>
            <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">3</span>
        </a>
    </nav>

    <!-- Section du bas -->
    <div class="mt-auto border-t pt-4">
        <!-- Paramètres -->
        <a href="{{ route('donateur.parametres') }}"
           class="nav-link flex items-center px-4 py-3 text-gray-700 hover:bg-teal-50 hover:text-teal-600 rounded-lg group transition-colors relative {{ request()->routeIs('donateur.parametres') ? 'active bg-teal-50 text-teal-600 font-medium' : '' }}">
            <div class="nav-indicator"></div>
            <i class="fas fa-cog w-5 h-5 mr-3"></i>
            <span>Paramètres</span>
        </a>

        <!-- Aide -->
        <a href="{{ route('donateur.aide') }}"
           class="nav-link flex items-center px-4 py-3 text-gray-700 hover:bg-teal-50 hover:text-teal-600 rounded-lg group transition-colors relative {{ request()->routeIs('donateur.aide') ? 'active bg-teal-50 text-teal-600 font-medium' : '' }}">
            <div class="nav-indicator"></div>
            <i class="fas fa-question-circle w-5 h-5 mr-3"></i>
            <span>Aide & Support</span>
        </a>
    </div>
</div>

<style>
.nav-link {
    position: relative;
    overflow: hidden;
}

.nav-indicator {
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 3px;
    height: 0;
    background: #0d9488;
    transition: height 0.3s ease;
    border-radius: 0 3px 3px 0;
}

.nav-link.active .nav-indicator {
    height: 24px;
}

.nav-link::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: currentColor;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.nav-link:hover::before {
    opacity: 0.04;
}

.nav-link.active::before {
    opacity: 0.08;
}

/* Animation pour les notifications */
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.nav-link:has(.bg-red-500) {
    animation: pulse 2s infinite;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const links = document.querySelectorAll('.nav-link');

    links.forEach(link => {
        link.addEventListener('click', function(e) {
            // Effet de ripple
            const ripple = document.createElement('div');
            const rect = this.getBoundingClientRect();

            ripple.style.position = 'absolute';
            ripple.style.borderRadius = '50%';
            ripple.style.backgroundColor = 'rgba(13, 148, 136, 0.2)';
            ripple.style.width = ripple.style.height = '100px';
            ripple.style.left = `${e.clientX - rect.left - 50}px`;
            ripple.style.top = `${e.clientY - rect.top - 50}px`;
            ripple.style.animation = 'ripple 0.6s linear';

            this.appendChild(ripple);

            setTimeout(() => ripple.remove(), 600);
        });
    });
});

@keyframes ripple {
    to {
        transform: scale(4);
        opacity: 0;
    }
}
</script>
