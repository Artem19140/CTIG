<script setup lang="ts">
import { router, useForm, useHttp } from '@inertiajs/vue3';
import BaseDialog from '../../../../Components/BaseDialog/BaseDialog.vue';
import PrimaryButton from '../../../../Components/PrimaryButton/PrimaryButton.vue';
import {  EmitFn, onMounted, ref } from 'vue';
import { Enrollment, Exam } from '../../../../interfaces/interfaces';
import AppAutocomplete from '../../../../Components/AppAutocomplete/AppAutocomplete.vue';


const props = defineProps<{
    enrollment:Enrollment,
    examTypeId:number,
    onRechedule: () => void
}>()

const isOpen = defineModel<boolean>({default:false})
const exams = ref<Exam[] | []>([])
const form = useForm({
    toExamId:null
})

const rechedule = () => {
    form.post(`/enrollments/${props.enrollment.id}/reschedule`,{
        onSuccess:(page :any) =>{
            if(page.flash.redirectUrl){
                window.open(page.flash.redirectUrl)
                props.onRechedule()
                form.resetAndClearErrors()
                isOpen.value=false
            }
            
        }
    })
}
const http = useHttp()

onMounted(() => {
    http.get(`/exams/available?examTypeId=${props.examTypeId}&foreignNationalId=${props.enrollment.foreignNational.id}`,{ 
        onSuccess:(response:any) => {
            exams.value = response
        }
    })
})
</script>

<template>
    <BaseDialog
        width="500"
        title="Перенос записи на экзамен"
        v-model="isOpen"
        @before-close="(done) => done()"
    >
        <AppAutocomplete
            v-model="form.toExamId"
            :items="exams"
            :disabled="http.processing"
            :loading="http.processing"
            item-title="beginTime"
            item-value="id"
            label="Дата и время"
        />
        <template #actions>
            <PrimaryButton
                :loading="form.processing"
                :disabled="!form.toExamId"
                text="Перенести"
                @click="rechedule"
            />
        </template>
    </BaseDialog>
</template>