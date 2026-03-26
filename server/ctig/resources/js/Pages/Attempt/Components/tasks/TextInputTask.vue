<script setup lang="ts">
import { useHttp } from '@inertiajs/vue3';
import AppInput from '../../../../Components/AppInput/AppInput.vue';
import BaseTask from './BaseTask.vue';
import { ref, watch } from 'vue';



const props = defineProps<{
    task:any,
    attempt:any
}>()

const answer = ref<string | null>(props.task?.answer)

let timeout: number | undefined

const http = useHttp<{ answer: string | null, taskVariantId:number }>({
    taskVariantId:props.task.id,
    answer:''
})

watch(answer, (text) => {
    if (timeout !== undefined) {
        clearTimeout(timeout)
    }

    timeout = setTimeout(async () => {
        http.answer = text
        http.put(`/exam-attempts/${props.attempt.id}/answers`,{
            onSuccess:(response:any) => {

            }
        })
        console.log('Пользователь перестал печатать:', text)
    }, 3000)
})

</script>

<template>
    <!-- {{ task }} -->
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