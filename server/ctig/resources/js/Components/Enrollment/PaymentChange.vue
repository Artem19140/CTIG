<script setup lang="ts">
import { Enrollment } from '@/interfaces/Interfaces';
import AppListDropDownItem from '@components/UI/AppListDropDownItem/AppListDropDownItem.vue';
import { useConfirmDialog } from '@/composables/useConfirmDialog';
import { router } from '@inertiajs/vue3';

const props = defineProps<{
    enrollment: Enrollment
}>()

const changePayment = async () => {
    const {confirmOpen} = useConfirmDialog()
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
</script>

<template>
    <AppListDropDownItem 
        @click="changePayment"
        :title="enrollment.hasPayment ? 'Отменить оплату' : 'Подтвердить оплату'"
    />
</template>