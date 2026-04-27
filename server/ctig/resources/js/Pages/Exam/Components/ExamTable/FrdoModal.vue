<script setup lang="ts">
import AppPrimaryButton from '@components/UI/AppPrimaryButton/AppPrimaryButton.vue';
import AppInput from '@components/UI/AppInput/AppInput.vue';
import BaseDialog from '@components/BaseComponents/BaseDialog/BaseDialog.vue';
import { useHttp } from '@inertiajs/vue3';
import AppAutocomplete from '@components/UI/AppAutocomplete/AppAutocomplete.vue';

const isOpen = defineModel<boolean>()

const items = [
    { name: 'Сертификаты', success : true},
    { name: 'Справки', success : false}
]
const  download = async () => {
    http.get('/reports/frdo/available', {
        onSuccess:(response:any) => {
            if(response.redirectUrl){
                window.location.href = String(response.redirectUrl)
            }
            
        }
    })
    
}

const http = useHttp({
    examDate:null,
    success:null
})

const beforeClose = async (fn : () => void) => {
    http.resetAndClearErrors()
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
            :error-messages="http.errors.success"
            :rules="[http.success  === !!http.success]"
            v-model="http.success"
        />

        <AppInput
            label="Дата"
            v-model="http.examDate"
            type="date"
            :error-messages="http.errors.examDate"
            :disabled="http.success === null"
        />
        
        <template #actions>
            <AppPrimaryButton
                @click="download"
                text="Скачать"
                :disabled="!http.examDate || http.success === null || http.processing"
            />
        </template>
    </BaseDialog>
</template>