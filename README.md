php artisan migrate:fresh 


composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate



php artisan install:broadcasting
composer require laravel/reverb
php artisan reverb:install
npm install --save-dev laravel-echo


php artisan make:event RendezVousCreate


php artisan reverb:start --debug

ngrok http 8000

php artisan make:mail AccountActivedMail --view=emails.users.activation

<!-- ENVIRONNEMENT LOCAL -->
<!-- Setup Initial : -->


<!-- Installation des dépendances -->
composer install
npm install

 <!-- Configuration base de données -->
php artisan migrate:fresh

<!-- Installation des services -->
composer require laravel/sanctum
composer require laravel/reverb
npm install --save-dev laravel-echo

<!-- # Configuration broadcasting -->
php artisan install:broadcasting
php artisan reverb:install



<!-- Développement Local : -->
<!-- # Serveur de développement (composer dev) -->
php artisan serve          # Serveur Laravel (port 8000)
php artisan queue:listen   # Queue worker
php artisan reverb:start   # WebSocket server (port 8080)
npm run dev               # Vite dev server (port 5173)

<!-- # Tunnel public (pour tests externes) -->
ngrok http 8000           # Tunnel vers localhost:8000
