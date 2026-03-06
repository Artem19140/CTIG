<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue';

const form = useForm({
    newPassword: '', //qwerty1231123@gmail.com
    newPassword_confirmed: '' //12345678 
})

const show = ref(false)
const showRepeat = ref(false)

const change = () => {
    // if(form.newPassword !== form.newPassword_confirmed){
    //     alert('Пароли не совпадают')
    //     return
    // }
  form.post('/password/change', {
    preserveScroll: true,
    preserveState: true, 
    onError: (errors) => {
      console.log('Ошибки валидации:', errors);
    },
    onSuccess: () => {
      console.log('Пароль успешно изменён');
    }
  })
}
</script>

<template>
    <form @submit.prevent="change">
        <v-text-field
            v-model="form.newPassword"
            :append-inner-icon="show ? 'mdi-eye-off' : 'mdi-eye'"
            :type="show ? 'text' : 'password'"
            label="Пароль"
            name="newPassword"
            @click:append-inner="show = !show"
            :invalid="form.errors.newPassword"
            :error-messages="form.errors.newPassword"
            placeholder="Введите новый пароль"
        ></v-text-field>

        <v-text-field
            v-model="form.newPassword_confirmed"
            :append-inner-icon="showRepeat ? 'mdi-eye-off' : 'mdi-eye'"
            :type="showRepeat ? 'text' : 'password'"
            label="Повтор пароля"
            name="newPassword_confirmed"
            @click:append-inner="showRepeat = !showRepeat"
            :invalid="form.errors.newPassword_confirmed"
            :error-messages="form.errors.newPassword_confirmed"
            placeholder="Повторите пароль"
        ></v-text-field>
        <v-btn 
            type="submit" 
        >Сменить</v-btn>
    </form>
    
</template>