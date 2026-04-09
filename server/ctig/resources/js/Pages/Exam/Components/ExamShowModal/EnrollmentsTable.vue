<script setup lang="ts">

import { Exam, ForeignNational } from '../../../../interfaces/interfaces';
import { useModals } from '../../../../Composables/useModals';
import { attemptResultStatus } from '../../../../Helpers/heplers';
import AppStatusChip from '../../../../Components/AppStatusChip/AppStatusChip.vue';
import EnrollmetsTableDropdown from './EnrollmetsTableDropdown.vue';

const props = defineProps<{
    exam: Exam
}>()

function foreignNationalShowModal(event:Event, {item}: any) {
    const {open} = useModals()
    open('foreignNationalShow', {foreignNationalId:item.id})  
}

const headers = [
    {title : "ФИО",sortable: false, key: 'foreignNational.fullName', align: 'start' },
    {title : "Паспорт",sortable: false, key: 'foreignNational.fullPassport', align: 'start' },
    {title : "Результаты",sortable: false, key: 'results', align: 'center' },
    {title : "Оплата",sortable: false, key: 'hasPayment', align: 'center' },
    {title : "",sortable: false, key: 'actions', align: 'end' },
]
props.exam.enrollments.forEach(fn => {
    if (fn.isLoading === undefined) fn.isLoading = false
})
</script>

<template>
    <v-data-table 
        :items="exam.enrollments"
        hide-default-footer
        :headers="headers"
        fixed-header
        hover
        @click:row="foreignNationalShowModal"
    >
        <template #item.hasPayment="{ item }" >
            <v-icon :color="item.hasPayment ? 'green' : 'red'" v-if="!item.isLoading">
                {{ item.hasPayment ? 'mdi-check-circle' : 'mdi-close-circle' }}
            </v-icon>
            <v-progress-circular v-else indeterminate color="primary" />
        </template>
        <template #item.actions="{item}">
            <EnrollmetsTableDropdown 
                :enrollment="item"
                :exam="exam"
                :loading="item"
            />
        </template>
        <template #item.results="{ item }">
            <AppStatusChip
                :color="attemptResultStatus(item?.foreignNational.attempts?.[0] ?? null, exam?.isPast).color"
                :text="attemptResultStatus(item?.foreignNational.attempts?.[0] ?? null, exam?.isPast).text"
            /> 
        </template>
    </v-data-table>
</template>