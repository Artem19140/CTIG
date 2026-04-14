<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue';
import AppInput from '@components/UI/AppInput/AppInput.vue';
import AppLogo from '@components/UI/AppLogo/AppLogo.vue';

const form = useForm({
    newPassword: '', //qwerty1231123@gmail.com
    newPassword_confirmation: '' //12345678 
})

const show = ref(false)
const showRepeat = ref(false)

const change = () => {
    if(form.newPassword !== form.newPassword_confirmation){
        alert('Пароли не совпадают')
        return
    }
  form.post('/password/change', {
    preserveScroll: true,
    preserveState: true
  })
}
</script>


<template>
  <v-container class="fill-height d-flex align-center justify-center">
    <v-card elevation="6" max-width="500" class="pa-6 text-center w-100">

      <v-card-title class="justify-center mb-4">
        <AppLogo 
            max-width="120" 
        />
      </v-card-title>

      <v-card-subtitle class="mb-6 text-h6">
        Смена пароля
      </v-card-subtitle>

      <form @submit.prevent="change">
        <AppInput
          v-model="form.newPassword"
          :append-inner-icon="show ? 'mdi-eye-off' : 'mdi-eye'"
          :type="show ? 'text' : 'password'"
          label="Новый пароль"
          name="newPassword"
          @click:append-inner="show = !show"
          :error-messages="form.errors.newPassword"
          class="mb-4"
          placeholder="Введите новый пароль"
        />

        <AppInput
          v-model="form.newPassword_confirmation"
          :append-inner-icon="showRepeat ? 'mdi-eye-off' : 'mdi-eye'"
          :type="showRepeat ? 'text' : 'password'"
          label="Повтор пароля"
          name="newPassword_confirmation"
          @click:append-inner="showRepeat = !showRepeat"
          :error-messages="form.errors.newPassword_confirmation"
          class="mb-6"
          placeholder="Повторите пароль"
        />

        <v-btn
          type="submit"
          color="primary"
          large
          block
          :loading="form.processing"
          :disabled="!form.newPassword || !form.newPassword_confirmation || form.processing"
        >
          Сменить
        </v-btn>
      </form>
    </v-card>
  </v-container>
</template>

<style scoped>
.v-card {
  border-radius: 16px;
}
</style>