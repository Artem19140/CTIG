<script setup lang="ts">
import { AttemptChecking, AttemptMonitoring } from '@/interfaces/Attempt';

const props = defineProps<{
  attempt: AttemptChecking | AttemptMonitoring
}>()

const getParams = (checkedAt:string | null) => {
  if(checkedAt === null) return {icon:'', color:'grey'}
  return checkedAt ? {icon:'mdi-check', color:'success'} : {icon:'mdi-close', color:'error'} 
}

const scrollToTask = (id: number) => {
  const el = document.getElementById(`task-${id}`)
  el?.scrollIntoView({
    behavior: 'smooth',
    block: 'start'
  }) 
}
</script>

<template>

  <v-list density="compact" nav>
    <v-list-item
      v-for="task in attempt.tasks"
      :key="task.id"
      @click="scrollToTask(task.id)"
      class="cursor-pointer"
    >
      <template #prepend>
        <v-avatar
          size="24"
          :color="getParams(task.attemptAnswer.checkedAt).color"
        >
          <v-icon size="14">
            {{ getParams(task.attemptAnswer.checkedAt).icon }}
          </v-icon>
        </v-avatar>
      </template>

      <v-list-item-title>
        Задание {{ task.order }}
      </v-list-item-title>
    </v-list-item>
  </v-list>
</template>