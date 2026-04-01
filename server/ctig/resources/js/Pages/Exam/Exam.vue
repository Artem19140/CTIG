<script setup lang="ts">
import ExamTable from './Components/ExamTable/ExamTable.vue';
import EmployeeLayout from '../../Layout/EmployeeLayout.vue';
import BaseLayout from '../../Layout/BaseLayout.vue';

defineOptions({
  layout: [BaseLayout, EmployeeLayout]
})

const props = defineProps<{
  exams: any,
  filters:any
}>()
</script>

<template> 
  <v-container>
    <v-card>
      <ExamTable 
        :exams="exams" 
        :filters="filters"
      />
    </v-card>
  </v-container>
    
</template>