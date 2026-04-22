<script setup lang="ts">
import ConfirmDialog from '@components/ConfirmDialog/ConfirmDialog.vue';
import Alert from '@components/Alert/Alert.vue';
import PromptDialog from '@components/PromptDialog/PromptDialog.vue';
import Modals from '@components/Modals/Modals.vue';
import AppLoadingSnackbar from '@components/UI/AppLoadingSnackbar/AppLoadingSnackbar.vue';
import AppSnackbarQueue from '@/components/UI/AppSnackbarQueue/AppSnackbarQueue.vue';
import { useSnackbarQueue } from '@/composables/useSnackbarQueue';
import { router, http } from '@inertiajs/vue3';
import { useHttpErrorHandler } from '@/composables/useHttpErrorHandler';

http.onResponse((response) => {
    return response
})

http.onError((error) => {
    console.log(156)
    const response = (error as any).response
    
    if (!response) return
    useHttpErrorHandler().handle(response)
    console.log(response)
})

const { add } = useSnackbarQueue()
router.on('flash', (event) => {
    if(event.detail.flash.success){
        add(String(event.detail.flash.success), 'green')
    }

    if(event.detail.flash.error){
        console.log('flash')
        add(String(event.detail.flash.error), 'red')
    }
})
</script>

<template>
    <slot />
    <confirm-dialog />
    <alert />
    <prompt-dialog />
    <modals />
    <app-loading-snackbar />
    <app-snackbar-queue />
</template>
