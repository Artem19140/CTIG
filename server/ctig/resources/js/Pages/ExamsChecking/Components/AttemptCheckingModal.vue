<script setup lang="ts">
import TasksList from '@pages/Attempt/Components/tasks/TasksList.vue';
import { useHttp } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import AttemptCheckingSidePanel from './AttemptCheckingSidePanel.vue';
import BaseDialog from '@/components/BaseComponents/BaseDialog/BaseDialog.vue';
import { AttemptAnswer } from '@/interfaces/Task';
import AppPrimaryButton from '@/components/UI/AppPrimaryButton/AppPrimaryButton.vue';
import { Attempt } from '@/interfaces/Attempt';
import TaskCheckingList from '@/pages/Attempt/Components/tasks/TaskCheckingList.vue';

const isOpen = defineModel<boolean>({default:false})

const props = defineProps<{
    attemptId:number | null,
    onFinishChecking:(attempt : any) => void
}>()

const attempt = ref<Attempt | null>(null)

const scrollToTask = (id: number) => {
  const el = document.getElementById(`task-${id}`)
  el?.scrollIntoView({
    behavior: 'smooth',
    block: 'start'
  })
}

const update = (value:AttemptAnswer) => {
    if(!attempt.value) return
    const task = attempt.value?.tasks.find(t => t.attemptAnswer.id === value.id)
    if(!task) return
    task.attemptAnswer = value
}

const http = useHttp()

const getAttemptTasks = () => {
    http.get(`/attempts/${props.attemptId}/checking/tasks`,{
        onSuccess:(response :any) => {
            attempt.value = response.data
        }
    })
}

const finishChecking = () => {
    http.post(`/attempts/${props.attemptId}/checking/finish`,{
        onSuccess:(response:any)=>{
            props.onFinishChecking(response.attempt)
            isOpen.value = false
        }
    })
}

onMounted(() => {
    getAttemptTasks()
})

</script>

<template>
    <BaseDialog
        v-model="isOpen"
        fullscreen
        :error="!http"
        :loading="http.processing"
        :onRetry="getAttemptTasks"
        @before-close="(close) => close()"
    >
        <h1 v-if="http.processing">Загрузка</h1>
        <div class="flex gap-10 items-start">
            <div class="flex-shrink-0 sticky top-0 self-start">
                <AttemptCheckingSidePanel
                    :tasks="attempt?.tasks" 
                    @select="scrollToTask"
                />
            </div>
            <div class="mx-auto">
                <TaskCheckingList
                    v-if="attempt"
                    @update-answer="update"
                    :attempt="attempt"
                    :checking="true"
                />
                <!-- <TasksList 
                    @update-answer="update"
                    v-if="attempt"
                    class="flex-grow"
                    :attempt="attempt"
                    :checking="true"
                /> -->
            </div>
        </div>
        
        <template #actions>
            <AppPrimaryButton 
                :loading="http.processing"
                :disabled="http.processing"
                @click="finishChecking"
                text="Завершить проверку"
            />
        </template>
    </BaseDialog>
</template>