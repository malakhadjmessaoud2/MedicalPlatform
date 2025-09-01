import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/app-patient.css',
                'resources/js/app.js',
                'resources/js/app-patient.js',
            ],
            refresh: true,
        }),
    ],
});
