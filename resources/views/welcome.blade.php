<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedicalPlatform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script>
        // Initialisation d'AOS
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,
                easing: 'ease-out-cubic',
                once: true,
                offset: 100
            });
        });
    </script>

    <style>
        html { scroll-behavior: smooth; }
        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }

        .gradient-text {
            background: linear-gradient(90deg, #14e75b, #125c91, #10B981);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-size: 200% auto;
            animation: gradient 3s linear infinite;
        }

        @keyframes gradient {
            0% { background-position: 0% center; }
            100% { background-position: 200% center; }
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 30px rgba(0, 0, 0, 0.1);
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .slide-in {
            animation: slideIn 1s ease-out;
        }

        @keyframes slideIn {
            0% { transform: translateX(-100%); opacity: 0; }
            100% { transform: translateX(0); opacity: 1; }
        }

        .typewriter {
            overflow: hidden;
            border-right: .15em solid #10d060;
            white-space: nowrap;
            animation: typing 3.5s steps(40, end),
                       blink-caret .75s step-end infinite;
        }

        @keyframes typing {
            from { width: 0 }
            to { width: 100% }
        }

        @keyframes blink-caret {
            from, to { border-color: transparent }
            50% { border-color: #10B981; }
        }

        .animate-fade-in-up {
            animation: fadeInUp 1s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Styles pour le texte de formation */
        .formation-text {
            line-height: 1.6;
            text-align: justify;
        }

        .formation-text p {
            margin-bottom: 0.75rem;
        }

        .formation-text ul {
            list-style-type: disc;
            margin-left: 1.5rem;
            margin-bottom: 0.75rem;
        }

        .formation-text li {
            margin-bottom: 0.25rem;
        }

        /* Animation des cartes */
        .card-hover {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        }

        /* Effet de brillance sur les cartes */
        .card-hover::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.5s;
        }

        .card-hover:hover::before {
            left: 100%;
        }

        /* Animation des badges de spécialité */
        .specialty-badge {
            animation: pulse 2s infinite;
        }

        /* Amélioration des gradients */
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Scrollbar personnalisée */
        .scrollbar-thin {
            scrollbar-width: thin;
        }

        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
        }

        .scrollbar-thin::-webkit-scrollbar-track {
            background: transparent;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #c084fc;
            border-radius: 3px;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #a855f7;
        }

        /* Animation des cartes au chargement */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-slide-in-up {
            animation: slideInUp 0.6s ease-out forwards;
        }

        /* Effet de profondeur sur les cartes */
        .card-depth {
            box-shadow:
                0 1px 3px rgba(0,0,0,0.12),
                0 1px 2px rgba(0,0,0,0.24);
            transition: all 0.3s cubic-bezier(.25,.8,.25,1);
        }

        .card-depth:hover {
            box-shadow:
                0 14px 28px rgba(0,0,0,0.25),
                0 10px 10px rgba(0,0,0,0.22);
        }

        /* Amélioration des badges de langues */
        .language-badge {
            transition: all 0.2s ease;
        }

        .language-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        /* Styles pour les cartes médecins simplifiées */
        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
        }

        /* Amélioration des scrollbars */
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 4px;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }

        /* Styles pour les étapes du processus */
        .step-card {
            transition: all 0.3s ease;
        }

        .step-card:hover {
            transform: translateY(-4px);
        }

        /* Animation des numéros d'étapes */
        .step-number {
            transition: all 0.3s ease;
        }

        .step-card:hover .step-number {
            transform: scale(1.1);
        }

        /* Effet de pulse sur le bouton principal */
        .btn-primary {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            }
            50% {
                box-shadow: 0 0 0 10px rgba(16, 185, 129, 0);
            }
        }

        /* Styles pour la section "Pourquoi choisir" */
        .feature-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
        }

        .feature-icon {
            transition: all 0.3s ease;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
        }

        /* Animation d'apparition des cartes */
        .feature-card:nth-child(1) { animation-delay: 0.1s; }
        .feature-card:nth-child(2) { animation-delay: 0.2s; }
        .feature-card:nth-child(3) { animation-delay: 0.3s; }
        .feature-card:nth-child(4) { animation-delay: 0.4s; }
        .feature-card:nth-child(5) { animation-delay: 0.5s; }
        .feature-card:nth-child(6) { animation-delay: 0.6s; }
    </style>
