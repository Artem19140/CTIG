<script setup lang="ts">
import DropDownForeignNationalsList from './DropDownForeignNationalsList.vue';
import { router, usePoll } from '@inertiajs/vue3'

const props = defineProps<{
    foreignNationals:any,
    exam:any,
    tasksCount:number,
    hasSpeakingTasks:boolean
}>()

const { start, stop } = usePoll(5000, {
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
    if(props.exam?.data?.isGoing && props.exam?.data?.foreignNationals?.length>0){
        start()
    }
})

onUnmounted(()=>{
    stop()
})

const headers = [
    {title:'ФИО', key:"fullName",sortable: false},
    {title:'Паспорт', key:"fullPassport",sortable: false},
    {title:'Решенные', key:"solved",sortable: false, align:'center'},
    {title:'Попытка', key:"status",sortable: false},
    {
        title:'Время',
        align:'center',
        children:[
            {title:'Начала',key:'startTime',sortable: false, align:'center'},
            {title:'Завершения',key:'endTime',sortable: false, align:'center'}
        ]
    },
    {title:'', key:"actions",sortable: false,align: 'end'}
]

const color = (status : string) => {
    switch (status) {
        case "active":
            return "text-green";
        case "finished":
            return  "text-grey";
        case "banned":
            return "text-red";
        case "checked":
            return "text-blue";
        default:
            return ''
    }
}

const getAttemptStatus = (status : string) =>{
    switch (status) {
        case "pending":
            return 'Введен код'
        case "active":
            return "Активна";
        case "finished":
            return  "Завершена";
        case "banned":
            return "Аннулирована";
        case "checked":
            return "Проверена";
        default:
            return '-'
    }
}
const {open} = useModals()
const openForeignNational = (event : Event, {item} :any) => {
    
    open('foreignNationalShow', {foreignNationalId:item.id})
}
</script>

<script lang="ts">
import EmployeeLayout from '../../Layout/EmployeeLayout.vue';
import ForeignNationalShowModal from '../ForeignNationals/Components/ForeignNationalShowModal.vue';
import { useModals } from '../../Composables/useModals';
import { onMounted, onUnmounted } from 'vue';

export default {
    layout: EmployeeLayout,
}
</script>

<template>
    <v-btn class="mt-4 ml-4" @click="router.visit('/exams/monitoring')">Назад</v-btn>
    <v-card 
        width="900" 
        class="mx-auto mt-16"
        title="Мониторинг"
    >

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
                    @click="open('examComment', {})"
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
                <template  #item.status="{ item }">
                    <span :class="color(item.attempts[0]?.status ?? null)">{{getAttemptStatus(item.attempts[0]?.status ?? null)}}</span>
                </template>
                <template  #item.solved="{ item }">
                    {{item.attempts[0]?.solved ?? '-'}} / {{ props.tasksCount }}
                </template>
                <template  #item.startTime="{ item }">
                    {{item.attempts[0]?.startedAt}}
                </template>
                <template  #item.endTime="{ item }">
                    {{item.attempts[0]?.finishedAt ? item.attempts[0]?.finishedAt : '-'}}
                </template>
            </v-data-table>
        </v-card-text>
    </v-card>
</template>