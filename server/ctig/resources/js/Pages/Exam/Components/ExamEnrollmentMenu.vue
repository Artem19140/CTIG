<script setup lang="ts">
import axios from 'axios';
import { ref } from 'vue';
import { formatterTime, formatterDate } from '../../../Helpers/heplers';
import { router } from '@inertiajs/vue3';

const props = defineProps<{
    student : any | null
}>()

const exams = ref()

const getExams = async () => {
    const res = await axios.get('exams/available')
    exams.value = res.data.data
}

const enroll = (exam : any) => {
  if(!exam || !props.student.id){
    return  
  }
  if(confirm(`Записать ${props.student?.surname} ${props.student?.name[0]}.${props.student?.patronymic[0]}. на экзамен по ${exam.shortName} на ${ formatterDate(exam.beginTime) }  в  ${ formatterTime(exam.beginTime)}`)){
    router.post(`exams/${exam.id}/enroll`, {studentId:props.student.id})
  }
}
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
      </v-list>
    </v-menu>
</template>