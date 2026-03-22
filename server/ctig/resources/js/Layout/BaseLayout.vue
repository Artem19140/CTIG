<template>
    <v-main 
      style="background-image: url('/storage/images/background.png');
              min-height: 100vh;
              background-size: cover;
              background-position: center"
              
    >
      <slot />
    </v-main>
    <app-snackbar 
    />
    <document-show-dialog />
    <confirm-dialog />
    <alert />
    <prompt-dialog />
    <modals />
</template>

<script setup>
import AppSnackbar from '../Components/AppSnackbar/AppSnackbar.vue'
import DocumentShowDialog from '../Components/DocumentShowDialog/DocumentShowDialog.vue';
import ConfirmDialog from '../Components/ConfirmDialog/ConfirmDialog.vue';
import Alert from '../Components/Alert/Alert.vue';
import PromptDialog from '../Components/PromptDialog/PromptDialog.vue';
import Modals from '../Components/Modals/Modals.vue';
</script>