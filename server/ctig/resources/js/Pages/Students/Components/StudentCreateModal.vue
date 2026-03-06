<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import AppInput from '../../../Components/UI/AppInput/AppInput.vue';
import { ref } from 'vue';

const isActive = ref(false)

const form = useForm({
    surname:'',
    name:'234',
    patronymic:'243',
    hasPatronymic:false,
    surnameLatin:'1243',
    nameLatin:'2143',
    patronymicLatin:'2143',
    passportNumber:'',
    passportSeries:'243',
    hasPassportNumber:false,
    hasPassportSeries:false,
    issuedBy:'234',
    issuesDate:'2025-11-11',
    migrationCardRequisite:'123',
    citizenship:'KZ',
    phone:'243',
    addressReg:'124',
    dateBirth:'2005-11-11'
})

const citizenships = [
    {name:'Узбекистан', value:'UZ'},
    {name:'Казахстан', value:'KZ'}
]

const create = () => {
    form.post('/students', {
    preserveScroll: true, // сохраняем прокрутку
    onSuccess: (page) => {
        isActive.value = false
        console.log('Форма успешно отправлена!', page)
    },
    onError: (errors) => {
        console.log('Ошибки валидации', errors)
    }
    })
}

</script>

<template>
    <v-btn
           @click="isActive = true"
            color="green"
            prepend-icon="mdi-plus"
            text="Добавить"
            variant="flat"
            size="large"
        ></v-btn>
    <v-dialog persistent max-width="500"  v-model="isActive">
        
    
        <v-card title="Добавление студента"  class="pl-4 pr-4">
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
                    v-model="form.hasPatronymic" 
                    label="Нет отчества"
                    :error-message="form.errors.hasPatronymic"
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
                    label="Граждество"
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
                    v-model="form.hasPassportSeries" 
                    label="Нет серии"
                    :error-message="form.errors.hasPassportSeries"
                ></v-checkbox>

                <AppInput 
                    label="Номер паспорта"
                    v-model="form.passportNumber"
                    :error-message="form.errors.passportNumber"
                    clearable
                />  

                <v-checkbox
                    v-model="form.hasPassportNumber" 
                    label="Нет номера"
                    :error-message="form.errors.hasPatronymic"
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

                <v-card-actions class="flex justify-center">
                    <v-btn
                        text="Добавить"
                        variant="flat"
                        color="green"
                        type="submit"
                    ></v-btn>
                    
                    <v-btn
                        text="Отменить"
                        @click="isActive = false"
                    ></v-btn>
                </v-card-actions>
            </form>    
        </v-card>
    
    </v-dialog>
</template>