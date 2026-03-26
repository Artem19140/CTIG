<script setup lang="ts">
import RenderBlocks from './TaskContentBlocks/RenderBlocks.vue';

const props = defineProps<{
    task?:any, 
    checking?:boolean
}>()

const getDefaultDescription = (type:string) => {
    switch(type){
        case 'single-choice':
        return 'Выберите один вариант ответа'
    }
}
        

</script>

<template>
    <v-card width="600"
        :subtitle ="`Задание ${task?.order}`"
    >
        <div class="description">
            {{ task?.description && task.description.trim() !== "" ? task.description : getDefaultDescription(task?.type) }}
        </div>
        
        
        <v-card-text>
            <RenderBlocks :content="task.content" />
        </v-card-text>

        <v-card-actions>
            <!-- <RenderBlocks :content="task" /> -->
            <slot name="answers" />
        </v-card-actions>

        <v-card-text v-if="checking">
            <v-select 
                
                :label="`Оцените от 0 ${task?.mark}`"
            />
        </v-card-text>
        <!-- <v-card-text v-if="$slots.saved">
            <slot name="saved" />
        </v-card-text> -->
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