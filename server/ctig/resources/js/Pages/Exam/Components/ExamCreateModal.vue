<script setup lang="ts">
import { useForm, useHttp } from '@inertiajs/vue3'
import axios from 'axios';
import { onMounted, ref } from 'vue';
import AppInput from '../../../Components/AppInput/AppInput.vue';
import BaseDialog from '../../../Components/BaseDialog/BaseDialog.vue';
import { Address, User, ExamType } from '../../../interfaces/interfaces';
import { ExamForm } from '../../../interfaces/interfaces';
import { useConfirmDialog } from '../../../Composables/useConfirmDialog';
import AddButton from '../../../Components/AddButton/AddButton.vue';

import AppAutocomplete from '../../../Components/AppAutocomplete/AppAutocomplete.vue';

const props = defineProps<{
    date?:string
}>()

const addresses = ref<Address[]>()
const examiners = ref<User[]>()
const examTypes = ref<ExamType[]>()
const isOpen = defineModel<boolean>({default:false})

const form = useForm<ExamForm>({
    examTypeId: null,
    addressId:null,
    comment:'',
    examiners:[],
    time:'',
    date:props.date ?? '',
    capacity:null
})

const http = useHttp()
onMounted( async () => {
    http.get('/exams/create/modal-data', {
        onSuccess:(response:any) => {
            addresses.value = response.addresses
            examiners.value = response.examiners
            examTypes.value = response.examTypes
        }
    })
})
const create =  () => {
    form.post('/exams', {
    preserveScroll: true,
    onSuccess: (page) => {
        if(page.flash.success){
            form.resetAndClearErrors()
            isOpen.value = false
        }
    },
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
    <BaseDialog 
        title="Добавление экзамена"
        width="500"
        v-model="isOpen"
        @before-close="(done) => close(done)"
    >
        <form>
            <AppAutocomplete 
                label="Тип экзамена"
                item-title="name"
                :items="examTypes"
                v-model="form.examTypeId"
                key="id"
                :error-messages="form.errors.examTypeId"
                :loading="http.processing"
                item-value="id"
                clearable
            />
           <div class="flex gap-5">
                <div class="flex-1">
                    <AppInput 
                    label="Дата"
                    type="date"
                    v-model="form.date"
                    :error-messages="form.errors.date"
                    />
                </div>

                <div class="flex-1">
                    <AppInput 
                    label="Время"
                    type="time"
                    v-model="form.time"
                    :error-messages="form.errors.time"
                    />
                </div>
            </div>
            
            <AppAutocomplete 
                label="Адрес"
                item-title="address"
                :items="addresses"
                item-value="id"
                v-model="form.addressId"
                :error-messages="form.errors.addressId"
                :loading="http.processing"
            />

            <v-number-input 
                v-model="form.capacity"
                :error-messages="form.errors.capacity"
                control-variant="hidden"
                label="Количество ИГ"
                :min="0"
            />

            <AppAutocomplete 
                label="Тестеры"
                item-title="fullName"
                :items="examiners"
                v-model="form.examiners"
                item-value="id"
                :error-messages="form.errors.examiners"
                multiple    
                :loading="http.processing"
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
            ></v-textarea>
        </form>
        <template #actions >
            <AddButton  
                text="Добавить"
                @click="create"
                :disabled="form.processing"
                :loading="form.processing"
            />
        </template>
    </BaseDialog>
</template>