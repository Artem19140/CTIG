<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import AppInput from '../../../Components/UI/AppInput/AppInput.vue';
import { computed, ref, watch } from 'vue';
import BaseDialog from '../../../Components/UI/BaseDialog/BaseDialog.vue';
import axios from 'axios';
import type {Exam, StudentCreateForm } from '../../../interfaces/interfaces';
import { formatterTime,formatterDate } from '../../../Helpers/heplers';
import { modalState } from '../../../Composables/modalState';

const isActive = ref<boolean>(false)
const examTypeId = ref<number | null>(null)

const exams = ref<Exam[]>()

const form = useForm<StudentCreateForm>({
    surname:'Иванов', 
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
    passportScanTranslate:null,
    examId:null
})

const citizenships = [
    {name:'Узбекистан', value:'UZ'},
    {name:'Казахстан', value:'KZ'}
]

const examTypes = [
    {name:'ПАТЕНТ', id:1},
    {name:'РВП', id:2},
    {name:'ВНЖ', id:3}
]
const create = () => {
    form.post('/students', {
    preserveScroll: true,
    preserveState: true,
    onSuccess: async (page) => {
        examTypeId.value = null
        exams.value = undefined
        form.reset()
        form.clearErrors()
        const studentId = page.props.studentId
        const examId = page.props.examId
        //const res = await axios.get('documents')
        window.open(`/students/${studentId}/application-forms?examId=${examId}`)
        modalState.fileUrl =  `students/${studentId}/application-forms?examId=${examId}` 
        console.log('Форма успешно отправлена!', page)
    },
    onError: (errors) => {
        console.log('Ошибки валидации', errors)
    }
    })
}

