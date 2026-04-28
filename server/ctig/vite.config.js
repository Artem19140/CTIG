import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import tailwindcss from '@tailwindcss/vite'
import vuetify from 'vite-plugin-vuetify'
import { fileURLToPath, URL } from 'node:url'


export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js', 'resources/css/app.css'],
            refresh: true,
        }),
        vue(),
        tailwindcss(),
        vuetify({ autoImport: true })
    ],
    resolve: {
        alias: {
            '@': fileURLToPath(new URL('./resources/js', import.meta.url)),
            '@components': fileURLToPath(new URL('./resources/js/components', import.meta.url)),
            '@pages': fileURLToPath(new URL('./resources/js/pages', import.meta.url)),
            '@layouts': fileURLToPath(new URL('./resources/js/layouts', import.meta.url)),
            '@composables': fileURLToPath(new URL('./resources/js/composables', import.meta.url)),
            '@interfaces': fileURLToPath(new URL('./resources/js/interfaces', import.meta.url)),
            '@helpers': fileURLToPath(new URL('./resources/js/helpers', import.meta.url)),
            '@domain': fileURLToPath(new URL('./resources/js/domain', import.meta.url)),
            '@constants': fileURLToPath(new URL('./resources/js/constants', import.meta.url)),
            '@data': fileURLToPath(new URL('./resources/data', import.meta.url)),
            '@stores': fileURLToPath(new URL('./resources/stores', import.meta.url)),
        }
    }
})