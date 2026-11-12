import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { fileURLToPath, URL } from 'node:url';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Public website
                'resources/sass/web/app.scss',
                'resources/js/web/app.js',
                // Admin (Vue SPA)
                'resources/sass/admin/app.scss',
                'resources/js/admin/main.js',
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: { base: null, includeAbsolute: false },
            },
        }),
    ],
    resolve: {
        alias: {
            '@': fileURLToPath(new URL('./resources/js/admin', import.meta.url)),
        },
    },
    css: {
        preprocessorOptions: {
            scss: {
                // The stylesheets still use @import and global functions
                silenceDeprecations: ['import', 'global-builtin', 'color-functions', 'slash-div', 'mixed-decls'],
            },
        },
    },
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
