import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/assets/js/jquery-global.js',
                'resources/assets/sass/app.sass',
                'resources/assets/js/client.js',
            ],
            refresh: true,
        }),
        react()
    ],
    resolve: {
        alias: {
            // Webpack's default context was resources/assets, so imports might be relative to that
            // or using aliases. We'll add common aliases here.
            '@': '/resources/assets/js',
            '~': '/node_modules', // Common for SASS
        },
    },
});
