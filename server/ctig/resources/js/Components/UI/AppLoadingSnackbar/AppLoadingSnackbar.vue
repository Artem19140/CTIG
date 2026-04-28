<script setup lang="ts">
import { useLoadingSnackbar } from '@composables/useLoadingSnackBar';
const {isOpen, message}  = useLoadingSnackbar()
</script>

<template>
    <v-snackbar
        v-model="isOpen"
        :timeout="-1"
        :text="message"
        contained
        loading
    ></v-snackbar>
</template>