<script setup lang="ts">
import {  Attempt, Enrollment, Exam } from '@/interfaces/Interfaces';
import BaseTable from '@/components/BaseComponents/BaseTable/BaseTable.vue';
import BaseContainer from '@/components/BaseComponents/BaseContainer/BaseContainer.vue';
import BaseLayout from '@layouts/BaseLayout.vue';
import EmployeeLayout from '@layouts/EmployeeLayout.vue';
import { DateFormatter } from '@/helpers/DateFormatter';
import { useModals } from '@composables/useModals';
import { ref } from 'vue';
import AppStatusChip from '@/components/UI/AppStatusChip/AppStatusChip.vue';
const {open} = useModals()

defineOptions({
  layout: [BaseLayout, EmployeeLayout],
})

const props = defineProps<{
    exam:{
        data:Exam
    }
}>()

const enrollments = ref<Enrollment[]>(props.exam.data.enrollments)

const headers = [
    {title : "№",sortable: false, key: 'index', align: 'center' },
    {title : "Рег номер",sortable: false, key: 'name', align: 'center' },
    {title : "Статус",sortable: false, key: 'status', align: 'center' }
]

const finishChecking = (updatedAttempt: Attempt) => {
    const enrollment = enrollments.value.find(e => 
        e.attempt?.id === updatedAttempt.id
    )

    if (!enrollment || !enrollment.attempt) return

    enrollment.attempt = updatedAttempt
}

const openAttempt =  (item : Enrollment) => {
    if(!item.attempt) return
    open('attemptChecking', {attemptId:item.attempt.id, onFinishChecking:finishChecking})
}
</script>

<template>
    <BaseContainer>
        <BaseTable
            :headers="headers"
            :title="`Попытки экзмена ${exam.data.shortName} от ${new DateFormatter(exam.data.beginTime).format('H:i, d.m.Y')}`"
            :items="enrollments"
            @row-click="openAttempt"
        >
        <template #item.index="{ index }">
           {{ index + 1 }}
        </template>
        <template #item.name="{ item, index }">
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
    <!-- <AttemptCheckingModal 
        v-model="isOpen"
        :attempt-id="attemptId"
    /> -->
</template>