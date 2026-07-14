import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import colors from 'tailwindcss/colors';

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
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: colors.indigo,
                neutral: colors.slate,
                success: colors.emerald,
                warning: colors.amber,
                danger: colors.red,
            },
            boxShadow: {
                'subtle': '0 1px 2px 0 rgba(0, 0, 0, 0.03)',
                'card': '0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03)',
                'floating': '0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04)',
                'modal': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
            },
            borderRadius: {
                'xl': '0.75rem',
                '2xl': '1rem',
                '3xl': '1.5rem',
            },
            zIndex: {
                'dropdown': '40',
                'sticky': '50',
                'modal': '60',
                'toast': '70',
                'tooltip': '80',
            },
            animation: {
                'fade-in': 'fadeIn 0.2s ease-out',
                'fade-out': 'fadeOut 0.2s ease-in',
                'slide-up': 'slideUp 0.3s ease-out',
                'slide-in-right': 'slideInRight 0.3s ease-out',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                fadeOut: {
                    '0%': { opacity: '1' },
                    '100%': { opacity: '0' },
                },
                slideUp: {
                    '0%': { transform: 'translateY(10px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
                slideInRight: {
                    '0%': { transform: 'translateX(100%)', opacity: '0' },
                    '100%': { transform: 'translateX(0)', opacity: '1' },
                }
            }
        },
    },

    plugins: [forms],
};
