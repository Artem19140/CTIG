<script setup lang="ts">
const props = defineProps<{
    content?:any
}>()
</script>

<template>
    <v-card width="600"
    >
        <v-card-text>
            <div>{{ content }}</div>
            <slot />
        </v-card-text>

        <v-card-actions>
            <slot name="answers" />
        </v-card-actions>
    </v-card>
</template>