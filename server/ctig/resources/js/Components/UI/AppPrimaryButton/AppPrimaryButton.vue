<script setup lang="ts">
    const prop = defineProps<{
        text:string
    }>()
</script>

<template>
    <v-btn
        variant="flat"
        color="primary"
    >
        {{ text }}
    </v-btn>
</template>