<script setup lang="ts">
import axios from 'axios'
import { onMounted, ref, watch } from 'vue';
import BaseTask from './BaseTask.vue';
import RenderBlocks from './TaskContentBlocks/RenderBlocks.vue';

const props = defineProps<{
    task:any,
    attempt:any
}>()

const attemptAnswer = ref<number>(
  props.task.attemptAnswer.answerId
)

const send = async () => {
    await axios.put(`/exam-attempts/${props.attempt.id}/answers`, {
        answer: attemptAnswer.value,
        taskVariantId:props.task.id
    })
}

watch(attemptAnswer, () => {
    send()
})

</script>

<template>
    <!-- <pre>
        {{ task }}
    </pre> -->

    <base-task
        :subtitle = "`Номер ${task.order}`"
    >
        <template #answers>

        <div class="mb-4 flex flex-column">
                
                <v-radio-group 
                    v-model="attemptAnswer"
                >      
                    <v-radio 
                        v-for="answer in props.task.answers"
                        :key="answer.id"
                        :value="answer.id"
                    >
                        <template #label>
                            <div class="">
                                <render-blocks :content="answer.content" />
                            </div>
                        </template>
                    </v-radio>
                </v-radio-group>
            </div>
        </template>
        <template #description>
            Выберите правильный вариант ответа
        </template>
    </base-task>
</template>