<script setup lang="ts">
import { useModals } from '../../../../Composables/useModals';
import type { Exam, Paginated } from '../../../../interfaces/interfaces';
import BaseServerTable from '../../../../Components/BaseServerTable.vue';
import ExamTableDropDown from './ExamTableDropDown.vue';
import ExamTableFilter from './ExamTableFilter.vue';
import { useForm } from '@inertiajs/vue3';
import AddButton from '../../../../Components/AddButton/AddButton.vue';
import { useAuth } from '../../../../Composables/useAuth';
import { Roles } from '../../../../Constants/Roles';
import { capacityColor } from '../../../../Helpers/heplers';
import AppStatusChip from '../../../../Components/AppStatusChip/AppStatusChip.vue';
import ExamStatusChip from '../ExamStatusChip.vue';
import { DateFormatter } from '../../../../Helpers/DateFormatter';

const props = defineProps<{
    exams: Paginated<Exam>,
    filters:any
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

const formFilters = useForm({
    dateFrom: props.filters.dateFrom ?? undefined,
    cancelled: props.filters.dateFrom ?? undefined,
    examTypeId: props.filters.examTypeId ?? undefined,
    dateTo: props.filters.dateTo ?? undefined,
    completed: props.filters.completed ?? undefined,
})
const {can} = useAuth()

</script>

<template>

    <BaseServerTable
        :headers="headers"
        :elements="exams"
        title="Экзамены"
        :loading="formFilters.processing"
        @row-click="openModal"
    >
        <template #toolbar-left>
            <ExamTableFilter 
                :filters="filters"
                :form="formFilters"
            />
        </template>
        <template #toolbar-actions>
            <AddButton
                text="Добавить"
                @click="open('examCreate', {})"
                v-if="can([Roles.SCHEDULER])"
            />
            <ExamTableDropDown />
        </template>
        <template #item.foreignNationalsCount="{ item }">
            <AppStatusChip
                :text="`${item.foreignNationalsCount}/${item.capacity}`"
                :color="capacityColor(item)"
                dark
            />
        </template>
        <template #item.beginTime="{ item }">
            {{ new DateFormatter(item.beginTime).format('H:i, d.m.Y') }}
        </template>
        <template #item.status="{ item }">
            <ExamStatusChip 
                :exam="item"
            />
        </template>
    </BaseServerTable>
</template>