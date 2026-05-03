<script setup lang="ts">
const props = defineProps<{
    text?:string
}>()
</script>

<template>
    <v-tooltip location="top">
        <template #activator="{ props }">
            <v-icon v-bind="props" icon="mdi-information-outline" />
        </template>

        <span>
            {{ text }}
        </span>
        <slot></slot>
    </v-tooltip>
</template>