<script setup lang="ts">
import AppListDropDownItem from '@components/UI/AppListDropDownItem/AppListDropDownItem.vue';
import { usePromptDialog } from '@composables/usePromptDialog';
import BaseThreeDotDropdown from '@components/BaseComponents/BaseThreeDotDropdown/BaseThreeDotDropdown.vue';
import { useLoadingSnackbar } from '@composables/useLoadingSnackBar';
import { useForm } from '@inertiajs/vue3';
import { Enrollment, Exam } from '@interfaces/Interfaces';
import { useExamStatus } from '@/composables/useExamStatus';
import { computed } from 'vue';
import PaymentChange from '@/components/Enrollment/PaymentChange.vue';
import { useModals } from '@/composables/useModals';

const props = defineProps<{ 
    enrollment:Enrollment,
    exam : Exam
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
    const form = useForm({
        banReason : res
    })
    form.put(`/attempts/${props.enrollment.attempt?.id}/ban`, {
        onFinish:()=> {
            loadingSnack.close()
        }
    })
}

const modals = useModals()

const isBanned = computed(() => props.enrollment.attempt?.status === 'banned')
const { isCancelled, isFinished, isGoing } = useExamStatus(props.exam)

const hasAttempt = computed(() => props.enrollment.attempt !== null)

const banDisabled = isCancelled.value || isFinished.value || !isGoing.value || isBanned.value || !hasAttempt.value
const getSpeakingDisabled =  !hasAttempt.value
const changePaymentDisabled = isCancelled.value || isFinished.value || hasAttempt.value
</script>

<template>
    <BaseThreeDotDropdown>
        <PaymentChange  
            :disabled="changePaymentDisabled "
            :enrollment="enrollment"
        />
        <AppListDropDownItem 
            :disabled="getSpeakingDisabled"
            v-if="exam?.hasSpeakingTasks"
            title="Говорение" 
            @click="modals.open('speaking', {enrollment:props.enrollment})"
        />
        <AppListDropDownItem 
            title="Нарушения" 
            @click="modals.open('violation', {enrollment:props.enrollment})"
        />
        <AppListDropDownItem    
            color="text-red" 
            :disabled="banDisabled"
            title="Аннулировать" 
            @click="ban"
        />
    </BaseThreeDotDropdown>
</template>