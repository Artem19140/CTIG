<script setup lang="ts">
import { onMounted, onUnmounted, ref, watch } from 'vue';
import AppAutocomplete from '../../../Components/AppAutocomplete/AppAutocomplete.vue';
import {useHttp} from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

const page = usePage()

const examsTypes = page.props.examTypes
const examDates = ref<any[]>([])

const examId = defineModel<number |null>()

const noExamTypeChoice = ref<boolean>(false)

const props = defineProps<{
  foreignNationalId?:number,
  examTypeId?:number | null
}>()

const http = useHttp<{
  examTypeId: number | null
  foreignNationalId?: number
}>({
  examTypeId: null,
  foreignNationalId: props.foreignNationalId ?? undefined
})

watch(() => http.examTypeId, async () => {
  if(http.examTypeId === null) return
  examDates.value = []
  http.get('/exams/available',{
    
    onSuccess:(response:any) => {
      examDates.value = response
    }
  })
})

onMounted(() => {
  if(!props.examTypeId) return
  http.examTypeId = props.examTypeId
  noExamTypeChoice.value = true
})

onUnmounted(() => {
  examId.value=null
  noExamTypeChoice.value = false
})
</script>

<template>
  <AppAutocomplete
    v-model="http.examTypeId"
    :items="examsTypes"
    item-title="name"
    item-value="id"
    :disabled="noExamTypeChoice"
    :error-messages="http.errors.examTypeId"
    label="Тип экзамена"
  />

  <AppAutocomplete
    v-model="examId"
    :items="examDates"
    :disabled="http.processing"
    :loading="http.processing"
    item-title="beginTime"
    item-value="id"
    label="Дата и время"
  />
</template>