<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue';

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
    <v-container width="500">
        <v-card>
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
                    v-model="form.newPassword_confirmation"
                    :append-inner-icon="showRepeat ? 'mdi-eye-off' : 'mdi-eye'"
                    :type="showRepeat ? 'text' : 'password'"
                    label="Повтор пароля"
                    name="newPassword_confirmation"
                    @click:append-inner="showRepeat = !showRepeat"
                    :invalid="form.errors.newPassword_confirmation"
                    :error-messages="form.errors.newPassword_confirmation"
                    placeholder="Повторите пароль"
                ></v-text-field>
                <v-btn 
                    type="submit" 
                >Сменить</v-btn>
            </form>
        </v-card>
    </v-container>
    
</template>