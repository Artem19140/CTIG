<script setup lang="ts">
import axios from 'axios';
import { ref } from 'vue';
import { formatterTime, formatterDate } from '../../../Helpers/heplers';
import { useConfirmDialog } from '../../../Composables/useConfirmDialog';
import { Exam } from '../../../interfaces/interfaces';
import { modalState } from '../../../Composables/modalState';


const props = defineProps<{
    student : any | null
}>()

const exams = ref<Exam[]>()

const getExams = async () => {
    const res = await axios.get('exams/available')
    exams.value = res.data.data
}


const {confirmOpen} = useConfirmDialog()
const enroll = async (exam : any) => {
  if(!exam || !props.student.id){
    return  
  }
  if(await confirmOpen(`Записать ${props.student?.surname} ${props.student?.name[0]}.${props.student?.patronymic[0]}. на экзамен по ${exam.shortName} на ${ formatterDate(exam.beginTime) }  в  ${ formatterTime(exam.beginTime)}`)){
    await axios.post(`exams/${exam.id}/student`, {studentId:props.student.id})
    modalState.fileUrl = `students/${props.student.id}/application-forms?examId=${exam.id}`
  }
}
//Если нет экзаменов - тогда сообщение!
</script>

<template>
    <v-menu 
        location="end"
        width="400"
    >
      <template v-slot:activator="{ props }">
        <v-btn
          color="primary"
          v-bind="props"
          @click="getExams"
        >
          Записать
        </v-btn>
      </template>

      <v-list>
        <v-list-item
        link
            v-for="(exam, index) in exams" :key="exam.id"
            @click="enroll(exam)"
        >
            <v-list-item-title>{{ exam.shortName }}</v-list-item-title>
            <v-list-item-subtitle>{{ formatterTime(exam.beginTime) }} {{ formatterDate(exam.beginTime) }}</v-list-item-subtitle>
        </v-list-item>
        <v-list-item v-if="!exams">
          <v-list-item-title>Нет доступных экзаменов</v-list-item-title>
        </v-list-item>
      </v-list>
      
    </v-menu>
</template>