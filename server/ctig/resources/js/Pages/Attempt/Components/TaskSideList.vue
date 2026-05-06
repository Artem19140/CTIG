<script setup lang="ts">
import { Task } from '@/interfaces/Task';
import { useAttempt } from '@/composables/useAttempt';

const props = defineProps<{
  tasks:Task[]
}>()

const go = (id:string) => {
  document.getElementById(id)?.scrollIntoView({
    behavior: 'smooth'
  })
}

const {errors} = useAttempt()

const getColor = (task:Task) :string => {
  if(errors.value.includes(task.order)){
    return 'red'
  }
  if(task?.attemptAnswer?.answer){
    return 'grey'
  }
  return ''
}
</script>

<template>
    <v-container class="pa-0">
      <v-row comfortable>
        <v-col
          v-for="task in tasks"
          :key="task.id"
          cols="3"
          sm="2"
          md="2"
          lg="2"
          class="d-flex justify-center"
        >
          <v-btn
            icon

            :color="getColor(task)"
            class="task-btn"
            @click="go(`task-${task.id}`)"
          >
            {{ task.order }}
          </v-btn>
        </v-col>
      </v-row>
    </v-container>
</template>

<style scoped>
.task-btn {
  width: 38px;
  height: 38px;
  min-width: 38px;
}
</style>