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
import AppSnackbar from '../Components/AppSnackbar/AppSnackbar.vue'
import ConfirmDialog from '../Components/ConfirmDialog/ConfirmDialog.vue';
import Alert from '../Components/Alert/Alert.vue';
import PromptDialog from '../Components/PromptDialog/PromptDialog.vue';
import Modals from '../Components/Modals/Modals.vue';
import AppLoadingSnackbar from '../Components/AppLoadingSnackbar/AppLoadingSnackbar.vue';
</script>