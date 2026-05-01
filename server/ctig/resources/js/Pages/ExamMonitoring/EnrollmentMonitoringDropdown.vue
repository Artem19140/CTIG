<script setup lang="ts">
import AppListDropDownItem from '@components/UI/AppListDropDownItem/AppListDropDownItem.vue';
import { usePromptDialog } from '@composables/usePromptDialog';
import BaseThreeDotDropdown from '@components/BaseComponents/BaseThreeDotDropdown/BaseThreeDotDropdown.vue';
import { useLoadingSnackbar } from '@composables/useLoadingSnackBar';
import { router, useHttp } from '@inertiajs/vue3';
import { useExamStatus } from '@/composables/useExamStatus';
import { computed } from 'vue';
import PaymentChange from '@/components/Enrollment/PaymentChange.vue';
import { useModals } from '@/composables/useModals';
import { Enrollment } from '@/interfaces/Enrollment';
import { ExamMonitoring } from '@/interfaces/Exam';

const props = defineProps<{ 
    enrollment:Enrollment,
    exam : ExamMonitoring
}>()

const promptDialog = usePromptDialog()

const ban = async () => {
    if(!props.enrollment.attempt?.id) return
    const res = await promptDialog.open(`Укажите причину аннулирования попытки ${props.enrollment.foreignNational.fullName}`)
    if(!res){
        return
    }
    const loadingSnack = useLoadingSnackbar()
    loadingSnack.open('Идет аннулирование')
    const http = useHttp({
        banReason : res
    })
    http.put(`/attempts/${props.enrollment.attempt?.id}/ban`, {
        onSuccess(response, httpResponse) {
            router.reload()
        },
        onFinish:()=> {
            loadingSnack.close()
        }
    })
}

const modals = useModals()

const isBanned = computed(() => props.enrollment.attempt?.status === 'banned')

const { isCancelled, isFinished } = useExamStatus(props.exam)

const hasAttempt = computed(() => props.enrollment.attempt !== null)

const speakingFinished = computed(() => props.enrollment.attempt?.speakingFinishedAt !== null)

const changePaymentDisabled = computed(() => isCancelled.value || isFinished.value || hasAttempt.value)
console.log(hasAttempt.value)
</script>

<template>
    <BaseThreeDotDropdown>
        <PaymentChange  
            :disabled="changePaymentDisabled"
            :enrollment="enrollment"
        />
        <AppListDropDownItem 
            :disabled="!hasAttempt || speakingFinished"
            v-if="exam?.hasSpeakingTasks"
            title="Говорение" 
            @click="modals.open('speaking', {enrollment:props.enrollment})"
        />
        <AppListDropDownItem 
            title="Нарушения" 
            :disabled = "!hasAttempt"
            @click="modals.open('violation', {enrollment:props.enrollment})"
        />
        <AppListDropDownItem    
            color="text-red" 
            :disabled="!hasAttempt || isBanned"
            title="Аннулировать" 
            @click="ban"
        />
    </BaseThreeDotDropdown>
</template>