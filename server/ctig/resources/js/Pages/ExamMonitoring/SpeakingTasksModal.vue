<script setup lang="ts">
import BaseDialog from '@/components/BaseComponents/BaseDialog/BaseDialog.vue';
import { Attempt, Enrollment } from '@/interfaces/Interfaces';
import { onMounted, ref } from 'vue';
import TasksList from '../Attempt/Components/tasks/TasksList.vue';
import { router, useHttp } from '@inertiajs/vue3';
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

const finishSpeaking = () => {
    http.put(`/attempts/${props.enrollment.attempt?.id}/speaking`,{
        onSuccess:()=>{
            isOpen.value = true
            router.reload()
        }
    })
}
</script>

<template>
    <BaseDialog
        fullscreen
        v-model="isOpen"
        @before-close="(close) => close()"
        :title="`Говорение ( ${enrollment.foreignNational.fullName}, ${enrollment.foreignNational.fullPassport} )`"
    >
        <div class="flex flex-column items-center gap-8 mt-2 mb-2">
            <TasksList 
                v-if="attempt" 
                :attempt="attempt" 
                :checking="checking"
            />
        </div>
        <template #actions>
            <AppPrimaryButton
                v-if="!checking"
                text="Продолжить"
                @click="checking = true"
                :disabled="checking"
            />
            <AppPrimaryButton
                v-else
                text="Оценить позднее"
                @click="finishSpeaking"
                :disabled="!checking"
            />
        </template>
    </BaseDialog>
</template>