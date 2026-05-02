<script setup lang="ts">
import AppPrimaryButton from '@components/UI/AppPrimaryButton/AppPrimaryButton.vue';
import AppInput from '@components/UI/AppInput/AppInput.vue';
import BaseDialog from '@components/BaseComponents/BaseDialog/BaseDialog.vue';
import { useHttp } from '@inertiajs/vue3';
import AppAutocomplete from '@components/UI/AppAutocomplete/AppAutocomplete.vue';
import { RedirectUrl } from '@/interfaces/Interfaces';

const isOpen = defineModel<boolean>()

const http = useHttp<FrdoExport, RedirectUrl>({
    examDate:null,
    success:null
})

const  download = async () => {
    http.get('/reports/frdo/available', {
        onSuccess:(response) => {
            if(response.redirectUrl){
                window.location.href = response.redirectUrl
            }     
        }
    })
}

interface FrdoExport{
    examDate:string | null,
    success: boolean | null
}

const items = [
    { name: 'Сертификаты', success : true},
    { name: 'Справки', success : false}
]
</script>

<template>
    <BaseDialog 
        v-model="isOpen"
        title="Отчеты ФИС ФРДО"
        width="500"
        @before-close="(close) => {
            http.resetAndClearErrors()
            close()
        }"
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