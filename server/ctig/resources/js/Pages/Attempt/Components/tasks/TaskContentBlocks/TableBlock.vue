<script setup lang="ts">
import RenderBlocks from './RenderBlocks.vue';

const props = defineProps<{
    rows : Array<Array<Object>>
}>()
</script>

<template>
    <div class="mt-5 mb-3">
        <div
            v-for="(row, index) in rows"
            :key="index"
            class="grid"
            :style="{ gridTemplateColumns: `repeat(${row.length}, minmax(0, 1fr))` }"
        >
            <div
                v-for="(data, i) in row"
                :key="i"
                class=" p-2 min-w-0 border border-gray-300"
            >
                <RenderBlocks :content="[data]" />
            </div>
        </div>
    </div>
</template>