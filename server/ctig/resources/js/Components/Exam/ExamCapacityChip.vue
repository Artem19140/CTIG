<script setup lang="ts">
import { capacityColor } from '@/helpers/heplers';
import AppStatusChip from '../UI/AppStatusChip/AppStatusChip.vue';
import { Exam } from '@/interfaces/Interfaces';

const props = defineProps<{
    exam?:Exam | null
}>()
</script>

<template>
    <AppStatusChip
        :text="`${exam?.enrollmentsCount}/${exam?.capacity}`"
        :color="capacityColor(exam ?? null)"
    />
</template>