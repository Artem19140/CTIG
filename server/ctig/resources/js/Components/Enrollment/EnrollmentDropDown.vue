<script setup lang="ts">
import { useConfirmDialog } from '@/composables/useConfirmDialog';
import { useModals } from '@/composables/useModals';
import { Enrollment } from '@/interfaces/Interfaces';
import BaseThreeDotDropdown from '@components/BaseComponents/BaseThreeDotDropdown/BaseThreeDotDropdown.vue';
import AppListDropDownItem from '@components/UI/AppListDropDownItem/AppListDropDownItem.vue';
import { router, useForm } from '@inertiajs/vue3';
import { useExamStatus } from '@/composables/useExamStatus';
import { computed } from 'vue';

const props = defineProps<{
    enrollment:Enrollment
}>()

const emit = defineEmits<{
    (e:'cancell', value:Enrollment):void,
    (e:'reschedule', value:Enrollment):void
}>()

const {confirmOpen} = useConfirmDialog()

const download = (document : string) => {
    window.open(`enrollments/${props.enrollment.id}/${document}`)
}

const cancell = async () => {
    const ok = await confirmOpen('Отменить запись на экзамен?')
    if(!ok){
        return
    }
    const form = useForm()
    form.delete(`enrollments/${props.enrollment.id}`,{
        onSuccess: (page) =>{
            console.log(page.flash.success)
            if(page.flash.success){
                emit('cancell', props.enrollment)
            }
        }
    })
}

const changePayment = async () => {
    const action = props.enrollment.hasPayment ?  'Отменить' : 'Подтвердить'
    const ok = await confirmOpen(`${action} оплату ${props.enrollment.foreignNational.fullName}`)
    if(!ok) return
    props.enrollment.isLoading = true
    router.put(`/enrollments/${props.enrollment.id}/payment`,{},{
        onSuccess:() => {
            props.enrollment.hasPayment = !props.enrollment.hasPayment
        },
        onFinish:() => {
            props.enrollment.isLoading = false
        }
    })
}
const {isGoing, isFinished, isPending, isCancelled} = useExamStatus(props.enrollment.exam)
const hasAttempt = computed(() => props.enrollment.attempt === null) 
const isPaymentChangeDisabled  = isFinished.value || isCancelled.value || !hasAttempt.value
const isCancellationDisabled  = isFinished.value || isCancelled.value || isGoing.value || hasAttempt.value
</script>

<template>
    
    <BaseThreeDotDropdown>
        <AppListDropDownItem 
            title="Заявление" 
            @click="() => download('statements')"
        />
        <AppListDropDownItem 
            :title="enrollment.hasPayment ?  'Отменить оплату' : 'Подтвердить оплату'" 
            :disabled="isPaymentChangeDisabled "
            @click="changePayment"
        />
        <AppListDropDownItem 
            title="Отменить" 
            @click="cancell"
            :disabled="isCancellationDisabled"
            color="text-red"
        />
    </BaseThreeDotDropdown>
</template>