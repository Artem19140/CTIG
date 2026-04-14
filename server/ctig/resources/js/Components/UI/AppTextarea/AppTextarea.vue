<script setup lang="ts">
defineProps<{
  label: string
  required?: boolean
}>()
</script>

<template>
    <v-textarea
        auto-grow
        rows="1"
        variant="outlined"
        density="comfortable"
        counter
    >
        <template #label>
            <span>
                {{ label }}
                <span v-if="required" style="color:red">*</span>
            </span>
        </template>
    </v-textarea>
</template>