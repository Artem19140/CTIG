<script setup lang="ts">
import { useModals } from '@composables/useModals';
import type { Exam, Paginated } from '@interfaces/Interfaces';
import BaseServerTable from '@components/BaseComponents/BaseServerTable/BaseServerTable.vue';
import ExamTableDropDown from './ExamTableDropDown.vue';
import ExamTableFilter from './ExamTableFilter.vue';
import AppAddButton from '@components/UI/AppAddButton/AppAddButton.vue';
import { useAuth } from '@composables/useAuth';
import { Roles } from '@constants/Roles';
import ExamStatusChip from '@components/Exam/ExamStatusChip.vue';
import { DateFormatter } from '@helpers/DateFormatter';
import ExamCapacityChip from '@/components/Exam/ExamCapacityChip.vue';
import { ref } from 'vue';
import AppPaginator from '@/components/UI/AppPaginator/AppPaginator.vue';

const props = defineProps<{
    exams: Paginated<Exam>
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
const auth = useAuth()
const loading = ref<boolean>(false)
</script>

<template>
    
    <BaseServerTable
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
            <!--  -->
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
            <ExamCapacityChip :exam="item" />
        </template>
        <template #item.beginTime="{ item }">
            {{ new DateFormatter(item.beginTime).format('H:i, d.m.Y') }}
        </template>
        <template #item.status="{ item }">
            <ExamStatusChip 
                :exam="item"
            />
        </template>
        <template #bottom>
            <AppPaginator
                :meta="exams.meta"
                :links="exams.links"
                v-model="loading"
            />
        </template>
    </BaseServerTable>
</template>