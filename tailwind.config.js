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
                primary: 'var(--primary)',
                'primary-dark': 'var(--primary-dark)',
                secondary: 'var(--secondary)',
                success: 'var(--success)',
                warning: 'var(--warning)',
                danger: 'var(--danger)',
                background: 'var(--background)',
                card: 'var(--card)',
                text: 'var(--text)',
                muted: 'var(--muted)',
                border: 'var(--border)',
                'text-light': 'var(--text-light)',
                'text-white': 'var(--text-white)',
                'hover-light': 'var(--hover-light)',
                'hover-danger-light': 'var(--hover-danger-light)',
                'sidebar-bg': 'var(--sidebar-bg)',
            },
        },
    },

    plugins: [forms],
};
