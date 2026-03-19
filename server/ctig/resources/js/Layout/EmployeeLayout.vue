<template>
    <v-layout>
      <v-navigation-drawer
        expand-on-hover
        permanent
        rail
      >
        <!-- <v-img
          width="50"
          src="/storage/images/tigr.png"
        >

        </v-img> -->
        <v-list>
          <v-list-item
            prepend-avatar="/storage/images/tigr.png"
            :subtitle="user?.job_title"
            :title="employeeName"
          ></v-list-item>
        </v-list>

        <v-divider></v-divider>

        <v-list density="compact" nav v-model="activeItem">
          <v-list-item 
            prepend-icon="mdi-account-group" 
            title="Студенты" 
            @click="router.get('/students')"  
            value="students"
          ></v-list-item>
          <v-list-item 
            prepend-icon="mdi-school" 
            title="Экзамены" 
            @click="router.get('/exams')"
            value="exams" 
          ></v-list-item>
          <v-list-item 
            prepend-icon="mdi-clipboard-check" 
            v-if="can([Roles.EXAMINER])"
            title="Проверка"
            @click="router.get('/attempts/checking')"
            value="checking" 
          ></v-list-item>
          <v-list-item 
            prepend-icon="mdi-monitor-eye" 
            v-if="can([Roles.EXAMINER])"
            title="Мониторинг экзамена" 
            value="monitoring" 
            @click="router.get('/exams/monitoring')"
          ></v-list-item>
          <v-list-item 
            prepend-icon="mdi-calendar-month" 
            title="Расписание" 
            @click="router.get('/exams/schedule')"
            value="schedule"
            ></v-list-item>
          <v-list-item 
            prepend-icon="mdi-office-building" 
            v-if="can([Roles.ORG_ADMIN, Roles.DIRECTOR])" 
            title="Организация" 
            value="organization" 
            @click="router.get(`/organizations/${organizationId}`)"
          ></v-list-item>
          <v-list-item 
            prepend-icon="mdi-account-group" 
            v-if="can([Roles.ORG_ADMIN, Roles.DIRECTOR])" 
            title="Сотрудники" 
            @click="router.get(`/organizations/${organizationId}/employees`)"
            value="employees" 
            ></v-list-item>
          <v-list-item 
            prepend-icon="mdi-logout" 
            title="Выйти из аккаунта" 
            @click="router.post('/logout')"
            value="logout" 
          ></v-list-item>
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
import { useAuth } from '../Composables/useAuth';
import { Roles } from '../Constants/Roles';

const {can, user} = useAuth()

defineOptions({
  layout: BaseLayout,
})

const page = usePage()
const organizationId = user?.organization_id
const employeeName = `${user?.surname} ${user?.name}`
const activeItem = ref('')
</script>

<style>
  html {
    overflow-y: scroll;
  }
</style>