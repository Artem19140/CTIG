<script setup lang="ts">
    import { formatterTime , formatterDate } from '../../../Helpers/heplers';
    import { useExamShowModal } from '../../../Composables/modalWindows/useExamShowModal';
    import ExamCreateModal from './ExamCreateModal.vue';
    import type { Exam, Paginated } from '../../../interfaces/interfaces';
    import BaseServerTable from '../../../Components/UI/BaseServerTable.vue';

   const props = defineProps<{
        exams: Paginated<Exam>
    }>()

    const headers = [
            {title : "Название",sortable: false, key: 'shortName', align: 'start' },
            {title : "Дата",sortable: false, key: 'beginTime', align: 'start' },
            {title : "Запись",sortable: false, key: 'studentsCount', align: 'start' },
        ]

    const openModal = (item :any) => {
        const {open} = useExamShowModal()
        open(item.id)
    }
</script>

<template>

    <BaseServerTable
        :headers="headers"
        :elements="exams"
        title="Экзамены"
        @row-click="openModal"
    >
        <template #toolbar-left>
            <v-btn icon variant="text">
                <v-icon>mdi-filter-menu</v-icon>
            </v-btn>
        </template>
        <template #toolbar-actions>
            <ExamCreateModal />
        </template>
        <template #item.beginTime="{item}">
            {{ formatterDate(item.beginTime) }} {{ formatterTime(item.beginTime) }}
        </template>
        <template #item.studentsCount="{item}">
            <div :class="{'text-red-500': ((item.studentsCount / item.capacity) === 1)}">{{` ${item.studentsCount }/${ item.capacity }`}}</div>
        </template>
    </BaseServerTable>
</template>