<script setup lang="ts">
import { useConfirmDialog } from '@/composables/useConfirmDialog';
import { Enrollment } from '@/interfaces/Interfaces';
import BaseThreeDotDropdown from '@components/BaseComponents/BaseThreeDotDropdown/BaseThreeDotDropdown.vue';
import AppListDropDownItem from '@components/UI/AppListDropDownItem/AppListDropDownItem.vue';
import { useHttp } from '@inertiajs/vue3';
import { useExamStatus } from '@/composables/useExamStatus';
import PaymentChange from './PaymentChange.vue';

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
    const http = useHttp()
    http.delete(`enrollments/${props.enrollment.id}`,{
        onSuccess: () =>{
            emit('cancell', props.enrollment)
        }
    })
}
const {isGoing, isFinished, isCancelled} = useExamStatus(props.enrollment.exam)
const isPaymentChangeDisabled  = isFinished.value || isCancelled.value
const isCancellationDisabled  = isFinished.value || isCancelled.value || isGoing.value
</script>

<template>
    
    <BaseThreeDotDropdown>
        <PaymentChange 
            :enrollment="enrollment"
            :disabled="isPaymentChangeDisabled"
        />
        <AppListDropDownItem 
            title="Заявление" 
            @click="() => download('statements')"
        />
        <AppListDropDownItem 
            title="Отменить" 
            @click="cancell"
            :disabled="isCancellationDisabled"
            color="text-red"
        />
    </BaseThreeDotDropdown>
</template>