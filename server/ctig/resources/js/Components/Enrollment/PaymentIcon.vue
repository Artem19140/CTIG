<script setup lang="ts">
import { Enrollment } from '@/interfaces/Interfaces';
import AppProgressCircular from '../UI/AppProgressCircular/AppProgressCircular.vue';

const props = defineProps<{
    enrollment:Enrollment
}>()
</script>

<template>
    <v-icon icon="mdi-check-circle" color="green" v-if="!enrollment.isLoading && enrollment.hasPayment"/>
    <AppProgressCircular v-if="enrollment.isLoading" />
</template>