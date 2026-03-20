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
    <student-show-modal />
    <document-show-dialog />
    <confirm-dialog />
    <alert />
    <prompt-dialog />
    <exam-show-modal />
</template>

<script setup>
import AppSnackbar from '../Components/AppSnackbar/AppSnackbar.vue'
import StudentShowModal from '../Pages/Students/Components/StudentShowModal.vue';
import DocumentShowDialog from '../Components/DocumentShowDialog/DocumentShowDialog.vue';
import ConfirmDialog from '../Components/ConfirmDialog/ConfirmDialog.vue';
import Alert from '../Components/Alert/Alert.vue';
import PromptDialog from '../Components/PromptDialog/PromptDialog.vue';
import ExamShowModal from '../Pages/Exam/Components/ExamShowModal/ExamShowModal.vue';
</script>