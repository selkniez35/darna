import { defineConfig } from 'vite';
import symfonyPlugin from 'vite-plugin-symfony';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        tailwindcss(),
        // Le navigateur (Windows) ne peut pas joindre 0.0.0.0 : on lui donne localhost
        symfonyPlugin({ stimulus: true, viteDevServerHostname: 'localhost' }),
    ],
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
