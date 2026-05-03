<script setup lang="ts">
import { Task } from '@/interfaces/Task';
import BaseTask from './BaseTask.vue';
import AppTextarea from '@/components/UI/AppTextarea/AppTextarea.vue';
import { ref, watch } from 'vue';
import { useHttp } from '@inertiajs/vue3';
import { Attempt } from '@/interfaces/Attempt';
import { useAttempt } from '@/composables/useAttempt';

const props = defineProps<{
    content?:any,
    task?:Task,
    checking:boolean,
    attempt:Attempt
}>()

const answer = ref<string | null>(props.task?.attemptAnswer.answer)

let timeout: number | undefined

const http = useHttp<{ answer: string | null }>({
    answer:props.task?.attemptAnswer?.answer
})
const  {updateAnswer} = useAttempt()
watch(answer, (text) => {
    if (timeout !== undefined) {
        clearTimeout(timeout)
    }

    timeout = setTimeout(async () => {
        http.answer = text
        console.log(1)
        http.put(`/attempts/${props.attempt.id}/answers/${props.task?.attemptAnswer.id}`,{
            onSuccess:(response:any) => {
                updateAnswer(props.task?.id, response.data)
                props.task.attemptAnswer = response.data
            }
        })
    }, 3000)
})

</script>

<template>
    <BaseTask 
        v-if="task"
        :task="task"
        :checking="checking"
    >
        <template #answers>
            <AppTextarea
                v-model="answer"
                label="Введите текст"
                rows="4"
                :readonly="checking"
            />
        </template>
    </BaseTask>
</template>