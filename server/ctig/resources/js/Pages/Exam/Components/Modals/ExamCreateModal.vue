<script setup lang="ts">
import { useForm, useHttp } from '@inertiajs/vue3'

import { onMounted, ref } from 'vue';
import BaseDialog from '@components/BaseComponents/BaseDialog/BaseDialog.vue';
import { Address, User, ExamType } from '@interfaces/interfaces';
import { ExamForm } from '@interfaces/interfaces';
import { useConfirmDialog } from '@composables/useConfirmDialog';
import AppAddButton from '@components/UI/AppAddButton/AppAddButton.vue';
import ExamCreateForm from './ExamCreateForm.vue';

const props = defineProps<{
    date?:string
}>()

const addresses = ref<Address[]>()
const examiners = ref<User[]>()
const examTypes = ref<ExamType[]>()
const isOpen = defineModel<boolean>({default:false})

const form = useForm<ExamForm>({
    examTypeId: null,
    addressId:null,
    comment:'',
    examiners:[],
    time:'',
    date:props.date ?? '',
    capacity:null
})

const http = useHttp()
onMounted( async () => {
    http.get('/exams/create/modal-data', {
        onSuccess:(response:any) => {
            addresses.value = response.addresses
            examiners.value = response.examiners
            examTypes.value = response.examTypes
        }
    })
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

const {confirmOpen} = useConfirmDialog()
const close = async (fn:  ()  => void) => {
    if(form.isDirty){
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
        @before-close="(done) => close(done)"
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