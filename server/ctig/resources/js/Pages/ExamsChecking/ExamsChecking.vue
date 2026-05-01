<script setup lang="ts">
import EmployeeLayout from '@layouts/EmployeeLayout.vue';
import ExamsCheckingTable from './Components/ExamsCheckingTable.vue';
import { ExamIndex } from '@/interfaces/Exam';

defineOptions({
  layout: [EmployeeLayout],
})

const props = defineProps<{
    exams: {
        data:ExamIndex[]
    }
}>()
</script>


<template>
    <v-container>
        <v-card>
            <ExamsCheckingTable :exams="exams.data" />
        </v-card>
    </v-container>
</template>