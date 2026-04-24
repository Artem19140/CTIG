<script setup lang="ts">
import ConfirmDialog from '@components/ConfirmDialog/ConfirmDialog.vue';
import Alert from '@components/Alert/Alert.vue';
import PromptDialog from '@components/PromptDialog/PromptDialog.vue';
import Modals from '@components/Modals/Modals.vue';
import AppLoadingSnackbar from '@components/UI/AppLoadingSnackbar/AppLoadingSnackbar.vue';
import AppSnackbarQueue from '@/components/UI/AppSnackbarQueue/AppSnackbarQueue.vue';
import ConfirmationOptionsModal from '@/components/Modals/ConfirmationOptionsModal.vue';

</script>

<template>
    <v-app>
        <!-- <v-app-bar title="App bar"></v-app-bar> -->
        <slot name="drawer" />
        <v-main style="background-image: url('/storage/images/background.png');
                        min-height: 100vh;
                        background-size: cover;
                        background-position: center;"
    >
        
        <slot />
        
        </v-main>
        <confirm-dialog />
        <alert />
        <prompt-dialog />
        <modals />
        <app-loading-snackbar />
        <app-snackbar-queue />
        <confirmation-options-modal />
    </v-app>
</template>
