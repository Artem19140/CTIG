<script setup lang="ts">
import SingleChoiceTask from './SingleChoiceTask.vue';
import SpeakingTask from './SpeakingTask.vue';
import EssayTask from './EssayTask.vue';
import TextInputTask from './TextInputTask.vue';
import { TaskTypes } from '@/constants/TaskTypes';
import { AttemptAnswer } from '@/interfaces/Task';
import { Attempt } from '@/interfaces/Attempt';
import { useAttempt } from '@/composables/useAttempt';
import { useHttp } from '@inertiajs/vue3';
import BaseEmptyState from '@/components/BaseComponents/BaseEmptyState/BaseEmptyState.vue';

const props = defineProps<{
    attempt:Attempt
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

const http = useHttp<{answer:any}, {data:AttemptAnswer}>({
    answer:null
})

const {updateAnswer, setError, removeError, setSaving, removeSaving } = useAttempt()

const update = (value:any) => {
    http.answer = value.answer
    setSaving(value.task.id)
    http.put(`/attempts/${props.attempt.id}/answers/${value.task.attemptAnswer.id}`,{
        onSuccess:(response) => {
            updateAnswer(value.task.id, response.data)
        },
        onFinish() {
            http.wasSuccessful ? removeError(value.task.id) : setError(value.task.id)
            removeSaving(value.task.id)
        },
    })
}
</script>

<template>
    <div class="flex flex-column gap-15"
        v-if="attempt.tasks.length > 0"
    >
        <div
            v-for="task in attempt.tasks"
        >   
            <component 
                :key="task.id"
                :is="taskComponent(task.type)"
                :task="task"
                :attempt="attempt"
                @update-answer="update"
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