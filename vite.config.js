import { defineConfig } from 'vite';
import { fileURLToPath } from 'node:url';

const packageRoot = fileURLToPath(new URL('.', import.meta.url));

export default defineConfig({
    build: {
        emptyOutDir: true,
        outDir: `${packageRoot}/dist`,
        rollupOptions: {
            input: `${packageRoot}/resources/js/index.js`,
            output: {
                entryFileNames: 'dense.js',
                assetFileNames: (asset) => asset.name?.endsWith('.css') ? 'dense.css' : '[name][extname]',
            },
        },
    },
});
