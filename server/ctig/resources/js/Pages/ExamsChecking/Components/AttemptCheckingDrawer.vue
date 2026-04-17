<script setup lang="ts">
import BaseDrawer from '@/components/BaseComponents/BaseDrawer/BaseDrawer.vue';

import AppAddButton from '@components/UI/AppAddButton/AppAddButton.vue';
import TasksList from '@pages/Attempt/Components/tasks/TasksList.vue';
import { useHttp } from '@inertiajs/vue3';
import { onMounted, ref, watch } from 'vue';
import AttemptCheckingSidePanel from './AttemptCheckingSidePanel.vue';

const isOpen = defineModel<boolean>({default:false})

const props = defineProps<{
    attemptId?:number | null
}>()

const tasks = ref()

const beforeClose = (fn:  ()  => void) =>{
    fn()
}
watch(() => props.attemptId, (id) =>{
    if(!props.attemptId) return
    http.get(`/attempts/${id}/checking/tasks`,{
        onSuccess:(response :any) => {
            tasks.value = response.data
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
    <BaseDrawer
        v-model="isOpen"
        :loading="http.processing"

        width="1200"
        @before-close="(close) => beforeClose(close)"
    >
        <h1 v-if="http.processing">Загрузка</h1>
        <div class="flex gap-10 items-start">
            <div class="flex-shrink-0 sticky top-0 self-start">
                <AttemptCheckingSidePanel
                    :tasks="tasks" 
                    @select="scrollToTask"
                />
            </div>
            <div class="mx-auto">
                <TasksList 
                    class="flex-grow"
                    :tasks="tasks"
                    :checking="true"
                />
            </div>
        </div>
        
        <template #actions>
            <AppAddButton  
                text="Сохранить"
            />
            
        </template>
        <template #bottom-info>
            Проверено 4 из 5
        </template>
    </BaseDrawer>
</template>