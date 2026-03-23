<script setup lang="ts">
import { onMounted, ref } from 'vue';
import BaseDialog from '../../Components/BaseDialog/BaseDialog.vue';
import { useApi } from '../../Composables/Api/useApi';
import axios from 'axios';

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
            {{ tasks }}
        </pre>
        Здесь будут задания
        
    
        
        <template #actions>
            <v-btn>Сохранить</v-btn>
        </template>
    </BaseDialog>
</template>