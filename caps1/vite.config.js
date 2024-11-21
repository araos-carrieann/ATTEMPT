import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/style.css',
                'resources/css/styles2.css',
                'resources/css/book-filter.css',
                'resources/js/app.js',
                'resources/js/back-to-top.js',
                'resources/js/increment-decrement.js',
                'resources/js/jquery.counterup.min.js',
                'resources/js/repeat-js.js',
                'resources/js/script.js',
                'resources/js/script2.js',
            ],
            refresh: true,
        }),
    ],
});
