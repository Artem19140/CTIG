<script setup lang="ts">
import { router, useHttp } from '@inertiajs/vue3'
import BaseDialog from '@components/BaseComponents/BaseDialog/BaseDialog.vue';
import {  ExamForm } from '@interfaces/Interfaces';
import { useConfirmDialog } from '@composables/useConfirmDialog';
import AppAddButton from '@components/UI/AppAddButton/AppAddButton.vue';
import ExamCreateForm from './ExamCreateForm.vue';
import { useSnackbarQueue } from '@/composables/useSnackbarQueue';

const props = defineProps<{
    date?:string
}>()
const isOpen = defineModel<boolean>({default:false})

const http = useHttp<ExamForm>({
    examTypeId: null,
    addressId:null,
    comment:'',
    examiners:[],
    time:null,
    date:props.date ?? null,
    capacity:null
})

const create =  () => {
    http.post('/exams', {
    onSuccess: (response:any) => {
        console.log(response)
        http.resetAndClearErrors()
        isOpen.value = false
        router.visit('/exams')
        const {add} = useSnackbarQueue()
        add('Экзамен создан', 'green')
    },
    })
    
}

const beforeClose = async (fn:  ()  => void) => {
    if(http.isDirty){
        const {confirmOpen} = useConfirmDialog()
        if(! await confirmOpen("Отменить добавление экзамена?") ){
            return
        }
    }
    http.resetAndClearErrors()
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
    <ExamCreateForm :form="http" />
        <template #actions >
            <AppAddButton  
                text="Добавить"
                @click="create"
                :disabled="http.processing"
                :loading="http.processing"
            />
        </template>
    </BaseDialog>
</template>