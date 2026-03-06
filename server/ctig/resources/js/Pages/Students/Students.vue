<script lang="ts">
import EmployeeLayout from '../../Layout/EmployeeLayout.vue';

export default {

    // Using shorthand syntax...
    layout: EmployeeLayout,
}
</script>

<script setup lang="ts">
import StudentsList from './Components/StudentsList.vue';



const props = defineProps<{
  students: any[]
}>()

</script>

<template>
    
    <div class="flex flex-col gap-4 w-auto">
      <StudentsList :students="students" />
    </div>
    
</template>