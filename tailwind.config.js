/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './node_modules/flowbite/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                // Warna kustom yang sebelumnya didefinisikan lewat tailwind.config
                // inline (CDN script) di resources/views/layouts/guest.blade.php
                blush: '#F3F4F6',
                rose: '#111111',
                mauve: '#000000',
                ink: '#111111',
                cream: '#FFFFFF',
                lilac: '#E5E7EB',
            },
            fontFamily: {
                display: ['Fraunces', 'serif'],
                sans: ['Plus Jakarta Sans', 'sans-serif'],
            },
        },
    },
    plugins: [
        require('flowbite/plugin'),
    ],
};