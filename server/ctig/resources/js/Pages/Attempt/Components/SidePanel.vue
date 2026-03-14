<script setup lang="ts">
import Timer from './Timer.vue';
import TaskSideList from './TaskSideList.vue';

const props = defineProps<{
    attempt:any,
    tasks:any
}>()
</script>

<template>
    <v-list>
        <v-list-item>
           <Timer 
                :time-begin="attempt.startedAt"
                :time-end="attempt.expiredAt"
           />
        </v-list-item>

        <TaskSideList :tasks="tasks" />
    </v-list>
</template>