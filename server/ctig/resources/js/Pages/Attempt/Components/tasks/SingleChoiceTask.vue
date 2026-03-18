<script setup lang="ts">
import axios from 'axios'
import { ref } from 'vue';
import BaseTask from './BaseTask.vue';

const props = defineProps<{
    task:any,
    attempt:any
}>()

const attemptAnswer = ref<number | null>(
  props.task.attemptAnswer
    ? Number(props.task.attemptAnswer)
    : null
)

// watch(
//     () => props.task.attemptAnswer,
//     (newVal) => {
//         attemptAnswer.value = newVal ? Number(newVal) : null
//     },
//     { immediate: true }
// )

const send = async () => {
    await axios.put(`/exam-attempts/${props.attempt.id}/answers`, {
        attemptAnswer: attemptAnswer.value,
        taskVariantId:props.task.id
    })
}

</script>

<template>
    <!-- <pre>{{ task }}</pre>
    {{ attemptAnswer }} -->
    <base-task
        :subtitle = "`Номер ${task.order}`"
    >
        <div class="mb-4">Выберите один вариант ответа</div>
            <v-radio-group 
                v-model="attemptAnswer"
                @update:modelValue="send"
            >
                <v-img 
                    v-if="task.file_path"
                    :src="task.file_path"
                />
                    

                {{ task.content }}
                <v-radio 
                    v-for="answer in props.task.answers"
                    :label="answer.content" 
                    :key="answer.id"
                    :value="answer.id">
                </v-radio>
            
            </v-radio-group>
    </base-task>
</template>