import '../css/app.css'
import './bootstrap.js'
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import {ru } from 'vuetify/locale'
import BaseLayout from '@layouts/BaseLayout.vue'


const vuetify = createVuetify({
  theme: {
    defaultTheme: 'light', // 'system' | 'light' | 'dark'
    themes: {
      light: {
        colors: {
          background: '#f5f5f5',
          surface: '#ffffff',
          primary:'#0176ff',
          'on-surface': '#1e293b',
          'on-background': '#1e293b'
        }
      }
    }
  },
   locale: {
    locale: 'ru',
    fallback: 'ru',
    messages: { ru },
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
    layout: () => BaseLayout
})

