<script setup lang="ts">
    import { useModals } from '../../../Composables/useModals';
    import type { Exam, Paginated } from '../../../interfaces/interfaces';
    import BaseServerTable from '../../../Components/BaseServerTable.vue';
    import ExamTableDropDown from './ExamTableDropDown.vue';
    import ExamTableFilter from './ExamTableFilter.vue';
    import { useForm } from '@inertiajs/vue3';
    import AddButton from '../../../Components/AddButton/AddButton.vue';
    import { useAuth } from '../../../Composables/useAuth';
    import { Roles } from '../../../Constants/Roles';

   const props = defineProps<{
        exams: Paginated<Exam>,
        filters:any
    }>()

    const headers = [
            {title : "Название",sortable: false, key: 'shortName', align: 'start' },
            {title : "Дата",sortable: false, key: 'beginTime', align: 'start' },
            {title : "Запись",sortable: false, key: 'studentsCount', align: 'start' },
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
        <template #item.studentsCount="{item}">
            <div :class="{'text-red-500': ((item.studentsCount / item.capacity) === 1)}">{{` ${item.studentsCount }/${ item.capacity }`}}</div>
        </template>
        <template #item.status="{item}">
            <span v-if="item.isGoing && !item.isCancelled" class="text-green">в процессе</span>
            <span v-else-if="item.isPast && !item.isCancelled" class="text-grey">прошел</span>
            <span v-else-if="item.isCancelled" class="text-red">отменен</span>
            <span v-else>ожидается</span>
        </template>
    </BaseServerTable>
</template>