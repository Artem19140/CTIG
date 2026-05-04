<template>
  <base-layout>
    <template #drawer>
      <BaseDrawer
        expand-on-hover
        permanent
        rail
      >
        <div class="d-flex flex-column fill-height">
          <BaseList>
            <BaseListItem
              prepend-avatar="/storage/images/tigr.png"
              :subtitle="user?.job_title"
              :title="employeeName"
            />
          </BaseList>

          <v-divider></v-divider>

          <BaseList density="compact" nav v-model="activeItem">
            <BaseListItem
              prepend-icon="mdi-account-group" 
              title="Иностранные граждане" 
              v-if="can([Roles.OPERATOR, Roles.DIRECTOR])"
              @click="go('/foreign-nationals')"  
              value="foreignNationals"
            />

            <BaseListItem 
              prepend-icon="mdi-school" 
              title="Экзамены" 
              v-if="can([Roles.OPERATOR, Roles.SCHEDULER, Roles.DIRECTOR])"
              @click="go('/exams')"
              value="exams" 
            />

            <BaseListItem
              prepend-icon="mdi-monitor-eye" 
              v-if="can([Roles.EXAMINER])"
              title="Мониторинг экзамена" 
              value="monitoring" 
              @click="go('/exams/monitoring')"
            />

            <BaseListItem 
              prepend-icon="mdi-clipboard-check" 
              v-if="can([Roles.EXAMINER])"
              title="Проверка"
              @click="go('/exams/checking')"
              value="checking" 
            />
            
            <BaseListItem
              prepend-icon="mdi-calendar-month" 
              title="Расписание" 
              v-if="can([Roles.OPERATOR, Roles.SCHEDULER, Roles.DIRECTOR])"
              @click="go('/exams/schedule')"
              value="schedule"
            />

            <BaseListItem 
              prepend-icon="mdi-office-building" 
              v-if="can([Roles.ORG_ADMIN])" 
              title="Центр" 
              value="center" 
              @click="go(`/centers/${centerId}`)"
            />
            
          </BaseList>
      
          <BaseList density="compact" nav class="mt-auto">
            <BaseListItem 
              prepend-icon="mdi-book-open-page-variant" 
              title="Инструкция" 
              value="instruction" 
              @click="go('/instruction/foreign-nationals')"
            />
            <BaseListItem
              prepend-icon="mdi-logout" 
              title="Выйти из аккаунта" 
              @click="logout"
            />
          </BaseList>
        </div>
      </BaseDrawer>
    </template>
    <slot />
  </base-layout>
</template>

<script setup lang="ts">

import { ref } from 'vue'
import BaseLayout from './BaseLayout.vue';
import { useAuth } from '@composables/useAuth';
import { Roles } from '@constants/Roles';
import { router } from '@inertiajs/vue3'
import { useConfirmDialog } from '@/composables/useConfirmDialog';
import BaseDrawer from '@/components/BaseComponents/BaseDrawer/BaseDrawer.vue';
import BaseList from '@/components/BaseComponents/BaseList/BaseList.vue';
import BaseListItem from '@/components/BaseComponents/BaseList/BaseListItem.vue';

const go = (url:string) => {
  router.visit(url)
}

const logout = async () => {
  const {confirmOpen} = useConfirmDialog()
  const ok = await confirmOpen('Выйти из аккаунта?')
  if(!ok) return 
  router.post('/logout')
}
const {can, user} = useAuth()

const centerId = user?.center_id
const employeeName = `${user?.surname} ${user?.name}`
const activeItem = ref('')
</script>

<style>
  html {
    overflow-y: scroll;
  }
</style>