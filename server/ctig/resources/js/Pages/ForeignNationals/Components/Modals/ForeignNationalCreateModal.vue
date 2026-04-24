<script setup lang="ts">
import {  router, useHttp } from '@inertiajs/vue3'
import {h, ref } from 'vue';
import BaseDialog from '@components/BaseComponents/BaseDialog/BaseDialog.vue';
import { type Exam, type IForeignNationalCreateForm } from '@interfaces/Interfaces';
import { useConfirmDialog } from '@composables/useConfirmDialog';
import AppAddButton from '@components/UI/AppAddButton/AppAddButton.vue';
import ForeignNationalCreateForm from './ForeignNationalCreateForm.vue';
import ExamEnrollment from '../../../../components/Exam/ExamEnrollment.vue';
import { useAlert } from '@composables/useAlert';
import AppCheckbox from '@components/UI/AppCheckbox/AppCheckbox.vue';

const isOpen = defineModel<boolean>({default:false})
const examTypeId = ref<number | null>(null)

const exams = ref<Exam[]>()

// const http = useHttp<IForeignNationalCreateForm>({
//     surname: '', 
//     name:'',
//     patronymic:"",
//     noPatronymic:false,
//     surnameLatin:'',
//     nameLatin:'',
//     patronymicLatin:"",
//     noPatronymicLatin:"",
//     passportNumber:"",
//     passportSeries:"",
//     noPassportNumber:false,
//     noPassportSeries:false,
//     issuedBy:'',
//     issuedDate:'',
//     citizenship:null,
//     phone:'',
//     dateBirth:'',
//     passportScan:null,
//     passportTranslateScan:null,
//     examId:null,
//     gender:null,
//     hasPayment:false,
//     comment:'',
//     addressReg:''
// })
const http = useHttp<IForeignNationalCreateForm>({
    surname: 'Иванов', 
    name:'Иван',
    patronymic:"Иванович",
    noPatronymic:false,
    noPatronymicLatin:false,
    surnameLatin:'Ivanov',
    nameLatin:'Ivan',
    patronymicLatin:"Ivanovich",
    passportNumber:"",
    passportSeries:"AB",
    noPassportNumber:false,
    noPassportSeries:false,
    issuedBy:'МВД по УР',
    issuedDate:'2025-10-10',
    citizenship:'UZ',
    phone:'89346573385',
    dateBirth:'2005-10-10',
    passportScan:null,
    passportTranslateScan:null,
    examId:null,
    gender:null,
    hasPayment:false,
    comment:'',
    addressReg:'г. Ижевск, ул. Удмурская 158, кв. 23'
})

const create = () => {
    // if(!http.examId){
    //     http.errors.examId = 'Выберите экзамен для записи'
    //     return   
    // }
        
    http.post('/foreign-nationals', {

    onSuccess: (response:any) => {
        if(response.redirectUrl){
            examTypeId.value = null
            exams.value = undefined
            http.resetAndClearErrors()
            window.open(String(response.redirectUrl))
            router.visit('/foreign-nationals')
            isOpen.value=false
        }
    }
    })
}

const {confirmOpen} = useConfirmDialog()

const  close  = async (fn:  ()  => void)  =>  {
    if (http.isDirty) {
        if(!await confirmOpen("Отменить добавление ИГ?")){
            return
        }
    }
    examTypeId.value = null
    exams.value = undefined
    http.reset()
    http.clearErrors()
    fn()
}

</script>

<template>  
        <BaseDialog
            v-model="isOpen"
            title="Добавление ИГ"
            width="1000"
            height="100%"
            @before-close="(done) => {close(done); }"
        >   
                <v-card title="Экзамен" class="mb-4" >
                    <v-card-text>
                        <v-container>
                            <v-col cols="12" class="subtitle mb-4">
                                    Выберите экзамен для записи
                            </v-col>
                            <ExamEnrollment 
                                v-model:exam-id="http.examId"
                                v-model:has-payment="http.hasPayment"
                                :exam-validation-errors="http.errors.examId"
                            />
                        </v-container>
                    </v-card-text>
                </v-card>
                
                <ForeignNationalCreateForm :form="http" />
                  
            <template #actions>
                <span class="text-red" v-if="http.hasErrors">Есть ошибки заполнения</span>
                <AppAddButton text="Добавить" 
                    :disabled="http.processing"
                    :loading="http.processing"
                    type="submit"
                    @click="create"
                />
            </template>
        </BaseDialog>
</template>

<style scoped>
    .subtitle {
        font-size: 18px; color: #757575; font-weight: 500;
    }
</style>