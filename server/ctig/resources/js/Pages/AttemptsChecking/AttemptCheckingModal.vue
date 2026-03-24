<script setup lang="ts">
import { onMounted, ref } from 'vue';
import BaseDialog from '../../Components/BaseDialog/BaseDialog.vue';
import { useApi } from '../../Composables/Api/useApi';
import axios from 'axios';
import AddButton from '../../Components/AddButton/AddButton.vue';
import TasksList from '../Attempt/Components/tasks/TasksList.vue';

const isOpen = defineModel<boolean>({default:false})

const props = defineProps<{
    attemptId:number
}>()

const tasks = ref()

const {request, loading, error, data} = useApi()
const canClose = (fn:  ()  => void) =>{
    fn()
}

onMounted(async() => {
    await request(() => axios.get(`/attempts/${props.attemptId}/checking/tasks`))
    if(!error.value && data.value){
        tasks.value = data.value
    }
})
</script>

<template>
    <BaseDialog 
        title="Проверка"
        width="1000"
        height="1000"
        v-model="isOpen"
        :loading="loading"
        @before-close="(done) => canClose(done)"
    >
        <pre>
            {{ tasks?.data[0] }}
        </pre>
        Здесь будут задания
        <TasksList :tasks="tasks?.data" />
    
        
        <template #actions>
            <AddButton 
                text="Сохранить"
            />
        </template>
    </BaseDialog>
</template>