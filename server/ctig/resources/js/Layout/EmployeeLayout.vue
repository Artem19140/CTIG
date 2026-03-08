<template>
    <v-layout>
      <v-navigation-drawer
        expand-on-hover
        permanent
        rail
      >
        <v-list>
          <v-list-item
            prepend-avatar="https://randomuser.me/api/portraits/women/85.jpg"
            subtitle="sandra_a88@gmailcom"
            :title="employeeName"
          ></v-list-item>
        </v-list>

        <v-divider></v-divider>

        <v-list density="compact" nav v-model="activeItem">
          <v-list-item prepend-icon="mdi-account-multiple" title="Студенты" @click="router.get('/students')"  value="myfiles"></v-list-item>
          <v-list-item prepend-icon="mdi-folder" value="shared" title="Экзамены" @click="router.get('/exams')"></v-list-item>
          <v-list-item prepend-icon="mdi-folder" title="Отчеты" value="starred"></v-list-item>
          <v-list-item prepend-icon="mdi-folder" title="Проверка" value="starred"></v-list-item>
        </v-list>
      </v-navigation-drawer>

    <v-main style="height: 100vh; background:#f1f5f9;">
      <slot />
    </v-main>
    </v-layout>




    <app-snackbar 
    />
    <student-show-modal />
    <document-show-dialog />
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'
import AppSnackbar from '../Components/UI/AppSnackbar/AppSnackbar.vue'
import StudentShowModal from '../Pages/Students/Components/StudentShowModal.vue';
import DocumentShowDialog from '../Components/UI/DocumentShowDialog/DocumentShowDialog.vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage()
const employeeName = `${page?.props.auth.user.surname} ${page?.props.auth.user.name[0]}. ${page?.props.auth.user.patronymic[0]}.`

const activeItem = ref('')
</script>