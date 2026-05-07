<script setup lang="ts">
import EmployeeLayout from '@layouts/EmployeeLayout.vue';
import ForeignNationalTable from './Components/ForeignNationalTable/ForeignNationalTable.vue';
import BaseContainer from '@/components/BaseComponents/BaseContainer/BaseContainer.vue';
import { Paginated } from '@/interfaces/Interfaces';
import { ForeignNationalIndex } from '@/interfaces/ForeignNational';
import { Head } from '@inertiajs/vue3';

defineOptions({
  layout: [EmployeeLayout]
})

const props = defineProps<{
  foreignNationals: Paginated<ForeignNationalIndex>
}>()
</script>

<template>
  <Head>
    <title>ИГ</title>
  </Head>
  <BaseContainer >
    <ForeignNationalTable 
      :foreignNationals="foreignNationals" 
    />
  </BaseContainer> 
</template>