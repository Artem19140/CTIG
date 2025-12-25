<script setup lang="ts">

const props = defineProps<{
  modelValue:any,
  placeholder?:string
  label?:string
  rules?:any[]
  type?:string
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: any): void
}>()
</script>

<template>
  <v-text-field
    :label="props.label"
    :type="type"
    @update:model-value="emit('update:modelValue', $event)"
    clearable
    :rules="rules"
></v-text-field>
</template>

<style></style>
