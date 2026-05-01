<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import AppLogo from '@components/UI/AppLogo/AppLogo.vue';
import AppPasswordConfirmation from '@/components/UI/AppPasswordConfirmation/AppPasswordConfirmation.vue';
import AppPrimaryButton from '@/components/UI/AppPrimaryButton/AppPrimaryButton.vue';

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
  <v-container class="fill-height d-flex align-center justify-center">
    <v-card elevation="6" max-width="500" class="pa-6 text-center w-100">

      <v-card-title class="justify-center mb-4">
       <div class="flex justify-center items-center mb-4">
            <AppLogo 
                max-width="150" 
            />
        </div>
      </v-card-title>

      <v-card-subtitle class="mb-6 text-h6">
        Смена пароля
      </v-card-subtitle>

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
    </v-card>
  </v-container>
</template>

<style scoped>
.v-card {
  border-radius: 16px;
}
</style>