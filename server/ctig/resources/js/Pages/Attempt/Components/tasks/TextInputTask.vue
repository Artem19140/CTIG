<script setup lang="ts">
import { useHttp } from '@inertiajs/vue3';
import AppInput from '@components/UI/AppInput/AppInput.vue';
import BaseTask from './BaseTask.vue';
import { ref, watch } from 'vue';
import { Attempt } from '@/interfaces/Interfaces';

const props = defineProps<{
    task:any,
    attempt:Attempt
}>()

const answer = ref<string | null>(props.task?.attemptAnswer.answer)

let timeout: number | undefined

const http = useHttp<{ answer: string | null }>({
    answer:props.task.attemptAnswer?.answer
})

watch(answer, (text) => {
    if (timeout !== undefined) {
        clearTimeout(timeout)
    }

    timeout = setTimeout(async () => {
        http.answer = text
        http.put(`/attempts/${props.attempt.id}/answers/${props.task.attemptAnswer.id}`,{
            onSuccess:(response:any) => {
                props.task.attemptAnswer = response.data
            }
        })
        console.log('Пользователь перестал печатать:', text)
    }, 3000)
})

</script>

<template>
    <BaseTask 
        :task="task"
    >   
        <template #description>
            <div>Прочитайте текст и вставьте пропущенное слово.</div>
        </template>
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