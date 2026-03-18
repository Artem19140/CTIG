<script setup lang="ts">
import RenderBlocks from './TaskContentBlocks/RenderBlocks.vue';

const props = defineProps<{
    content?:any
}>()

</script>

<template>
    <v-card width="600"
    >
        <v-card-text>
            <RenderBlocks :content="content" />
        </v-card-text>

        <v-card-actions>
            <slot name="answers" />
        </v-card-actions>
    </v-card>
</template>