import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel([
            'resources/sass/admin.scss',
            'resources/sass/app.scss',
            'resources/sass/contact.scss',
            'resources/sass/photography.scss',
            'resources/sass/software.scss',
            'resources/sass/splash.scss',
            'resources/js/app.js',
        ]),
    ],
});