</head>
<body class="bg-gradient-to-br from-teal-50 via-blue-50 to-white">
    <!-- Navbar avec animation -->
    <nav class="bg-white/80 backdrop-blur-md fixed w-full z-50 shadow-lg slide-in">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center group">
                        <span class="text-2xl font-bold gradient-text">Medical<span class="text-teal-600">Platform</span></span>
                    </a>
                </div>
                <div class="flex items-center space-x-8">
                    <a href="#home" class="text-gray-600 hover:text-teal-600 transition-all duration-300 hover:scale-110">Accueil</a>
                    <a href="#services" class="text-gray-600 hover:text-teal-600 transition-all duration-300 hover:scale-110">Services</a>
                    <a href="#contact" class="text-gray-600 hover:text-teal-600 transition-all duration-300 hover:scale-110">Contact</a>
                    <a href="{{ route('login') }}" class="bg-[#4dabb4] text-white px-6 py-2 rounded-full hover:bg-[#4dabb4]/80 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl">
                        Connexion
                    </a>
                    <a href="{{ route('register') }}" class="border-2 border-[#4dabb4] text-[#4dabb4] px-6 py-2 rounded-full hover:bg-[#4dabb4] hover:text-white transform hover:scale-105 transition-all duration-300">
                        Inscription
                    </a>
                </div>
            </div>
        </div>
    </nav>

        <!-- Hero Section avec animations avancées -->
    <div id="home" class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-br from-gray-50 via-teal-50 to-blue-50 pt-16">
        <!-- Cercles animés en arrière-plan -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-1/4 left-10 w-72 h-72 bg-teal-300/20 rounded-full mix-blend-multiply filter blur-xl animate-blob"></div>
            <div class="absolute top-1/3 right-10 w-72 h-72 bg-blue-300/20 rounded-full mix-blend-multiply filter blur-xl animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-1/2 w-72 h-72 bg-pink-300/20 rounded-full mix-blend-multiply filter blur-xl animate-blob animation-delay-4000"></div>
        </div>

        <!-- Contenu principal -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center space-y-12">
                <!-- Titre principal avec animation de typing -->
                <h1 class="text-6xl md:text-7xl font-bold leading-tight">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-teal-600 animate-gradient-x">
                        La Santé
                    </span>
                    <br />
                    <span class="typewriter inline-block mt-2">Connectée</span>
                </h1>

                <!-- Sous-titre avec fade-in -->
                <p class="max-w-2xl mx-auto text-xl text-gray-600 animate-fade-in-up">
                    Une plateforme innovante qui réunit
                    <span class="font-semibold text-teal-600">patients</span>,
                    <span class="font-semibold text-blue-600">médecins</span>,
                    <span class="font-semibold text-green-600">pharmacies</span>,
                    <span class="font-semibold text-purple-600">cliniques</span> et
                    <span class="font-semibold text-pink-600">donateurs</span>
                    pour une meilleure gestion de la santé.
                </p>

                <!-- Boutons d'action avec hover effects -->
                <div class="flex flex-wrap justify-center gap-6">
                    <a href="#features-auth" class="group relative inline-flex items-center px-8 py-3 overflow-hidden rounded-full bg-teal-600 text-white shadow-lg transition-transform hover:scale-105">
                        <span class="absolute left-0 top-0 h-full w-0 bg-gradient-to-r from-blue-600 to-teal-600 transition-all duration-500 ease-out group-hover:w-full"></span>
                        <span class="relative flex items-center gap-2">
                            Découvrir
                            <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </span>
                    </a>

                    <a href="#contact" class="group relative inline-flex items-center px-8 py-3 overflow-hidden rounded-full border-2 border-teal-600 text-teal-600 shadow-lg transition-transform hover:scale-105">
                        <span class="absolute left-0 top-0 h-full w-0 bg-teal-600 transition-all duration-500 ease-out group-hover:w-full"></span>
                        <span class="relative flex items-center gap-2 group-hover:text-white">
                            En savoir plus
                            <svg class="w-5 h-5 transform group-hover:rotate-45 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                        </span>
                    </a>
                </div>

                <!-- Section: Pourquoi choisir MedicalPlatform -->
                <div class="mt-16">
                    <h3 class="text-2xl font-bold text-center text-gray-800 mb-8">Pourquoi choisir MedicalPlatform ?</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Facilité d'utilisation -->
                        <div class="feature-card bg-white/90 backdrop-blur-sm p-6 rounded-2xl shadow-lg hover:shadow-xl border border-gray-100/50" data-aos="fade-up">
                            <div class="feature-icon w-14 h-14 bg-gradient-to-br from-blue-100 to-blue-200 rounded-2xl flex items-center justify-center mb-4 mx-auto">
                                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-800 text-center mb-3">Rapide & Simple</h4>
                            <p class="text-gray-600 text-center text-sm leading-relaxed">
                                Prise de rendez-vous en moins de 2 minutes. Interface intuitive et navigation fluide pour tous les utilisateurs.
                            </p>
                        </div>

                        <!-- Sécurité des données -->
                        <div class="feature-card bg-white/90 backdrop-blur-sm p-6 rounded-2xl shadow-lg hover:shadow-xl border border-gray-100/50" data-aos="fade-up">
                            <div class="feature-icon w-14 h-14 bg-gradient-to-br from-green-100 to-green-200 rounded-2xl flex items-center justify-center mb-4 mx-auto">
                                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-800 text-center mb-3">100% Sécurisé</h4>
                            <p class="text-gray-600 text-center text-sm leading-relaxed">
                                Vos données médicales sont protégées par des protocoles de sécurité avancés et un chiffrement de bout en bout.
                            </p>
                        </div>

                        <!-- Disponibilité 24/7 -->
                        <div class="feature-card bg-white/90 backdrop-blur-sm p-6 rounded-2xl shadow-lg hover:shadow-xl border border-gray-100/50" data-aos="fade-up">
                            <div class="feature-icon w-14 h-14 bg-gradient-to-br from-purple-100 to-purple-200 rounded-2xl flex items-center justify-center mb-4 mx-auto">
                                <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-800 text-center mb-3">Disponible 24/7</h4>
                            <p class="text-gray-600 text-center text-sm leading-relaxed">
                                Accédez à vos rendez-vous et dossiers médicaux à tout moment, depuis n'importe quel appareil connecté.
                            </p>
                        </div>

                        <!-- Médecins qualifiés -->
                        <div class="feature-card bg-white/90 backdrop-blur-sm p-6 rounded-2xl shadow-lg hover:shadow-xl border border-gray-100/50" data-aos="fade-up">
                            <div class="feature-icon w-14 h-14 bg-gradient-to-br from-teal-100 to-teal-200 rounded-2xl flex items-center justify-center mb-4 mx-auto">
                                <svg class="w-7 h-7 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-800 text-center mb-3">Médecins Qualifiés</h4>
                            <p class="text-gray-600 text-center text-sm leading-relaxed">
                                Tous nos médecins sont certifiés et expérimentés. Choisissez selon vos besoins et préférences.
                            </p>
                        </div>

                        <!-- Suivi médical -->
                        <div class="feature-card bg-white/90 backdrop-blur-sm p-6 rounded-2xl shadow-lg hover:shadow-xl border border-gray-100/50" data-aos="fade-up">
                            <div class="feature-icon w-14 h-14 bg-gradient-to-br from-orange-100 to-orange-200 rounded-2xl flex items-center justify-center mb-4 mx-auto">
                                <svg class="w-7 h-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-800 text-center mb-3">Suivi Complet</h4>
                            <p class="text-gray-600 text-center text-sm leading-relaxed">
                                Accédez à vos dossiers médicaux, ordonnances et historique de consultations en un clic.
                            </p>
                        </div>

                        <!-- Support client -->
                        <div class="feature-card bg-white/90 backdrop-blur-sm p-6 rounded-2xl shadow-lg hover:shadow-xl border border-gray-100/50" data-aos="fade-up">
                            <div class="feature-icon w-14 h-14 bg-gradient-to-br from-pink-100 to-pink-200 rounded-2xl flex items-center justify-center mb-4 mx-auto">
                                <svg class="w-7 h-7 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 100 19.5 9.75 9.75 0 000-19.5z"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-800 text-center mb-3">Support Réactif</h4>
                            <p class="text-gray-600 text-center text-sm leading-relaxed">
                                Notre équipe support est disponible pour vous accompagner à chaque étape de votre parcours médical.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vague décorative en bas -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg class="w-full h-24 fill-current text-white" viewBox="0 0 1440 120">
                <path d="M0,64L80,69.3C160,75,320,85,480,80C640,75,800,53,960,48C1120,43,1280,53,1360,58.7L1440,64L1440,120L1360,120C1280,120,1120,120,960,120C800,120,640,120,480,120C320,120,160,120,80,120L0,120Z"></path>
            </svg>
        </div>
    </div>

    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }

        .animate-blob {
            animation: blob 7s infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .animation-delay-4000 {
            animation-delay: 4s;
        }

        .animate-gradient-x {
            background-size: 200% 200%;
            animation: gradient 15s ease infinite;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .typewriter {
            overflow: hidden;
            border-right: .15em solid #10B981;
            white-space: nowrap;
            animation: typing 3.5s steps(40, end),
                       blink-caret .75s step-end infinite;
        }

        @keyframes typing {
            from { width: 0 }
            to { width: 100% }
        }

        @keyframes blink-caret {
            from, to { border-color: transparent }
            50% { border-color: #10B981; }
        }

        .animate-fade-in-up {
            animation: fadeInUp 1s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <!-- Services Section avec animations -->
    <section class="py-20 bg-white/50 backdrop-blur-md" id="services">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-16">
                <span class="text-blue-600">Nos</span>
                <span class="text-teal-600">Services</span>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-8">
                <!-- Patients -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4 text-gray-800">Patients</h3>
                    <p class="text-gray-600">Gérez vos rendez-vous et accédez à vos dossiers médicaux en un clic.</p>
                </div>

                <!-- Médecins -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300">
                    <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4 text-gray-800">Médecins</h3>
                    <p class="text-gray-600">Optimisez votre pratique et suivez vos patients efficacement.</p>
                </div>

                <!-- Pharmacies -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4 text-gray-800">Pharmacies</h3>
                    <p class="text-gray-600">Gérez vos stocks et traitez les ordonnances numériques.</p>
                </div>

                <!-- Cliniques -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2v16z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4 text-gray-800">Cliniques</h3>
                    <p class="text-gray-600">Administrez votre établissement et coordonnez vos services.</p>
                </div>

                <!-- Donateurs -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300">
                    <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4 text-gray-800">Donateurs</h3>
                    <p class="text-gray-600">Contribuez à la santé en faisant des dons de médicaments.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section: Fonctionnalités nécessitant une connexion -->
    <section class="py-20 bg-gradient-to-b from-white via-teal-50/40 to-white" id="features-auth">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-4">
                <span class="text-blue-600">Fonctionnalités</span>
                <span class="text-teal-600">nécessitant une connexion</span>
            </h2>
            <p class="text-center text-gray-600 mb-12">Connectez-vous ou créez un compte pour profiter pleinement de la plateforme.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="group relative bg-white/80 backdrop-blur p-6 rounded-2xl shadow-lg border border-teal-100 hover:border-teal-300 transition-all hover:-translate-y-1">
                    <div class="absolute -top-1 left-6 right-6 h-1 rounded-full bg-gradient-to-r from-blue-500 to-teal-500"></div>
                    <div class="flex items-center gap-3 mb-3">
                        <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M5 11h14M5 19h14M5 15h14"/></svg>
                        <h3 class="text-lg font-semibold text-gray-800">Prise de rendez-vous</h3>
                    </div>
                    <p class="text-gray-600 mb-5">Réservez et gérez vos rendez-vous avec des médecins.</p>
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-teal-600 text-white hover:bg-teal-700 transition">
                        Se connecter
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5-5 5M6 12h12"/></svg>
                    </a>
                </div>

                <div class="group relative bg-white/80 backdrop-blur p-6 rounded-2xl shadow-lg border border-teal-100 hover:border-teal-300 transition-all hover:-translate-y-1">
                    <div class="absolute -top-1 left-6 right-6 h-1 rounded-full bg-gradient-to-r from-blue-500 to-teal-500"></div>
                    <div class="flex items-center gap-3 mb-3">
                        <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7M16 3H8v4h8V3z"/></svg>
                        <h3 class="text-lg font-semibold text-gray-800">Dossier médical</h3>
                    </div>
                    <p class="text-gray-600 mb-5">Consultez vos dossiers médicaux en toute sécurité.</p>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-5 py-2 rounded-full border-2 border-teal-600 text-teal-600 hover:bg-teal-600 hover:text-white transition">
                        Créer un compte
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </a>
                </div>

                <div class="group relative bg-white/80 backdrop-blur p-6 rounded-2xl shadow-lg border border-teal-100 hover:border-teal-300 transition-all hover:-translate-y-1">
                    <div class="absolute -top-1 left-6 right-6 h-1 rounded-full bg-gradient-to-r from-blue-500 to-teal-500"></div>
                    <div class="flex items-center gap-3 mb-3">
                        <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6l7-3v6l-7 3z"/></svg>
                        <h3 class="text-lg font-semibold text-gray-800">Ordonnances numériques</h3>
                    </div>
                    <p class="text-gray-600 mb-5">Accédez à vos ordonnances et partagez-les avec votre pharmacie.</p>
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-teal-600 text-white hover:bg-teal-700 transition">
                        Se connecter
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5-5 5M6 12h12"/></svg>
                    </a>
                </div>

                <div class="group relative bg-white/80 backdrop-blur p-6 rounded-2xl shadow-lg border border-teal-100 hover:border-teal-300 transition-all hover:-translate-y-1">
                    <div class="absolute -top-1 left-6 right-6 h-1 rounded-full bg-gradient-to-r from-blue-500 to-teal-500"></div>
                    <div class="flex items-center gap-3 mb-3">
                        <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 15a4 4 0 004 4h8a4 4 0 004-4M12 3v8m0 0l-3-3m3 3l3-3"/></svg>
                        <h3 class="text-lg font-semibold text-gray-800">Dons de médicaments</h3>
                    </div>
                    <p class="text-gray-600 mb-5">Participez à la solidarité en faisant des dons sécurisés.</p>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-5 py-2 rounded-full border-2 border-teal-600 text-teal-600 hover:bg-teal-600 hover:text-white transition">
                        Créer un compte
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Nos Médecins Section -->
    <section class="py-24 bg-gradient-to-b from-white via-blue-50/30 to-teal-50/50 relative overflow-hidden" id="medecins">
        <!-- Éléments décoratifs en arrière-plan -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-10 left-10 w-32 h-32 bg-blue-200/20 rounded-full mix-blend-multiply filter blur-xl animate-blob"></div>
            <div class="absolute top-20 right-10 w-40 h-40 bg-teal-200/20 rounded-full mix-blend-multiply filter blur-xl animate-blob animation-delay-2000"></div>
            <div class="absolute bottom-20 left-1/3 w-24 h-24 bg-purple-200/20 rounded-full mix-blend-multiply filter blur-xl animate-blob animation-delay-4000"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-5xl font-bold mb-6">
                    <span class="text-blue-600">Nos</span>
                    <span class="text-teal-600">Médecins</span>
                    <span class="text-purple-600">Spécialisés</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    Découvrez notre équipe de médecins qualifiés et expérimentés,
                    prêts à vous accompagner dans votre parcours de santé.
                </p>
                <div class="w-24 h-1 bg-gradient-to-r from-blue-500 to-teal-500 mx-auto mt-6 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($medecins as $medecin)
                                                                                <div class="group bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 hover:border-teal-200 relative overflow-hidden" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <!-- En-tête de la carte -->
                        <div class="p-5">
                            <!-- Avatar et nom centrés -->
                            <div class="text-center mb-4">
                                <div class="w-16 h-16 rounded-full {{ $medecin['color_class'] }} flex items-center justify-center font-bold text-lg mx-auto mb-3 group-hover:scale-105 transition-transform duration-300">
                                    {{ $medecin['initials'] }}
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">
                                    Dr. {{ $medecin['prenom'] }} {{ $medecin['nom'] }}
                                </h3>
                                <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-teal-50 text-teal-700 rounded-full text-sm font-medium border border-teal-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    {{ $medecin['specialite'] ?? 'Spécialité' }}
                                </div>
                            </div>

                            <!-- Informations détaillées -->
                            <div class="space-y-3">
                                <!-- Expérience -->
                                @if(!empty($medecin['experience']))
                                    <div class="flex items-center justify-center gap-2 p-2 bg-blue-50 rounded-lg">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-sm font-medium text-blue-700">{{ $medecin['experience'] }} ans d'expérience</span>
                                    </div>
                                @endif

                                <!-- Formation -->
                                @if(!empty($medecin['formation']))
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                                            </svg>
                                            <span class="text-sm font-medium text-gray-700">Formation</span>
                                        </div>
                                        <div class="text-xs text-gray-600 leading-relaxed max-h-24 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-transparent">
                                            {!! nl2br(e($medecin['formation'])) !!}
                                        </div>
                                    </div>
                                @endif

                                <!-- Langues -->
                                @if(!empty($medecin['langues']))
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                                            </svg>
                                            <span class="text-sm font-medium text-gray-700">Langues</span>
                                        </div>
                                        <div class="flex flex-wrap gap-1.5 justify-center">
                                            @foreach(explode(',', $medecin['langues']) as $langue)
                                                <span class="px-2 py-1 bg-white text-gray-700 rounded-md text-xs font-medium border border-gray-200">
                                                    {{ trim($langue) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>


                        </div>
                    </div>
                                @empty
                    <div class="col-span-full text-center py-24" data-aos="fade-up">
                        <div class="w-40 h-40 bg-gradient-to-br from-gray-100 via-blue-50 to-teal-50 rounded-full flex items-center justify-center mx-auto mb-8 shadow-2xl border border-gray-200/50">
                            <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-800 mb-4">Aucun médecin disponible</h3>
                        <p class="text-gray-600 text-xl max-w-2xl mx-auto leading-relaxed mb-8">
                            Notre équipe médicale est en cours de constitution.
                            Revenez bientôt pour découvrir nos médecins spécialisés et qualifiés.
                        </p>
                        <div class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-teal-50 via-blue-50 to-purple-50 text-teal-800 rounded-2xl border border-teal-300/50 shadow-lg">
                            <svg class="w-6 h-6 animate-spin text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <span class="font-semibold text-lg">Mise à jour en cours...</span>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Section: Comment prendre rendez-vous -->
            <div class="mt-16 text-center">
                <h3 class="text-2xl font-bold text-gray-800 mb-8">Comment prendre rendez-vous ?</h3>
                <p class="text-gray-600 text-lg max-w-4xl mx-auto mb-12 leading-relaxed">
                    Prendre rendez-vous avec nos médecins est simple et rapide. Suivez ces étapes pour réserver votre consultation en toute simplicité.
                </p>

                <!-- Étapes du processus -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                    <!-- Étape 1: Connexion -->
                    <div class="step-card bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md">
                        <div class="step-number w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-blue-600 font-bold text-lg">1</span>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2">Connexion</h4>
                        <p class="text-sm text-gray-600">Connectez-vous à votre compte patient ou créez-en un nouveau</p>
                    </div>

                    <!-- Étape 2: Spécialité -->
                    <div class="step-card bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md">
                        <div class="step-number w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-teal-600 font-bold text-lg">2</span>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2">Choisir la spécialité</h4>
                        <p class="text-sm text-gray-600">Sélectionnez la spécialité médicale dont vous avez besoin</p>
                    </div>

                    <!-- Étape 3: Médecin -->
                    <div class="step-card bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md">
                        <div class="step-number w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-purple-600 font-bold text-lg">3</span>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2">Choisir le médecin</h4>
                        <p class="text-sm text-gray-600">Parcourez nos médecins qualifiés et sélectionnez celui qui vous convient</p>
                    </div>

                    <!-- Étape 4: Créneau -->
                    <div class="step-card bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md">
                        <div class="step-number w-12 h-12 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-pink-600 font-bold text-lg">4</span>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2">Réserver un créneau</h4>
                        <p class="text-sm text-gray-600">Choisissez la date et l'heure qui vous conviennent le mieux</p>
                    </div>
                </div>

                <!-- Message encourageant -->
                <div class="bg-gradient-to-r from-teal-50 to-blue-50 p-8 rounded-2xl border border-teal-200 mb-8">
                    <h4 class="text-xl font-bold text-teal-800 mb-4">Prêt à prendre soin de votre santé ?</h4>
                    <p class="text-teal-700 mb-6 max-w-2xl mx-auto">
                        Rejoignez des milliers de patients qui font confiance à notre plateforme pour leurs soins médicaux.
                        Prenez rendez-vous en quelques clics et bénéficiez d'un suivi médical de qualité.
                    </p>

                    <!-- Bouton d'action principal -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <a href="{{ route('login') }}" class="btn-primary inline-flex items-center gap-3 bg-teal-600 text-white px-8 py-3 rounded-xl font-semibold text-lg hover:bg-teal-700 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M5 11h14M5 19h14M5 15h14"/>
                            </svg>
                            Prendre rendez-vous maintenant
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-3 border-2 border-teal-600 text-teal-600 px-8 py-3 rounded-xl font-semibold text-lg hover:bg-teal-600 hover:text-white transition-all duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Créer un compte
                        </a>
                    </div>

                    <p class="text-sm text-teal-600 mt-4">
                        ⚡ Prise de rendez-vous en moins de 2 minutes • 🔒 Données sécurisées
                    </p>
                </div>
            </div>
        </div>
    </section>


    <!-- Footer avec animations et design moderne -->
    <footer id="contact" class="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 text-white pt-20 pb-10 relative overflow-hidden">
        <!-- Particules/Formes d'arrière-plan -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-4 left-1/4 w-32 h-32 bg-teal-500/10 rounded-full floating"></div>
            <div class="absolute top-1/3 right-1/4 w-24 h-24 bg-blue-500/10 rounded-full floating" style="animation-delay: 1s;"></div>
            <div class="absolute bottom-1/4 left-1/3 w-40 h-40 bg-purple-500/10 rounded-full floating" style="animation-delay: 2s;"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <!-- Section principale du footer -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                <!-- À propos -->
                <div class="space-y-4">
                    <h3 class="text-2xl font-bold mb-6">
                        <span class="text-blue-400">Medical</span>
                        <span class="text-teal-400">Platform</span>
                    </h3>
                    <p class="text-gray-400 leading-relaxed">
                        Votre plateforme de santé connectée qui réunit tous les acteurs du système de santé pour une meilleure coordination des soins.
                    </p>
                    <div class="flex space-x-4 pt-4">
                        <a href="#" class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center hover:bg-teal-600 transition-colors duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center hover:bg-teal-600 transition-colors duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center hover:bg-teal-600 transition-colors duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Liens Rapides -->
                <div>
                    <h4 class="text-lg font-semibold mb-6 text-teal-400">Liens Rapides</h4>
                    <ul class="space-y-3">
                        <li>
                            <a href="#" class="text-gray-400 hover:text-teal-400 transition-colors duration-300 flex items-center group">
                                <span class="mr-2 transform group-hover:translate-x-2 transition-transform duration-300">→</span>
                                Accueil
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-400 hover:text-teal-400 transition-colors duration-300 flex items-center group">
                                <span class="mr-2 transform group-hover:translate-x-2 transition-transform duration-300">→</span>
                                Services
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-400 hover:text-teal-400 transition-colors duration-300 flex items-center group">
                                <span class="mr-2 transform group-hover:translate-x-2 transition-transform duration-300">→</span>
                                À Propos
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-400 hover:text-teal-400 transition-colors duration-300 flex items-center group">
                                <span class="mr-2 transform group-hover:translate-x-2 transition-transform duration-300">→</span>
                                Contact
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Services -->
                <div>
                    <h4 class="text-lg font-semibold mb-6 text-teal-400">Nos Services</h4>
                    <ul class="space-y-3">
                        <li>
                            <a href="#" class="text-gray-400 hover:text-teal-400 transition-colors duration-300 flex items-center group">
                                <span class="mr-2 transform group-hover:translate-x-2 transition-transform duration-300">→</span>
                                Espace Patient
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-400 hover:text-teal-400 transition-colors duration-300 flex items-center group">
                                <span class="mr-2 transform group-hover:translate-x-2 transition-transform duration-300">→</span>
                                Espace Médecin
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-400 hover:text-teal-400 transition-colors duration-300 flex items-center group">
                                <span class="mr-2 transform group-hover:translate-x-2 transition-transform duration-300">→</span>
                                Espace Pharmacie
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-400 hover:text-teal-400 transition-colors duration-300 flex items-center group">
                                <span class="mr-2 transform group-hover:translate-x-2 transition-transform duration-300">→</span>
                                Espace Donateur
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="text-lg font-semibold mb-6 text-teal-400">Contact</h4>
                    <ul class="space-y-3">
                        <li class="flex items-start space-x-3 text-gray-400">
                            <svg class="w-6 h-6 text-teal-400 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>123 Rue de la Santé, 75000 Paris</span>
                        </li>
                        <li class="flex items-center space-x-3 text-gray-400">
                            <svg class="w-6 h-6 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>contact@MedicalPlatform.com</span>
                        </li>
                        <li class="flex items-center space-x-3 text-gray-400">
                            <svg class="w-6 h-6 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>+33 1 23 45 67 89</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Copyright -->
            <div class="border-t border-gray-800 pt-8 mt-8">
                <div class="text-center text-gray-400">
                    <p>&copy; 2025 MedicalPlatform. Tous droits réservés.</p>
                </div>
            </div>
        </div>
    </footer>
