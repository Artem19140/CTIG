<script setup lang="ts">
import axios from 'axios';
import AppInput from '../../../../Components/AppInput/AppInput.vue';
import BaseTask from './BaseTask.vue';
import { ref, watch } from 'vue';



const props = defineProps<{
    task:any,
    attempt:any
}>()

const answer = ref<string | null>(props.task.attemptAnswer.attemptAnswer)

let timeout: number | undefined

watch(answer, (text) => {
    if (timeout !== undefined) {
        clearTimeout(timeout)
    }

    timeout = setTimeout(async () => {
        await axios.put(`/exam-attempts/${props.attempt.id}/answers`, {
            answer: text,
            taskVariantId:props.task.id
        })
        console.log('Пользователь перестал печатать:', text)
    }, 3000)
})

</script>

<template>
    <!-- {{ task.attemptAnswer }} -->
    <BaseTask 
        :subtitle = "`Номер ${task.order}`"
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