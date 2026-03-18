<script setup lang="ts">
const props = defineProps<{
    value : string
}>()
</script>

<template>
    <div>{{ value }}</div>
</template>