<script setup lang="ts">
import RenderBlocks from './TaskContentBlocks/RenderBlocks.vue';
import { Task } from '@/interfaces/Task';
import { TaskTypes } from '@/constants/TaskTypes';
import TaskRatingBlock from './TaskRatingBlock.vue';
import { Attempt } from '@/interfaces/Interfaces';
import AppStatusChip from '@/components/UI/AppStatusChip/AppStatusChip.vue';
import AppRefreshButton from '@/components/UI/AppRefreshButton/AppRefreshButton.vue';
import TaskSavingStatus from './TaskSavingStatus.vue';

const props = defineProps<{
  task:Task, 
  attempt?:Attempt,
  checking?:boolean,
  onRetry:() => void,
  error:boolean,
  loading:boolean
}>()

const getDefaultDescription = (type:string) => {
  switch(type){
    case TaskTypes.SINGLE_CHOICE:
      return 'Выберите один вариант ответа'
    case TaskTypes.TEXT_INPUT:
      return 'Впишите ответ в поле ввода'
  }
}
</script>
<template>
  <div class="flex flex-column justify-center pa-4">
    <v-card
      width="600"
      elevation="6"
      rounded="lg"
      :id="`task-${task.id}`"
    >
      <v-card-title class="d-flex flex-column align-start ga-1">
        <div class="flex items-center gap-2">
          <AppStatusChip 
            size="small" 
            :text="`Задание ${task?.order}`"
            color="primary"
          />
          <TaskSavingStatus 
            :loading="loading"
            :success="false"
            :error="error"
          />
        </div>
      </v-card-title>
      
      <div class="text-subtitle-1 font-weight-medium mt-1 pl-6 pr-4 pre-like">
        {{ 
          task?.description && task.description.trim() !== "" 
            ? task.description 
            : getDefaultDescription(task.type) 
        }}
      </div>

      <v-divider />

      <v-card-text>
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

      <v-card-actions class="px-4">
        <slot name="answers" />
        
      </v-card-actions>
      
    </v-card>

    <div v-if="checking" class="mt-6 mb-10 w-100">
      <TaskRatingBlock :task="task" />
    </div>
    <div class="mt-4" v-if="error">
      <v-alert
        density="compact"
        variant="tonal"
        type="error"
        prominent
      >
        <div class="flex items-center justify-between" >
          <span>
            Ошибка сохранения, пожалуйста, повторите действие
          </span>
          <AppRefreshButton
            icon-size="25"
            @click="onRetry"
          />
        </div>
      </v-alert>
      
      
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