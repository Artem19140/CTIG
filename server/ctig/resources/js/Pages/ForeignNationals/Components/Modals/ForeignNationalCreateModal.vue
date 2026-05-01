<script setup lang="ts">
import {  router, useHttp } from '@inertiajs/vue3'
import {ref } from 'vue';
import BaseDialog from '@components/BaseComponents/BaseDialog/BaseDialog.vue';
import { useConfirmDialog } from '@composables/useConfirmDialog';
import AppAddButton from '@components/UI/AppAddButton/AppAddButton.vue';
import ForeignNationalForm from './ForeignNationalForm.vue';
import ExamEnrollment from '../../../../components/Exam/ExamEnrollment.vue';
import { Exam } from '@/interfaces/Exam';
import { ForeignNationalFormI } from '@/interfaces/ForeignNational';
import { RedirectUrl } from '@/interfaces/Interfaces';

const isOpen = defineModel<boolean>({default:false})
const examTypeId = ref<number | null>(null)

const exams = ref<Exam[]>()

// const http = useHttp<ForeignNationalCreateForm, RedirectUrl>({
//     surname: '', 
//     name:'',
//     patronymic:"",
//     noPatronymic:false,
//     surnameLatin:'',
//     nameLatin:'',
//     patronymicLatin:"",
//     noPatronymicLatin:false,
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
const http = useHttp<ForeignNationalFormI & {hasPayment:boolean, examId: number | null}, RedirectUrl>({
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
    http.post('/foreign-nationals', {
        onSuccess: (response) => {
            if(response.redirectUrl){
                window.open(String(response.redirectUrl))
                router.visit('/foreign-nationals')
                isOpen.value=false
                reset()
            }
        }
    })
}

const {confirmOpen} = useConfirmDialog()

const  reset = ()  =>  { 
    examTypeId.value = null
    exams.value = undefined
    http.resetAndClearErrors()
}

</script>

<template>  
        <BaseDialog
            v-model="isOpen"
            title="Добавление ИГ"
            width="1000"
            height="100%"
            @before-close="async (close) => {
                if (http.isDirty) {
                    const ok = await confirmOpen('Отменить добавление ИГ?')
                    if(!ok) return
                    
                }
                reset()
                close()
            }"
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
                
                <ForeignNationalForm 
                    v-model:form="http"
                    :loading="http.processing"
                    :errors="http.errors"    
                />
                  
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