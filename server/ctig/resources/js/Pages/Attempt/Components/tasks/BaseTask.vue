<script setup lang="ts">
import RenderBlocks from './TaskContentBlocks/RenderBlocks.vue';

const props = defineProps<{
    content?:any,
    task?:any, 
    checking?:boolean,
    description?:string
}>()

</script>

<template>
    <v-card width="600"
    >
        <div class="description">
            {{ task?.description !== "" ? task?.description : description }}
        </div>
        
        
        <v-card-text>
            <RenderBlocks :content="content" />
        </v-card-text>

        <v-card-actions>
            <!-- <RenderBlocks :content="task" /> -->
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

<style lang="css" scoped>
.description {
  padding: 12px 16px;
  background: #f5f5f5;
  border-left: 4px solid #1976d2;
  font-weight: 500;
}
</style>