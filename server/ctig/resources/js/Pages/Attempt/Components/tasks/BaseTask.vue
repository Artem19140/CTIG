<script setup lang="ts">
import AppAutocomplete from '@/components/UI/AppAutocomplete/AppAutocomplete.vue';
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
        
const marks = [
    {
        mark:props.task.mark,
        value:props.task.mark
    }
]
</script>

<template>
    <v-card width="600"
        :subtitle ="`Задание ${task?.order}`"
        :id="`task-${task.id}`"
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
        
        <!-- <v-card-text v-if="$slots.saved">
            <slot name="saved" />
        </v-card-text> -->
    </v-card>
     <div v-if="checking" class="mt-4">
        <AppAutocomplete 
            :label="`Введите балл от 0 до ${task.mark}`" 
            :items="marks"  
            item-title="mark"
        />
    </div>
    
</template>

<style lang="css" scoped>
.description {
  padding: 12px 16px;
  background: #f5f5f5;
  border-left: 4px solid #1976d2;
  font-weight: 500;
}
</style>