import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
    build: {
        // Assurer que les modules sont correctement résolus
        commonjsOptions: {
            include: [/node_modules/],
        },
    },
    optimizeDeps: {
        include: [
            '@fullcalendar/core',
            '@fullcalendar/daygrid',
            '@fullcalendar/timegrid',
            '@fullcalendar/list',
            '@fullcalendar/interaction',
        ],
    },
    define: {
        'process.env': {},
        'import.meta.env.VITE_PUSHER_APP_KEY': JSON.stringify(process.env.VITE_PUSHER_APP_KEY || '7807be1909553259daf8'),
        'import.meta.env.VITE_PUSHER_APP_CLUSTER': JSON.stringify(process.env.VITE_PUSHER_APP_CLUSTER || 'mt1'),
    },
});
