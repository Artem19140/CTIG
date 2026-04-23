<script setup lang="ts">
import Timer from './Timer.vue';
import TaskSideList from './TaskSideList.vue';
import { computed, ref } from 'vue';
import { Attempt } from '@/interfaces/Interfaces';
import { Task } from '@/interfaces/Task';

const props = defineProps<{
    attempt:Attempt,
    tasks:Task[]
}>()

const progress = computed(() => {
  if (!props.tasks?.length) return 0
  return (solved.value / props.tasks.length) * 100
})
const tasks = ref(props.tasks)
const solved = computed(() =>  props.tasks.filter(item => item?.attemptAnswer?.answer !== null).length)
const offset = props.attempt.serverNow - Date.now() / 1000
</script>

<template>
  <v-card class="pa-3" elevation="2" rounded="lg" variant="text">

    <div class="mb-4">
      <Timer
        :server-now="attempt.serverNow"
        :ends-at="attempt.endsAt"
      />
    </div>

    <v-divider class="mb-4" />
    <div class="mb-4">{{ attempt.examName }}</div>
    <div class="mb-4">{{ attempt.foreignNational.fullName }}</div>

    <v-divider class="mb-4" />
    <div class="d-flex align-center justify-space-between mb-2">
      <div class="text-subtitle-1 font-weight-medium">
        Задания
      </div>
      <div class="text-caption text-medium-emphasis">
        {{ solved }} / {{ tasks?.length }}
      </div>
    </div>

    <v-progress-linear
      color="green"
      height="10"
      rounded
      :model-value="progress"
      class="mb-4"
    />

    <TaskSideList :tasks="tasks" />

  </v-card>
</template>