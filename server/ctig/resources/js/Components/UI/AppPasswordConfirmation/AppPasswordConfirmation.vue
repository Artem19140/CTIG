<script setup lang="ts">
import AppPasswordInput from '../AppPasswordInput/AppPasswordInput.vue';

const props = defineProps<{
    passwordAttr:Object,
    passwordConfirmationAttr:object
}>()

const password = defineModel<string | null>('password')

const passwordConfirmation = defineModel<string | null>('passwordConfirmation')
</script>

<template>
<div>
    <AppPasswordInput 
        v-model="password"
        v-bind="passwordAttr"
    />

    <AppPasswordInput 
        v-model="passwordConfirmation"
        v-bind="passwordConfirmationAttr"
        label="Повтор пароля" 
        placeholder="Повторите пароль"
    />
</div>
</template>