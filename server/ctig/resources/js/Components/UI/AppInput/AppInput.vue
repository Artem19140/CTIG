<script setup lang="ts">
defineProps<{
  label?: string
  required?: boolean
}>()
</script>

<template>
    <v-text-field
      clearable
      variant="outlined"
      density="comfortable"
    >
      <template #label>
        <span>
          {{ label }}
          <span v-if="required" style="color:red">*</span>
        </span>
      </template>
    </v-text-field>
</template>