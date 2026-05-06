<script setup lang="ts">
import SidePanel from './Components/SidePanel.vue';
import TasksList from './Components/tasks/TasksList.vue';
import { useConfirmDialog } from '@composables/useConfirmDialog';
import { useForm } from '@inertiajs/vue3';
import { useAttempt } from '@/composables/useAttempt';
import BaseLayout from '@/layouts/BaseLayout.vue';
import BaseDrawer from '@/components/BaseComponents/BaseDrawer/BaseDrawer.vue';
import { Attempt } from '@/interfaces/Attempt';
import AppPrimaryButton from '@/components/UI/AppPrimaryButton/AppPrimaryButton.vue';

const props = defineProps<{
    attempt:{
        data:Attempt
    }
}>()

const {examAttempt, audioPlaying} = useAttempt()

examAttempt.value = props.attempt.data

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
            <BaseDrawer
                location="right"
                permanent
                width="300"
            >
                <SidePanel :attempt="examAttempt"/>
            </BaseDrawer>
        </template>
        <v-container class="flex flex-column items-center gap-10">
            <TasksList 
                v-if="examAttempt" 
                :attempt="examAttempt"
            />
            <AppPrimaryButton
                text="Завершить"
                @click="finish"
                :disabled="form.processing"
                :loading="form.processing"
            />
                

        </v-container>
    
    </BaseLayout>
</template>