<script setup lang="ts">
import { Task } from '@/interfaces/Task';
import BaseTask from './BaseTask.vue';
import AppTextarea from '@/components/UI/AppTextarea/AppTextarea.vue';

const props = defineProps<{
    content?:any,
    task?:Task
}>()

</script>

<template>
    <BaseTask 
        v-if="task"
        :task="task"
    >
        <AppTextarea
            label="Введите текст"
        />
    </BaseTask>
</template>