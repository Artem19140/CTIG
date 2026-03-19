<script setup lang="ts">
import EmployeeLayout from '../../Layout/EmployeeLayout.vue';
import StudentTable from './Components/StudentTable.vue';

defineOptions({
  layout: EmployeeLayout,
})

const props = defineProps<{
  students: any,
  filters:any
}>()
</script>

<template>
  <v-container>
    <v-card>
      <StudentTable 
        :filters="filters" 
        :students="students" 
      />
    </v-card>
  </v-container> 
</template>