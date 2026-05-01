<script setup lang="ts">
import ExamTable from './Components/ExamTable/ExamTable.vue';
import EmployeeLayout from '@layouts/EmployeeLayout.vue';
import BaseContainer from '@/components/BaseComponents/BaseContainer/BaseContainer.vue';
import { ExamIndex } from '@/interfaces/Exam';
import { Paginated } from '@/interfaces/Interfaces';

defineOptions({
  layout: [EmployeeLayout]
})

const props = defineProps<{
  exams: Paginated<ExamIndex>
}>()
</script>

<template> 
  <BaseContainer>
    <ExamTable 
      :exams="exams" 
    />
  </BaseContainer>
</template>