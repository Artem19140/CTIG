<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import BaseDialog from '@components/BaseComponents/BaseDialog/BaseDialog.vue';
import {  ExamForm } from '@interfaces/Interfaces';
import { useConfirmDialog } from '@composables/useConfirmDialog';
import AppAddButton from '@components/UI/AppAddButton/AppAddButton.vue';
import ExamCreateForm from './ExamCreateForm.vue';

const props = defineProps<{
    date?:string
}>()
const isOpen = defineModel<boolean>({default:false})

const form = useForm<ExamForm>({
    examTypeId: null,
    addressId:null,
    comment:'',
    examiners:[],
    time:null,
    date:props.date ?? null,
    capacity:null
})

const create =  () => {
    form.post('/exams', {
    preserveScroll: true,
    onSuccess: (page) => {
        if(page.flash.success){
            form.resetAndClearErrors()
            isOpen.value = false
        }
    },
    })
    
}

const beforeClose = async (fn:  ()  => void) => {
    if(form.isDirty){
        const {confirmOpen} = useConfirmDialog()
        if(! await confirmOpen("Отменить добавление экзамена?") ){
            return
        }
    }
    form.resetAndClearErrors()
    fn()
}
</script>

<template>
    <BaseDialog 
        title="Добавление экзамена"
        width="500"
        v-model="isOpen"
        @before-close="(done) => beforeClose(done)"
    >
    <ExamCreateForm :form="form" />
        <template #actions >
            <AppAddButton  
                text="Добавить"
                @click="create"
                :disabled="form.processing"
                :loading="form.processing"
            />
        </template>
    </BaseDialog>
</template>