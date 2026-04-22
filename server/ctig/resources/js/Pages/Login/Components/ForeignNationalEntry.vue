<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import AppLogo from '@components/UI/AppLogo/AppLogo.vue' 
import AppPrimaryButton from '@components/UI/AppPrimaryButton/AppPrimaryButton.vue'

const form = useForm({
  code: '',
})

const submit = () => {
  form.post('/exam-codes/verify', {
    preserveScroll: true,
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
          type="number"
          length="6"
        ></v-otp-input>
        <div class="text-red mb-4">{{ form.errors.code }}</div>
        <AppPrimaryButton
          text=" Войти"
          type="submit"
          large
          block
          :loading="form.processing"
          :disabled="form.processing"
        />
      </v-form>
    </v-card>
</template>

<style scoped>
.v-card {
  border-radius: 16px;
}
</style>