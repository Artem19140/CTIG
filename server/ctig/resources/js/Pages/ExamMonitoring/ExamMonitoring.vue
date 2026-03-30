<script setup lang="ts">
import DropDownForeignNationalsList from './DropDownForeignNationalsList.vue';
import { router, usePoll } from '@inertiajs/vue3'
import { attemptStatus } from '../../Helpers/heplers';
import ExamStatusChip from '../Exam/Components/ExamStatusChip.vue';


const props = defineProps<{
    foreignNationals:any,
    exam:any,
    hasSpeakingTasks:boolean
}>()

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
    if(props.exam?.data?.isGoing && props.exam?.data?.foreignNationals?.length>0 && !props.exam?.data?.isCancelled){
        start()
    }
})

onUnmounted(()=>{
    stop()
})

const headers = [
    {title:'ФИО', key:"fullName",sortable: false},
    {title:'Паспорт', key:"fullPassport",sortable: false},
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

<script lang="ts">
import EmployeeLayout from '../../Layout/EmployeeLayout.vue';
import { useModals } from '../../Composables/useModals';
import { onMounted, onUnmounted } from 'vue';
import AppStatusChip from '../../Components/AppStatusChip/AppStatusChip.vue';
import { Exam } from '../../interfaces/interfaces';

export default {
    layout: EmployeeLayout,
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
                    <div>{{ exam.data?.beginTime }}</div>
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
                :items="foreignNationals.data"
                :headers="headers"
                hover
                hide-default-footer
                :loading="foreignNationals.lenght === 0"
                @click:row="openForeignNational"
            >
                <template  #item.actions="{ item }">
                    <DropDownForeignNationalsList :foreignNational="item" :hasSpeakingTasks="hasSpeakingTasks" />
                </template>
                <template #item.status="{ item }">
                    <AppStatusChip
                        v-if="item.attempts.length"
                        :color="attemptStatus(item.attempts[0].status).color.replace('text-', '')"
                        :text="attemptStatus(item.attempts[0].status).text"
                    />
                    <span v-else>-</span>
                </template>
                <template  #item.startTime="{ item }">
                    {{item.attempts[0]?.startedAt}}
                </template>
                <template  #item.endTime="{ item }">
                    {{item.attempts[0]?.finishedAt ? item.attempts[0]?.finishedAt : '-'}}
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