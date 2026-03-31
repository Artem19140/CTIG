<script setup>
import { computed } from 'vue'
import { router } from '@inertiajs/vue3';

const props = defineProps({ status: Number })


const title = computed(() => {
  return {
    503: '503: Сервис недоступен',
    500: '500: Ошибка сервера',
    404: '404: Страница не найдена',
    403: '403: Доступ запрещён',
  }[props.status] || 'Неизвестная ошибка'
})

const description = computed(() => {
  return {
    503: 'Извините, сейчас проводятся технические работы. Попробуйте позже.',
    500: 'Упс! Что-то пошло не так на сервере.',
    404: 'Извините, страница, которую вы ищете, не найдена.',
    403: 'Извините, у вас нет доступа к этой странице.',
  }[props.status] || 'Произошла неизвестная ошибка.'
})

const icon = computed(() => {
  return {
    503: 'mdi-server-off',
    500: 'mdi-alert-circle',
    404: 'mdi-file-question',
    403: 'mdi-lock-alert',
  }[props.status] || 'mdi-alert'
})

const goHome = () => {
  router.get('/exams')
}
</script>

<template>
  <div class="error-page">
    <v-card class="error-card" elevation="4" color="red lighten-5">
      <v-card-title class="title">
        <v-icon class="me-3" color="red darken-2" size="40">{{ icon }}</v-icon>
        {{ title }}
      </v-card-title>
      <v-card-text class="description">
        {{ description }}
      </v-card-text>
      <v-card-actions class="actions">
        <v-spacer></v-spacer>
        <v-btn color="red darken-2" variant="elevated" @click="goHome">
          На главную
        </v-btn>
      </v-card-actions>
    </v-card>
  </div>
</template>

<style scoped>
.error-page {
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  background: linear-gradient(135deg, #ffe5e5, #ffd6d6);
  animation: fadeIn 0.8s ease-in-out;
}

.error-card {
  width: 100%;
  max-width: 480px;
  text-align: center;
  padding: 24px;
  border-radius: 16px;
  animation: slideUp 0.6s ease-out;
}

.title {
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
}

.description {
  margin-top: 12px;
  font-size: 1.1rem;
  color: #b71c1c;
}

.actions {
  margin-top: 20px;
}

/* Анимации */
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes slideUp {
  from { transform: translateY(30px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}
</style>