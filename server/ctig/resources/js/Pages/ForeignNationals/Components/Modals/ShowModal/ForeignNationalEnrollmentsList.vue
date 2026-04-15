<script setup lang="ts">
import type { ForeignNational } from '@interfaces/Interfaces';
import EnrollmentDropDown from '@/components/Enrollment/EnrollmentDropDown.vue';
import { DateFormatter } from '@helpers/DateFormatter';
import { useModals } from '@composables/useModals';
import ForeignNationalEnrollmentsDropdown from './ForeignNationalEnrollmentsDropdown.vue';
import ExamResultStatusChip from '@/components/Exam/ExamResultStatusChip.vue';

defineProps<{
  foreignNational: ForeignNational
}>();

const modals = useModals()
</script>

<template>
    <div class="text-h6 mb-4">Записи на экзамены</div>
    <div v-if="!foreignNational?.enrollments || foreignNational?.enrollments.length === 0" class="text-center text-medium-emphasis">
      Записей на экзамены не было
    </div>

    <v-sheet
      
      v-else
      max-height="360"
      class="overflow-y-auto pr-2"
      rounded="lg"
    >
      <v-card
        v-for="enrollment in foreignNational.enrollments"
        :key="enrollment.id"
        @click="modals.open('examShow', {examId:enrollment.exam.id})"
        class="mb-3"
        variant="tonal"
        rounded="lg"
      >
        <v-card-text class="d-flex justify-space-between align-center">
          <div>
            <div class="text-subtitle-1 font-weight-medium">
              {{ enrollment.exam.shortName }}
            </div>
            <div class="text-caption text-medium-emphasis">
              {{ new DateFormatter(enrollment.exam.beginTime).format('d.m.Y') }}
            </div>
          </div>
          <div>
            <ExamResultStatusChip 
              :status="enrollment.examResult"
            />
            <EnrollmentDropDown :enrollment="enrollment"/>
            <!-- <ForeignNationalEnrollmentsDropdown :enrollment="enrollment" :foreign-national="foreignNational" /> -->
          </div>
        </v-card-text>
      </v-card>
    </v-sheet>

</template>