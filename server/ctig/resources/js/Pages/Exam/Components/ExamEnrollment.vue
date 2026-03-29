<script setup lang="ts">
import { onUnmounted, ref, watch } from 'vue';
import AppAutocomplete from '../../../Components/AppAutocomplete/AppAutocomplete.vue';
import {useHttp} from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

const page = usePage()

const examsTypes = page.props.examTypes
const examDates = ref<any[]>([])

const examId = defineModel<number |null>()

const props = defineProps<{
  foreignNationalId?:number
}>()

const http = useHttp({
  examTypeId:null,
  foreignNationalId:props.foreignNationalId ?? undefined
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

onUnmounted(() => {
  examId.value=null
})
</script>

<template>
  <AppAutocomplete
    v-model="http.examTypeId"
    :items="examsTypes"
    item-title="name"
    item-value="id"
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