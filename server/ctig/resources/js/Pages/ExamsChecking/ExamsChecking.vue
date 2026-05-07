<script setup lang="ts">
import EmployeeLayout from '@layouts/EmployeeLayout.vue';
import ExamsCheckingTable from './Components/ExamsCheckingTable.vue';
import { ExamIndex } from '@/interfaces/Exam';
import { Head } from '@inertiajs/vue3';

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
    <Head>
        <title>Проверка список</title>
    </Head>
    <v-container>
        <v-card>
            <ExamsCheckingTable :exams="exams.data" />
        </v-card>
    </v-container>
</template>