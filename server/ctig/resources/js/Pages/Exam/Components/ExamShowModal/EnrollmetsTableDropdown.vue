<script setup lang="ts">
import ThreeDotDropdown from '../../../../Components/ThreeDotDropdown/ThreeDotDropdown.vue';
import AppListDropDownItem from '../../../../Components/AppListDropDownItem/AppListDropDownItem.vue';
import { useConfirmDialog } from '../../../../Composables/useConfirmDialog';
import { router, useForm } from '@inertiajs/vue3';
import { useModals } from '../../../../Composables/useModals';
import { Enrollment, Exam } from '../../../../interfaces/interfaces';

const props = defineProps<{
    enrollment:Enrollment,
    exam:Exam
}>()

const emit = defineEmits<{
    (e:'cancell', value:Enrollment):void,
    (e:'reschedule', value:Enrollment):void
}>()

const {confirmOpen} = useConfirmDialog()

const download = (document : string) => {
    window.open(`enrollments/${props.enrollment.id}/${document}`)
}

const reschedule = () => {
    const {open} = useModals()
    open('reschedule', {
                            enrollment:props.enrollment, 
                            examTypeId:props.exam.examTypeId,
                            onRechedule: () => emit('reschedule', props.enrollment)
                        })
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
            title="Заявление" 
            @click="() => download('statements')"
        />
        <AppListDropDownItem 
            title="Перенести" 
            @click="reschedule"
            
        />
        <AppListDropDownItem 
            :title="enrollment.hasPayment ?  'Отменить оплату' : 'Подтвердить оплату'" 
            @click="changePayment"
        />

        <AppListDropDownItem 
            title="Отменить" 
            @click="cancell"
            color="text-red"
        />
    </ThreeDotDropdown>
</template>