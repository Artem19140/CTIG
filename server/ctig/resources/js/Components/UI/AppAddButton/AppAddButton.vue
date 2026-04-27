<script setup lang="ts">
const props = defineProps<{
  text?: string
}>()
</script>

<template>
    <v-btn
        color="green"
        variant="flat"
        class="me-2"
        rounded="lg"
        border
    >
        {{ text ?? 'Добавить'}}
    </v-btn>
</template>