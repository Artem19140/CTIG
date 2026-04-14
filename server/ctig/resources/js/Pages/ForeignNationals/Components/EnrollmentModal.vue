<script setup lang="ts">
import {  ref } from 'vue';
import BaseDialog from '@components/BaseComponents/BaseDialog/BaseDialog.vue';
import ExamEnrollment from '../../Exam/Components/ExamEnrollment.vue';
import AppPrimaryButton from '@components/UI/AppPrimaryButton/AppPrimaryButton.vue';
import { ForeignNational } from '@interfaces/interfaces';
import {  useForm } from '@inertiajs/vue3';
import { useAlert } from '@composables/useAlert';
import { useConfirmDialog } from '@composables/useConfirmDialog';

const props = defineProps<{
    foreignNational: ForeignNational | null,
    examTypeId?:number | null
}>()

const isOpen = defineModel<boolean>()

const enroll = async () => {
    const {open} = useAlert()
    if(examId.value === null){
        open('Выберите время экзамена')
        return
    }
    if(!form.foreignNationalId){
        open('Неизвестная ошибка')
    }
    form.post(`exams/${examId.value}/foreign-nationals`,
    {
        onSuccess: (page) => {
            if(page.flash.redirectUrl){
                window.open(String(page.flash.redirectUrl))
                isOpen.value=false
                //emit push  в exams или куда там
                form.resetAndClearErrors()
            }
        }
    })
}

const form = useForm({
    foreignNationalId:props.foreignNational?.id ?? null,
    hasPayment:false
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
            v-model="examId"
            :examTypeId="examTypeId"
            :foreignNational-id="foreignNational?.id"
        />
        <v-checkbox
            label="Есть оплата"
            v-model="form.hasPayment"
            :error-messages="form.errors.hasPayment"
        />
        <template #actions>
            <AppPrimaryButton
                @click="enroll"
                text="Записать"
                :loading="form.processing"
                :disabled="form.processing || !examId"
            />
        </template>

    </BaseDialog>
</template>