<script setup lang="ts">
import { Task } from '@/interfaces/Task';
import BaseTask from './BaseTask.vue';
import { provide, ref, watch } from 'vue';

const props = defineProps<{
    task:Task,
    //checking:boolean
}>()

const emit = defineEmits<{
    (e:'updateAnswer', value: {
        task:Task,
        answer:any
    }):void
}>()

const answers = {
  ...props.task.answers[0].content,
  ...(props.task.attemptAnswer?.answer ?? {})
}

const form = ref<Record<string, any>>({})

Object.assign(form.value, answers)

const show = () => {
    console.log(form.value)
}

let timeoutSet: boolean = false

watch(form.value, () => {
    if (timeoutSet) {
        return 
    }
    timeoutSet = true

    setTimeout(async () => {
        timeoutSet = false
        emit('updateAnswer', {
            task:props.task,
            answer:form.value ?? ''
        })
    }, 100)
})
provide('form', form)
</script>

<template>
    <BaseTask
        :task="task"
        :loading="false"
        @click="show"
    >
        <template #answers>
            
        </template>
    </BaseTask>

</template>