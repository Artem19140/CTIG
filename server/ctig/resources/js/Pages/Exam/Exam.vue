<script lang="ts">
import EmployeeLayout from '../../Layout/EmployeeLayout.vue';

export default {
    layout: EmployeeLayout,
}
</script>

<script setup lang="ts">
import ExamList from './Components/ExamList.vue';

const props = defineProps<{
  exams: Object
}>()
</script>

<template>
    
    <div class="flex flex-col gap-4 w-auto">
      <ExamList :exams="props.exams" />
    </div>
    
</template>