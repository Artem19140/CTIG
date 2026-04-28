<script setup lang="ts">
import { useHttp } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import BaseTask from './BaseTask.vue';
import RenderBlocks from './TaskContentBlocks/RenderBlocks.vue';
import { Task } from '@/interfaces/Task';
import { Attempt } from '@/interfaces/Interfaces';
import { useAttempt } from '@/composables/useAttempt';

const props = defineProps<{
    task:Task,
    attempt:Attempt,
    checking?:boolean
}>()

const attemptAnswer = ref<number | null>(
  props.task.attemptAnswer?.answer?.id
)

const error = ref<boolean>(false)

const attemptAnswerId = props.task?.attemptAnswer?.id

const http = useHttp<{ answer: number | null}>({
    answer: null
})

const  {updateAnswer} = useAttempt()

const send = async () => {
    error.value = false
    http.answer = attemptAnswer.value
    http.put(`/attempts/${props.attempt.id}/answers/${attemptAnswerId}`,{
        onSuccess:(response : any) => {
            updateAnswer(props.task.id, response.data)
        },
        onFinish() {
            if(!http.wasSuccessful){
                error.value = true
            }
        },
    })
}

watch(attemptAnswer, () => {
    send()
})

</script>

<template>
    <base-task
        :onRetry="send"
        :task="task"
        :checking="checking"
        :attempt="attempt"
        :error="error"
        :loading="http.processing"
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