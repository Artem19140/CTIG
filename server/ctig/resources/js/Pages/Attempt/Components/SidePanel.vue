<script setup lang="ts">
import Timer from './Timer.vue';
import TaskSideList from './TaskSideList.vue';
import { ref } from 'vue';

const props = defineProps<{
    attempt:any,
    tasks:any
}>()

const progress = ref(props.attempt.solved/22 * 100)
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
            ><span>{{ attempt?.solved }} / 22</span></v-progress-linear>
        </v-list-item>
        <v-list-item>
            <TaskSideList :tasks="tasks" />
        </v-list-item>
    </v-list>
</template>