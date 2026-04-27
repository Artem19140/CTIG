<script setup lang="ts">
import EmployeeLayout from '@layouts/EmployeeLayout.vue';
import EmployeesTable from './EmployeesTable.vue';
import BaseContainer from '@/components/BaseComponents/BaseContainer/BaseContainer.vue';

defineOptions({
  layout:[EmployeeLayout]
})

const props = defineProps<{
  employees : any
}>()
</script>

<template>
  <BaseContainer>
    <EmployeesTable :employees="employees" />
  </BaseContainer>
  
</template>