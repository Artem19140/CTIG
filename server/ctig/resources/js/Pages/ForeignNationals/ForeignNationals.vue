<script setup lang="ts">
import EmployeeLayout from '../../Layout/EmployeeLayout.vue';
import ForeignNationalTable from './Components/ForeignNationalTable.vue';

defineOptions({
  layout: EmployeeLayout,
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