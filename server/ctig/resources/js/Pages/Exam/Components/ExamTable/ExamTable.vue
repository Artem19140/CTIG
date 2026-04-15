<script setup lang="ts">
import { useModals } from '@composables/useModals';
import type { Exam, Paginated } from '@interfaces/Interfaces';
import BaseServerTable from '@components/BaseComponents/BaseServerTable/BaseServerTable.vue';
import ExamTableDropDown from './ExamTableDropDown.vue';
import ExamTableFilter from './ExamTableFilter.vue';
import { useForm } from '@inertiajs/vue3';
import AppAddButton from '@components/UI/AppAddButton/AppAddButton.vue';
import { useAuth } from '@composables/useAuth';
import { Roles } from '@constants/Roles';
import { capacityColor } from '@helpers/heplers';
import AppStatusChip from '@components/UI/AppStatusChip/AppStatusChip.vue';
import ExamStatusChip from '../ExamStatusChip.vue';
import { DateFormatter } from '@helpers/DateFormatter';

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
const auth = useAuth()

</script>

<template>
    
    <BaseServerTable
        :headers="headers"
        :elements="exams"
        :page="filters.page"
        :items-per-page="filters.perPage"
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
            <AppAddButton
                text="Добавить"
                @click="open('examCreate', {})"
                v-if="auth.can([Roles.SCHEDULER])"
            />
            <ExamTableDropDown  v-if="auth.can([Roles.DIRECTOR])" />
        </template>
        <template #item.foreignNationalsCount="{ item }">
            <AppStatusChip
                :text="`${item.enrollmentsCount}/${item.capacity}`"
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