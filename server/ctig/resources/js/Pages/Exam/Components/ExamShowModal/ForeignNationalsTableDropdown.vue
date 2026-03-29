<script setup lang="ts">
import ThreeDotDropdown from '../../../../Components/ThreeDotDropdown/ThreeDotDropdown.vue';
import AppListDropDownItem from '../../../../Components/AppListDropDownItem/AppListDropDownItem.vue';
import { useConfirmDialog } from '../../../../Composables/useConfirmDialog';
import { router, useHttp } from '@inertiajs/vue3';
import { useModals } from '../../../../Composables/useModals';
import { computed } from 'vue';
import { Exam } from '../../../../interfaces/interfaces';

const props = defineProps<{
    exam:Exam,
    foreignNational:any
}>()

const foreignNationalId = computed(() => props.foreignNational.id)

const download = () => {
    window.open(`foreign-nationals/${foreignNationalId.value}/application-forms?examId=${props.exam?.id}`)
}

const transfer = () => {
    const {open} = useModals()
    open('transfer', {foreignNational:props.foreignNational, oldExam: props.exam})
}
const {confirmOpen} = useConfirmDialog()

const cancell = async () => {
    const ok = await confirmOpen('Отменить запись на экзамен?')
    if(!ok){
        return
    }
    router.delete(`exams/${props.exam?.id}/foreign-nationals/${foreignNationalId.value}`,{
        onSuccess: (page) =>{
            const success = page.flash.success
            if(!success) return
            const index = props.exam.foreignNationals.findIndex(
                i => i.id === foreignNationalId.value
            )
            if (index === -1) return
            props.exam.foreignNationals.splice(index, 1)
            
            
        }
    })
}
const http = useHttp()
const changePayment = async () => {
    const action = props.foreignNational.hasPayment ?  'Отменить' : 'Подтвердить'
    const ok = await confirmOpen(`${action} оплату ${props.foreignNational.fullName}`)
    if(!ok) return
    props.foreignNational.isLoading = true
    http.put(`/exams/${props.exam.id}/foreign-nationals/${foreignNationalId.value}/payment/change`,{
        onSuccess:() => {
            props.foreignNational.hasPayment = !props.foreignNational.hasPayment
        },
        onFinish:() => {
            props.foreignNational.isLoading = false
        }
    })
}

const examNotBegin = computed(() => props.exam?.isGoing || props.exam?.isPast)
</script>

<template>
    <ThreeDotDropdown>
        <AppListDropDownItem 
            title="Скачать заявление" 
            @click="download"
        />
        <AppListDropDownItem 
            title="Перенести запись" 
            @click="transfer"
            :disabled="examNotBegin"
        />
        <AppListDropDownItem 
            :title="foreignNational.hasPayment ?  'Отменить оплату' : 'Подтвердить оплату'" 
            @click="changePayment"
            :disabled="examNotBegin"
        />

        <AppListDropDownItem 
            :disabled="examNotBegin"
            title="Отменить запись" 
            @click="cancell"
            color="text-red"
        />
    </ThreeDotDropdown>
</template>