<script setup lang="ts">
import { useHttp } from '@inertiajs/vue3';
import AppInput from '@components/UI/AppInput/AppInput.vue';
import BaseTask from './BaseTask.vue';
import { ref, watch } from 'vue';
import { useAttempt } from '@/composables/useAttempt';
import { Attempt } from '@/interfaces/Attempt';

const props = defineProps<{
    task:any,
    attempt:Attempt
}>()

const answer = ref<string | null>(props.task?.attemptAnswer.answer)

let timeout: number | undefined

const http = useHttp<{ answer: string | null }>({
    answer:props.task.attemptAnswer?.answer
})

const  {updateAnswer} = useAttempt()
watch(answer, (text) => {
    if (timeout !== undefined) {
        clearTimeout(timeout)
    }

    timeout = setTimeout(async () => {
        http.answer = text
        http.put(`/attempts/${props.attempt.id}/answers/${props.task.attemptAnswer.id}`,{
            onSuccess:(response:any) => {
                updateAnswer(props.task.id, response.data)
                props.task.attemptAnswer = response.data
            }
        })
    }, 3000)
})

</script>

<template>
    <BaseTask 
        :task="task"
    >   
        <template #answers>
            <v-card-text>
                <AppInput
                    v-model="answer"
                    label="Введите ответ"
                />
            </v-card-text>
        </template>
    </BaseTask>
</template>