<script setup lang="ts">
import AppSnackbar from '@components/UI/AppSnackbar/AppSnackbar.vue'
import ConfirmDialog from '@components/ConfirmDialog/ConfirmDialog.vue';
import Alert from '@components/Alert/Alert.vue';
import PromptDialog from '@components/PromptDialog/PromptDialog.vue';
import Modals from '@components/Modals/Modals.vue';
import AppLoadingSnackbar from '@components/UI/AppLoadingSnackbar/AppLoadingSnackbar.vue';
import AppSnackbarQueue from '@/components/AppSnackbarQueue/AppSnackbarQueue.vue';
</script>

<template>
    <slot />
    <app-snackbar />
    <confirm-dialog />
    <alert />
    <prompt-dialog />
    <modals />
    <app-loading-snackbar />
    <app-snackbar-queue />
</template>
