<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import axios from 'axios';
import { ref } from 'vue';
import AppInput from '../../../Components/UI/AppInput/AppInput.vue';
import BaseDialog from '../../../Components/UI/BaseDialog/BaseDialog.vue';
import { Address, User, ExamType } from '../../../interfaces/interfaces';
import { ExamForm } from '../../../interfaces/interfaces';

const addresses = ref<Address[]>()
const testers = ref<User[]>()
const examTypes = ref<ExamType[]>()

const form = useForm<ExamForm>({
    examTypeId: null,
    addressId:null,
    comment:'',
    testers:[],
    beginTime:''
})

const isActive = ref<boolean>(false)

const loadModalData = async () => {
    isActive.value = true
    const response = await axios.get('exams/create/modal-data')
    const data = response.data
    addresses.value = data.addresses
    testers.value = data.testers
    examTypes.value = data.examTypes
}
const create =  () => {
    form.post('/exams', {
    preserveScroll: true,
    onSuccess: (page) => {
        isActive.value = false
        form.resetAndClearErrors()
    }
    })
    
}

const close = () :boolean => {
    if(form.isDirty){
        if(!confirm("Отменить добавление?") ){
            return false
        }
    }
    form.resetAndClearErrors()
    return true
}
</script>

<template>
    
    <v-btn
        @click="loadModalData"
        color="green"
        text="Добавить"
        size="small"
    ></v-btn>

    <BaseDialog 
        title="Добавление экзамена"
        v-model="isActive"
        width="500"
        @before-close="(done) => {
            if (close()) done()
        }"
    >
        <form>
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
                :error-messages="form.errors.beginTime"
                clearable
            />
            
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
        </form>
        <template #actions="{ close }" >
            <v-btn
                text="Добавить"
                variant="flat"
                color="green"
                @click="create"
            ></v-btn>
            
            <v-btn
                text="Отменить"
                @click="close()"
            ></v-btn>
        </template>

    </BaseDialog>
</template>