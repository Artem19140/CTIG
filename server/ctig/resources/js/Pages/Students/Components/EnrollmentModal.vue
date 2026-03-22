<script setup lang="ts">
import {  ref } from 'vue';
import BaseDialog from '../../../Components/BaseDialog/BaseDialog.vue';
import ExamEnrollment from '../../Exam/Components/ExamEnrollment.vue';
import PrimaryButton from '../../../Components/PrimaryButton/PrimaryButton.vue';
import { Student } from '../../../interfaces/interfaces';
import {  useForm } from '@inertiajs/vue3';
import { useAlert } from '../../../Composables/useAlert';
import { useConfirmDialog } from '../../../Composables/useConfirmDialog';

const props = defineProps<{
    student: Student | null
}>()

const isOpen = defineModel<boolean>()

const enroll = async () => {
    const {open} = useAlert()
    
    if(examId.value === null){
        open('Выберите время экзамена')
        return
    }
    if(!form.studentId){
        open('Неизвестная ошибка')
    }
    form.post(`exams/${examId.value}/students`,
    {
        onSuccess: (page) => {
            console.log(page.flash)
            if(page.flash.redirectUrl){
                window.open(String(page.flash.redirectUrl))
            }
        }
    })
}

const form = useForm({
    studentId:props.student?.id ?? null
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
        />
        <template #actions="{close}">
            <div class="flex justify-center">
                <PrimaryButton
                    @click="enroll"
                    text="Записать"
                    :loading="form.processing"
                    :disabled="form.processing || !examId"
                />
                <v-btn @click="close">
                    Отменить
                </v-btn>
            </div>
        </template>

    </BaseDialog>
</template>