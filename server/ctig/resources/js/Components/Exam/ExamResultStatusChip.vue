<script setup lang="ts">
import AppStatusChip from '@components/UI/AppStatusChip/AppStatusChip.vue';
import { examResultStatus } from '@/helpers/heplers';

const props = defineProps<{
    status?:string
}>()
</script>

<template>
    
    <AppStatusChip 
        v-if="status"
        :text="examResultStatus(status).text"
        :color="examResultStatus(status).color"
    />
</template>