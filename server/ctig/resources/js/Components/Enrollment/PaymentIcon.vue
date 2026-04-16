<script setup lang="ts">
import { Enrollment } from '@/interfaces/Interfaces';

const props = defineProps<{
    enrollment:Enrollment
}>()
</script>

<template>
    <v-icon icon="mdi-check-circle" color="green" v-if="!enrollment.isLoading && enrollment.hasPayment"/>
    <v-progress-circular v-if="enrollment.isLoading" indeterminate color="primary" />
</template>