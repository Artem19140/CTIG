<template>
    <slot />
    <app-snackbar />
    <confirm-dialog />
    <alert />
    <prompt-dialog />
    <modals />
    <app-loading-snackbar />
</template>

<script setup>
import AppSnackbar from '@components/UI/AppSnackbar/AppSnackbar.vue'
import ConfirmDialog from '@components/ConfirmDialog/ConfirmDialog.vue';
import Alert from '@components/Alert/Alert.vue';
import PromptDialog from '@components/PromptDialog/PromptDialog.vue';
import Modals from '@components/Modals/Modals.vue';
import AppLoadingSnackbar from '@components/UI/AppLoadingSnackbar/AppLoadingSnackbar.vue';
</script>