const close = (fn: () => void) => {
    if (form.isDirty && !confirm("Отменить добавление?")) {
        return
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
 
watch(examTypeId, async () => {
    if(!examTypeId.value){
        form.examId = null
        return
    }
    const res = await axios.get(`/exams/available?examTypeId=${examTypeId.value}`)
    exams.value = res.data.data
})

</script>

<template>
    <v-btn
           @click="isActive = true"
            text="Добавить"
            color="green"
            size="small"
        ></v-btn>
        <BaseDialog
            v-model="isActive"
            title="Добавление студента"
            width="1000"
            height="100%"
            @before-close="(done) => {close(done); }"
        >
        <template #skeleton>
            <v-skeleton-loader type="article" />
        </template>
                <v-card title="Экзамен" class="mb-4">
                    <v-card-text>
                        <v-container fluid>
                            <v-row density="comfortable">
                                <v-col cols="12" class="subtitle">
                                    Выберите экзамен для записи
                                </v-col>

                                <v-col cols="12" md="6">
                                    <v-autocomplete 
                                        label="Тип экзамена"
                                        item-title="name"
                                        :items="examTypes"
                                        item-value="id"
                                        v-model="examTypeId"
                                        :error-messages="form.errors.examId"
                                        clearable
                                    />
                                </v-col>

                                <v-col cols="12" class="subtitle" >
                                    Выберите время и дату
                                </v-col>

                                <v-col cols="12" md="6">
                                    <v-select
                                        label="Дата и время"
                                        :items="exams"
                                        item-title="beginTime"
                                        item-value="id"
                                        v-model="form.examId"
                                        :disabled="!examTypeId"
                                        :error-messages="form.errors.examId"
                                        clearable
                                        >
                                    </v-select>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-card-text>
                </v-card>

                <v-card title="Персональные данные" class="mb-4">
                    <v-card-text>
                        <v-container fluid>
                            <v-row density="comfortable">
                                <v-col cols="12"  class="subtitle" >
                                    Нотариальный перевод
                                </v-col>

                                <v-col cols="12" md="6">
                                    <AppInput
                                        label="Фамилия на русском"
                                        v-model="form.surname"
                                        :error-messages="form.errors.surname"
                                    />
                                </v-col>

                                <v-col cols="12" md="6">
                                    <AppInput 
                                label="Имя на русском"
                                v-model="form.name"
                                :error-messages="form.errors.name"
                            />
                                </v-col>

                                <v-col cols="12" md="6">
                                    <AppInput
                                        label="Отчество на русском"
                                        v-model="form.patronymic"
                                        :error-messages="form.errors.patronymic"
                                        :disabled="form.noPatronymic"
                                    />
                                </v-col>
                                
                                <v-col cols="12" md="6">
                                    <v-checkbox
                                        v-model="form.noPatronymic" 
                                        label="Нет отчества"
                                        :error-messages="form.errors.noPatronymic"
                                    ></v-checkbox>
                                </v-col>


                                <v-col cols="12" class="subtitle">
                                    Паспортные данные
                                </v-col>

                                
                                <v-col cols="12" md="6">
                                    <AppInput  
                                        label="Фамилия на латинице"
                                        v-model="form.surnameLatin"
                                        :error-messages="form.errors.surnameLatin"
                                    />
                                </v-col>

                                <v-col cols="12" md="6">
                                    <AppInput  
                                        label="Имя на латинице"
                                        v-model="form.nameLatin"
                                        :error-messages="form.errors.nameLatin"
                                    />
                                </v-col>

                                <v-col cols="12" md="6">
                                    <AppInput 
                                        label="Отчество на латинице"
                                        v-model="form.patronymicLatin"
                                        :error-messages="form.errors.patronymicLatin"
                                        :disabled="form.noPatronymic"
                                    />  
                                </v-col>

                                <v-col cols="12" md="6">
                                        <AppInput 
                                            label="Дата рождения"
                                            v-model="form.dateBirth"
                                            :error-messages="form.errors.dateBirth"
                                            type="date"
                                        /> 
                                </v-col>

                                <v-col cols="12">
                                    <v-autocomplete 
                                        label="Гражданство"
                                        item-title="name"
                                        :items="citizenships"
                                        item-value="value"
                                        v-model="form.citizenship"
                                        :error-messages="form.errors.citizenship"
                                        clearable
                                    />
                                </v-col>

                                <v-col cols="12" md="6">
                                    <AppInput  
                                        label="Серия паспорта"
                                        v-model="form.passportSeries"
                                        :error-messages="form.errors.passportSeries"
                                        :disabled="form.noPassportSeries"
                                    />  
                                </v-col>

                                <v-col cols="12" md="6">
                                    <v-checkbox
                                        v-model="form.noPassportSeries" 
                                        label="Нет серии"
                                        :error-messages="form.errors.noPassportSeries"
                                    ></v-checkbox>
                                </v-col>

                                <v-col cols="12" md="6">
                                    <AppInput 
                                        label="Номер паспорта"
                                        v-model="form.passportNumber"
                                        :error-messages="form.errors.passportNumber"
                                        :disabled="form.noPassportNumber"
                                    />  
                                </v-col>

                                <v-col cols="12" md="6">
                                    <v-checkbox
                                        v-model="form.noPassportNumber" 
                                        label="Нет номера"
                                        :error-messages="form.errors.noPassportNumber"
                                    ></v-checkbox>
                                </v-col>

                                <v-col cols="12" md="6">  
                                    <AppInput
                                        label="Кем выдан"
                                        v-model="form.issuedBy"
                                        :error-messages="form.errors.issuedBy"
                                        clearable
                                    />
                                </v-col>

                                <v-col cols="12" md="6">
                                    <AppInput
                                        label="Дата выдачи"
                                        v-model="form.issuedDate"
                                        :error-messages="form.errors.issuedDate"
                                        type="date"
                                    /> 
                                </v-col>

                                    <v-col cols="12" class="subtitle">
                                        Миграционная карта
                                    </v-col>

                                    <v-col cols="12" md="6">
                                        <AppInput  
                                            label="Реквизиты миграционной карты"
                                            v-model="form.migrationCardRequisite"
                                            :error-messages="form.errors.migrationCardRequisite"
                                            :disabled="form.noMigrationCard"
                                        /> 
                                    </v-col>

                                    <v-col cols="12" md="6">
                                        <v-checkbox
                                            v-model="form.noMigrationCard" 
                                            label="Нет миграционной карты"
                                            :error-messages="form.errors.noMigrationCard"
                                            
                                        ></v-checkbox>
                                    </v-col>
                                    <v-col cols="12" class="subtitle">
                                        Адрес регистрации
                                    </v-col>

                                
                                    <AppInput  
                                        label="Адрес регистрации"
                                        v-model="form.addressReg"
                                        :error-messages="form.errors.addressReg"
                                    /> 
                                    <v-col cols="12" md="6"></v-col>
                                    <v-col cols="12"  class="subtitle">
                                        Контакты    
                                    </v-col>
   
                                    <v-col cols="12" md="6">
                                        <AppInput 
                                            label="Номер телефона"
                                            v-model="form.phone"
                                            :error-messages="form.errors.phone"
                                        /> 
                                    </v-col>
                            </v-row>
                        </v-container>
                    </v-card-text>
                </v-card>

               

                <v-card title="Документы" class="mb-4">
                    <v-card-text>
                        <v-container fluid>
                            <v-row density="comfortable">
                                <v-file-input 
                                    label="Скан перевода паспорта"
                                    v-model="form.passportScanTranslate"
                                    clearable
                                    accept=".pdf,application/pdf"
                                    :error-messages="form.errors.passportScanTranslate"
                                ></v-file-input>

                                <v-file-input 
                                    label="Скан паспорта PDF"
                                    clearable
                                    v-model="form.passportScan"
                                    accept=".pdf,application/pdf"
                                    :error-messages="form.errors.passportScan"
                                ></v-file-input>
                            </v-row>
                        </v-container>
                    </v-card-text>
                    
                </v-card>
                  
            <template #actions="{close}">
                    <v-btn
                        :disabled="form.processing"
                        :loading="form.processing"
                        @click="create"
                        color="green"
                        variant="flat"
                    >
                        Добавить
                    </v-btn>
                    <v-btn
                        @click="close"
                    >
                        Отменить
                    </v-btn>
            </template>
        </BaseDialog>
</template>

<style scoped>
    .subtitle {
        font-size: 18px; color: #757575; font-weight: 500;
    }
</style>