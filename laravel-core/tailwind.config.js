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
                'primary-dark': '#082B59',
                'primary-light': '#30A9D9',
                'accent': '#0BC4D9',
                'brand-bg': '#F2F2F2',
                'secondary': '#0DD0D0',
            },
        },
    },

    plugins: [forms],
};
