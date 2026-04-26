<script setup lang="ts">
import { Enrollment } from '@/interfaces/Interfaces';
import AppListDropDownItem from '@components/UI/AppListDropDownItem/AppListDropDownItem.vue';
import { useHttp } from '@inertiajs/vue3';
import { useConfirmationOptionsDialog } from '@/composables/useConfirmationOptionsDialog';

const props = defineProps<{
    enrollment: Enrollment
}>()

const changePayment = async () => {
    const {open} = useConfirmationOptionsDialog()
    const action = props.enrollment.hasPayment ?  'Отменить' : 'Подтвердить'
    const ok = await open(`${action} оплату ${props.enrollment.foreignNational.fullName}`)
    if(!ok) return
    const http = useHttp()
    props.enrollment.isLoading = true
    http.put(`/enrollments/${props.enrollment.id}/payment`,{
        onSuccess:() => {
            props.enrollment.hasPayment = !props.enrollment.hasPayment
        },
        onFinish:() => {
            props.enrollment.isLoading = false
        }
    })
}
</script>

<template>
    <AppListDropDownItem 
        @click="changePayment"
        :title="enrollment.hasPayment ? 'Отменить оплату' : 'Подтвердить оплату'"
    />
</template>