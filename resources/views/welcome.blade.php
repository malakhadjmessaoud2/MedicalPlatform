<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediConnect - Plateforme Médicale</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
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
    </style>
</head>
<body class="bg-gradient-to-br from-teal-50 via-blue-50 to-white">
    <!-- Navbar avec animation -->
    <nav class="bg-white/80 backdrop-blur-md fixed w-full z-50 shadow-lg slide-in">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center group">
                        <span class="text-2xl font-bold gradient-text">Medi<span class="text-teal-600">Connect</span></span>
                    </a>
                </div>
                <div class="flex items-center space-x-8">
                    <a href="#" class="text-gray-600 hover:text-teal-600 transition-all duration-300 hover:scale-110">Accueil</a>
                    <a href="#" class="text-gray-600 hover:text-teal-600 transition-all duration-300 hover:scale-110">Services</a>
                    <a href="#" class="text-gray-600 hover:text-teal-600 transition-all duration-300 hover:scale-110">Contact</a>
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
    <div class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-br from-gray-50 via-teal-50 to-blue-50">
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
                    <a href="#explorer" class="group relative inline-flex items-center px-8 py-3 overflow-hidden rounded-full bg-teal-600 text-white shadow-lg transition-transform hover:scale-105">
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

                <!-- Stats flottantes -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-16">
                    <div class="bg-white/80 backdrop-blur-sm p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all hover:-translate-y-1">
                        <div class="text-3xl font-bold text-teal-600 counter">1000+</div>
                        <div class="text-gray-600">Patients</div>
                    </div>
                    <div class="bg-white/80 backdrop-blur-sm p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all hover:-translate-y-1">
                        <div class="text-3xl font-bold text-blue-600 counter">500+</div>
                        <div class="text-gray-600">Médecins</div>
                    </div>
                    <div class="bg-white/80 backdrop-blur-sm p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all hover:-translate-y-1">
                        <div class="text-3xl font-bold text-green-600 counter">200+</div>
                        <div class="text-gray-600">Pharmacies</div>
                    </div>
                    <div class="bg-white/80 backdrop-blur-sm p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all hover:-translate-y-1">
                        <div class="text-3xl font-bold text-purple-600 counter">100+</div>
                        <div class="text-gray-600">Cliniques</div>
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
    <!-- Footer avec animations et design moderne -->
    <footer class="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 text-white pt-20 pb-10 relative overflow-hidden">
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
                        <span class="text-blue-400">Medi</span>
                        <span class="text-teal-400">Connect</span>
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
                            <span>contact@mediconnect.com</span>
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
                    <p>&copy; 2024 MediConnect. Tous droits réservés.</p>
                </div>
            </div>
        </div>
    </footer>
