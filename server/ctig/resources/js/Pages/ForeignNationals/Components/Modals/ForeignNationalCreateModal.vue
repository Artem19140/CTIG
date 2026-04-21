<script setup lang="ts">
import {  useForm } from '@inertiajs/vue3'
import {ref, watch } from 'vue';
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

// const form = useForm<IForeignNationalCreateForm>({
//     surname: '', 
//     name:'',
//     patronymic:"",
//     noPatronymic:false,
//     surnameLatin:'',
//     nameLatin:'',
//     patronymicLatin:"",
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
const form = useForm<IForeignNationalCreateForm>({
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
    } 
})

watch(() => form.noPatronymicLatin, (val) => {
    if (val){
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
                            <AppCheckbox
                                v-model="form.hasPayment" 
                                label="Есть оплата"
                                :error-messages="form.errors.hasPayment"
                            ></AppCheckbox>
                        </v-container>
                    </v-card-text>
                </v-card>
                
                <ForeignNationalCreateForm :form="form" />
                
            
                  
            <template #actions>
                <span class="text-red" v-if="form.hasErrors">Есть ошибки заполнения</span>
                <AppAddButton text="Добавить" 
                    :disabled="form.processing"
                    :loading="form.processing"
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