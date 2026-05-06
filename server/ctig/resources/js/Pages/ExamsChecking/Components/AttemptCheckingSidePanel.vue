<script setup lang="ts">
import { Attempt } from '@/interfaces/Attempt';

const props = defineProps<{
  attempt: Attempt
}>()

const emit = defineEmits<{
  (e: 'select', id: number): void
}>()

const getParams = (checkedAt:string | null) => {
  if(checkedAt === null) return {icon:'', color:'grey'}
  return checkedAt ? {icon:'mdi-check', color:'success'} : {icon:'mdi-close', color:'error'} 
}

</script>

<template>

  <v-list density="compact" nav>
    <v-list-item
      v-for="task in attempt.tasks"
      :key="task.id"
      @click="emit('select', task.id)"
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