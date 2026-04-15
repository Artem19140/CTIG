<script setup lang="ts">
import { ref } from 'vue';
import AppPrimaryButton from '@components/UI/AppPrimaryButton/AppPrimaryButton.vue';
import AppPeriodDate from '@components/UI/AppPeriodDate/AppPeriodDate.vue';
import AppInput from '@components/UI/AppInput/AppInput.vue';
import BaseDialog from '@components/BaseComponents/BaseDialog/BaseDialog.vue'; 
import { useHttp } from '@inertiajs/vue3';

const isOpen = defineModel<boolean>({default:false})

const form = ref({
    dateFrom:'',
    dateTo:''
})

const http = useHttp({
    dateFrom:null,
    dateTo:null
})

const donwload = () => {
    window.open(`/reports/flat-table?dateFrom=${http.dateFrom}&dateTo=${http.dateTo}`)
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
        <AppPeriodDate 
            :errors="http.errors"
            v-model:date-from="http.dateFrom"
            v-model:date-to="http.dateTo"
        />  
        <template #actions>
            <AppPrimaryButton 
                :disabled="!http.dateFrom || !http.dateTo"
                :loading="http.processing"
                text="Скачать"
                @click="donwload"
            />
        </template>
    </BaseDialog>
</template>