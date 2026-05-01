<script setup lang="ts">
import AppPrimaryButton from '@components/UI/AppPrimaryButton/AppPrimaryButton.vue';
import AppPeriodDate from '@components/UI/AppPeriodDate/AppPeriodDate.vue';
import BaseDialog from '@components/BaseComponents/BaseDialog/BaseDialog.vue'; 
import { useHttp } from '@inertiajs/vue3';
import { RedirectUrl } from '@/interfaces/Interfaces';

const isOpen = defineModel<boolean>({default:false})

const http = useHttp<FlatTable, RedirectUrl>({
    dateFrom:null,
    dateTo:null
})

interface FlatTable{
    dateFrom:string | null,
    dateTo:string |  null
}

const donwload = () => {
    window.open(`/reports/flat-table?dateFrom=${http.dateFrom}&dateTo=${http.dateTo}`)
}

</script>

<template>
    <BaseDialog 
        width="500"
        title="Плоская таблица"
        v-model="isOpen"
        @before-close="(close) => close()"
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