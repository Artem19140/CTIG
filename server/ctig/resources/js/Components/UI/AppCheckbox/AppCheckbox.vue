<script setup lang="ts">
const props = defineProps<{
    label?:string
}>()
</script>

<template>
    <v-checkbox :label="label">

    </v-checkbox>
</template>