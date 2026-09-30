import { defineConfig } from 'vite';
import symfonyPlugin from 'vite-plugin-symfony';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [tailwindcss(), symfonyPlugin({ stimulus: true })],
    build: {
        rollupOptions: {
            input: {
                app: './assets/app.js',
            },
        },
    },
    server: {
        host: '0.0.0.0',
    },
});
