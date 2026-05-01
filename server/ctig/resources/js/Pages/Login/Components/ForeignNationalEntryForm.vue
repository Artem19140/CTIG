<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import AppPrimaryButton from '@components/UI/AppPrimaryButton/AppPrimaryButton.vue'

const form = useForm<{code:string | null}>({
  code: null,
})

const submit = () => {
  form.post('/exam-codes/verify', {
    preserveScroll: true,
  })
}
</script>

<template>
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
</template>

<style scoped>
.v-card {
  border-radius: 16px;
}
</style>