<script setup lang="ts">
import { useHttp } from '@inertiajs/vue3';
import BaseDialog from '@components/BaseComponents/BaseDialog/BaseDialog.vue';
import { Exam, ExamForm } from '@interfaces/Interfaces';
import ExamCreateForm from './ExamCreateForm.vue';
import { DateFormatter } from '@helpers/DateFormatter';
import AppPrimaryButton from '@components/UI/AppPrimaryButton/AppPrimaryButton.vue';
import { useConfirmDialog } from '@composables/useConfirmDialog';
import { computed, ref } from 'vue';

const props = defineProps<{
    exam: Exam,
    onEdit?:(exam:Exam) => void
}>()

const http = useHttp<ExamForm>({
    examTypeId: props.exam.examTypeId,
    addressId:props.exam.addressId,
    comment:props.exam.comment ?? '',
    examiners: props.exam.examiners.map(e => e.id),
    time: new DateFormatter(props.exam.beginTime ?? '').format('H:i'),
    date:new DateFormatter(props.exam.beginTime ?? '').format('Y-m-d'),
    capacity:props.exam.capacity
})

const isOpen = defineModel<boolean>({default:false})

const beforeClose = async (fn: () => void) => {
    if(http.isDirty){
        const {confirmOpen} = useConfirmDialog()
        const ok = await confirmOpen('Отменить редактирование?')
        if(!ok) return
    }
    http.resetAndClearErrors()
    fn()
}

const hasEnrollment = computed(() => Boolean(props.exam.enrollmentsCount))
const loading = ref(false)
const edit = () => {
    http.put(`/exams/${props.exam.id}`,{
        onSuccess:(response: any) => {
            if(response.exam)
            //if(!props.onEdit)return
            console.log(response.exam)
            props.onEdit(response.exam)
            isOpen.value = false
        }
    })
}

</script>

<template>
    <BaseDialog
        width="500"
        v-model="isOpen"
        title="Редактирование экзамена"
        @before-close="(done) => beforeClose(done)"
    >
        <ExamCreateForm 
            :form="http"
            :has-enrollment="hasEnrollment"
        />
        
    <template #actions>
        <AppPrimaryButton 
            text="Сохранить"
            @click="edit"
            :disabled="!http.isDirty"
            :loading="http.processing || loading"
        />
    </template>
    </BaseDialog>
</template>