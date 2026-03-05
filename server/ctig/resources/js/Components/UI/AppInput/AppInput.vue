<script setup lang="ts">
const props = defineProps<{
  name?: string,
  modelValue?:string,
  label?: string,
  errorMessage?:string,
  placeholder?:string,
  hint?:string,
  type?:string,
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: string): void
}>()

const onInput = (event: Event) => {
  const value = (event.target as HTMLInputElement).value
  emit('update:modelValue', value)
}
</script>

<template>
      <v-text-field
        :label="label"
        :hint="hint"
        :name="name"
        :model="modelValue"
        @input="onInput"
        :placeholder="placeholder"
        :type="type"
        :error-messages="errorMessage"
        clearable
      />
      
        
    
</template>