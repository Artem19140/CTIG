<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import axios from 'axios';
import { ref } from 'vue';
import AppInput from '../../../Components/UI/AppInput/AppInput.vue';

const addresses = ref()
const testers = ref()
const examTypes = ref()

const form = useForm({
    examTypeId: null,
    capacity: null,
    addressId:null,
    comment:'',
    testers:[],
    beginTime:undefined
})

const isActive = ref(false)

const loadModalData = async () => {
    isActive.value = true
    const response = await axios.get('exams/create/modal-data')
    const data = response.data
    addresses.value = data.addresses
    testers.value = data.testers
    examTypes.value = data.examTypes
}
const create = () => {
    form.post('/exams', {
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
            isActive.value = false
            form.clearErrors()
            return
        }else{
            return
        }
    }
    form.clearErrors()
    form.reset()
    isActive.value = false
}
</script>

<template>
    
    <v-btn
        @click="loadModalData"
        color="green"
        text="Добавить"
        variant="flat"
        size="large"
    ></v-btn>
    
    <v-dialog persistent max-width="500" v-model="isActive">
        <v-card title="Добавление экзамена"  class="pl-4 pr-4">
            <form @submit.prevent="create">
                <v-autocomplete 
                    label="Тип экзамена"
                    item-title="name"
                    :items="examTypes"
                    v-model="form.examTypeId"
                    key="id"
                    :error-messages="form.errors.examTypeId"
                    :loading="!examTypes"
                    item-value="id"
                    clearable
                />
                
                <AppInput 
                    label="Дата и время"
                    type="datetime-local"
                    v-model="form.beginTime"
                    clearable
                />

                <v-number-input
                    :min="0"
                    label="Количество студентов"
                    v-model="form.capacity"
                    clearable
                    :error-messages="form.errors.capacity"
                ></v-number-input>
                
                <v-autocomplete 
                    label="Адрес"
                    item-title="address"
                    :items="addresses"
                    item-value="id"
                    v-model="form.addressId"
                    :error-messages="form.errors.addressId"
                    :loading="!addresses"
                    clearable
                />

                <v-autocomplete 
                    label="Тестеры"
                    item-title="fullName"
                    :items="testers"
                    v-model="form.testers"
                    item-value="id"
                    :error-messages="form.errors.testers"
                    clearable
                    multiple    
                    :loading="!testers"
                />

                <v-textarea
                    label="Комментарий"
                    rows="1"
                    v-model="form.comment"
                    :error-messages="form.errors.comment"
                    hint="Максимум 256 символов"
                    maxlength="256"
                    auto-grow
                    counter
                    clearable
                ></v-textarea>

                <v-card-actions class="flex justify-center">
                    <v-btn
                        text="Добавить"
                        variant="flat"
                        color="green"
                        type="submit"
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