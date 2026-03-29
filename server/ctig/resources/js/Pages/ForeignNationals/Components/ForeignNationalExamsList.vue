<script setup lang="ts">
import type { Attempt, Exam } from '../../../interfaces/interfaces';

const props = defineProps<{
    exams:Array<Exam> | null
}>()

const attemptStatus = (attempt: Attempt): string => {
  if (attempt.status === 'banned') return 'Аннулирована'
  if (attempt.status === 'finished' && !attempt.isPassed) return 'На проверке'
  if (attempt.isPassed) return 'Пройдена'
  return 'Не пройдена'
}
</script>

<template>
    <v-list>
        <v-list-item>
            <v-list-item-subtitle class="mb-4">Экзамены</v-list-item-subtitle>
            <v-list-item-title class="text-center" v-if="exams?.length === 0">Записей на экзамены не было</v-list-item-title>
                <v-list 
                    scrollable 
                    max-height="200"
                >
                    <v-list-item 
                        :class="['px-2', index % 2 === 0 ?  '' : 'bg-grey-lighten-4']"
                        v-for="(exam, index) in exams ?? []" :key="exam.id"
                        link
                        >
                            <v-list-item-title>{{exam.name}}</v-list-item-title>
                            <v-list-item-subtitle> {{exam.beginTime}} </v-list-item-subtitle>
                            <v-list-item-subtitle v-if="exam.attempts?.length && exam.isPast"> Попытки: </v-list-item-subtitle>
                            <v-list-item-subtitle 
                                v-for="(attempt) in exam.attempts ?? []"  
                                class="text-medium-emphasis ml-2"
                                :key="attempt.id"
                            >
                               {{attemptStatus(attempt)}}
                            </v-list-item-subtitle>
                            <v-list-item-subtitle 
                                class="text-medium-emphasis ml-2" 
                                v-if="!exam.attempts?.length && exam.isPast"> Неявка </v-list-item-subtitle
                            >
                            <v-list-item-subtitle 
                                class="text-medium-emphasis ml-2" 
                                v-if="!exam.attempts?.length && !exam.isPast"> Ожидается </v-list-item-subtitle
                            >
                    </v-list-item>
                </v-list>
        </v-list-item>
    </v-list>
</template>