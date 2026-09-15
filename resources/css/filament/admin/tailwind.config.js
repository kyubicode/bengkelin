/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.php',
        './vendor/filament/**/*.blade.php',
    ],
    theme: {
        extend: {},
    },
    plugins: [],
}