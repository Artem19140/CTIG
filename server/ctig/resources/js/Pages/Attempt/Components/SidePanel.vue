<script setup lang="ts">
import Timer from './Timer.vue';
import TaskSideList from './TaskSideList.vue';
import { computed, ref } from 'vue';

const props = defineProps<{
    attempt:any,
    tasks:any
}>()

const progress = computed(() => {
  if (!props.tasks?.length) return 0
  return (solved.value / props.tasks.length) * 100
})
const tasks = ref(props.tasks)
const solved = computed(() =>  props.tasks.filter(item => item?.attemptAnswer?.answer !== null).length)

</script>

<template>
    <v-list>
        <v-list-item>
           <Timer 
                :time-begin="attempt.startedAt"
                :time-end="attempt.expiredAt"
           />
        </v-list-item>
        <v-list-item>
           <v-list-item-title>
            Задания
            </v-list-item-title>
            <v-progress-linear
                color="green"
                :height="20"
                :model-value="progress"
            ><span>{{ solved }} / {{ tasks?.length }}</span></v-progress-linear>
        </v-list-item>
        <v-list-item>
            <TaskSideList :tasks="tasks" />
        </v-list-item>
    </v-list>
</template>