import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
          input: [
            'resources/css/app.css',
            'resources/js/app.js',
          ],
          refresh: true,
        }),
        react(),
    ],
    server: {
        host: '127.0.0.1',
        port: 5173,
    },
    resolve: {
        extensions: ['.js', '.jsx'], // Explicitly resolve React extensions
    },
    esbuild: {
        loader: 'jsx',
        include: /.*\.jsx?$/,
        exclude: []
    },
    optimizeDeps: {
        esbuildOptions: {
            loader: {
                '.js': 'jsx',
            },
        },
    },
});
