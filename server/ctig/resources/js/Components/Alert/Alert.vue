<script setup lang="ts">
import { watch } from 'vue';
import { useAlert } from '../../Composables/useAlert';
import { usePage } from '@inertiajs/vue3';

const page = usePage()

watch(
    () => page.flash,
    (flash) => {
        if (flash?.error) {
            isOpen.value = true
            message.value = String(flash.error)
        }
    },
    { immediate: true }
)

import { http } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'

const {open} = useAlert()

const removeErrorHandler = http.onError((error) => {
    console.log()
  const message = JSON.parse(error.response.data)?.message
  switch(error.response.status){
    case 400:
      open(message)
        break;
    case 500:
      open('Ошибка сервера')
        break;
    case 401:
      router.visit('/login')
      open('Вы не авторизованы')
        break;
    case 403:
      open('Нет доступа')
        break;
    case 404:
      open('Не найдено')
        break;
    case 503:
        open('Неизвестная ошибка')
        break;

    default:
      open('Неизвестная ошибка')
  }
  
})

const {isOpen, message, close} = useAlert()
</script>

<template>
    <v-dialog
        v-model="isOpen"
        persistent
        width="400"
    >
        <v-card
            width="400"
            subtitle="Внимание"
        >
            <v-card-text>
                {{ message }}
            </v-card-text>
        <v-card-actions>
            <v-btn color="primary" variant="flat" @click="close">
                Ok
            </v-btn>
        </v-card-actions>
        </v-card>
    </v-dialog>
</template>