<script setup lang="ts">
import BaseTask from './BaseTask.vue';

const props = defineProps<{
    task:any,
    attempt:any
}>()

</script>

<template>
    <BaseTask 
        :subtitle = "`Номер ${task.order}`"
        :content="task.content"
        :task="task"
    >
        
    </BaseTask>
</template>