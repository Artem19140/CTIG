<script setup lang="ts">
import { ref } from 'vue';
import AddButton from '../../../Components/AddButton/AddButton.vue';
import AppInput from '../../../Components/AppInput/AppInput.vue';
import BaseDialog from '../../../Components/BaseDialog/BaseDialog.vue'; 
import { downloadFile } from '../../../Helpers/heplers';
import { useHttp } from '@inertiajs/vue3';

const isOpen = defineModel<boolean>({default:false})

const form = ref({
    dateFrom:'',
    dateTo:''
})

const http = useHttp({
    dateFrom:'',
    dateTo:''
})

const donwload = () => {
    http.get('/reports/flat-table',{
        onSuccess:(response :any) => {
            const url = window.URL.createObjectURL(response);
            const disposition = response.headers.get('content-disposition');

            let filename = 'download';

            if (disposition?.includes('filename=')) {
            filename = disposition.split('filename=')[1].replace(/"/g, '');
            }
            const a = document.createElement('a');
            a.download = filename;
            
            a.href = url;
           
            a.click();

            window.URL.revokeObjectURL(url);
        }
    })
    //window.location.href = `/reports/flat-table?dateFrom=${form.value.dateFrom}&dateTo=${form.value.dateTo}`
}

</script>

<template>
    <BaseDialog 
        width="500"
        title="Плоская таблица"
        v-model="isOpen"
        @before-close="(done) => done()"
    >
        <AppInput
            v-model="http.dateFrom"
            label="Дата с"
            :error-messages="http.errors.dateFrom"
            type="date"
        />
        <AppInput
            v-model="http.dateTo"
            label="Дата по"
            :error-messages="http.errors.dateTo"
            type="date"
        />
        <template #actions>
            <AddButton 
                :disabled="!http.dateFrom || !http.dateTo"
                text="Скачать"
                @click="donwload"
            />
        </template>
    </BaseDialog>
</template>