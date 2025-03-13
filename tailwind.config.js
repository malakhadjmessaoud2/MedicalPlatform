import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            animation: {
                'float-slow': 'float 8s ease-in-out infinite',
                'float-medium': 'float 6s ease-in-out infinite',
                'float-fast': 'float 4s ease-in-out infinite',
                'pulse-slow': 'pulse 10s ease-in-out infinite',
                'pulse-medium': 'pulse 7s ease-in-out infinite',
                'pulse-fast': 'pulse 5s ease-in-out infinite',
            },
            keyframes: {
                float: {
                    '0%, 100%': { transform: 'translateY(0) translateX(0)' },
                    '25%': { transform: 'translateY(-10px) translateX(10px)' },
                    '50%': { transform: 'translateY(0) translateX(20px)' },
                    '75%': { transform: 'translateY(10px) translateX(10px)' },
                },
                pulse: {
                    '0%, 100%': { transform: 'scale(1)', opacity: '0.5' },
                    '50%': { transform: 'scale(1.1)', opacity: '0.3' },
                }
            }
        },
    },

    plugins: [forms, typography],
};
