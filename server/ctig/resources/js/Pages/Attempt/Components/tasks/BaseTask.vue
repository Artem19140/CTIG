<script setup lang="ts">
import RenderBlocks from './TaskContentBlocks/RenderBlocks.vue';
import { Task } from '@/interfaces/Task';
import { TaskTypes } from '@/constants/TaskTypes';
import AppStatusChip from '@/components/UI/AppStatusChip/AppStatusChip.vue';

import AppRetryAlert from '@/components/UI/AppRetryAlert/AppRetryAlert.vue';
import { useAttempt } from '@/composables/useAttempt';
import AppProgressCircular from '@/components/UI/AppProgressCircular/AppProgressCircular.vue';

const props = defineProps<{
  task:Task
}>()

const emit = defineEmits<{
  (e:'retry'):void
}>() 

const getDefaultDescription = (type:string) => {
  switch(type){
    case TaskTypes.SINGLE_CHOICE:
      return 'Выберите правильный ответ.'
    case TaskTypes.TEXT_INPUT:
      return 'Впишите ответ в поле ввода'
  }
}

const {errors, saving} = useAttempt()

</script>

<template>
  <div class="flex flex-column justify-center">
    <v-card
      width="600"
      elevation="6"
      rounded="lg"
      :id="`task-${task.id}`"
    >
      <v-card-title class="d-flex flex-column align-start ga-1">
        <div class="flex items-center gap-2 mt-2">
          <AppStatusChip 
            size="small" 
            :text="`Задание ${task?.order}`"
            color="primary"
          />
          <div 
            v-if="saving.has(task.id)" 
            class="flex items-center gap-2 text-grey text-caption"
            style="font-size: 12px;"
          >
              <AppProgressCircular size="20" />
              <span>Идет сохранение ответа...</span>
          </div>
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
            :content="task.content" 
        />
        </v-sheet>
      </v-card-text>

      <v-card-text v-if="task.postscriptum">
        {{ task.postscriptum }}
      </v-card-text>

      <v-card-actions class="px-4">
        <slot name="answers" /> 
      </v-card-actions>
    </v-card>

    <AppRetryAlert 
      v-if="errors.has(task.id)"
      text="Ошибка сохранения, пожалуйста, повторите действие"
      :onRetry="() => emit('retry')"
    />
  </div>
</template>

<style scoped>


.v-card {
  transition: all 0.2s ease;
}

.v-card:hover {
  transform: translateY(-2px);
}
</style>