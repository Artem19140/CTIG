<script setup lang="ts">
import { useConfirmDialog } from '@/composables/useConfirmDialog';
import BaseThreeDotDropdown from '@components/BaseComponents/BaseThreeDotDropdown/BaseThreeDotDropdown.vue';
import { useHttp } from '@inertiajs/vue3';
import { useExamStatus } from '@/composables/useExamStatus';
import PaymentChange from './PaymentChange.vue';
import { Enrollment } from '@/interfaces/Enrollment';
import { useAuth } from '@/composables/useAuth';
import { Roles } from '@/constants/Roles';
import BaseListItem from '../BaseComponents/BaseList/BaseListItem.vue';

const props = defineProps<{
    enrollment:Enrollment
}>()

const emit = defineEmits<{
    (e:'cancell', value:Enrollment):void,
    (e:'reschedule', value:Enrollment):void
}>()

const {confirmOpen} = useConfirmDialog()

const download = (document : string) => {
    window.open(`/enrollments/${props.enrollment.id}/${document}`)
}

const {isFinished, isCancelled} = useExamStatus(props.enrollment.exam)
const isPaymentChangeDisabled  = isFinished.value || isCancelled.value
const {can} = useAuth()
</script>

<template>
    
    <BaseThreeDotDropdown v-if="can([Roles.OPERATOR])">
        <PaymentChange 
            :enrollment="enrollment"
            :disabled="isPaymentChangeDisabled"
        />
        <BaseListItem 
            title="Заявление" 
            @click="() => download('statements')"
        />
    </BaseThreeDotDropdown>
</template>