<script setup lang="ts">
import { ref } from 'vue';
import AppInput from '../../../Components/UI/AppInput/AppInput.vue';
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    email: 'qwerty@gmail.com', //qwerty1231123@gmail.com
    password: '12345678' //12345678 
})

const show = ref<boolean>(false)
</script>

<template>
    <div>   
        <form @submit.prevent="form.post('/login', {preserveScroll: true})">
            <v-card
                width="400"
                class="mx-auto pt-4 my-64"
            >
                <template v-slot:title>
                    <div class="flex items-center flex-col justify-center">
                        <span class="font-weight-black">ТИГР</span>
                        <span class="text-base">Аутентификация</span>
                    </div>
                </template>
                <v-card-item>
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
                    
                    <div style="display: flex; justify-content: center;" class="pb-4">
                        <v-btn size="large" type="submit" :loading="form.processing">Войти</v-btn>
                    </div>
                </v-card-item>
            </v-card>
        </form>
    </div>
   
</template>