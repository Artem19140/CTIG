<script setup lang="ts">
import AppAutocomplete from '@components/UI/AppAutocomplete/AppAutocomplete.vue';
import BaseDialog from '@components/BaseComponents/BaseDialog/BaseDialog.vue';
import AppPrimaryButton from '@components/UI/AppPrimaryButton/AppPrimaryButton.vue';
import countries from '@data/countries.json'
import { useHttp } from '@inertiajs/vue3';
import AppPeriodDate from '@/components/UI/AppPeriodDate/AppPeriodDate.vue';
import { RedirectUrl } from '@/interfaces/Interfaces';

const isOpen = defineModel({default:false})

interface ForeignNationalExport{
    dateFrom:string | null,
    dateTo: string | null,
    citizenship: string | undefined,
}

const http = useHttp<ForeignNationalExport, RedirectUrl>({
    dateFrom:null,
    dateTo:null,
    citizenship:undefined,
})

const download = () => {
    http.get('/foreign-nationals/export/available',{
        onSuccess(response){
            if(response.redirectUrl){
                window.open(response.redirectUrl)
            }
        }
    })
}
</script>

<template>
    <BaseDialog
        width="500"
        v-model="isOpen"
        title="Выгрузка ИГ"
        @before-close="(close) => close()"
    >
        <AppPeriodDate 
            :errors="http.errors"
            v-model:date-from="http.dateFrom"
            v-model:date-to="http.dateTo"
        />

        <AppAutocomplete
            label="Гражданство"
            item-title="text"
            :items="countries"
            item-value="value"
            v-model="http.citizenship"
            :error-messages="http.errors.citizenship"
        />
        <template #actions>
            <AppPrimaryButton
                :loading="http.processing"
                :disabled="http.processing || !http.dateFrom ||!http.dateTo" 
                @click="download"
                text="Выгрузить"
            />
        </template>
    </BaseDialog>
</template>