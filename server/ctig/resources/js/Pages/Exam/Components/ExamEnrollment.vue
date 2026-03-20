<script setup lang="ts">
import { onMounted, onUnmounted, ref, watch } from 'vue';
import AppAutocomplete from '../../../Components/AppAutocomplete/AppAutocomplete.vue';
import {router} from '@inertiajs/vue3'

const examsTypes = ref()
const examDates = ref()
const loading1 = ref<boolean>(false)
const loading2 = ref<boolean>(false)

const examType = ref<number | null>(null)

const examId = defineModel<number |null>()

onMounted(()=>{
  loading1.value = true
  router.get('/exams/types',{},{
    onSuccess: (page)=>{
      loading1.value = false
      if(page.flash.examTypes){
        examsTypes.value = page.flash.examTypes
      }
    }
  })
})

watch(examType,() => {
  if(examType.value === null) return
  loading2.value = true
  router.get('/exams/available',
    {
      examTypeId:examType.value
    },
    {
      onSuccess: (page) => {
        if(page.flash.exams){
          examDates.value = page.flash.exams
        }
        loading2.value = false
      }
    }
  )
})


onUnmounted(() => {
  examId.value=null
  loading1.value=false
  loading2.value=false
})
</script>

<template>
    <AppAutocomplete
      v-model="examType"
      :items="examsTypes"
      :loading="loading1"
      :disabled="loading1"
      item-title="name"
      item-value="id"
      label="Тип экзамена"
    />

    <AppAutocomplete
      v-model="examId"
      :items="examDates"
      :disabled="loading1"
      :loading="loading2"
      item-title="begin_time"
      item-value="id"
      label="Дата и время"
    />
</template>