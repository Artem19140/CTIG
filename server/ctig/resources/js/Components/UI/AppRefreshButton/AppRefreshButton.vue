<script setup lang="ts">
const props = defineProps<{
    iconSize:string
}>()
</script>

<template>
    <v-btn 
        icon
        variant="text"
    >
        <v-icon :size="iconSize">mdi-refresh</v-icon>
    </v-btn>
</template>