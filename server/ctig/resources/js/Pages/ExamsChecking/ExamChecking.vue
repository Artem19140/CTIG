<script setup lang="ts">
import {  Enrollment, Exam } from '@/interfaces/Interfaces';
import BaseTable from '@/components/BaseComponents/BaseTable/BaseTable.vue';
import BaseContainer from '@/components/BaseComponents/BaseContainer/BaseContainer.vue';
import BaseLayout from '@layouts/BaseLayout.vue';
import EmployeeLayout from '@layouts/EmployeeLayout.vue';
import { DateFormatter } from '@/helpers/DateFormatter';
import { useModals } from '@composables/useModals';
import { ref } from 'vue';
import AttemptCheckingDrawer from './Components/AttemptCheckingDrawer.vue';
const {open} = useModals()

defineOptions({
  layout: [BaseLayout, EmployeeLayout],
})
const props = defineProps<{
    exam:{
        data:Exam
    }
}>()
const headers = [
    {title : "",sortable: false, key: 'index', align: 'center' },
]
const attemptId = ref<number | null>(null)
const openAttempt =  (item : Enrollment) => {
    if(!item.attempt) return
    isOpen.value= true
    attemptId.value = item.attempt.id
    //open('attemptChecking', {attemptId:item.id})
}
const isOpen = ref(false)
</script>

<template>
    <BaseContainer>
        <BaseTable
            :headers="headers"
            :title="`Попытки экзмена ${exam.data.shortName} от ${new DateFormatter(exam.data.beginTime).format('H:i, d.m.Y')}`"
            :items="exam.data.enrollments"
            @row-click="openAttempt"
        >
        <template #item.index="{ index }">
            Попытка {{ index + 1 }}
        </template>
    </BaseTable>
    </BaseContainer>
    <AttemptCheckingDrawer 
        v-model="isOpen"
        :attempt-id="attemptId"
    />
    <!-- <pre>
        {{ exam }}
    </pre> -->
</template>