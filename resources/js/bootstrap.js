import axios from 'axios';

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


// Configuration de Laravel Echo et Pusher
import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

// Configuration Pusher
const pusherKey = import.meta.env.VITE_PUSHER_APP_KEY;
const pusherCluster = import.meta.env.VITE_PUSHER_APP_CLUSTER;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: pusherKey,
    cluster: pusherCluster,
    forceTLS: true,
    disableStats: true,
    encrypted: true
});

// Logs de débogage
window.Echo.connector.pusher.connection.bind('connected', () => {
    console.log('✅ Pusher Connected', {
        key: pusherKey,
        cluster: pusherCluster
    });
});

window.Echo.connector.pusher.connection.bind('error', (error) => {
    console.error('❌ Pusher Error:', error);
});

console.log('Echo initialisé avec succès');
