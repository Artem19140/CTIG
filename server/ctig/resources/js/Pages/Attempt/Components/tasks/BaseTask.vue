<script setup lang="ts">
import AppAutocomplete from '@/components/UI/AppAutocomplete/AppAutocomplete.vue';
import RenderBlocks from './TaskContentBlocks/RenderBlocks.vue';
import { Task } from '@/interfaces/Task';
import { TaskTypes } from '@/constants/TaskTypes';

const props = defineProps<{
    task:Task, 
    checking?:boolean
}>()

const getDefaultDescription = (type:string) => {
    switch(type){
        case TaskTypes.SINGLE_CHOICE:
            return 'Выберите один вариант ответа'
        case TaskTypes.TEXT_INPUT:
            return 'Впишите ответ в поле ввода'
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
            <slot name="answers" />
        </v-card-actions>
    </v-card>

    <div v-if="checking" class="mt-4">
        <AppAutocomplete 
            :label="`Выберите балл от 0 до ${task.mark}`" 
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