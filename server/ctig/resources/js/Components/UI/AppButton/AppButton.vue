<script setup lang="ts">
const props = defineProps<{
  name?: string,
  model?:string,
  label?: string,
  severity?:string
}>()
</script>

<template>
    <FloatLabel variant="in">
        <InputText  :name="name"/>
        <label>{{ label }}</label>
    </FloatLabel>
</template>