const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['"Bebas Neue"', 'sans-serif'],
            },
            colors: {
                cream: '#F5F0EB',
                ivory: '#FAF8F5',
                ink: '#1A1A1A',
                charcoal: '#2D2D2D',
                ash: '#6B6B6B',
                silver: '#A3A3A3',
                smoke: '#E8E4DF',
                pearl: '#F0ECE7',
            },
            letterSpacing: {
                'widest-xl': '0.2em',
            },
            animation: {
                'reveal': 'reveal 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                'line-grow': 'lineGrow 1s cubic-bezier(0.16, 1, 0.3, 1) forwards',
            },
            keyframes: {
                reveal: {
                    '0%': { opacity: '0', transform: 'translateY(20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                lineGrow: {
                    '0%': { transform: 'scaleX(0)' },
                    '100%': { transform: 'scaleX(1)' },
                },
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
