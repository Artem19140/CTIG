<script setup lang="ts">
import EmployeeLayout from '../../Layout/EmployeeLayout.vue';

defineOptions({
  layout: EmployeeLayout,
})
import StudentTable from './Components/StudentTable.vue';



const props = defineProps<{
  students: any
}>()



</script>

<template>
  <v-container>
    <v-card>
      <StudentTable :students="students" />
    </v-card>
  </v-container> 
</template>