<script setup lang="ts">
import BaseTable from '@components/BaseComponents/BaseTable/BaseTable.vue';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { DateFormatter } from '@/helpers/DateFormatter';
import { ExamIndex } from '@/interfaces/Exam';
import AppTooltip from '@/components/UI/AppTooltip/AppTooltip.vue';

const props = defineProps<{
    exams: ExamIndex[]
}>()

const headers = [
    {title : "Тип",sortable: false, key: 'shortName', align: 'center' },
    {title : "Дата",sortable: false, key: 'beginTime', align: 'center' }
]

const examCheck =  (item : ExamIndex) => {
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
        <template #toolbar-left>
            <AppTooltip  
            >
                <div>Здесь будут экзамены, требующие ручной проверки(РВП, ВНЖ)</div>
                <div>После ПОЛНОЙ проверки экзамен исчезнет из списка</div>
            </AppTooltip>
        </template>
        <template #item.beginTime="{item}">
            {{ new DateFormatter(item.beginTime).format('H:i, d.m.Y') }}
        </template>

    </BaseTable>

</template>