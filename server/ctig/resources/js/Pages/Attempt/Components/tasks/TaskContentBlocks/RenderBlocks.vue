<script setup lang="ts">
import AudioBlock from './AudioBlock.vue';
import ImageBlock from './/ImageBlock.vue';
import TextBlock from  './TextBlock.vue';

const props = defineProps<{
    content?:any
}>()

const taskBlocks = (type: string) => {
    switch (type) {
        case 'text':
            return TextBlock
        case 'image':
            return ImageBlock
        case 'audio':
            return AudioBlock
        default:
            return TextBlock
    }
}
</script>

<template>
    <component 
        v-for="(block, index) in content"
        :key="index"
        :is="taskBlocks(block.type)"
        v-bind="block"
    />
</template>