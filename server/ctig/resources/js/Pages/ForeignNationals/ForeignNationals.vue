<script setup lang="ts">
import EmployeeLayout from '@layouts/EmployeeLayout.vue';
import ForeignNationalTable from './Components/ForeignNationalTable/ForeignNationalTable.vue';
import BaseContainer from '@/components/BaseComponents/BaseContainer/BaseContainer.vue';

defineOptions({
  layout: [EmployeeLayout]
})

const props = defineProps<{
  foreignNationals: any
}>()
</script>

<template>
  <BaseContainer >
      <ForeignNationalTable 
        :foreignNationals="foreignNationals" 
      />
  </BaseContainer> 
</template>