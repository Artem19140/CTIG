<script setup lang="ts">
import { onUnmounted, ref, watch } from 'vue';
import AppAutocomplete from '../../../Components/AppAutocomplete/AppAutocomplete.vue';
import { useApi } from '../../../Composables/Api/useApi';
import axios from 'axios';
import { usePage } from '@inertiajs/vue3';

const page = usePage()

const examsTypes = page.props.examTypes
const examDates = ref<any[]>([])

const examTypeId = ref<number | null>(null)
const examId = defineModel<number |null>()

const props = defineProps<{
  studentId?:number
}>()

const datesApi =  useApi()
watch(examTypeId, async () => {
  if(examTypeId.value === null) return
  examDates.value = []
  await datesApi.request(()=> axios.get(`/exams/available?examTypeId=${examTypeId?.value}${props?.studentId ? `&studentId=${props?.studentId}` : ''}`))
  if(!datesApi.error.value && datesApi.data.value !== null){
    examDates.value = datesApi.data.value
  }
})

onUnmounted(() => {
  examId.value=null
})
</script>

<template>
  <AppAutocomplete
    v-model="examTypeId"
    :items="examsTypes"
    item-title="name"
    item-value="id"
    label="Тип экзамена"
  />

  <AppAutocomplete
    v-model="examId"
    :items="examDates"
    :disabled="datesApi.loading.value"
    :loading="datesApi.loading.value"
    item-title="begin_time"
    item-value="id"
    label="Дата и время"
  />
</template>