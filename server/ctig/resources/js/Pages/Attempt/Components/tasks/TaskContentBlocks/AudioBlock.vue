<script setup lang="ts">
const props = defineProps<{
    value : string
}>()
</script>

<template>
    <audio
        :src="value"
    >

    </audio>
</template>