<script lang="ts">
import EmployeeLayout from '../../Layout/EmployeeLayout.vue';
import type { Student } from '../../interfaces/interfaces';
export default {
  layout: EmployeeLayout,
}
</script>

<script setup lang="ts">
import StudentsList from './Components/StudentsList.vue';



const props = defineProps<{
  students: any
}>()



</script>

<template>
    <StudentsList :students="students"/>

    
</template>