<script setup lang="ts">
import { useHttp } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import BaseTask from './BaseTask.vue';
import RenderBlocks from './TaskContentBlocks/RenderBlocks.vue';
import { Task } from '@/interfaces/Task';
import { Attempt } from '@/interfaces/Attempt';

const props = defineProps<{
    task:Task,
    attempt:Attempt,
    checking?:boolean
}>()

const emit = defineEmits<{
    (e:'updateAnswer', value: {
        task:Task,
        answer:any
    }):void
}>()

const attemptAnswer = ref<number | null>(
  props.task.attemptAnswer?.answer?.id
)

const http = useHttp<{ answer: number | null}>({
    answer: null
})

const send = async () => {
    emit('updateAnswer', {
        task:props.task,
        answer: attemptAnswer.value
    })
}

watch(attemptAnswer, () => {
    send()
})
</script>

<template>
    <base-task
        @retry="send"
        :task="task"
        :checking="checking"
        :attempt="attempt"
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
                        :readonly="checking"
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