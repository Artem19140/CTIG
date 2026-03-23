<script setup lang="ts">
import EmployeeLayout from '../../Layout/EmployeeLayout.vue';
import AttemptsTable from './AttemptsTable.vue';

defineOptions({
  layout: EmployeeLayout,
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