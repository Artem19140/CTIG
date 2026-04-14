<script setup lang="ts">
import { onMounted, ref } from 'vue';
import BaseDialog from '@components/BaseComponents/BaseDialog/BaseDialog.vue';
import AppAddButton from '@components/UI/AppAddButton/AppAddButton.vue';
import TasksList from '../Attempt/Components/tasks/TasksList.vue';
import { useHttp } from '@inertiajs/vue3';

const isOpen = defineModel<boolean>({default:false})

const props = defineProps<{
    attemptId:number
}>()

const tasks = ref()

const beforeClose = (fn:  ()  => void) =>{
    fn()
}

const http = useHttp()
onMounted(async() => {
    http.get(`/attempts/${props.attemptId}/checking/tasks`,{
        onSuccess:(response :any) => {
            tasks.value = response.data
        }
    })
})
</script>

<template>
    <BaseDialog 
        title="Проверка"
        width="1000"
        height="1000"
        v-model="isOpen"
        :loading="http.processing"
        @before-close="(done) => beforeClose(done)"
    >
        
        Здесь будут задания
        <TasksList :tasks="tasks?.data" />
    
        
        <template #actions>
            <AppAddButton 
                text="Сохранить"
            />
        </template>
    </BaseDialog>
</template>