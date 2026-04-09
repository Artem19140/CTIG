<script setup lang="ts">
import ThreeDotDropdown from '../../../../Components/ThreeDotDropdown/ThreeDotDropdown.vue';
import AppListDropDownItem from '../../../../Components/AppListDropDownItem/AppListDropDownItem.vue';
import { useConfirmDialog } from '../../../../Composables/useConfirmDialog';
import { router, useHttp } from '@inertiajs/vue3';
import { useModals } from '../../../../Composables/useModals';
import { Enrollment, Exam } from '../../../../interfaces/interfaces';

const props = defineProps<{
    enrollment:Enrollment,
    exam:Exam
}>()
const {confirmOpen} = useConfirmDialog()

const download = () => {
    window.open(`enrollments/${props.enrollment.id}/statements`)
}

const reschedule = () => {
    const {open} = useModals()
    open('reschedule', {enrollment:props.enrollment, examTypeId:props.exam.examTypeId})
}


const cancell = async () => {
    const ok = await confirmOpen('Отменить запись на экзамен?')
    if(!ok){
        return
    }
    router.delete(`enrollments/${props.enrollment.id}`,{
        onSuccess: (page) =>{
            const success = page.flash.success
            if(!success) return
        }
    })
}
// const http = useHttp()
const changePayment = async () => {
    const action = props.enrollment.hasPayment ?  'Отменить' : 'Подтвердить'
    const ok = await confirmOpen(`${action} оплату ${props.enrollment.foreignNational.fullName}`)
    if(!ok) return
    props.enrollment.isLoading = true
    router.put(`enrollments/${props.enrollment.id}/payment`,{},{
        onSuccess:() => {
            props.enrollment.hasPayment = !props.enrollment.hasPayment
        },
        onFinish:() => {
            props.enrollment.isLoading = false
        }
    })
}

//const examNotBegin = computed(() => props.exam?.isGoing || props.exam?.isPast) :disabled="examNotBegin"
</script>

<template>
    <ThreeDotDropdown>
        <AppListDropDownItem 
            title="Скачать заявление" 
            @click="download"
        />
        <AppListDropDownItem 
            title="Перенести запись" 
            @click="reschedule"
            
        />
        <AppListDropDownItem 
            :title="enrollment.hasPayment ?  'Отменить оплату' : 'Подтвердить оплату'" 
            @click="changePayment"
        />

        <AppListDropDownItem 
            title="Отменить запись" 
            @click="cancell"
            color="text-red"
        />
    </ThreeDotDropdown>
</template>