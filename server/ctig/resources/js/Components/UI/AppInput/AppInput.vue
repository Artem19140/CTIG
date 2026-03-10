<script setup lang="ts">
const props = defineProps<{
  name?: string,
  modelValue?:string,
  label?: string,
  errorMessages?:string,
  placeholder?:string,
  hint?:string,
  type?:string,
  disabled?:boolean
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: string): void
}>()


</script>

<template>
      <v-text-field
        :label="label"
        :hint="hint"
        :name="name"
        :model-value="modelValue"
        @update:modelValue="emit('update:modelValue', $event)"
        :placeholder="placeholder"
        :type="type"
        :error-messages="errorMessages"
        clearable
        :disabled="disabled"
      />
</template>