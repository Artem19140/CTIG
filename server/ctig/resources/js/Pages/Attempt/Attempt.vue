<script setup lang="ts">
import SidePanel from './Components/SidePanel.vue';
import TasksList from './Components/tasks/TasksList.vue';
import { useConfirmDialog } from '../../Composables/useConfirmDialog';
import { useForm } from '@inertiajs/vue3';
import ConfirmDialog from '../../Components/ConfirmDialog/ConfirmDialog.vue';

const props = defineProps<{
    attempt:any,
    tasks: any
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
            {{ attempt }}
            <v-container class="flex flex-column gap-10 items-center">
                <TasksList :tasks="tasks.data|| []" :attempt="attempt?.data" />

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
            <SidePanel :attempt="attempt.data" :tasks="tasks.data"/>
        </v-navigation-drawer>
    </v-app>
    <ConfirmDialog />
</template>