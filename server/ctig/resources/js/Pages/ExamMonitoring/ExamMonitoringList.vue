<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import EmployeeLayout from '../../Layout/EmployeeLayout.vue';
import ExamActionsDropdown from '../Exam/Components/ExamShowModal/ExamActionsDropdown.vue';
import BaseServerTable from '../../Components/BaseServerTable.vue';

defineOptions({
  layout: EmployeeLayout,
})
const props = defineProps<{
    exams:any
}>()

const headers = [
    {title:'Название', key:"shortName",sortable:false},
    {title:'Дата', key:"beginTime",sortable:false},
    {title:'Запись', key:"capacity", sortable:false, align:'center'},
    {title:'Статус', key:"status", sortable:false, align:'center'},
    {title:'', key:"actions", sortable:false, align:'center'}
]

let cancelRequest: any = null;

const open = (event:Event, {item} : any) => {
    if (cancelRequest) {
        cancelRequest(); 
    }

    router.visit(`/exams/${item.id}/monitoring`,{
        onCancelToken:(cancelToken) => {
            cancelRequest = cancelToken
        },
        onFinish: () => {
            cancelRequest = null;
        }
    })
}


</script>

<template>
    <v-container>
        <v-card>
            <BaseServerTable
                :elements="exams"
                :headers="headers"
                @click:row="open"
                title="Мониторинг"
            >
                
                <template #item.capacity="{ item }">
                    {{` ${item.studentsCount }/${ item.capacity }`}}
                </template>
                <template #item.status="{item}">
                    <span v-if="item.isGoing && !item.isCancelled" class="text-green">в процессе</span>
                    <span v-else-if="item.isPast && !item.isCancelled" class="text-grey">прошел</span>
                    <span v-else-if="item.isCancelled" class="text-red">отменен</span>
                    <span v-else>ожидается</span>
                </template>
                <template #item.actions="{ item }">
                    <ExamActionsDropdown :exam="item" />
                </template>
                
            </BaseServerTable>
        </v-card>
    </v-container>
</template>