<script setup lang="ts">
import DropDownStudentsList from './DropDownStudentsList.vue';
import { formatterDate, formatterTime } from '../../Helpers/heplers';
import { router, usePoll } from '@inertiajs/vue3'

const props = defineProps<{
    students:any,
    exam:any,
    tasksCount:number,
    hasSpeakingTasks:boolean
}>()

usePoll(4000, {
    only: ['students'],
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

const open = (event : Event, {item} :any) => {
    const {open} = useModals()
    open('studentShow', {studentId:item.id})
}
</script>

<script lang="ts">
import EmployeeLayout from '../../Layout/EmployeeLayout.vue';
import StudentShowModal from '../Students/Components/StudentShowModal.vue';
import { useModals } from '../../Composables/useModals';

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
        <!-- <pre>
            {{ exam }}
        </pre> -->
        <v-card-subtitle>
            <div>{{ exam.data.shortName }}</div>
            <div>{{ formatterTime(exam.data?.beginTime) }} - {{formatterTime(exam.data?.endTime) }}</div>
            <div>{{ formatterDate(exam.data?.beginTime) }}</div>
        </v-card-subtitle>
        <v-card-text>
            <v-data-table
                :items="students.data"
                :headers="headers"
                hover
                hide-default-footer
                :loading="students.lenght === 0"
                @click:row="open"
            >
                <template  #item.actions="{ item }">
                    <DropDownStudentsList :student="item" :hasSpeakingTasks="hasSpeakingTasks" />
                </template>
                <template  #item.status="{ item }">
                    {{getAttemptStatus(item.attempts[0]?.status ?? null)}}
                </template>
                <template  #item.solved="{ item }">
                    {{item.attempts[0]?.solved ?? '-'}}/ {{ props.tasksCount }}
                </template>
                <template  #item.startTime="{ item }">
                    {{formatterTime(item.attempts[0]?.startedAt)}}
                </template>
                <template  #item.endTime="{ item }">
                    {{item.attempts[0]?.finishedAt ? formatterTime(item.attempts[0]?.finishedAt) : '-'}}
                </template>
            </v-data-table>
        </v-card-text>
    </v-card>

    <StudentShowModal />
</template>