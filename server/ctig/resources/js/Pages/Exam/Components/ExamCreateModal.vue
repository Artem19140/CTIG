<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import axios from 'axios';
import { ref } from 'vue';
import AppInput from '../../../Components/AppInput/AppInput.vue';
import BaseDialog from '../../../Components/BaseDialog/BaseDialog.vue';
import { Address, User, ExamType } from '../../../interfaces/interfaces';
import { ExamForm } from '../../../interfaces/interfaces';
import { useConfirmDialog } from '../../../Composables/useConfirmDialog';
import AddButton from '../../../Components/AddButton/AddButton.vue';
import { useAuth } from '../../../Composables/useAuth';
import { Roles } from '../../../Constants/Roles';

const addresses = ref<Address[]>()
const examiners = ref<User[]>()
const examTypes = ref<ExamType[]>()

const {can} = useAuth()

const props = defineProps<{
    datetime?:string | null
}>()

const form = useForm<ExamForm>({
    examTypeId: null,
    addressId:null,
    comment:'',
    examiners:[],
    beginTime:''
})

const isActive = ref<boolean>(false)

const loadModalData = async () => {
    isActive.value = true
    const response = await axios.get('/exams/create/modal-data')
    const data = response.data
    addresses.value = data.addresses
    examiners.value = data.examiners
    examTypes.value = data.examTypes
}
const create =  () => {
    form.post('/exams', {
    preserveScroll: true,
    onSuccess: (page) => {
        if(page.flash.success){
            isActive.value = false
            form.resetAndClearErrors()
        }
    },
    onError:() => {

    }
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
    <AddButton 
        text="Добавить" 
        @click="loadModalData" 
        v-if="can([Roles.SCHEDULER])"
    />
    <BaseDialog 
        title="Добавление экзамена"
        v-model="isActive"
        width="500"
        @before-close="(done) => close(done)"
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
                :items="examiners"
                v-model="form.examiners"
                item-value="id"
                :error-messages="form.errors.examiners"
                clearable
                multiple    
                :loading="!examiners"
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
            <AddButton  
                text="Добавить"
                @click="create"
                :disabled="form.processing"
                :loading="form.processing"
            />
            <v-btn
                text="Отменить"
                @click="close"
            ></v-btn>
        </template>

    </BaseDialog>
</template>