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
                primary: {
                50: '#effef6',
                100: '#d9fde8',
                200: '#b1f7ce',
                300: '#7aebb0',
                400: '#3ed593',
                500: '#16b176', // Primary brand green
                600: '#0d8f60',
                700: '#0c714f',
                800: '#0d5a40',
                900: '#0c4a36'
                }
            }
        },
    },

    plugins: [forms],
};
