<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'


const form = useForm({
    code: '', //qwerty1231123@gmail.com
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
    <div>   
        
        <form @submit.prevent="submit">
            
            <v-card
                width="400"
                class="mx-auto pt-4 my-64"
            >
                <template v-slot:title>
                    <div class="flex items-center flex-col justify-center">
                        <span class="font-weight-black">ТИГР</span>
                        <span class="text-base">Введите код из 4 цифр</span>
                    </div>
                </template>
                <v-card-item>
                    <v-otp-input
                        v-model="form.code"
                        class="mb-8"
                        divider="•"
                        length="4"
                        variant="outlined"
                    ></v-otp-input>
                    
                    <div style="display: flex; justify-content: center;" class="pb-4">
                        <v-btn
                            size="large" 
                            type="submit" 
                            :loading="form.processing"
                            :disabled="form.code.length < 4 || form.processing"
                        >Войти
                        </v-btn>
                    </div>
                </v-card-item>
            </v-card>
        </form>
    </div>
</template>