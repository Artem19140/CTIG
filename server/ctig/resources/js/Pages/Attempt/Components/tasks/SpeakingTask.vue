<script setup lang="ts">
import { Task } from '@/interfaces/Task';
import BaseTask from './BaseTask.vue';

const props = defineProps<{
    task:Task
}>()

</script>

<template>
    <BaseTask 
        :content="task.content"
        :task="task"
    >  
    </BaseTask>
</template>