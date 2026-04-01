<script setup lang="ts">
import EmployeeLayout from '../../Layout/EmployeeLayout.vue';
import EmployeesTable from './EmployeesTable.vue';
import BaseLayout from '../../Layout/BaseLayout.vue';

defineOptions({
  layout:[BaseLayout, EmployeeLayout]
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