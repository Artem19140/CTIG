<script setup lang="ts">
import RenderBlocks from './TaskContentBlocks/RenderBlocks.vue';
import { Task } from '@/interfaces/Task';
import { TaskTypes } from '@/constants/TaskTypes';
import TaskRatingBlock from './TaskRatingBlock.vue';
import { Attempt } from '@/interfaces/Interfaces';

const props = defineProps<{
    task:Task, 
    attempt?:Attempt,
    checking?:boolean
}>()

const emit = defineEmits<{
    (e:'answerSaved', value:any):void
}>()

const saved = (value:any) => {
    emit('answerSaved', value)
}

const getDefaultDescription = (type:string) => {
  switch(type){
      case TaskTypes.SINGLE_CHOICE:
          return 'Выберите один вариант ответа'
      case TaskTypes.TEXT_INPUT:
          return 'Впишите ответ в поле ввода'
  }
}
</script>

<!-- <template>
    <div>
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

        <div v-if="checking" class="mt-4 mb-8">
            <TaskRatingBlock @saved="saved" :task="task" />
        </div>
    </div>
</template>

<style lang="css" scoped>
    .description {
    padding: 12px 16px;
    background: #f5f5f5;
    border-left: 4px solid #1976d2;
    font-weight: 500;
    }
</style> -->

<template>
  <div class="flex flex-column justify-center pa-4">
    <v-card
      width="600"
      elevation="6"
      rounded="lg"
      :id="`task-${task.id}`"
    >
      <v-card-title class="d-flex flex-column align-start ga-1">
        <v-chip
          size="small"
          color="primary"
          variant="tonal"
        >
          Задание {{ task?.order }}
        </v-chip>
      </v-card-title>
      <div class="text-subtitle-1 font-weight-medium mt-1 pl-6 pr-4 pre-like">
        {{ 
          task?.description && task.description.trim() !== "" 
            ? task.description 
            : getDefaultDescription(task.type) 
        }}
      </div>

      <v-divider />

      <v-card-text class="pt-4">
        <v-sheet
          rounded="lg"
          class="pa-3"
        >
          <RenderBlocks 
            :task="task"
            :attempt="attempt"
            :content="task.content" 
        />
        </v-sheet>
      </v-card-text>



      <v-card-actions class="px-4 py-3">
        <slot name="answers" />
      </v-card-actions>
    </v-card>

    <div v-if="checking" class="mt-6 mb-10 w-100">
        <TaskRatingBlock @saved="saved" :task="task" />
    </div>
  </div>
</template>

<style scoped>
.description {
  line-height: 1.5;
  color: rgba(0, 0, 0, 0.75);
}

.v-card {
  transition: all 0.2s ease;
}

.v-card:hover {
  transform: translateY(-2px);
}

.pre-like{
  white-space: pre-wrap;
}
</style>