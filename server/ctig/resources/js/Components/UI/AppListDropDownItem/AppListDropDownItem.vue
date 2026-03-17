<script setup lang="ts">
const props = defineProps<{
    title:string,
    color?:string
}>()
</script>

<template>
    <v-list-item link class="cursor-pointer">
        <v-list-title :class="color">
            {{ title }}
        </v-list-title>
    </v-list-item>
</template>