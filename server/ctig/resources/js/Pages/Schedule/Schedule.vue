<script setup lang="ts">
import EmployeeLayout from '../../Layout/EmployeeLayout.vue';
import { useExamShowModal } from '../../Composables/modalWindows/useExamShowModal';
import ExamCreateModal from '../Exam/Components/ExamCreateModal.vue';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Exam } from '../../interfaces/interfaces';

defineOptions({
  layout: EmployeeLayout,
})
const props = defineProps<{
    exams : any | null
}>()

const calendar = ref()
const focus = ref('')
const dayDate= ref('')

const type = ref<string>('week')
const types = [
    {value:'day', label:'День'},
    {value:'week', label:'Неделя'},
    {value:'month', label:'Месяц'}
]

const openExam = (nativeEvent : Event, { event } :any) => {
    const {open} = useExamShowModal()
    open(event.id)
}

const getColor = (event : Exam) => {
    if(event?.isCancelled === true){
        return 'black'
    }
    if (event?.isPast) {
        return 'grey'
    }
    return 'blue'
}

const prev = () => {
  calendar.value?.prev()
}

const next = () => {
  calendar.value?.next()
}

function getEvents ({ start, end } :any) {
  router.reload({
      data: {
        dateFrom: start.date,
        dateTo:end.date
      },
  })
}
// function getEvents(payload: any) {
//   console.log(payload)
// }

// const addExam = (nativeEvent : Event, { date } : any) => {
//   console.log(date)
//   dayDate.value=date
// }
</script>

<template>
    <v-sheet class="d-flex" tile>
      <v-btn
        class="ma-2"
        variant="text"
        icon
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
        <ExamCreateModal />
      </div>
      
      <v-btn
        class="ma-2"
        variant="text"
        icon
        @click="next"
      >
        <v-icon>mdi-chevron-right</v-icon>
      </v-btn>
    </v-sheet>
      <v-calendar
        event-name = 'shortName'
        v-model="focus"
        color="primary"
        ref="calendar"
        :events="exams?.data"
        :event-color="getColor"
        @click:event="openExam"
        @click:more="openExam"
        :type="type"
        @change="getEvents"
        @click:date=""
      >
      </v-calendar>
</template>