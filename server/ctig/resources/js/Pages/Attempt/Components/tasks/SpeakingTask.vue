<script setup lang="ts">
import { Task } from '@/interfaces/Task';
import BaseTask from './BaseTask.vue';
import { Attempt } from '@/interfaces/Interfaces';

const props = defineProps<{
    task:Task,
    attempt:Attempt
}>()

</script>

<template>
    <BaseTask 
        :content="task.content"
        :task="task"
    >  
    </BaseTask>
</template>