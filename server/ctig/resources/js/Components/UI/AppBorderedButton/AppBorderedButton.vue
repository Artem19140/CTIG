<script setup lang="ts">
const props = defineProps<{
    text:string
}>()
</script>

<template>
    <v-btn
        border
    >
        {{ text }}
    </v-btn>
</template>