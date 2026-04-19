<script setup lang="ts">
import SingleChoiceTask from './SingleChoiceTask.vue';
import SpeakingTask from './SpeakingTask.vue';
import EssayTask from './EssayTask.vue';
import TextInputTask from './TextInputTask.vue';
import { TaskTypes } from '@/constants/TaskTypes';
import { Attempt } from '@/interfaces/Interfaces';
import { AttemptAnswer } from '@/interfaces/Task';

const props = defineProps<{
    attempt:Attempt,
    checking?:boolean 
}>()

const emit = defineEmits<{
    (e:'updateAnswer', value:AttemptAnswer):void
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
const saved = (value:AttemptAnswer) => {
    emit('updateAnswer', value)
}
</script>

<template>
    <component 
        v-if="attempt.tasks.length > 0"
        @answerSaved="saved"
        v-for="task in attempt.tasks"
        :key="task.id"
        :is="taskComponent(task.type)"
        v-bind="task"
        :task="task"
        :attempt="attempt"
        :checking="checking"
    />
    <div v-else>
        <v-empty-state
            
            icon="mdi-clipboard-text-off-outline"
            title="Заданий нет"
            text="Пока что здесь ничего не появилось"
        ></v-empty-state>
    </div>
</template>