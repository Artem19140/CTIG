<script setup lang="ts">
import BaseLayout from '../../Layout/BaseLayout.vue';
import EmployeeLayout from '../../Layout/EmployeeLayout.vue';
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