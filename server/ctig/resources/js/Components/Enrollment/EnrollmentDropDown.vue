<script setup lang="ts">
import BaseThreeDotDropdown from '@components/BaseComponents/BaseThreeDotDropdown/BaseThreeDotDropdown.vue';
import { useExamStatus } from '@/composables/useExamStatus';
import PaymentChange from './PaymentChange.vue';
import { Enrollment } from '@/interfaces/Enrollment';
import BaseListItem from '../BaseComponents/BaseList/BaseListItem.vue';
import { ExamActionsPermissions } from '@/interfaces/Exam';

const props = defineProps<{
    enrollment:Enrollment,
    permissions:ExamActionsPermissions
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
            v-if="permissions.enrollments.payment"
            :disabled="isPaymentChangeDisabled"
        />
        <BaseListItem 
            title="Заявление" 
            v-if="permissions.enrollments.statement"
            @click="() => download('statements')"
        />
    </BaseThreeDotDropdown>
</template>