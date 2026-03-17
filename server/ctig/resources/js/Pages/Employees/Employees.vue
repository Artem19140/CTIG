<script setup lang="ts">
import EmployeeLayout from '../../Layout/EmployeeLayout.vue';
import EmployeesTable from './EmployeesTable.vue';

defineOptions({
  layout: EmployeeLayout,
})

const props = defineProps<{
  employees : any
}>()
</script>

<template>
  <v-container>
    <v-card>
      <EmployeesTable :employees="employees" />
    </v-card>
  </v-container>
  
</template>