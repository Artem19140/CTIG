<script setup lang="ts">
import ExamTable from './Components/ExamTable.vue';
import EmployeeLayout from '../../Layout/EmployeeLayout.vue';
defineOptions({
  layout: EmployeeLayout

})

const props = defineProps<{
  exams: any
}>()
</script>

<template>
    
  <v-container>
    <v-card>
      <ExamTable :exams="exams" />
    </v-card>
  </v-container>
    
</template>