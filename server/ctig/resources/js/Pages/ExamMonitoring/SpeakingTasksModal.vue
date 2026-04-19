<script setup lang="ts">
import BaseDialog from '@/components/BaseComponents/BaseDialog/BaseDialog.vue';
import { Attempt, Enrollment } from '@/interfaces/Interfaces';
import { onMounted, ref } from 'vue';
import TasksList from '../Attempt/Components/tasks/TasksList.vue';
import { useHttp } from '@inertiajs/vue3';
import AppPrimaryButton from '@/components/UI/AppPrimaryButton/AppPrimaryButton.vue';

const props = defineProps<{
    enrollment:Enrollment
}>()

const isOpen = defineModel<boolean>({default:false})
const attempt = ref<Attempt | null>(null)

const http = useHttp()
onMounted(() => {
    http.get(`/attempts/${props.enrollment.attempt?.id}/tasks/speaking`,{
        onSuccess:(response : any) => {
            attempt.value=response.data
        }
    })
})
const checking = ref<boolean>(false)
</script>

<template>
    <BaseDialog
        fullscreen
        v-model="isOpen"
        @before-close="(close) => close()"
        :title="`Говорение ( ${enrollment.foreignNational.fullName}, ${enrollment.foreignNational.fullPassport} )`"
    >
        <v-window>
            <v-window-item>
            <div class="flex flex-column items-center gap-8 mt-2 mb-2">
                <TasksList 
                    v-if="attempt" 
                    :attempt="attempt" 
                    :checking="checking"
                />
            </div>
         </v-window-item>
        </v-window>
        <template #actions>
            <AppPrimaryButton
                text="Продолжить"
                @click="checking=true"
                :disabled="checking"
            />
        </template>
    </BaseDialog>
</template>