<script setup lang="ts">
import {  ref } from 'vue';
import BaseDialog from '../../../Components/BaseDialog/BaseDialog.vue';
import ExamEnrollment from '../../Exam/Components/ExamEnrollment.vue';
import PrimaryButton from '../../../Components/PrimaryButton/PrimaryButton.vue';
import { ForeignNational } from '../../../interfaces/interfaces';
import {  useForm } from '@inertiajs/vue3';
import { useAlert } from '../../../Composables/useAlert';
import { useConfirmDialog } from '../../../Composables/useConfirmDialog';

const props = defineProps<{
    foreignNational: ForeignNational | null
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
            console.log(page.flash)
            if(page.flash.redirectUrl){
                window.open(String(page.flash.redirectUrl))
                isOpen.value=false
                form.resetAndClearErrors()
            }
        }
    })
}

const form = useForm({
    foreignNationalId:props.foreignNational?.id ?? null
})

const {confirmOpen} = useConfirmDialog()

const examId = ref<number | null>(null)
const canClose  = async (fn: () => void) => {
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
        @before-close="(done) => canClose(done)"
    >
        <ExamEnrollment 
            v-model="examId"
            :foreignNational-id="foreignNational?.id"
        />
        <template #actions>
            <PrimaryButton
                @click="enroll"
                text="Записать"
                :loading="form.processing"
                :disabled="form.processing || !examId"
            />
        </template>

    </BaseDialog>
</template>