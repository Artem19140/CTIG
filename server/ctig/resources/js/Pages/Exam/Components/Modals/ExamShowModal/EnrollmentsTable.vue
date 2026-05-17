<script setup lang="ts">

import { useModals } from '@composables/useModals';
import { ref } from 'vue';
import EnrollmentDropDown from '@/components/Enrollment/EnrollmentDropDown.vue';
import ExamResultStatusChip from '@/components/Exam/ExamResultStatusChip.vue';
import PaymentIcon from '@/components/Enrollment/PaymentIcon.vue';
import { Exam, ExamActionsPermissions } from '@/interfaces/Exam';

const props = defineProps<{
    exam: Exam,
    permissions:ExamActionsPermissions
}>()

const exam = ref<Exam>(props.exam)

const modals = useModals()

function foreignNationalShowModal(event:Event, {item}: any) {
    modals.open('foreignNationalShow', {foreignNationalId:item.foreignNational.id})  
}

const headers = [
    {title : "ФИО",sortable: false, key: 'foreignNational.fullName', align: 'start' },
    {title : "Паспорт",sortable: false, key: 'foreignNational.fullPassport', align: 'start' },
    {title : "Оплата",sortable: false, key: 'hasPayment', align: 'center' },
    {title : "Результаты",sortable: false, key: 'results', align: 'center' },
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
        hover
        @click:row="foreignNationalShowModal"
        v-if="permissions.enrollments.view"
        :items-per-page="-1"
    >
        <template #item.hasPayment="{ item }" >
            <PaymentIcon :enrollment="item" />
        </template>
        <template #item.actions="{item}">
            <EnrollmentDropDown 
                v-if="permissions.enrollments.statement || permissions.enrollments.payment"
                :permissions="permissions"
                :enrollment="item"
                :exam="exam"
                :loading="item"
            />
        </template>
        <template #item.results="{ item }">
            <ExamResultStatusChip 
                :status="item.examResult"
            />
        </template>
    </v-data-table>
</template>