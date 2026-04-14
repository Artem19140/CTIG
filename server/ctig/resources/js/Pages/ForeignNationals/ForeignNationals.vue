<script setup lang="ts">
import EmployeeLayout from '@layouts/EmployeeLayout.vue';
import ForeignNationalTable from './Components/ForeignNationalTable/ForeignNationalTable.vue';
import BaseLayout from '@layouts/BaseLayout.vue';

defineOptions({
  layout: [BaseLayout, EmployeeLayout]
})

const props = defineProps<{
  foreignNationals: any,
  filters:any
}>()
</script>

<template>
  <v-container>
    <v-card>
      <ForeignNationalTable 
        :filters="filters" 
        :foreignNationals="foreignNationals" 
      />
    </v-card>
  </v-container> 
</template>