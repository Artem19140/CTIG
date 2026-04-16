<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import EmployeeLayout from '@layouts/EmployeeLayout.vue';
import ExamActionsDropdown from '@components/Exam/ExamActionsDropdown.vue';
import BaseServerTable from '@components/BaseComponents/BaseServerTable/BaseServerTable.vue';
import ExamStatusChip from '@components/Exam/ExamStatusChip.vue';
import { DateFormatter } from '@helpers/DateFormatter';
import BaseLayout from '@layouts/BaseLayout.vue';
import { ref } from 'vue';
import ExamCapacityChip from '@/components/Exam/ExamCapacityChip.vue';

defineOptions({
  layout: [BaseLayout,EmployeeLayout]
})

const props = defineProps<{
    exams:any,
    past:boolean
}>()

const past = ref<boolean>(props.past)

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
const loading=ref<boolean>(false)
const getPastExams = () =>{
    loading.value = true
    past.value = !past.value
    router.reload({
        data:{
            past:past.value
        },
        onFinish:() => {
            loading.value = false
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
                :loading="loading"
            >
                <template #toolbar-actions>
                    <v-btn 
                        width="120"
                        border
                        :loading="loading"
                        :disabled="loading"
                        @click="getPastExams"
                    >
                        {{ past ? 'Текущие' : 'Прошедшие'}}
                    </v-btn>
                </template>
                <template #item.capacity="{ item }">
                    <ExamCapacityChip :exam="item" />
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