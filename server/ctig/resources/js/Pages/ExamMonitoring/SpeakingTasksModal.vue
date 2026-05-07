<script setup lang="ts">
import BaseDialog from '@/components/BaseComponents/BaseDialog/BaseDialog.vue';
import { computed, onMounted, ref } from 'vue';
import { router, useHttp } from '@inertiajs/vue3';
import AppPrimaryButton from '@/components/UI/AppPrimaryButton/AppPrimaryButton.vue';
import BaseEmptyState from '@/components/BaseComponents/BaseEmptyState/BaseEmptyState.vue';
import { Enrollment } from '@/interfaces/Enrollment';
import { AttemptMonitoring } from '@/interfaces/Attempt';
import { AttemptAnswer } from '@/interfaces/Task';
import AppTooltip from '@/components/UI/AppTooltip/AppTooltip.vue';
import AttemptCheckingPanel from '@/components/Attempt/AttemptCheckingPanel.vue';
import TasksList from '../Attempt/Components/tasks/TasksList.vue';
import { useConfirmDialog } from '@/composables/useConfirmDialog';

const props = defineProps<{
    enrollment:Enrollment
}>()

const isOpen = defineModel<boolean>({default:false})
const attempt = ref<AttemptMonitoring | null>(null)
const speakingStarted = computed(() => props.enrollment?.attempt?.speakingStartedAt)
const checking = ref<boolean>(false)

const http = useHttp()

onMounted(() => {
    if(!speakingStarted.value) return
    getSpeaking()
})

const getSpeaking = () => {
    http.get(`/attempts/${props.enrollment.attempt?.id}/speaking`,{
        onSuccess:(response : any) => {
            attempt.value=response.data
        },
    })
}


const startHttp = useHttp()

const start = () => {
    startHttp.post(`/attempts/${props.enrollment.attempt?.id}/speaking/start`,{
        onSuccess:(response : any)=>{
            getSpeaking()
            props.enrollment.attempt = {... response.data}
        },
    })
}    

const finishHttp = useHttp()
const finish = async () => {
    const {confirmOpen} = useConfirmDialog()
    const ok = await confirmOpen('Завершить говорение? \n Задания больше не будут доступны')
    if(!ok) return
    finishHttp.post(`/attempts/${props.enrollment.attempt?.id}/speaking/finish`,{
        onSuccess:()=> {
            router.reload()
            checking.value = true
        }
    })
}

const update = (value: AttemptAnswer) => {
    if(!attempt.value) return
    const task = attempt.value?.tasks.find(t => t.attemptAnswer.id === value.id)
    if(!task) return
    task.attemptAnswer = {...value}
}
</script>

<template>
    <BaseDialog
        fullscreen
        v-model="isOpen"
        :loading="http.processing || startHttp.processing"
        @before-close="(close) => close()"
        :title="`Говорение ( ${enrollment.foreignNational.fullName}, ${enrollment.foreignNational.fullPassport} )`"
    >
        <AttemptCheckingPanel 
            v-if="speakingStarted && attempt && checking"
            :attempt="attempt"
            @rated="update"
        />
        <div v-else-if="attempt" class="flex justify-center">
            <TasksList 
                :attempt="attempt"
            />
        </div>
        <BaseEmptyState 
            v-else
            action-text="Начать"
            icon="mdi-account-clock-outline"
            title="Говорение не начато"
            text="Нажмите, чтобы начать"
            @click:action="start"
        />

        <template #actions>
            <AppPrimaryButton
                v-if="!checking"
                text="Завершить"
                @click="finish"
            />
            <div v-if="checking" class="flex gap-2 items-center">
                <AppTooltip
                    text="Оценить задания возможно будет после завершения"
                />
                <AppPrimaryButton
                    text="Завершить"
                    @click="isOpen = false"
                />
            </div>
        </template>
    </BaseDialog>
</template>