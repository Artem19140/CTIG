<script setup lang="ts">
import BaseList from '@/components/BaseComponents/BaseList/BaseList.vue';
import BaseListItem from '@/components/BaseComponents/BaseList/BaseListItem.vue';
import AppPeriodDate from '@/components/UI/AppPeriodDate/AppPeriodDate.vue';
import AppPrimaryButton from '@/components/UI/AppPrimaryButton/AppPrimaryButton.vue';
import { PeriodDate } from '@/interfaces/Interfaces';
import EmployeeLayout from '@/layouts/EmployeeLayout.vue';
import SuperAdminLayout from '@/layouts/SuperAdminLayout.vue';
import { router, useHttp } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

defineOptions({
  layout: [EmployeeLayout, SuperAdminLayout],
})

interface Stats {
  examsCount: number
  enrollmentsCount: number
  foreignNationalsCount: number
  attemptsCount: number
  pendingAttempts: number
}

const stats = ref<Stats>({
  examsCount: 0,
  enrollmentsCount: 0,
  foreignNationalsCount: 0,
  attemptsCount: 0,
  pendingAttempts: 0,
})

const statistics = computed( () => [
    {label:'Экзамены', value: stats.value.examsCount},
    {label:'Записи на экзамен', value: stats.value.enrollmentsCount},
    {label:'ИГ', value: stats.value.foreignNationalsCount},
    {label:'Попытки', value: stats.value.attemptsCount},
    {label:'Попытки pending', value: stats.value.pendingAttempts}
]);
const http = useHttp<PeriodDate, Stats>({
    dateFrom:null,
    dateTo:null
})

const get = () => {
    http.get(`/admin/statistics`,{
        onSuccess(response) {
            stats.value = {... response}
        },
    })
}
window.addEventListener('offline', () => {
  console.log('Нет интернета')
})
</script>

<template>
     <v-container class="py-6">
        <v-card>
            <v-card-text>
                <AppPeriodDate
                    class="mb-2"
                    v-model:date-from="http.dateFrom"
                    v-model:date-to="http.dateTo"
                    :errors="http.errors"
                />
                <AppPrimaryButton 
                    @click="get"
                    text="Получить"
                />
            </v-card-text>
        
        <BaseList

        >
            <BaseListItem 
                v-for="stat in statistics"
                :key="stat.label"
                :title="stat.label"
                :subtitle="stat.value"
            >
            </BaseListItem>
        </BaseList>
    </v-card>
  </v-container>
</template>