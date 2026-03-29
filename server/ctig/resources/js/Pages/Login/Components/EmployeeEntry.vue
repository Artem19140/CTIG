<script setup lang="ts">
import { ref } from 'vue';
import AppInput from '../../../Components/AppInput/AppInput.vue';
import { useForm } from '@inertiajs/vue3';
import AppLogo from '../../../Components/AppLogo/AppLogo.vue';

const form = useForm({
  email: 'qwerty@bk.com',
  password: '123456789'
});

const show = ref<boolean>(false);

const submit = () => {
  form.post('/login', { preserveScroll: true });
};
</script>

<template>
    <v-card elevation="6" max-width="500" class="pa-6 text-center w-100">
      <v-card-title class="justify-center mb-4">
        <AppLogo 
            max-width="150" 
        />
      </v-card-title>

      <v-card-subtitle class="mb-6 text-h6">
        Войдите в свой аккаунт
      </v-card-subtitle>
      
      <form @submit.prevent="submit">
        <AppInput 
          label="Логин"
          name="email"
          v-model="form.email"
          :error-message="form.errors.email"
          placeholder="Введите логин"
        />

        <AppInput
          v-model="form.password"
          :append-inner-icon="show ? 'mdi-eye-off' : 'mdi-eye'"
          :type="show ? 'text' : 'password'"
          label="Пароль"
          name="password"
          @click:append-inner="show = !show"
          :invalid="!!form.errors.password"
          :error-messages="form.errors.password"
          placeholder="Введите пароль"
          class="mb-6"
        />

        <v-btn
          type="submit"
          color="primary"
          large
          block
          :loading="form.processing"
          :disabled="!form.email || !form.password || form.processing"
        >
          Войти
        </v-btn>
      </form>

    </v-card>
</template>

<style scoped>
.v-card {
  border-radius: 16px;
}
</style>