<script setup lang="ts">
import { useForm, useHttp } from '@inertiajs/vue3';
import BaseDialog from '@components/BaseComponents/BaseDialog/BaseDialog.vue';
import AppPrimaryButton from '@components/UI/AppPrimaryButton/AppPrimaryButton.vue';
import { Enrollment } from '@interfaces/Interfaces';
import ExamEnrollment from '../ExamEnrollment.vue';


const props = defineProps<{
    enrollment:Enrollment,
    examTypeId:number,
    onRechedule?: () => void
}>()

const isOpen = defineModel<boolean>({default:false})
const form = useForm({
    toExamId:null
})

const rechedule = () => {
    form.post(`/enrollments/${props.enrollment.id}/reschedule`,{
        onSuccess:(page :any) =>{
            if(page.flash.redirectUrl){
                window.open(page.flash.redirectUrl)
                if(props.onRechedule){
                    props.onRechedule()
                }
                
                form.resetAndClearErrors()
                isOpen.value=false
            }
            
        }
    })
}
const http = useHttp()

</script>

<template>
    <BaseDialog
        width="500"
        title="Перенос записи на экзамен"
        v-model="isOpen"
        @before-close="(done) => done()"
    >
        <ExamEnrollment 
            :exam-type-id="examTypeId"
            v-model="form.toExamId"
            :foreign-national-id="enrollment.foreignNational.id"
        />
        <template #actions>
            <AppPrimaryButton
                :loading="form.processing"
                :disabled="!form.toExamId"
                text="Перенести"
                @click="rechedule"
            />
        </template>
    </BaseDialog>
</template>