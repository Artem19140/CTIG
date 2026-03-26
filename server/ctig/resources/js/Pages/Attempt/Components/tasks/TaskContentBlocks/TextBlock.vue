<script setup lang="ts">
const props = defineProps<{
    value : string
}>()
</script>

<template>
    <div class="whitespace-pre-wrap break-words">{{ value }}</div>
</template>