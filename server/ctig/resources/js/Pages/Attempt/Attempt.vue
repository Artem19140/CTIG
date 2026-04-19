<script setup lang="ts">
import SidePanel from './Components/SidePanel.vue';
import TasksList from './Components/tasks/TasksList.vue';
import { useConfirmDialog } from '@composables/useConfirmDialog';
import { useForm } from '@inertiajs/vue3';
import ConfirmDialog from '@components/ConfirmDialog/ConfirmDialog.vue';
import { Attempt } from '@/interfaces/Interfaces';

const props = defineProps<{
    attempt:{
        data:Attempt
    }
}>()

const form = useForm()

const finish = async () => {
    const {confirmOpen} = useConfirmDialog()
    const ok = await confirmOpen("Вы уверены, что хотите завершить попытку?")
    if(!ok) return
    form.put(`/exam-attempts/${props.attempt.data.id}/finish`)
}
</script>

<template>
    <v-app>
        <v-main style="background:#f1f5f9; min-height: 100vh;">
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
        </v-main>

        <v-navigation-drawer
            location="right"
            permanent
            width="300"
        >
            <SidePanel :attempt="attempt.data" :tasks="attempt.data.tasks"/>
        </v-navigation-drawer>
    </v-app>
    <ConfirmDialog />
</template>