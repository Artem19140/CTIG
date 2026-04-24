<script setup lang="ts">
import SidePanel from './Components/SidePanel.vue';
import TasksList from './Components/tasks/TasksList.vue';
import { useConfirmDialog } from '@composables/useConfirmDialog';
import { useForm } from '@inertiajs/vue3';
import { Attempt } from '@/interfaces/Interfaces';
import { useExamAttempt } from '@/composables/useExamAttempt';
import BaseLayout from '@/layouts/BaseLayout.vue';

const props = defineProps<{
    attempt:{
        data:Attempt
    }
}>()

const {examAttempt} = useExamAttempt()

examAttempt.value = props.attempt.data

console.log(examAttempt.value.tasks)

const form = useForm()

const finish = async () => {
    const {confirmOpen} = useConfirmDialog()
    const ok = await confirmOpen("Вы уверены, что хотите завершить попытку?")
    if(!ok) return
    form.put(`/attempts/${props.attempt.data.id}/finish`,{
        preserveState:true,
        preserveScroll:true
    })
}
</script>

<template>
    <BaseLayout>
    <template #drawer>
        <v-navigation-drawer
            location="right"
            permanent
            width="300"
        >
            <SidePanel :attempt="attempt.data" :tasks="attempt.data.tasks"/>
        </v-navigation-drawer>

    </template>
    <v-container class="flex flex-column gap-10 items-center">
        <TasksList :attempt="attempt.data" />
        <v-btn
            @click="finish"
            variant="flat"
            color="primary"
            :disabled="form.processing"
            :loading="form.processing"
        >
            Завершить
        </v-btn>
    </v-container>
    
    </BaseLayout>
</template>