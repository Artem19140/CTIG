<script setup lang="ts">
defineProps<{
  label?: string
}>()

</script>

<template>
    <v-number-input 
        clearable
        variant="outlined"
        density="comfortable"
        :label="label"
    />
</template>