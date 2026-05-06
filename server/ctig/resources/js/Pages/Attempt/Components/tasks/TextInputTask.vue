<script setup lang="ts">
import AppInput from '@components/UI/AppInput/AppInput.vue';
import BaseTask from './BaseTask.vue';
import { ref, watch } from 'vue';
import { Attempt } from '@/interfaces/Attempt';
import { Task } from '@/interfaces/Task';

const props = defineProps<{
    task:Task,
    attempt:Attempt
}>()

const emit = defineEmits<{
    (e:'updateAnswer', value: {
        task:Task,
        answer:any
    }):void
}>()

const answer = ref<string | null>(props.task?.attemptAnswer.answer)

let timeout: number | undefined

watch(answer, (text) => {
    if (timeout !== undefined) {
        clearTimeout(timeout)
    }

    timeout = setTimeout(async () => {
        emit('updateAnswer', {
            task:props.task,
            answer:text
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