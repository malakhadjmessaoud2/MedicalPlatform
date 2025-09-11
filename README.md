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
