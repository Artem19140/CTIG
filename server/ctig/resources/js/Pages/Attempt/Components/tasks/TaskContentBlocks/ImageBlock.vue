<script setup lang="ts">
const props = defineProps<{
    value : string
}>()
</script>

<template>

    <v-img
        :src="value"
        :width="300"
    >

    </v-img>
</template>