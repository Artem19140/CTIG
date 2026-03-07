<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import AppInput from '../../../Components/UI/AppInput/AppInput.vue';
import { ref } from 'vue';

const isActive = ref(false)

const form = useForm({
    surname:'', 
    name:'',
    patronymic:'',
    noPatronymic:false,
    surnameLatin:'',
    nameLatin:'',
    patronymicLatin:'',
    passportNumber:'',
    passportSeries:'',
    noPassportNumber:false,
    noPassportSeries:false,
    issuedBy:'',
    issuesDate:'',
    migrationCardRequisite:'',
    citizenship:'',
    phone:'',
    addressReg:'',
    dateBirth:''
})

const citizenships = [
    {name:'Узбекистан', value:'UZ'},
    {name:'Казахстан', value:'KZ'}
]

const create = () => {
    form.post('/students', {
    preserveScroll: true,
    onSuccess: (page) => {
        isActive.value = false
        form.reset()
        form.clearErrors()
        console.log('Форма успешно отправлена!', page)
    },
    onError: (errors) => {
        console.log('Ошибки валидации', errors)
    }
    })
}

const close = () => {
    if(form.isDirty){
        const isCancel = confirm("Отменить добавление?")
        if(isCancel){
            form.reset()
            form.clearErrors()
            isActive.value = false
            return
        }else{
            return
        }
    }
    form.reset()
    form.clearErrors()
    isActive.value = false
}

</script>

<template>
    <v-btn
           @click="isActive = true"
            color="green"
            text="Добавить"
            variant="flat"
            size="large"
        ></v-btn>
    <v-dialog 
        persistent 
        max-width="700"  
        v-model="isActive"
        
    >
        <v-card
            title="Добавление студента"  
            class="pl-4 pr-4" 
            prepend-icon="mdi-account"
        >
            <form @submit.prevent="create">
                <AppInput 
                    label="Фамилия"
                    v-model="form.surname"
                    clearable
                    :error-message="form.errors.surname"
                />
                <AppInput 
                    label="Имя"
                    v-model="form.name"
                    clearable
                    :error-message="form.errors.name"
                />
                <AppInput 
                    label="Отчество"
                    v-model="form.patronymic"
                    clearable
                    :error-message="form.errors.patronymic"
                />

                <v-checkbox
                    v-model="form.noPatronymic" 
                    label="Нет отчества"
                    :error-message="form.errors.noPatronymic"
                ></v-checkbox>

                <AppInput 
                    label="Фамилия лат"
                    v-model="form.surnameLatin"
                    clearable
                    :error-message="form.errors.surnameLatin"
                />
                <AppInput 
                    label="Имя лат"
                    v-model="form.nameLatin"
                    clearable
                    :error-message="form.errors.nameLatin"
                />
                <AppInput 
                    label="Отчество лат"
                    v-model="form.patronymicLatin"
                    :error-message="form.errors.patronymicLatin"
                    clearable
                />  

                <AppInput 
                    label="Дата рождения"
                    v-model="form.dateBirth"
                    :error-message="form.errors.dateBirth"
                    type="date"
                    clearable
                /> 

                <v-autocomplete 
                    label="Гражданство"
                    item-title="name"
                    :items="citizenships"
                    item-value="value"
                    v-model="form.citizenship"
                    :error-messages="form.errors.citizenship"
                    clearable
                />
                
                <AppInput 
                    label="Серия паспорта"
                    v-model="form.passportSeries"
                    :error-message="form.errors.passportSeries"
                    clearable
                />  

                <v-checkbox
                    v-model="form.noPassportSeries" 
                    label="Нет серии"
                    :error-message="form.errors.noPassportSeries"
                ></v-checkbox>

                <AppInput 
                    label="Номер паспорта"
                    v-model="form.passportNumber"
                    :error-message="form.errors.passportNumber"
                    clearable
                />  

                <v-checkbox
                    v-model="form.noPassportNumber" 
                    label="Нет номера"
                    :error-message="form.errors.noPatronymic"
                ></v-checkbox>
                
                <AppInput 
                    label="Кем выдан"
                    v-model="form.issuedBy"
                    :error-message="form.errors.issuedBy"
                    clearable
                />  

                <AppInput 
                    label="Дата выдачи"
                    v-model="form.issuesDate"
                    :error-message="form.errors.issuesDate"
                    type="date"
                    clearable
                /> 

                <AppInput 
                    label="Адрес регистрации"
                    v-model="form.addressReg"
                    :error-message="form.errors.addressReg"
                    clearable
                /> 

                <AppInput 
                    label="Реквизиты миграционной карты"
                    v-model="form.migrationCardRequisite"
                    :error-message="form.errors.migrationCardRequisite"
                    clearable
                /> 

                <AppInput 
                    label="Номер телефона"
                    v-model="form.phone"
                    :error-message="form.errors.phone"
                    clearable
                /> 

                <v-file-input label="Фотография студента"></v-file-input>

                <v-file-input label="Скан паспорта"></v-file-input>

                <v-card-actions class="flex justify-center">
                    <v-btn
                        text="Добавить"
                        variant="flat"
                        color="green"
                        type="submit"
                        :loading="form.processing"
                    ></v-btn>
                    
                    <v-btn
                        text="Отменить"
                        @click="close"
                    ></v-btn>
                </v-card-actions>
            </form>    
        </v-card>
    
    </v-dialog>
</template>