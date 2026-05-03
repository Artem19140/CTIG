<script setup lang="ts">
import EnrollmentMonitoringDropdown from './EnrollmentMonitoringDropdown.vue';
import { usePoll } from '@inertiajs/vue3'
import { attemptStatus } from '@helpers/heplers';
import ExamStatusChip from '@components/Exam/ExamStatusChip.vue';
import EmployeeLayout from '@layouts/EmployeeLayout.vue';
import { useModals } from '@composables/useModals';
import { computed, onMounted, onUnmounted, watch} from 'vue';
import AppStatusChip from '@components/UI/AppStatusChip/AppStatusChip.vue';
import { DateFormatter } from '@helpers/DateFormatter';
import { useExamStatus } from '@/composables/useExamStatus';
import BaseContainer from '@/components/BaseComponents/BaseContainer/BaseContainer.vue';
import PaymentIcon from '@/components/Enrollment/PaymentIcon.vue';
import ExamMonitoringDropdown from './ExamMonitoringDropdown.vue';
import { ExamMonitoring } from '@/interfaces/Exam';
import AppTooltip from '@/components/UI/AppTooltip/AppTooltip.vue';

defineOptions({
  layout: [EmployeeLayout]
})

const props = defineProps<{
    exam:{
        data:ExamMonitoring
    },
}>()

const pollFrequency = 15000

const { start, stop } = usePoll(pollFrequency, {}, {
    autoStart: false,
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

if(props.exam.data.hasSpeakingTasks){
    headers.splice(headers.length -1 , 0, {title:'Говор.', key:"speaking",sortable: false, align:'center'})
}

const {isGoing, isCancelled} = useExamStatus(props.exam.data)

const pollingCanStart = computed(() => isGoing.value && !isCancelled.value && props.exam.data.enrollments.length > 0)

onMounted(()=>{
    if(pollingCanStart.value){
        start()
    }
})

onUnmounted(()=>{
    stop()
})

const {open} = useModals()

const openForeignNational = (event : Event, {item} :any) => {
    open('foreignNationalShow', {foreignNationalId:item.foreignNational.id})
}

const back = () => {
    window.history.go(-1)
}
</script>


<template>
    <v-btn class="mt-4 ml-4" @click="back">Назад</v-btn>
    <BaseContainer>
            <v-card-title>
                <div class="flex justify-between">
                    <div>
                        Мониторинг
                        <ExamStatusChip 
                            :status="exam.data.status" 
                        />
                        <AppTooltip 
                            v-if="pollingCanStart"
                        >
                            <div >
                                <div>
                                Обновления происходят каждые {{pollFrequency / 1000}} секунд
                                </div>
                                <div>
                                При необходимости обновите страницу
                                </div>
                            </div>
                        </AppTooltip>    
                        
                    </div>
                    <ExamMonitoringDropdown :exam="exam.data" />
                </div>
            </v-card-title>

            <v-card-subtitle >
                <div>{{ exam.data.shortName }} </div>
                <div>
                    {{ new DateFormatter(exam.data?.beginTime).format('H:i, d.m.Y') }}

                </div>
            </v-card-subtitle>

            <v-card-text>
                <v-data-table
                    :items="props.exam.data.enrollments"
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
                        {{new DateFormatter(item.attempt?.startedAt ?? '').format('H:i')}}
                    </template>
                    <template  #item.finishedAt="{ item }">
                        {{new DateFormatter(item.attempt?.finishedAt ?? '').format('H:i')}}
                    </template>
                    <template #item.hasPayment="{ item }">
                        <PaymentIcon :enrollment="item" />
                    </template>
                    <template #item.speaking="{ item }">
                        <v-icon icon="mdi-check-circle" color="green" v-if="item.attempt?.speakingFinishedAt"/>
                    </template>
                </v-data-table>
            </v-card-text>
    </BaseContainer>
</template>