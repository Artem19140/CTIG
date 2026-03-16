<script lang="ts">
import EmployeeLayout from '../../Layout/EmployeeLayout.vue';
import { useExamShowModal } from '../../Composables/modalWindows/useExamShowModal';
import ExamCreateModal from '../Exam/Components/ExamCreateModal.vue';

export default {
  layout: EmployeeLayout,
}
</script>



<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps<{
    exams : any | null
}>()

const calendar = ref()
const focus = ref('')

const type = ref<string>('week')
const types = [
    {value:'day', label:'День'},
    {value:'week', label:'Неделя'},
    {value:'month', label:'Месяц'}
]

const open = () => {
    alert(1)
}
const openExam = (nativeEvent : Event, { event } :any) => {
    const {open} = useExamShowModal()
    open(event.id)
}

const getColor = (event : any) => {
    if(event.isCancelled === true){
        return 'black'
    }
    if (new Date(event.beginTime) < new Date()) { //endTime
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
  router.get('/exams/schedule', {dateFrom:start.date, dateTo:end.date}, {preserveState:true, preserveScroll:true})
  console.log(start, end)
}

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
        <ExamCreateModal />
        {{ calendar.title }}
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
            v-model="focus"
            color="primary"
            ref="calendar"
            :events="exams.data"
            :event-color="getColor"
            @click:event="openExam"
            @click:more="openExam"
            :type="type"
            @change="getEvents"
        >
        </v-calendar>
<!-- @click:date="openExam" -->
</template>