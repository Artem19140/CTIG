<script setup lang="ts">
import { ref } from 'vue';
import AppInput from '../../../Components/AppInput/AppInput.vue';
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    email: 'anna_ryasanova@gmail.com', 
    password: '12345678'
})

const show = ref<boolean>(false)
</script>

<template>
    <form @submit.prevent="form.post('/login', {preserveScroll: true})" >
        <!-- <v-img width="250" src="/storage/images/tigr_full.png" /> -->
        <AppInput 
            label="Логин"
            name="email"
            v-model="form.email"
            :error-message="form.errors.email"
            placeholder="Введите логин"
        />

        <v-text-field
            v-model="form.password"
            :append-inner-icon="show ? 'mdi-eye-off' : 'mdi-eye'"
            :type="show ? 'text' : 'password'"
            label="Пароль"
            name="password"
            @click:append-inner="show = !show"
            :invalid="form.errors.password"
            :error-messages="form.errors.password"
            placeholder="Введите пароль"
        ></v-text-field>
        <div class="flex justify-center"> 
            <v-btn 
                size="large" 
                type="submit" 
                :loading="form.processing"
                color="primary"
                :disabled="!form.email ||  !form.password || form.processing"
            >Войти</v-btn>
        </div>
    </form>
</template>