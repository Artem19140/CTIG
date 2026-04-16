<script setup lang="ts">

import { Enrollment, Exam} from '@interfaces/Interfaces';
import { useModals } from '@composables/useModals';
import { ref } from 'vue';
import EnrollmentDropDown from '@/components/Enrollment/EnrollmentDropDown.vue';
import ExamResultStatusChip from '@/components/Exam/ExamResultStatusChip.vue';
import PaymentIcon from '@/components/Enrollment/PaymentIcon.vue';

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
    {title : "Оплата",sortable: false, key: 'hasPayment', align: 'center' },
    {title : "Результаты",sortable: false, key: 'results', align: 'center' },
    {title : "",sortable: false, key: 'actions', align: 'end' },
]
props.exam.enrollments.forEach(fn => {
    if (fn.isLoading === undefined) fn.isLoading = false
})

const cancell = (value : Enrollment) => {
    exam.value.enrollments = exam.value.enrollments.filter(e => e.id !== value.id)
    exam.value.enrollmentsCount -= 1 //Почему computed не пересчитывает!
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
            <PaymentIcon :enrollment="item" />
            <!-- <v-icon :color="item.hasPayment ? 'green' : 'red'" v-if="!item.isLoading">
                {{ item.hasPayment ? 'mdi-check-circle' : 'mdi-close-circle' }}
            </v-icon>
            <v-progress-circular v-else indeterminate color="primary" /> -->
        </template>
        <template #item.actions="{item}">
            <EnrollmentDropDown 
                :enrollment="item"
                :exam="exam"
                :loading="item"
                @cancell="cancell"
                @reschedule="reschedule"
            />
        </template>
        <template #item.results="{ item }">
            <ExamResultStatusChip 
                :status="item.examResult"
            />
        </template>
    </v-data-table>
</template>