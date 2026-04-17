<script setup lang="ts">
import SingleChoiceTask from './SingleChoiceTask.vue';
import SpeakingTask from './SpeakingTask.vue';
import EssayTask from './EssayTask.vue';
import TextInputTask from './TextInputTask.vue';

const props = defineProps<{
    tasks:any,
    attempt?:any,
    checking?:boolean 
}>()

const taskComponent = (type: string) => {
    switch (type) {
        case 'single_choice':
            return SingleChoiceTask
        case 'speaking':
            return SpeakingTask
        case 'essay':
            return EssayTask
        case 'text-input':
            return TextInputTask
        default:
            return SingleChoiceTask
    }
}
//v-bind="task"
</script>

<template>
    <component 
        v-for="task in tasks"
        :key="task.id"
        :is="taskComponent(task.type)"
        v-bind="task"
        :task="task"
        
        :attempt="attempt"
        :checking="checking"
    />
</template>