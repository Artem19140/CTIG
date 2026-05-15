<script setup lang="ts">
import { useModals } from '@composables/useModals';
import type { Paginated } from '@interfaces/Interfaces';
import BasePaginatedTable from '@components/BaseComponents/BasePaginatedTable/BasePaginatedTable.vue';
import ExamTableDropDown from './ExamTableDropDown.vue';
import ExamTableFilter from './ExamTableFilter.vue';
import AppAddButton from '@components/UI/AppAddButton/AppAddButton.vue';
import ExamStatusChip from '@components/Exam/ExamStatusChip.vue';
import { DateFormatter } from '@helpers/DateFormatter';
import ExamCapacityChip from '@/components/Exam/ExamCapacityChip.vue';
import { ref } from 'vue';
import { ExamIndex, ExamPagePermissions } from '@/interfaces/Exam';

const props = defineProps<{
    exams: Paginated<ExamIndex>,
    permissions:ExamPagePermissions
}>()

const headers = [
    {title : "Название",sortable: false, key: 'shortName', align: 'center' },
    {title : "Дата",sortable: false, key: 'beginTime', align: 'center' },
    {title : "Запись",sortable: false, key: 'foreignNationalsCount', align: 'center' },
    {title : "Статус",sortable: false, key: 'status', align: 'center' },
]
const {open} = useModals()

const openModal = (item :any) => {
    open('examShow', {examId:item.id})
}
const loading = ref<boolean>(false)
</script>

<template>    
    <BasePaginatedTable
        :headers="headers"
        :elements="exams"
        title="Экзамены"
        :loading="loading"
        @row-click="openModal"
    >
    
        <template #toolbar-left>
            <ExamTableFilter 
                v-model="loading"
            />
        </template>
        <template #toolbar-actions>
            <AppAddButton
                text="Добавить"
                @click="open('examCreate', {})"
                v-if="permissions.create"
            />
            <ExamTableDropDown 
                :permissions="permissions"
            />
        </template>
        <template #item.foreignNationalsCount="{ item }">
            <ExamCapacityChip :exam="item" />
        </template>
        <template #item.beginTime="{ item }">
            {{ new DateFormatter(item.beginTime).format('H:i, d.m.Y') }}
        </template>
        <template #item.status="{ item }">
            <ExamStatusChip 
                :status="item.status"
            />
        </template>
    </BasePaginatedTable>
</template>