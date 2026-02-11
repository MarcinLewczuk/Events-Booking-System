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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Primary Teal/Turquoise (Delapre Abbey)
                primary: {
                    50: '#f0fdfa',
                    100: '#ccfbf1',
                    200: '#99f6e4',
                    300: '#5ae4d4',
                    400: '#2dd4bf',
                    500: '#4DB8A7', // Main teal
                    600: '#0d9488',
                    700: '#0f766e',
                    800: '#115e59',
                    900: '#134e4a',
                    950: '#042f2e',
                },
                // Secondary Golden Yellow (Delapre Abbey)
                secondary: {
                    50: '#fefce8',
                    100: '#fef9c3',
                    200: '#fef08a',
                    300: '#fde047',
                    400: '#facc15',
                    500: '#F5B800', // Main gold/yellow
                    600: '#ca8a04',
                    700: '#a16207',
                    800: '#854d0e',
                    900: '#713f12',
                    950: '#422006',
                },
                // Accent colors
                accent: {
                    success: '#10b981',
                    warning: '#f59e0b',
                    info: '#3b82f6',
                    danger: '#ef4444',
                    teal: '#4DB8A7',
                    gold: '#F5B800',
                },
            },
        },
    },

    plugins: [forms],
};