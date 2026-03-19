<template>
    <v-layout>
      <v-navigation-drawer
        expand-on-hover
        permanent
        rail
      >
        <v-img
          width="50"
          src="/storage/images/tigr.png"
        >

        </v-img>
        <v-list>
          <v-list-item
            prepend-avatar="https://randomuser.me/api/portraits/women/85.jpg"
            subtitle="sandra_a88@gmailcom"
            :title="employeeName"
          ></v-list-item>
        </v-list>

        <v-divider></v-divider>

        <v-list density="compact" nav v-model="activeItem">
          <v-list-item prepend-icon="mdi-account-group" title="Студенты" @click="router.get('/students')"  value="myfiles"></v-list-item>
          <v-list-item prepend-icon="mdi-school" value="shared" title="Экзамены" @click="router.get('/exams')"></v-list-item>
          <v-list-item prepend-icon="mdi-clipboard-check" title="Проверка" value="starred"  @click="router.get('/attempts/checking')"></v-list-item>
          <v-list-item prepend-icon="mdi-monitor-eye" title="Мониторинг экзамена" value="examMonitoring" @click="router.get('/exams/monitoring')"></v-list-item>
          <v-list-item prepend-icon="mdi-calendar-month" value="schedule" title="Расписание" @click="router.get('/exams/schedule')"></v-list-item>
          <v-list-item prepend-icon="mdi-office-building" title="Организация" value="organization" @click="router.get(`/organizations/${page.props?.auth?.user?.organization_id}`)"></v-list-item>
          <v-list-item prepend-icon="mdi-account-group" title="Сотрудники" value="employees" @click="router.get(`/organizations/${page.props?.auth?.user?.organization_id}/employees`)"></v-list-item>
          <v-list-item prepend-icon="mdi-logout" title="Выйти из аккаунта" value="logout" @click="router.post('/logout')"></v-list-item>
        </v-list>
        
      </v-navigation-drawer>

    <v-main 
      style="background-image: url('/storage/images/background.png');
              min-height: 100vh;
              background-size: cover;
              background-position: center"
              
    >
      <slot />
    </v-main>
    </v-layout>




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
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'
import AppSnackbar from '../Components/AppSnackbar/AppSnackbar.vue'
import StudentShowModal from '../Pages/Students/Components/StudentShowModal.vue';
import DocumentShowDialog from '../Components/DocumentShowDialog/DocumentShowDialog.vue';
import { usePage } from '@inertiajs/vue3';
import ConfirmDialog from '../Components/ConfirmDialog/ConfirmDialog.vue';
import Alert from '../Components/Alert/Alert.vue';
import PromptDialog from '../Components/PromptDialog/PromptDialog.vue';
import ExamShowModal from '../Pages/Exam/Components/ExamShowModal/ExamShowModal.vue';
import BaseLayout from './BaseLayout.vue';

defineOptions({
  layout: BaseLayout,
})

const page = usePage()
const employeeName = `${page?.props.auth?.user?.surname} ${page?.props.auth?.user?.name}`

const activeItem = ref('')
</script>

<style>
  html {
    overflow-y: scroll;
  }
</style>