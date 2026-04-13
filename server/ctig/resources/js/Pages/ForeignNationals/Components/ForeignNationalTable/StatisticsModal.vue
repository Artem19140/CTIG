<script setup lang="ts">
import { useHttp } from '@inertiajs/vue3';
import AppInput from '../../../../Components/AppInput/AppInput.vue';
import BaseDialog from '../../../../Components/BaseDialog/BaseDialog.vue';
import PrimaryButton from '../../../../Components/PrimaryButton/PrimaryButton.vue';
import { ref } from 'vue';

const isOpen = defineModel({default:false})
const http = useHttp({
    dateFrom:'',
    dateTo:''
})

const statistics = ref({
    examsCount:null,
    attemptsCount:null,
    attemptsTakersCount:null,
    failedAttemptsCount:null,
    successfulAttemptsCount:null,
    bannedAttemptsCount:null
})

const getStatistics = () => {
    http.get('/statistics',{
        onSuccess:(response: any) => {
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
        @before-close="(done) => done()"
    >
    период
    <div class="d-flex align-center gap-2">
        <AppInput 
            type="date"
            v-model="http.dateFrom"
            :error-messages="http.errors.dateFrom"
        />
        <AppInput 
            v-model="http.dateTo"
            :error-messages="http.errors.dateTo"
            type="date"
        />
      </div>
        <v-container class="pa-0" fluid>
    <v-row dense>
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
            <PrimaryButton
                :loading="http.processing"
                :disabled="http.processing || !http.dateFrom || !http.dateTo"
                @click="getStatistics"
                text="Загрузить"
            />
        </template>
    </BaseDialog>
</template>