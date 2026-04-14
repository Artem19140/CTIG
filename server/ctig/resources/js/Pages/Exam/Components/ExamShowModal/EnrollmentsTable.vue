<script setup lang="ts">

import { Enrollment, Exam} from '@interfaces/interfaces';
import { useModals } from '@composables/useModals';
import { attemptResultStatus } from '@helpers/heplers';
import AppStatusChip from '@components/UI/AppStatusChip/AppStatusChip.vue';
import EnrollmetsTableDropdown from './EnrollmetsTableDropdown.vue';
import { ref } from 'vue';

const props = defineProps<{
    exam: Exam
}>()
const exam = ref<Exam>(props.exam)

const emit = defineEmits<{
    (e:'reschedule', value:Enrollment) : void
}>()

const modals = useModals()

function foreignNationalShowModal(event:Event, {item}: any) {
    modals.open('foreignNationalShow', {foreignNationalId:item.foreignNational.id})  
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

const cancell = (value : Enrollment) => {
    exam.value.enrollments = exam.value.enrollments.filter(e => e.id !== value.id)
}

const reschedule = (value : Enrollment) => {
    exam.value.enrollments =exam.value.enrollments.filter(e => e.id !== value.id)
    emit('reschedule', value)
}
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
                @cancell="cancell"
                @reschedule="reschedule"
            />
        </template>
        <template #item.results="{ item }">
            {{ item?.foreignNational.attempt }}
            <AppStatusChip
                :color="attemptResultStatus(item?.foreignNational.attempt, exam?.isPast).color"
                :text="attemptResultStatus(item?.foreignNational.attempt, exam?.isPast).text"
            /> 
        </template>
    </v-data-table>
</template>