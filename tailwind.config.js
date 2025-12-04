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
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
                accent: ['Spectral', 'serif'],
            },
            colors: {
                renews: {
                    vert: '#70CD25',
                    electric: '#74FD08',
                    fonce: '#4B9635',
                    gris: '#808080',
                    noir: '#000000',
                    'noir-impure': '#1C1C1C',
                    'gris-fonce': '#232222',
                }
            }
        },
    },

    plugins: [forms],
};