<script setup lang="ts">
import ExamTable from './Components/ExamTable/ExamTable.vue';
import EmployeeLayout from '@layouts/EmployeeLayout.vue';
import BaseContainer from '@/components/BaseComponents/BaseContainer/BaseContainer.vue';

defineOptions({
  layout: [EmployeeLayout]
})

const props = defineProps<{
  exams: any
}>()
</script>

<template> 
  <BaseContainer>
      <ExamTable 
        :exams="exams" 
      />
  </BaseContainer>
    
</template>