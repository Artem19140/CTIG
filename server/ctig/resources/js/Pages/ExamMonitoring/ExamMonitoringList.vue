<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import EmployeeLayout from '../../Layout/EmployeeLayout.vue';
import ExamActionsDropdown from '../Exam/Components/ExamShowModal/ExamActionsDropdown.vue';
import BaseServerTable from '../../Components/BaseServerTable.vue';
import { capacityColor } from '../../Helpers/heplers';
import AppStatusChip from '../../Components/AppStatusChip/AppStatusChip.vue';
import ExamStatusChip from '../Exam/Components/ExamStatusChip.vue';
import { DateFormatter } from '../../Helpers/DateFormatter';

defineOptions({
  layout: EmployeeLayout,
})
const props = defineProps<{
    exams:any
}>()

const headers = [
    {title:'Название', key:"shortName",sortable:false, align:'center'},
    {title:'Дата', key:"beginTime",sortable:false, align:'center'},
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
                    <AppStatusChip
                        :text="`${item.foreignNationalsCount}/${item.capacity}`"
                        :color="capacityColor(item)"
                    />
                </template>
                
                <template #item.status="{ item }">
                    <ExamStatusChip
                        :exam="item"
                    />
                </template>
                <template #item.beginTime="{ item }">
                    {{ new DateFormatter(item.beginTime).format('H:i, d.m.Y') }}
                </template>
                <template #item.actions="{ item }">
                    <ExamActionsDropdown :exam="item" />
                </template>
                
            </BaseServerTable>
        </v-card>
    </v-container>
</template>