<script setup lang="ts">
import BaseDialog from '@/components/BaseComponents/BaseDialog/BaseDialog.vue';
import AppCheckbox from '@/components/UI/AppCheckbox/AppCheckbox.vue';
import AppPeriodDate from '@/components/UI/AppPeriodDate/AppPeriodDate.vue';
import AppPrimaryButton from '@/components/UI/AppPrimaryButton/AppPrimaryButton.vue';
import { RedirectUrl } from '@/interfaces/Interfaces';
import { useHttp } from '@inertiajs/vue3';
import { computed, watch } from 'vue';

const isOpen = defineModel<boolean>({default:false})

interface MinistryEducation{
    lastWeek:boolean,
    dateFrom:string | null,
    dateTo: string | null
}

const http = useHttp<MinistryEducation, RedirectUrl>({
    lastWeek:false,
    dateFrom:null,
    dateTo:null
})

const download = () => {
    http.get('/reports/ministry-education/available',{
        onSuccess(response) {
            window.open(response.redirectUrl)
        },
    })
}
watch(() => http.lastWeek, (lastWeek) => {
    if(lastWeek){
        http.dateFrom = null
        http.dateTo = null
    }
})

const loading = computed(() =>http.processing)

const isCustomPeriodInvalid = computed(() =>
  !http.lastWeek && (!http.dateFrom || !http.dateTo)
)

const disabled = computed(() =>
  loading.value || isCustomPeriodInvalid.value
)

</script>

<template>
    <BaseDialog
        v-model="isOpen"
        width="500"
        title="Отчет МИНОБРНАУКИ"
        @before-close="(close) => {
            http.resetAndClearErrors()
            close()
        }"
    >
        <AppCheckbox 
            label="Предыдущая неделя"
            v-model="http.lastWeek"
            :error-messages="http.errors.lastWeek"
        />
        <div v-if="!http.lastWeek">
            <AppPeriodDate
                v-model:date-from="http.dateFrom"
                v-model:date-to="http.dateTo"
            />
        </div>
        <template #actions>
            <AppPrimaryButton 
                text="Скачать"
                @click="download"
                :loading="loading"
                :disabled="disabled"
            />
        </template>
    </BaseDialog>
</template>