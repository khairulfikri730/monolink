import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
                display: ['Poppins', 'Inter', 'sans-serif'],
                serif: ['Playfair Display', 'serif'],
            },
            colors: {
                brand: {
                    50: '#eff6ff',
                    100: '#dbeafe',
                    200: '#bfdbfe',
                    300: '#93c5fd',
                    400: '#60a5fa',
                    500: '#3b82f6',
                    600: '#2563eb',
                    700: '#1d4ed8',
                    800: '#1e40af',
                    900: '#1e3a8a',
                },
            },
            borderRadius: {
                '4xl': '28px',
                '5xl': '36px',
            },
            boxShadow: {
                soft: '0 8px 30px rgba(0,0,0,0.06)',
                card: '0 4px 24px rgba(0,0,0,0.06)',
                float: '0 12px 40px rgba(0,0,0,0.10)',
            },
            maxWidth: {
                profile: '440px',
            },
        },
    },

    plugins: [forms],
};
