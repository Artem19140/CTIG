<script lang="ts">
import EmployeeLayout from '../../Layout/EmployeeLayout.vue';

export default {
  layout: EmployeeLayout,
}
</script>

<script setup lang="ts">
import StudentsList from './Components/StudentsList.vue';



const props = defineProps<{
  students: Object
}>()

</script>

<template>
    <StudentsList :students="students" />

    
</template>