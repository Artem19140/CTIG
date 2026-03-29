<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import AppLogo from '../../../Components/AppLogo/AppLogo.vue'

const form = useForm({
  code: '',
})

const submit = () => {
  form.post('/exam-codes/verify', {
    preserveScroll: true,
    onSuccess: () => {
      console.log('Успешно!')
    },
    onError: (errors) => {
      console.log('Ошибки валидации:', errors)
    },
  })
}
</script>

<template>
    <v-card elevation="6" max-width="500" class="pa-6 text-center w-100">
      <v-card-title class="flex items-center justify-center">
        <AppLogo  
          max-width="100" 
        />
      </v-card-title>

      <v-card-subtitle class="mb-4">
        Введите код из 6 цифр
      </v-card-subtitle>

      <v-form @submit.prevent="submit">
        <v-otp-input
          v-model="form.code"
          length="6"
          divider=""
          class="mb-6 w-100"
        ></v-otp-input>

        <v-btn
          type="submit"
          color="primary"
          large
          block
          :loading="form.processing"
          :disabled="form.processing"
        >
          Войти
        </v-btn>
      </v-form>
    </v-card>
</template>

<style scoped>
.v-card {
  border-radius: 16px;
}
</style>