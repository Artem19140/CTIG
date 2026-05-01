<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import EmployeeLayout from '@layouts/EmployeeLayout.vue';
import ExamActionsDropdown from '@components/Exam/ExamActionsDropdown.vue';
import BasePaginatedTable from '@components/BaseComponents/BasePaginatedTable/BasePaginatedTable.vue';
import ExamStatusChip from '@components/Exam/ExamStatusChip.vue';
import { DateFormatter } from '@helpers/DateFormatter';
import { ref } from 'vue';
import ExamCapacityChip from '@/components/Exam/ExamCapacityChip.vue';
import AppPaginator from '@/components/UI/AppPaginator/AppPaginator.vue';
import { Paginated } from '@/interfaces/Interfaces';
import BaseContainer from '@/components/BaseComponents/BaseContainer/BaseContainer.vue';
import AppBorderedButton from '@/components/UI/AppBorderedButton/AppBorderedButton.vue';
import { ExamIndex } from '@/interfaces/Exam';

defineOptions({
  layout: [EmployeeLayout]
})

const props = defineProps<{
    exams:Paginated<ExamIndex>,
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

const open = (event:Event, {item} : any) => {
    router.visit(`/exams/${item.id}/monitoring`,{})
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
    <BaseContainer>
        <BasePaginatedTable
            :elements="exams"
            :headers="headers"
            @click:row="open"
            title="Мониторинг"
            :loading="loading"
        >
            <template #toolbar-actions>
                <AppBorderedButton
                    :loading="loading"
                    :disabled="loading"
                    @click="getPastExams"
                    :text="past ? 'Текущие' : 'Прошедшие'"
                />
            </template>

            <template #item.capacity="{ item }">
                <ExamCapacityChip :exam="item" />
            </template>
            
            <template #item.status="{ item }">
                <ExamStatusChip
                    :status="item.status"
                />
            </template>

            <template #item.beginTime="{ item }">
                {{ new DateFormatter(item.beginTime).format('H:i, d.m.Y') }}
            </template>

            <template #item.actions="{ item }">
                <ExamActionsDropdown :exam="item" />
            </template>
            
            <template #bottom>
                <AppPaginator
                    :meta="exams.meta"
                    :links="exams.links"
                    v-model="loading"
                />
            </template>
            
        </BasePaginatedTable>
    </BaseContainer>
</template>