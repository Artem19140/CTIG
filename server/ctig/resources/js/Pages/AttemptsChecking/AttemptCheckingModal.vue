<script setup lang="ts">
import BaseDialog from '../../Components/BaseDialog/BaseDialog.vue';
import { useAttemptCheckingModal } from '../../Composables/modalWindows/useAttemptCheckingModal';
import SingleChoiceTask from '../Attempt/Components/tasks/SingleChoiceTask.vue';

const {isOpen, close, tasks, loading, attemptId} = useAttemptCheckingModal()


const canClose = (fn:  ()  => void) =>{
    close()
}
</script>

<template>
    <BaseDialog 
        title="Проверка"
        width="800"
        v-model="isOpen"
        @before-close="(done) => canClose(done)"
    >
    <!-- <pre>
        {{ tasks }}
    </pre> -->
        <SingleChoiceTask
            v-for="task in tasks?.data"
            :task="task.task"
            :attempt="attemptId"
        />
        
    
        
        <template #actions="{close}">
            <v-btn>Сохранить</v-btn>
            <v-btn @click="close">Закрыть</v-btn>
        </template>
    </BaseDialog>
</template>