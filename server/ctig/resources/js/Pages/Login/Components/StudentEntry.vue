<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'


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
    onFinish: () => {
      console.log('Запрос завершён')
    },
  })
}
</script>

<template>
    <form @submit.prevent="submit">
      <div class="flex flex-column items-center">
        <v-img width="90" src="/storage/images/tigr.png" />
        <span class="text-base">Введите код из 4 цифр</span>
      </div>
      <v-otp-input
          v-model="form.code"
          class="mb-8"
          length="4"
          variant="outlined"
      ></v-otp-input>
      
      <div style="display: flex; justify-content: center;" class="pb-4">
          <v-btn
              size="large" 
              color="primary"
              type="submit" 
              :loading="form.processing"
              :disabled="form.code.length < 4 || form.processing"
          >Войти
          </v-btn>
      </div>
    </form>
</template>