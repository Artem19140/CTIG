<script setup lang="ts">
import EmployeeLayout from '@layouts/EmployeeLayout.vue';
import EmployeesTable from './EmployeesTable.vue';
import BaseContainer from '@/components/BaseComponents/BaseContainer/BaseContainer.vue';
import { Employee } from '@/interfaces/Employee';

defineOptions({
  layout:[EmployeeLayout]
})

const props = defineProps<{
  employees : Employee[]
}>()
</script>

<template>
  <BaseContainer>
    <EmployeesTable :employees="employees" />
  </BaseContainer>
</template>