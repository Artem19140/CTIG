<script setup lang="ts">
import { ref } from 'vue';
import EmployeeEntry from './Components/EmployeeEntry.vue';
import StudentEntry from './Components/StudentEntry.vue';

const isStudentEntry = ref<boolean>(true)
</script>

<template>
    <StudentEntry v-if="isStudentEntry" />
    <EmployeeEntry v-else  />
    <v-btn
        class="ms-auto"
        text="Ok"
        @click="isStudentEntry = !isStudentEntry"
    ></v-btn>
</template>