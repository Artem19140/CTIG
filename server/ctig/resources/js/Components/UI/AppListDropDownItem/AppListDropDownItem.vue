<script setup lang="ts">

const props = defineProps<{
    title:string,
    color?:string,
    subtitle?:string | null,
}>()
</script>

<template>
    <v-list-item link class="cursor-pointer">
        <v-list-item-title :class="color">
            {{ title }}
        </v-list-item-title>
        <v-list-item-subtitle :class="color">
            {{ subtitle }}
        </v-list-item-subtitle>
    </v-list-item>
</template>