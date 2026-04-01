<script setup lang="ts">
import type { Exam } from '../../../interfaces/interfaces';
import { attemptResultStatus } from '../../../Helpers/heplers';
import AppStatusChip from '../../../Components/AppStatusChip/AppStatusChip.vue';
import { DateFormatter } from '../../../Helpers/DateFormatter';

const props = defineProps<{
  exams: Array<Exam> | []
}>();
</script>

<template>
  <v-list two-line>
    <v-list-item>
      <v-list-item-subtitle class="mb-4 text-h6">Экзамены</v-list-item-subtitle>
      <v-list-item-title class="text-center" v-if="!exams || exams.length === 0">
        Записей на экзамены не было
      </v-list-item-title>

      <v-list
        v-else
        max-height="300"
        class="overflow-y-auto"
      >
        <v-list-item
          v-for="(exam, index) in exams ?? []"
          :key="exam.id"
          class="px-4 py-3 mb-2 rounded-lg"
          :elevation="1"
        >
            <v-list-item-title class="text-subtitle-1 font-medium">
              {{ exam.name }}
            </v-list-item-title>
            <v-list-item-subtitle class="mb-2">
              {{ new DateFormatter(exam.beginTime).format('d.m.Y') }}
            </v-list-item-subtitle>

            <AppStatusChip
              :color="attemptResultStatus(exam.attempts?.[0] ?? null, exam.isPast).color"
              :text="attemptResultStatus(exam.attempts?.[0] ?? null, exam.isPast).text"
            />
        </v-list-item>
      </v-list>
    </v-list-item>
  </v-list>
</template>