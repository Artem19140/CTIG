<script setup lang="ts">
import EnrollmentDropDown from '@/components/Enrollment/EnrollmentDropDown.vue';
import { DateFormatter } from '@helpers/DateFormatter';
import { useModals } from '@composables/useModals';
import ExamResultStatusChip from '@/components/Exam/ExamResultStatusChip.vue';
import ExamStatusChip from '@/components/Exam/ExamStatusChip.vue';
import { ref, watch } from 'vue';
import AppStatusChip from '@/components/UI/AppStatusChip/AppStatusChip.vue';
import { ForeignNational } from '@/interfaces/ForeignNational';
import { Enrollment } from '@/interfaces/Enrollment';

const props = defineProps<{
  foreignNational: ForeignNational
}>();

const enrollments = ref<Enrollment[]>(props.foreignNational.enrollments)
watch(()=> props.foreignNational.enrollments, () => {
  enrollments.value = props.foreignNational.enrollments
})
const modals = useModals()
</script>

<template>
    <div class="text-h6 mb-4">Записи на экзамены</div>
    <div v-if="!foreignNational?.enrollments || foreignNational?.enrollments.length === 0" class="text-center text-medium-emphasis">
      Записей на экзамены не было
    </div>

    <v-sheet
      v-else
      max-height="400"
      class="overflow-y-auto pr-2"
      rounded="lg"
    >
      <v-card
        v-for="enrollment in enrollments"
        :key="enrollment.id"
        @click="modals.open('examShow', {examId:enrollment.exam.id})"
        class="mb-3"
        variant="outlined"
        rounded="lg"
      >
        <v-card-text class="d-flex justify-space-between align-center">
          <div>
            <div class="text-subtitle-1 font-weight-medium">
              {{ enrollment.exam.shortName }}
                
            </div>
            <div class="text-caption text-medium-emphasis">
              {{ new DateFormatter(enrollment.exam.beginTime).format('d.m.Y') }}
              <AppStatusChip 
                v-if="!enrollment.hasPayment"
                text="Нет оплаты"
                color="red"
                size="x-small"
              />
            </div> 
          </div>
          <div>
            <ExamResultStatusChip 
              :status="enrollment.examResult"
            />
            <ExamStatusChip 
              :exam="enrollment.exam"
              v-if="enrollment.exam.status === 'cancelled'"
            />
            <EnrollmentDropDown :enrollment="enrollment"/>
          </div>
        </v-card-text>
      </v-card>
    </v-sheet>

</template>