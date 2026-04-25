<script setup lang="ts">
import { ref, watch } from 'vue';
import AppAutocomplete from '@components/UI/AppAutocomplete/AppAutocomplete.vue';
import {useHttp} from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import AppCheckbox from '../UI/AppCheckbox/AppCheckbox.vue';

const page = usePage()

const examId = defineModel<number | null>('examId')
const hasPayment = defineModel<boolean>('hasPayment')

const examsTypes = page.props.examTypes
const examDates = ref<any[]>([])

const props = defineProps<{
  foreignNationalId?:number,
  examValidationErrors?:string
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
    :error-messages="examValidationErrors"
    item-title="beginTime"
    item-value="id"
    label="Дата и время"
  />
  <AppCheckbox
    v-model="hasPayment" 
    label="Есть оплата"
  ></AppCheckbox>
   <div class="text-caption text-medium-emphasis mt-2">
    Запись закрывается за 10 минут до начала экзамена
  </div>
</template>