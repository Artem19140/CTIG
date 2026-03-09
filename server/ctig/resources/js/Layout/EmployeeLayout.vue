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
          <v-list-item prepend-icon="mdi-account-group" title="Студенты" @click="router.get('/students')"  value="myfiles"></v-list-item>
          <v-list-item prepend-icon="mdi-school" value="shared" title="Экзамены" @click="router.get('/exams')"></v-list-item>
          <v-list-item prepend-icon="mdi-file-document" title="Отчеты" value="reports" @click="router.get('/reports')"></v-list-item>
          <v-list-item prepend-icon="mdi-clipboard-check" title="Проверка" value="starred"></v-list-item>
          <v-list-item prepend-icon="mdi-logout" title="Выйти из аккаунта" value="starred"></v-list-item>
        </v-list>
        
      </v-navigation-drawer>

    <v-main 
      
      style="background:#f1f5f9; min-height: 100vh;"
     
    >
      <slot />
    </v-main>
    </v-layout>




    <app-snackbar 
    />
    <student-show-modal />
    <document-show-dialog />
    <confirm-dialog />
</template>

<script setup>
//  <!-- style="height: 100vh;
//         background-image: url('/storage/images/background.png');
//         background-size: cover;
//         background-position: center;
//       " -->
//background:#f1f5f9;"
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'
import AppSnackbar from '../Components/UI/AppSnackbar/AppSnackbar.vue'
import StudentShowModal from '../Pages/Students/Components/StudentShowModal.vue';
import DocumentShowDialog from '../Components/UI/DocumentShowDialog/DocumentShowDialog.vue';
import { usePage } from '@inertiajs/vue3';
import ConfirmDialog from '../Components/UI/ConfirmDialog/ConfirmDialog.vue';

const page = usePage()
const employeeName = `${page?.props.auth.user.surname} ${page?.props.auth.user.name[0]}. ${page?.props.auth.user.patronymic[0]}.`

const activeItem = ref('')
</script>