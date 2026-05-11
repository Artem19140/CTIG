<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import AppPasswordConfirmation from '@/components/UI/AppPasswordConfirmation/AppPasswordConfirmation.vue';
import AppPrimaryButton from '@/components/UI/AppPrimaryButton/AppPrimaryButton.vue';
import BaseEntryCard from '@/components/BaseComponents/BaseEntryCard/BaseEntryCard.vue';
import BaseLayout from '@/layouts/BaseLayout.vue';

interface PasswordChange{
  password: string | null, 
  password_confirmation: string | null 
}

const form = useForm<PasswordChange>({
  password: null, 
  password_confirmation: null 
})

const change = () => {
  form.errors.password = undefined
  form.errors.password_confirmation = undefined
  if(form.password !== form.password_confirmation){
    form.errors.password = 'Пароли не совпадают!'
    form.errors.password_confirmation = 'Пароли не совпадают!'
    return
  }
  form.post('/password/change', {
    preserveScroll: true,
    preserveState: true
  })
}
</script>


<template>
  <BaseLayout>
    <BaseEntryCard
      subtitle="Смена пароля"
    >
    <div>Вам необходимо сменить временный пароль. Придумайте свой пароль, минимум 8 символов.</div>
      <form @submit.prevent="change">
        <AppPasswordConfirmation
          v-model:password="form.password"
          v-model:password-confirmation="form.password_confirmation"
          :password-attr="{'error-messages':form?.errors?.password}"
          :password-confirmation-attr="{'error-messages':form?.errors?.password_confirmation}"
        />
        <AppPrimaryButton
          type="submit"
          text="Сменить"
          large
          block
          :loading="form.processing"
          :disabled="!form.password || !form.password_confirmation || form.processing"
        />
      </form>
    </BaseEntryCard>
  </BaseLayout>
</template>

<style scoped>
.v-card {
  border-radius: 16px;
}
</style>