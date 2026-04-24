<script setup lang="ts">
import AppPrimaryButton from '@components/UI/AppPrimaryButton/AppPrimaryButton.vue'
import AppInput from '@components/UI/AppInput/AppInput.vue';
import { useForm } from '@inertiajs/vue3';
import AppPasswordInput from '@components/UI/AppPasswordInput/AppPasswordInput.vue';
import AppCheckbox from '@components/UI/AppCheckbox/AppCheckbox.vue';

const form = useForm({
  email: 'qwerty@bk.com',
  password: '123456789',
  rememberMe:false
});

const submit = () => {
  form.post('/login', { preserveScroll: true });
};
</script>

<template>
      <form @submit.prevent="submit">
        <AppInput 
          label="email"
          name="email"
          v-model="form.email"
          :invalid="!!form.errors.email"
          :error-messages="form.errors.email"
          placeholder="Введите email"
        />
        <AppPasswordInput
          v-model="form.password"
          :invalid="!!form.errors.password"
          :error-messages="form.errors.password"
          class="mb-6"
        />
        <AppCheckbox
          label="Запомнить меня" 
          v-model="form.rememberMe"
          :error-messages="form.errors.rememberMe"
        />

        <AppPrimaryButton
          type="submit"
          color="primary"
          large
          block
          text="Войти"
          :loading="form.processing"
          :disabled="!form.email || !form.password || form.processing"
      />
      </form>
</template>

<style scoped>
  .v-card {
    border-radius: 16px;
  }
</style>