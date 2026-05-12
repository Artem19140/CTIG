<script setup lang="ts">
import RenderBlocks from './RenderBlocks.vue';
const props = defineProps<{
    value : any
}>()

</script>

<template>
    <v-card class="mt-4 mb-3">
        <v-card-text>
            <RenderBlocks :content="value" />
        </v-card-text>
    </v-card>
</template>