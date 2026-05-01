<script setup lang="ts">
import AppStatusChip from '@components/UI/AppStatusChip/AppStatusChip.vue';
import { examStatus } from '@helpers/heplers';

const props = defineProps<{
    status: string | null
}>()

</script>

<template>
    <AppStatusChip
        v-if="status"
        :color="examStatus(status).color.replace('text-', '')"
        :text="examStatus(status).text"
    />
</template>