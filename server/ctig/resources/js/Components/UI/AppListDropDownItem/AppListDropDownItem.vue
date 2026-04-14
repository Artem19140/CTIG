<script setup lang="ts">
const props = defineProps<{
    title:string,
    color?:string
}>()
</script>

<template>
    <v-list-item link class="cursor-pointer">
        <v-list-item-title :class="color">
            {{ title }}
        </v-list-item-title>
    </v-list-item>
</template>