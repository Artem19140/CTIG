<script setup lang="ts">
import EnrollmentMonitoringDropdown from './EnrollmentMonitoringDropdown.vue';
import { usePoll } from '@inertiajs/vue3'
import { attemptStatus } from '@helpers/heplers';
import ExamStatusChip from '@components/Exam/ExamStatusChip.vue';
import BaseLayout from '@layouts/BaseLayout.vue';
import EmployeeLayout from '@layouts/EmployeeLayout.vue';
import { useModals } from '@composables/useModals';
import { onMounted, onUnmounted, ref, watch } from 'vue';
import AppStatusChip from '@components/UI/AppStatusChip/AppStatusChip.vue';
import { Enrollment, Exam } from '@interfaces/Interfaces';
import { DateFormatter } from '@helpers/DateFormatter';
import { useExamStatus } from '@/composables/useExamStatus';
import BaseContainer from '@/components/BaseComponents/BaseContainer/BaseContainer.vue';
import PaymentIcon from '@/components/Enrollment/PaymentIcon.vue';
import ExamMonitoringDropdown from './ExamMonitoringDropdown.vue';

defineOptions({
  layout: [BaseLayout,EmployeeLayout]
})

const props = defineProps<{
    exam:{
        data:Exam
    },
}>()

const enrollments = ref<Enrollment[]>(props.exam.data.enrollments)
watch(() => props.exam.data.enrollments, (data) => {
    enrollments.value = data
})

const { start, stop } = usePoll(10000, {}, {
    autoStart: false,
})
const {isGoing, isCancelled} = useExamStatus(props.exam.data)
onMounted(()=>{
    if(isGoing.value && !isCancelled.value && enrollments.value.length > 0){
        start()
    }
})

onUnmounted(()=>{
    stop()
})

const headers = [
    {title:'ФИО', key:"foreignNational.fullName",sortable: false},
    {title:'Паспорт', key:"foreignNational.fullPassport",sortable: false},
    {title:'Оплата', key:"hasPayment",sortable: false,align: 'center'},
    {title:'Попытка', key:"status",sortable: false, align:'center'},
    {
        title:'Время',
        align:'center',
        children:[
            {title:'Начала',key:'startedAt',sortable: false, align:'center'},
            {title:'Завершения',key:'finishedAt',sortable: false, align:'center'}
        ]
    },
    {title:'', key:"actions",sortable: false,align: 'end'}
]

const {open} = useModals()
const openForeignNational = (event : Event, {item} :any) => {
    open('foreignNationalShow', {foreignNationalId:item.foreignNational.id})
}

const back = () => {
    window.history.go(-1)}
</script>


<template>
    <v-btn class="mt-4 ml-4" @click="back">Назад</v-btn>
    <BaseContainer>
            <v-card-title>
                <div class="flex justify-between">
                    <div>
                        Мониторинг
                        <ExamStatusChip 
                            :exam="exam.data" 
                        />
                    </div>
                    <div>
                        <ExamMonitoringDropdown :exam="exam.data" />
                    </div>
                </div>
            </v-card-title>

            <v-card-subtitle >
                <div>
                    <div>
                        <div>{{ exam.data.shortName }}</div>
                        <div>{{ new DateFormatter(exam.data?.beginTime).format('H:i') }}
                            - {{ new DateFormatter(exam.data?.endTime).format('H:i, d.m.Y') }}
                        </div>
                    </div>
                    <v-spacer />
                    <v-btn 
                        border 
                        v-if="isGoing"
                        variant="text" 
                        class="mr-4 text-black"
                        @click="open('examComment', {exam:props.exam.data})"
                    >Комментарий</v-btn>
                    
                </div>
            </v-card-subtitle>

            <v-card-text>
                <v-data-table
                    :items="enrollments"
                    :headers="headers"
                    hover
                    hide-default-footer
                    @click:row="openForeignNational"
                >
                    <template  #item.actions="{ item }">
                        <EnrollmentMonitoringDropdown  :enrollment="item" :exam="exam.data" />
                    </template>
                    <template #item.status="{ item }">
                        <AppStatusChip
                            v-if="item.attempt"
                            :color="attemptStatus(item.attempt?.status).color.replace('text-', '')"
                            :text="attemptStatus(item.attempt?.status).text"
                        />
                        <span v-else></span>
                    </template>
                    <template  #item.startedAt="{ item }">
                        {{new DateFormatter(item.attempt?.startedAt).format('H:i')}}
                    </template>
                    <template  #item.finishedAt="{ item }">
                        {{new DateFormatter(item.attempt?.finishedAt ?? '').format('H:i')}}
                    </template>
                    <template #item.hasPayment="{ item }">
                        <PaymentIcon :enrollment="item" />
                    </template>
                </v-data-table>
            </v-card-text>
    </BaseContainer>
</template>