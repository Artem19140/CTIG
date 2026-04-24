<script setup lang="ts">
import EmployeeLayout from '@layouts/EmployeeLayout.vue';
import ExamsCheckingTable from './Components/ExamsCheckingTable.vue';
import { Exam } from '@/interfaces/Interfaces';

defineOptions({
  layout: [EmployeeLayout],
})

const props = defineProps<{
    exams: {
        data:Exam[]
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