<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import AppPasswordConfirmation from '@/components/UI/AppPasswordConfirmation/AppPasswordConfirmation.vue';
import AppPrimaryButton from '@/components/UI/AppPrimaryButton/AppPrimaryButton.vue';
import BaseEntryCard from '@/components/BaseComponents/BaseEntryCard/BaseEntryCard.vue';

interface PasswordChange{
  password: string | null, 
  password_confirmation: string | null 
}

const form = useForm<PasswordChange>({
  password: null, 
  password_confirmation: null 
})

const change = () => {
  if(form.password !== form.password_confirmation){
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
  <BaseEntryCard
    subtitle="Смена пароля"
  >
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
</template>

<style scoped>
.v-card {
  border-radius: 16px;
}
</style>