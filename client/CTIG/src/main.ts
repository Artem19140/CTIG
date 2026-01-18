import { createApp } from 'vue'
import { createPinia } from 'pinia'
import { ru } from 'vuetify/locale'

import App from './App.vue'
import router from './router'

import { createVuetify } from 'vuetify'
import 'vuetify/styles' // Базовые стили Vuetify
import '@mdi/font/css/materialdesignicons.css' // Иконки
const app = createApp(App)

app.use(createPinia())
app.use(router)

const vuetify = createVuetify({
    locale: {
    locale: 'ru',
    fallback: 'en',
    messages: { ru },
  }, 
})
app.use(vuetify)

app.mount('#app')
