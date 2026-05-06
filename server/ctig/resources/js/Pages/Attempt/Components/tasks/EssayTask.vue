<script setup lang="ts">
import { Task } from '@/interfaces/Task';
import BaseTask from './BaseTask.vue';
import AppTextarea from '@/components/UI/AppTextarea/AppTextarea.vue';
import { ref, watch } from 'vue';
import { Attempt } from '@/interfaces/Attempt';

const props = defineProps<{
    content?:any,
    task:Task,
    checking:boolean,
    attempt:Attempt
}>()

const emit = defineEmits<{
    (e:'updateAnswer', value: {
        task:Task,
        answer:any
    }):void
}>()

const answer = ref<string | null>(props.task?.attemptAnswer.answer)

let timeoutSet: boolean = false

watch(answer, (text) => {
    if (timeoutSet) {
        return 
    }
    timeoutSet = true
    setTimeout(async () => {
        timeoutSet = false
        emit('updateAnswer', {
            task:props.task,
            answer:text
        })
    }, 10000)
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