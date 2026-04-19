<script setup lang="ts">
import SingleChoiceTask from './SingleChoiceTask.vue';
import SpeakingTask from './SpeakingTask.vue';
import EssayTask from './EssayTask.vue';
import TextInputTask from './TextInputTask.vue';
import { TaskTypes } from '@/constants/TaskTypes';
import { Attempt } from '@/interfaces/Interfaces';

const props = defineProps<{
    attempt:Attempt,
    checking?:boolean 
}>()

const taskComponent = (type: string) => {
    switch (type) {
        case TaskTypes.SINGLE_CHOICE:
            return SingleChoiceTask
        case TaskTypes.SPEAKING:
            return SpeakingTask
        case TaskTypes.ESSAY:
            return EssayTask
        case TaskTypes.TEXT_INPUT:
            return TextInputTask
        default:
            return SingleChoiceTask
    }
}
const saved = (value:any) => {
    console.log(value)
}
</script>

<template>
    <component 
        @answerSaved="saved"
        v-for="task in attempt.tasks"
        :key="task.id"
        :is="taskComponent(task.type)"
        v-bind="task"
        :task="task"
        :attempt="attempt"
        :checking="checking"
    />
</template>