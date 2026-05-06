<script setup lang="ts">
import SingleChoiceTask from './SingleChoiceTask.vue';
import SpeakingTask from './SpeakingTask.vue';
import EssayTask from './EssayTask.vue';
import TextInputTask from './TextInputTask.vue';
import { TaskTypes } from '@/constants/TaskTypes';
import { AttemptAnswer } from '@/interfaces/Task';
import { Attempt } from '@/interfaces/Attempt';
import TaskRatingBlock from './TaskRatingBlock.vue';
import BaseEmptyState from '@/components/BaseComponents/BaseEmptyState/BaseEmptyState.vue';

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
</script>

<template>
    <div v-if="attempt.tasks.length > 0">
        <div
            class="flex flex-column gap-5 mb-15"
            v-for="task in attempt.tasks"
        >
            <component 
                :key="task.id"
                :is="taskComponent(task.type)"
       
                :task="task"
                :attempt="attempt"
            />
            <TaskRatingBlock 
                :task="task"
                v-if="checking"
            />
        </div>
    </div>
    
    <BaseEmptyState
        v-else
        icon="mdi-clipboard-text-off-outline"
        title="Заданий нет"
        text="Пока что здесь ничего не появилось"
    />

</template>