<script setup lang="ts">
import ExamTable from './Components/ExamTable/ExamTable.vue';
import EmployeeLayout from '@layouts/EmployeeLayout.vue';
import BaseLayout from '@layouts/BaseLayout.vue';
import BaseContainer from '@/components/BaseComponents/BaseContainer/BaseContainer.vue';

defineOptions({
  layout: [BaseLayout, EmployeeLayout]
})

const props = defineProps<{
  exams: any,
  filters:any
}>()
</script>

<template> 
  <BaseContainer>
      <ExamTable 
        :exams="exams" 
        :filters="filters"
      />
  </BaseContainer>
    
</template>