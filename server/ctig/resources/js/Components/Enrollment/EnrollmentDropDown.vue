<script setup lang="ts">
import BaseThreeDotDropdown from '@components/BaseComponents/BaseThreeDotDropdown/BaseThreeDotDropdown.vue';
import { useExamStatus } from '@/composables/useExamStatus';
import PaymentChange from './PaymentChange.vue';
import { Enrollment } from '@/interfaces/Enrollment';

import BaseListItem from '../BaseComponents/BaseList/BaseListItem.vue';
import { ExamActionPermissions } from '@/interfaces/Exam';

const props = defineProps<{
    enrollment:Enrollment,
    permissions:ExamActionPermissions
}>()

const emit = defineEmits<{
    (e:'cancell', value:Enrollment):void,
    (e:'reschedule', value:Enrollment):void
}>()

const download = (document : string) => {
    window.open(`/enrollments/${props.enrollment.id}/${document}`)
}

const {isFinished, isCancelled} = useExamStatus(props.enrollment.exam)
const isPaymentChangeDisabled  = isFinished.value || isCancelled.value
</script>

<template>
    
    <BaseThreeDotDropdown ">
        <PaymentChange 
            :enrollment="enrollment"
            v-if="permissions.payment"
            :disabled="isPaymentChangeDisabled"
        />
        <BaseListItem 
            title="Заявление" 
            v-if="permissions.statement"
            @click="() => download('statements')"
        />
    </BaseThreeDotDropdown>
</template>