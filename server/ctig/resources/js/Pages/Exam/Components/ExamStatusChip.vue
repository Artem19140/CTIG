<script setup lang="ts">
import AppStatusChip from '@components/UI/AppStatusChip/AppStatusChip.vue';
import { examStatus } from '@helpers/heplers';
import type { Exam } from '@interfaces/interfaces';

const props = defineProps<{
    exam: Exam | null
}>()

</script>

<template>
    <AppStatusChip
        v-if="exam"
        :color="examStatus(exam).color.replace('text-', '')"
        :text="examStatus(exam).text"
    />
</template>