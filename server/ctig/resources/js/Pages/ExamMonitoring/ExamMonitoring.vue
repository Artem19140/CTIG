<script setup lang="ts">
import DropDownForeignNationalsList from './DropDownForeignNationalsList.vue';
import { router, usePoll } from '@inertiajs/vue3'
import { attemptStatus } from '../../Helpers/heplers';
import ExamStatusChip from '../Exam/Components/ExamStatusChip.vue';
import BaseLayout from '../../Layout/BaseLayout.vue';
import EmployeeLayout from '../../Layout/EmployeeLayout.vue';
import { useModals } from '../../Composables/useModals';
import { onMounted, onUnmounted, ref } from 'vue';
import AppStatusChip from '../../Components/AppStatusChip/AppStatusChip.vue';
import { Enrollment, Exam } from '../../interfaces/interfaces';
import { DateFormatter } from '../../Helpers/DateFormatter';

defineOptions({
  layout: [BaseLayout,EmployeeLayout]
})

const props = defineProps<{
    foreignNationals:any,
    exam:any,
    hasSpeakingTasks:boolean
}>()

const enrollments = ref<Enrollment[]>(props.exam.data.enrollments)

const { start, stop } = usePoll(10000, {
        only: ['foreignNationals'],
        onStart() {
                console.log('Polling request started')
        },
        onFinish() {
                console.log('Polling request finished')
        }
        }, {
    autoStart: false,
})

onMounted(()=>{
    console.log(props.exam.data)
    if(props.exam?.data?.isGoing && props.exam?.data?.foreignNationals?.length>0 && !props.exam?.data?.isCancelled){
        start()
    }
})

onUnmounted(()=>{
    stop()
})

const headers = [
    {title:'ФИО', key:"foreignNational.fullName",sortable: false},
    {title:'Паспорт', key:"foreignNational.fullPassport",sortable: false},
    {title:'Попытка', key:"status",sortable: false, align:'center'},
    {
        title:'Время',
        align:'center',
        children:[
            {title:'Начала',key:'startTime',sortable: false, align:'center'},
            {title:'Завершения',key:'endTime',sortable: false, align:'center'}
        ]
    },
    {title:'Оплата', key:"hasPayment",sortable: false,align: 'center'},
    {title:'', key:"actions",sortable: false,align: 'end'}
]

const {open} = useModals()
const openForeignNational = (event : Event, {item} :any) => {
    open('foreignNationalShow', {foreignNationalId:item.id})
}
</script>


<template>
    <v-btn class="mt-4 ml-4" @click="router.visit('/exams/monitoring')">Назад</v-btn>
    <v-card 
        width="900" 
        class="mx-auto mt-16"
        
    >
        <v-card-title>
            Мониторинг
            <ExamStatusChip 
                :exam="exam.data" 
            />
        </v-card-title>

        <v-card-subtitle >
            <div class="flex">
                <div>
                    <div>{{ exam.data.shortName }}</div>
                    <div>{{ new DateFormatter(exam.data?.beginTime).format('H:i') }}
                        - {{ new DateFormatter(exam.data?.endTime).format('H:i, d.m.Y') }}
                    </div>
                </div>
                <v-spacer />
                <v-btn 
                    border 
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
                :loading="foreignNationals.lenght === 0"
                @click:row="openForeignNational"
            >
                <template  #item.actions="{ item }">
                    <DropDownForeignNationalsList :foreignNational="item.foreignNational" :hasSpeakingTasks="hasSpeakingTasks" />
                </template>
                <template #item.status="{ item }">
                    <AppStatusChip
                        v-if="item.length"
                        :color="attemptStatus(item.foreignNational.attempts[0]?.status).color.replace('text-', '')"
                        :text="attemptStatus(item.foreignNational.attempts[0]?.status).text"
                    />
                    <span v-else></span>
                </template>
                <template  #item.startTime="{ item }">
                    {{new DateFormatter(item.foreignNational.attempts[0]?.startedAt).format('H:i')}}
                </template>
                <template  #item.endTime="{ item }">
                    {{new DateFormatter(item.foreignNational.attempts[0]?.finishedAt ?? '').format('H:i')}}
                </template>
                <template #item.hasPayment="{ item }">
                    <v-icon :color="item.hasPayment ? 'green' : 'red'">
                        {{ item.hasPayment ? 'mdi-check-circle' : 'mdi-close-circle' }}
                    </v-icon>
                </template>
            </v-data-table>
        </v-card-text>
    </v-card>
</template>