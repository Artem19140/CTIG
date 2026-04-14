<script setup lang="ts">
import { useLoadingSnackbar } from '@composables/useLoadingSnackBar';
const {isOpen, message, isSuccess}  = useLoadingSnackbar()
</script>

<template>
        <v-snackbar
            v-model="isOpen"
            :timeout="-1"
            :text="message"
            contained
            loading
        ></v-snackbar>
        <!-- <v-snackbar
            v-model="isSuccess"
            :timeout="2000"
            color="success"
            prepend-icon="$complete"
            text="Успешно!"
            contained
    ></v-snackbar> -->
</template>