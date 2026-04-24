<script setup lang="ts">
defineProps<{
  label?: string
}>()
</script>

<template>
    <v-text-field
      clearable
      variant="outlined"
      density="comfortable"
      :label="label"
    >
    </v-text-field>
</template>