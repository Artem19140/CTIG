<script setup lang="ts">
import { useHttp } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import BaseTask from './BaseTask.vue';
import RenderBlocks from './TaskContentBlocks/RenderBlocks.vue';


const props = defineProps<{
    task:any,
    attempt:any
}>()

const attemptAnswer = ref<number | null>(
  props.task?.answer?.id
)
const http = useHttp<{ answer: number | null, taskVariantId:number }>({
    answer: null,
    taskVariantId:props.task.id
})

const send = async () => {
    http.answer = attemptAnswer.value
    http.put(`/exam-attempts/${props.attempt.id}/answers`,{
        onSuccess:(response) => {
            
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
    >
        <template #answers>
            <div class="mb-4 flex flex-column">
                <v-radio-group 
                    v-model="attemptAnswer"
                >      
                    <v-radio 
                        v-for="answer in props.task?.answers"
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
        <template #saved v-if="attemptAnswer">
            <div>sdf</div>
        </template>
        
    </base-task>
    
</template>