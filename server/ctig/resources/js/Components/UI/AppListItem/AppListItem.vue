<script setup lang="ts">
const props = defineProps<{
    title:string
}>()
</script>

<template>
    <v-list-item link class="cursor-pointer">
        <v-list-title>
            {{ title }}
        </v-list-title>
    </v-list-item>
</template>