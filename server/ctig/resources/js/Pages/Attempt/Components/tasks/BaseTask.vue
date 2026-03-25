<script setup lang="ts">
import RenderBlocks from './TaskContentBlocks/RenderBlocks.vue';

const props = defineProps<{
    content?:any,
    task?:any, 
    checking?:boolean
}>()

</script>

<template>
    <v-card width="600"
    >
        <v-card-subtitle>
            <slot name=description />
        </v-card-subtitle>
        
        <v-card-text>
            <RenderBlocks :content="content" />
        </v-card-text>

        <v-card-subtitle>
            <slot name=postscriptum />
        </v-card-subtitle>

        <v-card-actions>
            <slot name="answers" />
        </v-card-actions>

        <v-card-text>
            <v-select 
                v-if="checking"
                :label="`Оцените от 0 ${task?.mark}`"
            />
        </v-card-text>
    </v-card>
</template>