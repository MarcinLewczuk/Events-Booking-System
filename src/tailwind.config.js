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
                // Primary Teal (Delapré Abbey - Header)
                primary: {
                    50: '#e6f4f5',
                    100: '#b3e0e2',
                    200: '#80cccf',
                    300: '#4db8bc',
                    400: '#299194', // Header background
                    500: '#299194', // Main teal (header)
                    600: '#247a7c',
                    700: '#206177', // Darker teal for hover
                    800: '#1a4d5f',
                    900: '#133946',
                    950: '#0d252e',
                },
                // Lighter Teal for components
                'teal-light': {
                    50: '#eaf7f5',
                    100: '#c2e9e4',
                    200: '#9adbd2',
                    300: '#72cdc1',
                    400: '#6bbbae', // Lighter teal for components
                    500: '#6bbbae',
                    600: '#569689',
                    700: '#417167',
                    800: '#2c4c44',
                    900: '#172722',
                },
                // Secondary Golden Yellow (Delapré Abbey accents)
                secondary: {
                    50: '#fef9e6',
                    100: '#fceeb3',
                    200: '#fae380',
                    300: '#f8d84d',
                    400: '#daaa00', // Yellow accents
                    500: '#daaa00', // Main yellow
                    600: '#ae8800',
                    700: '#826600',
                    800: '#564400',
                    900: '#2a2200',
                    950: '#1a1500',
                },
                // Dark grey for text and backgrounds
                'dark-grey': {
                    DEFAULT: '#25282a',
                    light: '#3a3d3f',
                    dark: '#1a1c1d',
                },
                // Accent colors
                accent: {
                    success: '#10b981',
                    warning: '#f59e0b',
                    info: '#3b82f6',
                    danger: '#ef4444',
                    teal: '#299194',
                    gold: '#daaa00',
                },
            },
        },
    },

    plugins: [forms],
};