<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tableau de bord')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Importation de Heroicons v2 -->
    <script src="https://unpkg.com/heroicons@2.1.1/dist/heroicons.js"></script>
    <style>
        /* Styles pour la navbar */
        .navbar {
            background-color: #f7fafc;
            /* bg-gray-100 */
            border-radius: 8px;
            /* Arrondi pour tous les coins */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar input {
            border: 1px solid #ccc;
            border-radius: 8px;
            /* Arrondi pour tous les coins */
            padding: 8px;
            width: 100%;
            background-color: #ffffff;
            /* Couleur de fond blanche */
        }

        .navbar button {
            background-color: #f0f0f0;
            border-radius: 8px;
            /* Arrondi pour tous les coins */
            padding: 8px;
            transition: background-color 0.3s;
        }

        .navbar button:hover {
            background-color: #e0e0e0;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Styles pour la sidebar */
        /* Styles pour la sidebar */
        .active-link {
            color: #000;
            /* Texte noir pour le lien actif */
            background-color: #c6f6d5;
            /* Fond vert plus foncé pour le lien actif */
        }

        .active-link i {
            color: #38a169;
            /* Couleur verte pour l'icône active */
        }

        .active-link .w-2 {
            display: block;
            /* Afficher le div dégradé pour le lien actif */
        }

        .w-2 {
            display: none;
            /* Masquer le div dégradé par défaut */
        }
    </style>
</head>

<body>
    <div class="flex flex-col md:flex-row min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg p-4">
            @include('dashDonateur.sidebar')
        </div>

        <!-- Contenu principal -->
        <div class="flex-1 flex flex-col">
            <!-- Navbar -->
            <div class=" p-4 mb-2 px-8"> <!-- Ajout de mb-4 pour l'espace vertical -->
                @include('dashDonateur.navbar')
            </div>

            <!-- Content -->
            <main class="flex-1 px-8"> <!-- Réduction de l'espace horizontal avec px-4 -->
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Script JavaScript pour gérer les liens actifs -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const links = document.querySelectorAll('.sidebar-link');

            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault(); // Empêche le comportement par défaut du lien

                    // Retire la classe active de tous les liens
                    links.forEach(l => l.classList.remove('active-link'));

                    // Ajoute la classe active au lien cliqué
                    this.classList.add('active-link');

                    // Redirige vers l'URL du lien après avoir ajouté la classe active
                    window.location.href = this.getAttribute('href');
                });
            });

            // Ajoute la classe active au lien correspondant à la page actuelle
            const currentUrl = window.location.href;
            links.forEach(link => {
                if (link.href === currentUrl) {
                    link.classList.add('active-link');
                }
            });
        });
    </script>
</body>

</html>
