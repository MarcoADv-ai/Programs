import ckeditor5 from '@ckeditor/vite-plugin-ckeditor5';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { createRequire } from 'node:module';
import { defineConfig } from 'vite';

const require = createRequire( import.meta.url );
export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        ckeditor5( { theme: require.resolve( '@ckeditor/ckeditor5-theme-lark' ) } )
    ],
    resolve: {
        alias: {
            // '#': fileURLToPath( new URL( './src', import.meta.url ) )
        },
    },    
});
