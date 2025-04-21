import tailwindcss from 'tailwindcss';
import { defineConfig } from 'vite';


export const content = [
    "./src/**/*.{html,js,ts,jsx,tsx}",
];
export const theme = {
    extend: {
        fontFamily: {
            raleway: ['Raleway', 'sans-serif'],
        },
        colors: {
            disabledColor: 'gray-600'
        }
    },
};
export const plugins = [
    tailwindcss,
    require('autoprefixer'),
    require('@tailwindcss/typography')
];
