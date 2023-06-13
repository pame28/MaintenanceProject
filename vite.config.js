import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'public/vendor/orchid/css/orchid.css',
                'public/vendor/orchid/js/orchid.js',
            ],
            refresh: true,
        }),
    ],
});
