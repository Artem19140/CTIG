<script setup lang="ts">

const props = defineProps<{
  modelValue:any,
  items:any[], 
  label?:string
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: any): void
}>()
</script>

<template>
  <v-card>
    <v-container fluid>
      <v-row>
        <v-col cols="12">
          <v-combobox
            :model-value="props.modelValue"
            :items="props.items"
            :label="props.label"
            @update:model-value="emit('update:modelValue', $event)"
          ></v-combobox>
        </v-col>
      </v-row>
    </v-container>
  </v-card>
</template>

<style></style>