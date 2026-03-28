<script setup lang="ts">
const props = defineProps<{
    text?: string
}>()
</script>

<template>
    <v-chip
        small
        dark
    >
        {{ text }}
    </v-chip>
</template>