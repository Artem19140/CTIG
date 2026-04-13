<script setup lang="ts">
import type { Enrollment } from '../../../interfaces/interfaces';
import { attemptResultStatus } from '../../../Helpers/heplers';
import AppStatusChip from '../../../Components/AppStatusChip/AppStatusChip.vue';
import { DateFormatter } from '../../../Helpers/DateFormatter';
import { useModals } from '../../../Composables/useModals';

defineProps<{
  enrollments: Array<Enrollment> | []
}>();

const modals = useModals()
</script>

<template>
    <div class="text-h6 mb-4">Записи на экзамены</div>
    <div v-if="!enrollments || enrollments.length === 0" class="text-center text-medium-emphasis">
      Записей на экзамены не было
    </div>

    <v-sheet
      
      v-else
      max-height="300"
      class="overflow-y-auto pr-2"
      rounded="lg"
    >
      <v-card
        v-for="enrollment in enrollments"
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

          <AppStatusChip
            :color="attemptResultStatus(enrollment.attempt, enrollment.exam.isPast).color"
            :text="attemptResultStatus(enrollment.attempt, enrollment.exam.isPast).text"
          />
        </v-card-text>
      </v-card>
    </v-sheet>

</template>