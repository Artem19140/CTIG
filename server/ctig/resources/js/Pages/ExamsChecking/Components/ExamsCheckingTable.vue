<script setup lang="ts">
import BaseTable from '@components/BaseComponents/BaseTable/BaseTable.vue';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { Exam } from '@/interfaces/Interfaces';
import { DateFormatter } from '@/helpers/DateFormatter';

const props = defineProps<{
    exams: Exam[]
}>()

const headers = [
    {title : "Тип",sortable: false, key: 'shortName', align: 'center' },
    {title : "Дата",sortable: false, key: 'beginTime', align: 'center' }
]

const examCheck =  (item : Exam) => {
    router.visit(`/exams/${item.id}/checking`)
}
const loading = ref<boolean>(false)
</script>

<template>
    <BaseTable 
        title="Экзамены для проверки"
        :headers="headers"
        :items="exams"
        :loading="loading"
        @row-click="examCheck"
    >
        <template #item.beginTime="{item}">
            {{ new DateFormatter(item.beginTime).format('H:i, d.m.Y') }}
        </template>

    </BaseTable>

</template>