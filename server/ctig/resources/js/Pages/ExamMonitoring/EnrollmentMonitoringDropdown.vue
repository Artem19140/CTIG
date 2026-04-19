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

const props = defineProps<{ 
    enrollment:Enrollment,
    exam : Exam
}>()

const promptDialog = usePromptDialog()
const ban = async () => {
    const res = await promptDialog.open(`Укажите причину снятия ${props.enrollment.foreignNational.fullName} с экзамена`)
    if(!res){
        return
    }
    const loadingSnack = useLoadingSnackbar()
    loadingSnack.open('Идет аннулирование')
    const form = useForm({
        banReason : res
    })
    form.put(`/attempts/${props.enrollment.attempt?.id}/ban`, 
    {
        onFinish:()=> {
            loadingSnack.close()
        }
    }
    )
    
}

const getSpeakingTasks = () => {

    alert('Тут будет устная часть')
}
const isBanned = computed(() => props.enrollment.attempt?.status === 'banned')
const { isCancelled, isFinished, isGoing } = useExamStatus(props.exam)

const banDisabled = isCancelled.value || isFinished.value || !isGoing.value || isBanned.value
const getSpeakingDisabled = !props.exam?.hasSpeakingTasks
const changePaymentDisabled = isCancelled.value || isFinished.value
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
            title="Устная часть" 
            @click="getSpeakingTasks"
        />
        <AppListDropDownItem    
            color="text-red" 
            :disabled="banDisabled"
            title="Снять с экзамена" 
            @click="ban"
        />
    </BaseThreeDotDropdown>
</template>