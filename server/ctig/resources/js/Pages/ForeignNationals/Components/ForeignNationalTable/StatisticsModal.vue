<script setup lang="ts">
import { useHttp } from '@inertiajs/vue3';
import BaseDialog from '@components/BaseComponents/BaseDialog/BaseDialog.vue';
import AppPrimaryButton from '@components/UI/AppPrimaryButton/AppPrimaryButton.vue';
import AppPeriodDate from '@components/UI/AppPeriodDate/AppPeriodDate.vue';
import { ref } from 'vue';

const isOpen = defineModel({default:false})

interface StatisticsHttp{
  dateFrom:string | null,
  dateTo: string | null
}

interface StatisticsData{
  examsCount:number | null,
  attemptsCount: number | null,
  attemptsTakersCount: number | null,
  failedAttemptsCount: number | null,
  successfulAttemptsCount: number | null,
  bannedAttemptsCount: number | null
}

const http = useHttp<StatisticsHttp, StatisticsData>({
  dateFrom:null,
  dateTo:null
})

const statistics = ref<StatisticsData>({
  examsCount:null,
  attemptsCount:null,
  attemptsTakersCount:null,
  failedAttemptsCount:null,
  successfulAttemptsCount:null,
  bannedAttemptsCount:null
})

const getStatistics = () => {
  http.get('/statistics',{
      onSuccess:(response) => {
        statistics.value = response
      }
  })
}
</script>

<template>
    <BaseDialog 
        width="500"
        title="Статистика"
        subtitle="Выберите период для статистики"
        v-model="isOpen"
        @before-close="(close) => close()"
    >
      <AppPeriodDate 
        :errors="http.errors"
        v-model:date-from="http.dateFrom"
        v-model:date-to="http.dateTo"
      />
        <v-container class="pa-0" fluid>
          <v-row density="comfortable">
            <v-col cols="12" sm="6" md="4">
              <v-card class="pa-4" elevation="1">
                <div class="text-caption text-medium-emphasis">Экзаменов</div>
                <div class="text-h5 font-weight-medium">{{ statistics.examsCount }}</div>
              </v-card>
            </v-col>

            <v-col cols="12" sm="6" md="4">
              <v-card class="pa-4" elevation="1">
                <div class="text-caption text-medium-emphasis">Попыток</div>
                <div class="text-h5 font-weight-medium">{{ statistics.attemptsCount }}</div>
              </v-card>
            </v-col>

            <v-col cols="12" sm="6" md="4">
              <v-card class="pa-4" elevation="1">
                <div class="text-caption text-medium-emphasis">Сдававших</div>
                <div class="text-h5 font-weight-medium">{{ statistics.attemptsTakersCount }}</div>
              </v-card>
            </v-col>

            <v-col cols="12" sm="4">
              <v-card class="pa-4" color="green-lighten-5" elevation="0">
                <div class="text-caption text-green-darken-2">Успешных</div>
                <div class="text-h5 font-weight-medium text-green-darken-2">
                  {{ statistics.successfulAttemptsCount }}
                </div>
              </v-card>
            </v-col>

            <v-col cols="12" sm="4">
              <v-card class="pa-4" color="red-lighten-5" elevation="0">
                <div class="text-caption text-red-darken-2">Не успешных</div>
                <div class="text-h5 font-weight-medium text-red-darken-2">
                  {{ statistics.failedAttemptsCount }}
                </div>
              </v-card>
            </v-col>

            <v-col cols="12" sm="4">
              <v-card class="pa-4" color="grey-lighten-3" elevation="0">
                <div class="text-caption text-grey-darken-2">Снято</div>
                <div class="text-h5 font-weight-medium text-grey-darken-2">
                  {{ statistics.bannedAttemptsCount }}
                </div>
              </v-card>
            </v-col>

          </v-row>
        </v-container>
        <template #actions>
          <AppPrimaryButton
            :loading="http.processing"
            :disabled="http.processing || !http.dateFrom || !http.dateTo"
            @click="getStatistics"
            text="Загрузить"
          />
        </template>
    </BaseDialog>
</template>