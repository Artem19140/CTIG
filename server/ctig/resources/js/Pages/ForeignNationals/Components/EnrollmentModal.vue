<script setup lang="ts">
import {  ref } from 'vue';
import BaseDialog from '@components/BaseComponents/BaseDialog/BaseDialog.vue';
import ExamEnrollment from '@components/Exam/ExamEnrollment.vue';
import AppPrimaryButton from '@components/UI/AppPrimaryButton/AppPrimaryButton.vue';
import { Enrollment, ForeignNational } from '@interfaces/Interfaces';
import {  useHttp } from '@inertiajs/vue3';
import { useConfirmDialog } from '@composables/useConfirmDialog';

const props = defineProps<{
    foreignNational: ForeignNational | null,
    examTypeId?:number | null,
    onEnroll:(enrollment:Enrollment) => void
}>()

const isOpen = defineModel<boolean>()

const enroll = async () => {
    http.post(`enrollments`,
    {
        onSuccess: (response:any) => {
            if(response.redirectUrl){
                isOpen.value=false
                props.onEnroll(response.enrollment)
                window.open(String(response.redirectUrl))
                http.resetAndClearErrors()
            }
        }
    })
}

const http = useHttp({
    foreignNationalId:props.foreignNational?.id ?? null,
    hasPayment:false,
    examId:null
})

const {confirmOpen} = useConfirmDialog()

const examId = ref<number | null>(null)

const beforeClose  = async (fn: () => void) => {
    if(examId.value){
        const ok = await confirmOpen('Отменить создание записи на экзамен?')
        if(!ok) return
    }
    examId.value=null
    fn()
}
</script>

<template>
    <BaseDialog 
        v-model="isOpen"
        width="500"
        title="Запись на экзамен"
        @before-close="(done) => beforeClose(done)"
    >
        <ExamEnrollment 
            v-model:exam-id="http.examId"
            v-model:has-payment="http.hasPayment"
            :foreignNational-id="foreignNational?.id"
        />
        <template #actions>
            <AppPrimaryButton
                @click="enroll"
                text="Записать"
                :loading="http.processing"
                :disabled="http.processing || !http.examId"
            />
        </template>
    </BaseDialog>
</template>