<script setup lang="ts">
import AppAddButton from '@components/UI/AppAddButton/AppAddButton.vue';
import TasksList from '@pages/Attempt/Components/tasks/TasksList.vue';
import { useHttp } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import AttemptCheckingSidePanel from './AttemptCheckingSidePanel.vue';
import BaseDialog from '@/components/BaseComponents/BaseDialog/BaseDialog.vue';
import { Attempt } from '@/interfaces/Interfaces';

const isOpen = defineModel<boolean>({default:false})

const props = defineProps<{
    attemptId:number | null
}>()

const attempt = ref<Attempt | null>(null)

const beforeClose = (fn:  ()  => void) =>{
    fn()
}
watch(() => props.attemptId, (id) =>{
    if(!props.attemptId) return
    http.get(`/attempts/${id}/checking/tasks`,{
        onSuccess:(response :any) => {
            attempt.value = response.data
        }
    })
})

const http = useHttp()
const scrollToTask = (id: number) => {
  const el = document.getElementById(`task-${id}`)
  el?.scrollIntoView({
    behavior: 'smooth',
    block: 'start'
  })
}
</script>

<template>
    <BaseDialog
        v-model="isOpen"
        fullscreen
        :loading="http.processing"
        @before-close="(close) => beforeClose(close)"
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
                <TasksList 
                    v-if="attempt"
                    class="flex-grow"
                    :attempt="attempt"
                    :checking="true"
                />
            </div>
        </div>
        
        <template #actions>
            
        </template>
        <template #bottom-info>
            Проверено 4 из 5
        </template>
    </BaseDialog>
</template>