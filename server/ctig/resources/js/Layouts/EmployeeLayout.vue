<template>

    <v-layout>
      <v-navigation-drawer
        expand-on-hover
        permanent
        rail
      >
      <div class="d-flex flex-column fill-height">
        
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
              title="Иностранные граждане" 
              v-if="can([Roles.OPERATOR])"
              @click="go('/foreign-nationals')"  
              value="foreignNationals"
            ></v-list-item>
            <v-list-item 
              prepend-icon="mdi-school" 
              title="Экзамены" 
              v-if="can([Roles.OPERATOR, Roles.SCHEDULER])"
              @click="go('/exams')"
              value="exams" 
            ></v-list-item>
            <v-list-item 
              prepend-icon="mdi-clipboard-check" 
              v-if="can([Roles.EXAMINER])"
              title="Проверка"
              @click="go('/exams/checking')"
              value="checking" 
            ></v-list-item>
            <v-list-item 
              prepend-icon="mdi-monitor-eye" 
              v-if="can([Roles.EXAMINER])"
              title="Мониторинг экзамена" 
              value="monitoring" 
              @click="go('/exams/monitoring')"
            ></v-list-item>
            <v-list-item 
              prepend-icon="mdi-calendar-month" 
              title="Расписание" 
              v-if="can([Roles.OPERATOR, Roles.SCHEDULER])"
              @click="go('/exams/schedule')"
              value="schedule"
              ></v-list-item>
            <v-list-item 
              prepend-icon="mdi-office-building" 
              v-if="can([Roles.ORG_ADMIN])" 
              title="Центр" 
              value="center" 
              @click="go(`/centers/${centerId}`)"
            ></v-list-item>
            <v-list-item 
              prepend-icon="mdi-account-group" 
              v-if="can([Roles.ORG_ADMIN])" 
              title="Сотрудники" 
              @click="go(`/centers/${centerId}/employees`)"
              value="employees" 
              ></v-list-item>
            
          </v-list>
        
        <v-list density="compact" nav class="mt-auto">
          <v-list-item 
            prepend-icon="mdi-logout" 
            title="Выйти из аккаунта" 
            @click="logout"
          ></v-list-item>
        </v-list>
        </div>
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
</template>

<script setup lang="ts">

import { ref } from 'vue'
import BaseLayout from './BaseLayout.vue';
import { useAuth } from '@composables/useAuth';
import { Roles } from '@constants/Roles';
import { router } from '@inertiajs/vue3'
import { useConfirmDialog } from '@/composables/useConfirmDialog';

const go = (url:string) => {
  router.visit(url)
}

const logout = async () => {
  const {confirmOpen} = useConfirmDialog()
  const ok = await confirmOpen('Выйти из аккаунта?')
  if(!ok) return 
  router.post('/logout')
}

router.on("httpException", (event) => {
  console.log(`An invalid Inertia response was received.`);
  console.log(event.detail.response);
});

const {can, user} = useAuth()

defineOptions({
  layout: BaseLayout,
})

const centerId = user?.center_id
const employeeName = `${user?.surname} ${user?.name}`
const activeItem = ref('')
</script>

<style>
  html {
    overflow-y: scroll;
  }
</style>