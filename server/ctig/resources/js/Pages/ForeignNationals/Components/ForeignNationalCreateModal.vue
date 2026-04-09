<script setup lang="ts">
import {  useForm } from '@inertiajs/vue3'

import {ref, watch } from 'vue';
import BaseDialog from '../../../Components/BaseDialog/BaseDialog.vue';
import { type Exam, type IForeignNationalCreateForm } from '../../../interfaces/interfaces';
import { useConfirmDialog } from '../../../Composables/useConfirmDialog';
import AddButton from '../../../Components/AddButton/AddButton.vue';
import ForeignNationalCreateForm from './ForeignNationalCreateForm.vue';
import ExamEnrollment from '../../Exam/Components/ExamEnrollment.vue';
import { useAlert } from '../../../Composables/useAlert';

const isOpen = defineModel<boolean>({default:false})
const examTypeId = ref<number | null>(null)

const exams = ref<Exam[]>()

const form = useForm<IForeignNationalCreateForm>({
    surname: 'Иванов', 
    name:'Иван',
    patronymic:"Иванович",
    noPatronymic:false,
    surnameLatin:'Ivanov',
    nameLatin:'Ivan',
    patronymicLatin:"Ivanovich",
    passportNumber:"",
    passportSeries:"AB",
    noPassportNumber:false,
    noPassportSeries:false,
    issuedBy:'МВД по УР',
    issuedDate:'2025-10-10',
    migrationCardRequisite:"MC234245234",
    citizenship:'UZ',
    phone:'89346573385',
    addressReg:'Ижевск, Пушкинская 131',
    dateBirth:'2005-10-10',
    noMigrationCard:false,
    passportScan:null,
    passportTranslateScan:null,
    examId:null,
    gender:null,
    photo:null,
    hasPayment:false,
    comment:''
})

const create = () => {
    const {open} = useAlert()
    if(!form.examId){
        open('Выберите экзамен для записи')
        return   
    }
        
    form.post('/foreign-nationals', {
    preserveScroll: true,
    preserveState: true,
    onSuccess: (page) => {
        if(page.flash?.redirectUrl){
            examTypeId.value = null
            exams.value = undefined
            form.resetAndClearErrors()
            window.open(String(page.flash?.redirectUrl))
            isOpen.value=false
        }
    }
    })
}

const {confirmOpen} = useConfirmDialog()

const  close  = async (fn:  ()  => void)  =>  {
    if (form.isDirty) {
        if(!await confirmOpen("Отменить добавление ИГ?")){
            return
        }
    }
    examTypeId.value = null
    exams.value = undefined
    form.reset()
    form.clearErrors()
    fn()
}

watch(() => form.noPatronymic, (val) => {
    if (val){
        form.patronymic = ''
        form.patronymicLatin = ''
    } 
})

watch(() => form.noPassportNumber, (val) => {
    if (val){
        form.passportNumber = ''
    } 
})

watch(() => form.noPassportSeries, (val) => {
    if (val){
        form.passportSeries = ''
    } 
})

watch(() => form.noMigrationCard, (val) => {
    if (val){
        form.migrationCardRequisite = ''
    } 
})

</script>

<template>  
        <BaseDialog
            v-model="isOpen"
            title="Добавление ИГ"
            width="1000"
            height="100%"
            @before-close="(done) => {close(done); }"
        >   
                <v-card title="Экзамен" class="mb-4">
                    <v-card-text>
                        <v-container>
                            <v-col cols="12" class="subtitle mb-4">
                                    Выберите экзамен для записи
                            </v-col>
                            <ExamEnrollment v-model="form.examId" />
                        </v-container>
                    </v-card-text>
                    <v-card-text>
                        <v-checkbox
                            v-model="form.hasPayment" 
                            label="Есть оплата"
                            :error-messages="form.errors.hasPayment"
                        ></v-checkbox>
                    </v-card-text>
                </v-card>

                <ForeignNationalCreateForm :form="form" />
                
            
                  
            <template #actions>
                <span class="text-red" v-if="form.hasErrors">Есть ошибки валидации полей формы</span>
                <AddButton text="Добавить" 
                    :disabled="form.processing"
                    :loading="form.processing"
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