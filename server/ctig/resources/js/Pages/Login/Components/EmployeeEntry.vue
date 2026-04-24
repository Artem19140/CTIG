<script setup lang="ts">
import AppPrimaryButton from '@components/UI/AppPrimaryButton/AppPrimaryButton.vue'
import AppInput from '@components/UI/AppInput/AppInput.vue';
import { useForm } from '@inertiajs/vue3';
import AppLogo from '@components/UI/AppLogo/AppLogo.vue';
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
    <v-card elevation="6" max-width="500" class="pa-6 text-center w-100">
      <v-card-title >
        <div class="flex justify-center mb-4">
          <AppLogo 
            max-width="150" 
          />
        </div>
      </v-card-title>

      <v-card-subtitle class="mb-6 text-h6">
        Войдите в свой аккаунт
      </v-card-subtitle>
      
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
          label="Пароль"
          name="password"
          :invalid="!!form.errors.password"
          :error-messages="form.errors.password"
          placeholder="Введите пароль"
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

    </v-card>
</template>

<style scoped>
.v-card {
  border-radius: 16px;
}
</style>