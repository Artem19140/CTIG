import '../css/app.css'
import './bootstrap.js'
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'
import { createVuetify } from 'vuetify'


const vuetify = createVuetify({
  theme: {
    defaultTheme: 'light', // 'system' | 'light' | 'dark'
  },
})
createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        return pages[`./Pages/${name}.vue`]
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(vuetify)
            .mount(el)
    },
    defaults: {
        future: {
            useDialogForErrorModal: true,
        },
    },
})

