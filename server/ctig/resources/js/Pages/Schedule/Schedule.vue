<script setup lang="ts">
import EmployeeLayout from '@layouts/EmployeeLayout.vue';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Exam } from '@interfaces/Interfaces';
import { useModals } from '@composables/useModals';
import AppAddButton from '@components/UI/AppAddButton/AppAddButton.vue';
import { useAuth } from '@composables/useAuth';
import { Roles } from '@constants/Roles';
import { examStatus } from '@helpers/heplers';

defineOptions({
  layout: [EmployeeLayout],
})
const props = defineProps<{
  exams : any | null
}>()

const {can} = useAuth()
const {open} = useModals()

const calendar = ref()
const focus = ref<string>('')
const loading = ref<boolean>(false)

const type = ref<string>('month')

const types = [
    {value:'day', label:'День'},
    {value:'week', label:'Неделя'},
    {value:'month', label:'Месяц'}
]

const openExam = (nativeEvent : Event, { event } :any) => {
  open('examShow', {examId:event.id})
}

const getColor = (event : Exam) => {
  return examStatus(event).color
}

const prev = () => {
  calendar.value?.prev()
}

const next = () => {
  calendar.value?.next()
}


function getEvents ({ start, end } :any) {
  loading.value=true
  router.reload({
      data: {
        dateFrom: start.date,
        dateTo:end.date
      },
      onFinish:()=>{
        loading.value=false
      }
  })
}
const addExam = (nativeEvent : Event, { date } : any) => {
  if(!can([Roles.SCHEDULER])) return
  open('examCreate', {date})
}
</script>

<template>
    <v-sheet class="d-flex" tile>
      <v-btn
        class="ma-2"
        variant="text"
        icon
        :disabled="loading"
        @click="prev"
      >
        <v-icon>mdi-chevron-left</v-icon>
      </v-btn>
      <div class="flex items-center gap-8 mr-8">
        {{ calendar?.title }}
      </div>
      <v-select
        v-model="type"
        :items="types"
        class="ma-2"
        density="comfortable"
        label="Период"
        items-value="value"
        item-title="label"
        variant="outlined"
        hide-details
        
      ></v-select>
      <v-spacer></v-spacer>
      <div class="flex items-center gap-8 mr-8">
        <AppAddButton
            text="Добавить"
            @click="open('examCreate', {})"
            v-if="can([Roles.SCHEDULER])"
        />
      </div>
      
      <v-btn
        class="ma-2"
        variant="text"
        icon
        @click="next"
        :disabled="loading"
      >
        <v-icon>mdi-chevron-right</v-icon>
      </v-btn>
    </v-sheet>
      <v-calendar
        v-model="focus"
        color="primary"
        ref="calendar"
        :events="exams?.data"
        :event-color="getColor"
        @click:event="openExam"
        @click:more="openExam"
        :type="type"
        @change="getEvents"
        @click:date="addExam"
      >
      </v-calendar>
</template>