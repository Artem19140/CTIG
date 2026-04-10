<script setup lang="ts">
import AppAutocomplete from '../../../../Components/AppAutocomplete/AppAutocomplete.vue';
import AppInput from '../../../../Components/AppInput/AppInput.vue';
import BaseDialog from '../../../../Components/BaseDialog/BaseDialog.vue';
import PrimaryButton from '../../../../Components/PrimaryButton/PrimaryButton.vue';
import countries from '../../../../../../storage/app/public/countries.json'
import { useHttp } from '@inertiajs/vue3';

const isOpen = defineModel({default:false})
const http = useHttp({
    dateFrom:null,
    dateTo:null,
    citizenship:undefined,
    withAttemtps:false
})

const download = () => {

    window.location.href = `/foreign-nationals/export?dateFrom=${http.dateFrom}&dateTo=${http.dateTo}`
    //http.get('/foreign-nationals/export') &citizenship=${http.citizenship}
}
</script>

<template>
    <BaseDialog
        width="500"
        v-model="isOpen"
        title="Выгрузка ИГ"
        @before-close="(done) => done()"
    >
        <AppInput
            label="Дата с"
            type="date"
            :required="true"
            v-model="http.dateFrom"
            :error-messages="http.errors.dateFrom"
        />

        <AppInput
            label="Дата по"
            :required="true"
            type="date"
            v-model="http.dateTo"
            :error-messages="http.errors.dateTo"
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
            <PrimaryButton
                :loading="http.processing"
                :disabled="http.processing" 
                @click="download"
                text="Выгрузить"
            />
        </template>
    </BaseDialog>
</template>