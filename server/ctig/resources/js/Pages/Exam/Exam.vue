<script setup lang="ts">
import ExamList from './Components/ExamList.vue';

const props = defineProps<{
  exams: any[]
}>()
</script>

<template>
    
    <div class="flex flex-col gap-4 w-auto">
      <ExamList :exams="props.exams" />
    </div>
    
</template>