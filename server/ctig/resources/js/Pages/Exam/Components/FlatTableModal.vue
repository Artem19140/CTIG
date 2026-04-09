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
    window.location.href = `/reports/flat-table?dateFrom=${http.dateFrom}&dateTo=${http.dateTo}`
    // http.get('/reports/flat-table',{
    //     onSuccess:(response :any) => {
    //         window.location.href = response
    //     }
    // })
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
            required
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
                :loading="http.processing"
                text="Скачать"
                @click="donwload"
            />
        </template>
    </BaseDialog>
</template>