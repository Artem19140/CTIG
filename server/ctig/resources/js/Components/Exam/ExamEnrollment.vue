<script setup lang="ts">
import { onMounted, ref, watch } from 'vue';
import AppAutocomplete from '@components/UI/AppAutocomplete/AppAutocomplete.vue';
import {useHttp} from '@inertiajs/vue3';
import AppCheckbox from '@components/UI/AppCheckbox/AppCheckbox.vue';
import AppTooltip from '@components/UI/AppTooltip/AppTooltip.vue';
import { ExamIndex, ExamType } from '@/interfaces/Exam';

const examId = defineModel<number | null>('examId', {default:null})
const hasPayment = defineModel<boolean>('hasPayment', {default:false})

const examsTypes = ref<ExamType[] | []>([])
const examDates = ref<ExamIndex[]>([])

const props = defineProps<{
  foreignNationalId?:number,
  examValidationErrors?:string
}>()

const http = useHttp<{
  examTypeId: number | null
  foreignNationalId?: number
}, ExamIndex[]>({
  examTypeId: null,
  foreignNationalId: props.foreignNationalId ?? undefined
})

watch(() => http.examTypeId, async () => {
  examId.value = null
  if(http.examTypeId === null) return
  examDates.value = []
  http.get('/exams/available',{
    onSuccess:(response) => {
      examDates.value = response
    }
  })
})

const examTypesHttp = useHttp<{}, ExamType[]>()

onMounted(() => {
  examTypesHttp.get('/exams/types', {
    onSuccess(response) {
      examsTypes.value = response
    },
  })
})
</script>

<template>
  <div class="mb-3">
    Выберите экзамен для записи
    <AppTooltip 
      text="Запись закрывается за 10 минут до начала экзамена"
    />
  </div>
  <AppAutocomplete
    v-model="http.examTypeId"
    :items="examsTypes"
    item-title="name"
    item-value="id"
    :error-messages="http.errors.examTypeId"
    label="Тип экзамена"
    :loading="examTypesHttp.processing"
    :disabled="examTypesHttp.processing"
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
</template>