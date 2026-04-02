<script setup lang="ts">
import { onMounted, ref } from 'vue';
import BaseDialog from '../../Components/BaseDialog/BaseDialog.vue';
import AddButton from '../../Components/AddButton/AddButton.vue';
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
            <AddButton 
                text="Сохранить"
            />
        </template>
    </BaseDialog>
</template>