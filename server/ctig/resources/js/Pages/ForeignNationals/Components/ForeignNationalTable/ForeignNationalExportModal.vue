<script setup lang="ts">
import AppAutocomplete from '@components/UI/AppAutocomplete/AppAutocomplete.vue';
import BaseDialog from '@components/BaseComponents/BaseDialog/BaseDialog.vue';
import AppPrimaryButton from '@components/UI/AppPrimaryButton/AppPrimaryButton.vue';
import countries from '../../../../../../storage/app/public/countries.json'
import { useHttp } from '@inertiajs/vue3';
import AppPeriodDate from '@/components/UI/AppPeriodDate/AppPeriodDate.vue';

const isOpen = defineModel({default:false})
const http = useHttp({
    dateFrom:null,
    dateTo:null,
    citizenship:undefined,
    withAttemtps:false
})

const download = () => {
    http.get('/foreign-nationals/export/available',{
        onSuccess(response: any){
            if(response.redirectUrl){
                window.open(String(response.redirectUrl))
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
        @before-close="(done) => done()"
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