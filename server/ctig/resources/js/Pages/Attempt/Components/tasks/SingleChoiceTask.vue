<script setup lang="ts">
import { useHttp } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import BaseTask from './BaseTask.vue';
import RenderBlocks from './TaskContentBlocks/RenderBlocks.vue';
import { Task } from '@/interfaces/Task';
import { useExamAttempt } from '@/composables/useExamAttempt';
import { Attempt } from '@/interfaces/Interfaces';

const props = defineProps<{
    task:Task,
    attempt:Attempt,
    checking?:boolean
}>()

const attemptAnswer = ref<number | null>(
  props.task.attemptAnswer?.answer?.id
)

const attemptAnswerId = props.task?.attemptAnswer?.id

const http = useHttp<{ answer: number | null}>({
    answer: null
})

const send = async () => {
    http.answer = attemptAnswer.value
    // const {updateAnswer} = useExamAttempt()
    // updateAnswer(attemptAnswerId, attemptAnswer.value)
    http.put(`/attempts/${props.attempt.id}/answers/${attemptAnswerId}`,{
        onSuccess:(response : any) => {
            props.task.attemptAnswer.answer = response.data.answer
        },
        onError:() => {

        }
    })
}

watch(attemptAnswer, () => {
    send()
})

</script>

<template>
    <base-task
        :task="task"
        :checking="checking"
        :attempt="attempt"
    >
        <template #answers>
            <div class="mb-4 flex flex-column">
                <span v-if="checking">1</span>
                <v-radio-group 
                    v-model="attemptAnswer"
                    :disabled="checking"
                >      
                    <v-radio 
                        v-for="answer in props.task.answers"
                        :key="answer?.id"
                        :value="answer?.id"
                    >
                        <template #label>
                            <div class="">
                                <render-blocks :content="answer?.content" />
                            </div>
                        </template>
                    </v-radio>
                </v-radio-group>
            </div>
        </template>
    </base-task>
    
    
</template>