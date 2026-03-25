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
    {title:'Дата и время', key:"beginTime",sortable:false},
    {title:'Запись', key:"capacity", sortable:false, align:'center'},
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
            <!-- <v-btn 
                border
                variant="text"
                class="mr-4 mt-2"
            >Прошедшие</v-btn>
        </div> -->
            <BaseServerTable
                :elements="exams"
                :headers="headers"
                @click:row="open"
                title="Мониторинг"
            >
                
                <template #item.capacity="{ item }">
                    {{` ${item.studentsCount }/${ item.capacity }`}}
                </template>
                <template #item.actions="{ item }">
                    <ExamActionsDropdown :exam="item" />
                </template>
            </BaseServerTable>
        </v-card>
    </v-container>
</template>