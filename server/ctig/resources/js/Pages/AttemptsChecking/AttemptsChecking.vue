<script setup lang="ts">
import BaseLayout from '@layouts/BaseLayout.vue';
import EmployeeLayout from '@layouts/EmployeeLayout.vue';
import AttemptsTable from './AttemptsTable.vue';

defineOptions({
  layout: [BaseLayout, EmployeeLayout],
})

const props = defineProps<{
    attempts: any
}>()


</script>


<template>
    <!-- <pre>
        {{ attempts }}
    </pre> -->
    <v-container>
        <v-card>
            <AttemptsTable :attempts="attempts" />
        </v-card>
    </v-container>
</template>