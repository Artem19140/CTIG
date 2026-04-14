<script setup lang="ts">
import { ref } from 'vue';
import AppInput from '@components/UI/AppInput/AppInput.vue';
const show = ref<boolean>(false);
</script>

<template>
    <AppInput
        :append-inner-icon="show ? 'mdi-eye-off' : 'mdi-eye'"
        :type="show ? 'text' : 'password'"
        name="password"
        @click:append-inner="show = !show"
        placeholder="Введите пароль"
    />
</template>