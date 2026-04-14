<script setup lang="ts">
import AppPrimaryButton from '@components/UI/AppPrimaryButton/AppPrimaryButton.vue';
import AppInput from '@components/UI/AppInput/AppInput.vue';
import BaseDialog from '@components/BaseComponents/BaseDialog/BaseDialog.vue';
import { useForm } from '@inertiajs/vue3';
import AppAutocomplete from '@components/UI/AppAutocomplete/AppAutocomplete.vue';

const isOpen = defineModel<boolean>()

const items = [
    { name: 'Сертификаты', success : true},
    { name: 'Справки', success : false}
]
const  download = async () => {
    form.get('/reports/frdo/available', {
        onSuccess:(page) => {
            if(page.flash.redirectUrl){
                window.location.href = String(page.flash.redirectUrl)
            }
            
        },
        preserveState:true,
        preserveScroll:true
    })
    
}

const form = useForm({
    examDate:null,
    success:null
})

const beforeClose = async (fn : () => void) => {
    form.examDate = null
    form.success = null
    fn()
}
</script>

<template>
    
    <BaseDialog 
        v-model="isOpen"
        title="Отчеты ФИС ФРДО"
        width="500"
        @before-close="(done) => beforeClose(done)"
    >
        <AppAutocomplete
            label="Тип"
            :items=items
            item-value="success"
            item-title="name"
            clearable
            :error-messages="form.errors.success"
            :rules="[form.success  === !!form.success]"
            v-model="form.success"
        />

        <AppInput
            label="Дата"
            v-model="form.examDate"
            type="date"
            :error-messages="form.errors.examDate"
            :disabled="form.success === null"
        />
        
        <template #actions>
            <AppPrimaryButton
                @click="download"
                text="Скачать"
                :disabled="!form.examDate || form.success === null || form.processing"
            />
        </template>
    </BaseDialog>
</template>