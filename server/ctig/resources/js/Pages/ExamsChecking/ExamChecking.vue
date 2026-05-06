<script setup lang="ts">
import BaseTable from '@/components/BaseComponents/BaseTable/BaseTable.vue';
import BaseContainer from '@/components/BaseComponents/BaseContainer/BaseContainer.vue';
import EmployeeLayout from '@layouts/EmployeeLayout.vue';
import { DateFormatter } from '@/helpers/DateFormatter';
import { useModals } from '@composables/useModals';
import AppStatusChip from '@/components/UI/AppStatusChip/AppStatusChip.vue';
import { Enrollment } from '@/interfaces/Enrollment';
import { ExamChecking } from '@/interfaces/Exam';

defineOptions({
  layout: [EmployeeLayout],
})

const props = defineProps<{
    exam:{
        data:ExamChecking
    }
}>()

const headers = [
    {title : "№",sortable: false, key: 'index', align: 'center' },
    {title : "Рег номер",sortable: false, key: 'name', align: 'center' },
    {title : "Статус",sortable: false, key: 'status', align: 'center' }
]

const {open} = useModals()
const openAttempt =  (item : Enrollment) => {
    if(!item.attempt) return
    open('attemptChecking', {attemptId:item.attempt.id})
}
</script>

<template>
    <BaseContainer>
        <BaseTable
            :headers="headers"
            :title="`Попытки экзмена ${exam.data.shortName} от ${new DateFormatter(exam.data.beginTime).format('H:i, d.m.Y')}`"
            :items="props.exam.data.enrollments"
            @row-click="openAttempt"
        >
        <template #item.index="{ index }">
           {{ index + 1 }}
        </template>
        <template #item.name="{ item }">
           {{ item.regNum }}
        </template>
        <template #item.status="{ item }">
            <AppStatusChip 
                v-if="item.attempt?.isPassed !== null"
                color="green"
                text="Проверено"
            />
        </template>
    </BaseTable>
    </BaseContainer>
</template>