<script setup lang="ts">
import { router, useForm } from '@inertiajs/vue3';
import BaseDialog from '../../../Components/BaseDialog/BaseDialog.vue';
import { ExamForm } from '../../../interfaces/interfaces';
import ExamCreateForm from './ExamCreateForm.vue';
import { DateFormatter } from '../../../Helpers/DateFormatter';
import PrimaryButton from '../../../Components/PrimaryButton/PrimaryButton.vue';
import { useConfirmDialog } from '../../../Composables/useConfirmDialog';
import { computed } from 'vue';

const props = defineProps<{
    exam:any
}>()
const form = useForm<ExamForm>({
    examTypeId: props.exam.examTypeId,
    addressId:props.exam.addressId,
    comment:props.exam.comment ?? '',
    examiners:props.exam.examiners ?? [],
    time: new DateFormatter(props.exam.beginTime ?? '').format('H:i'),
    date:new DateFormatter(props.exam.beginTime ?? '').format('Y-m-d'),
    capacity:props.exam.capacity
})
const isOpen = defineModel<boolean>({default:false})

const beforeClose = async (fn: () => void) => {
    if(form.isDirty){
        const {confirmOpen} = useConfirmDialog()
        const ok = await confirmOpen('Отменить редактирование?')
        if(!ok) return
    }
    form.resetAndClearErrors()
    fn()
}

const hasEnrollment = computed(() => Boolean(props.exam.foreignNationalsCount))
const edit = () => {
    console.log(form.examiners)
    if(hasEnrollment.value){
        router.put(`exams/${props.exam.id}/examiners`,{
            examiners: form.examiners.map(item => item.id)
        },
        {
            onSuccess:(page) => {
                if(page.flash.success){
                    props.exam.examiners = form.examiners
                }
            }
        }
    )
    }
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
            :form="form"
            :has-enrollment="hasEnrollment"
        />
        
    <template #actions>
        <PrimaryButton 
            text="Сохранить"
            @click="edit"
        />
    </template>
    </BaseDialog>
</template>