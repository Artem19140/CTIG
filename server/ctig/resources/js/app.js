import '../css/app.css'
import './bootstrap.js'
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import { ru } from 'vuetify/locale'
import { useHttpErrorHandler } from '@/composables/useHttpErrorHandler';
import { http, router } from '@inertiajs/vue3';
import { useSnackbarQueue } from '@/composables/useSnackbarQueue';
import { createPinia } from 'pinia'

http.onError((error) => {
    const response = (error).response
    if (!response) return
    useHttpErrorHandler().handle(response)
})

const { add } = useSnackbarQueue()
router.on('flash', (event) => {
    if(event.detail.flash.success){
        add(String(event.detail.flash.success), 'green')
    }

    if(event.detail.flash.error){
        add(String(event.detail.flash.error), 'red')
    }
})

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
        const pages = import.meta.glob('./pages/**/*.vue', { eager: true })
        return pages[`./pages/${name}.vue`]
    },
    setup({ el, App, props, plugin }) {
        const pinia = createPinia()
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(vuetify)
            .use(pinia)
            .mount(el)
    },
    defaults: {
        future: {
            useDialogForErrorModal: true,
        },
        // visitOptions: (href, options) => {
        //   return { viewTransition: true };
        // },
    },
    //layout: () => BaseLayout
})

