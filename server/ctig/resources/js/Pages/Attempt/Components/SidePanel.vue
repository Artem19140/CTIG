<script setup lang="ts">
import Timer from './Timer.vue';
import TaskSideList from './TaskSideList.vue';
import { computed } from 'vue';
import { Attempt } from '@/interfaces/Interfaces';

const props = defineProps<{
  attempt:Attempt
}>()

const progress = computed(() => {
  if (!props.attempt.tasks?.length) return 0
  return (solved.value  / props.attempt.tasks.length) * 100
})

const solved = computed(() =>  props.attempt.tasks.filter(item => item?.attemptAnswer?.answer !== null).length)
</script>

<template>

  <div class="p-6 mt-5">
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
        {{ solved }} / {{ attempt.tasks?.length }}
      </div>
    </div>

    <v-progress-linear
      color="green"
      height="10"
      rounded
      :model-value="progress"
      class="mb-4"
    />

    <TaskSideList :tasks="attempt.tasks" />
  </div>
</template>