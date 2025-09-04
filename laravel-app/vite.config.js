import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from "@vitejs/plugin-vue";
import vuetify from 'vite-plugin-vuetify';
import path from 'path';

export default defineConfig({
    plugins: [
        vue(),
        vuetify(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        port: 5173,
        host: '0.0.0.0',
        protocol: 'ws',
        clientPort: 5173,
        hmr: {
            host: 'localhost', // ホスト側からアクセスするアドレス
        },
    },
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
        },
    },
});
