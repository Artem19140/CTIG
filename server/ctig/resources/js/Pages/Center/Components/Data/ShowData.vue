<script setup lang="ts">
import { Center } from '@/interfaces/Center';

const props = defineProps<{
  center : Center
}>()
</script>

<template>
  <v-card class="pa-4" variant="text">

  <v-row class="py-2" align="start">
    <v-col cols="12" sm="4" class="text-medium-emphasis">
      Название
    </v-col>
    <v-col cols="12" sm="8">
      <div class="break-words whitespace-pre-wrap">
        {{ center.name }}
      </div>
    </v-col>
  </v-row>

  <v-divider />

  <v-row class="py-2" align="start">
    <v-col cols="12" sm="4" class="text-medium-emphasis">
      ОГРН
    </v-col>
    <v-col cols="12" sm="8">
      <div class="break-words">
        {{ center.ogrn }}
      </div>
    </v-col>
  </v-row>

  <v-divider />

  <v-row class="py-2" align="start">
    <v-col cols="12" sm="4" class="text-medium-emphasis">
      ИНН
    </v-col>
    <v-col cols="12" sm="8">
      <div class="break-words">
        {{ center.inn }}
      </div>
    </v-col>
  </v-row>

  <v-divider />

  <v-row class="py-2" align="start">
    <v-col cols="12" sm="4" class="text-medium-emphasis">
      Адрес центра
    </v-col>
    <v-col cols="12" sm="8">
      <div class="break-words whitespace-pre-wrap">
        {{ center.address }}
      </div>
    </v-col>
  </v-row>

  <v-divider />

  <v-row class="py-2" align="start">
    <v-col cols="12" sm="4" class="text-medium-emphasis">
      Адрес выдачи сертификатов
    </v-col>
    <v-col cols="12" sm="8">
      <div class="break-words whitespace-pre-wrap">
        {{ center.certificatesIssueAddress }}
      </div>
    </v-col>
  </v-row>

  <v-divider />

  <v-row class="py-2" align="start">
    <v-col cols="12" sm="4" class="text-medium-emphasis">
      Директор
    </v-col>
    <v-col cols="12" sm="8">
      <div class="break-words">
        {{ center.directorFio }}
      </div>
    </v-col>
  </v-row>

  <v-divider />

  <v-row class="py-2" align="start">
    <v-col cols="12" sm="4" class="text-medium-emphasis">
      Председатель комиссии
    </v-col>
    <v-col cols="12" sm="8">
      <div class="break-words">
        {{ center.commissionChairman }}
      </div>
    </v-col>
  </v-row>

  <v-divider />

  <v-row class="py-2" align="start">
    <v-col cols="12" sm="4" class="text-medium-emphasis">
      Название (в родительном падеже)
    </v-col>
    <v-col cols="12" sm="8">
      <div class="break-words whitespace-pre-wrap">
        {{ center.nameGenitive }}
      </div>
    </v-col>
  </v-row>

</v-card>
</template>

<style lang="css" scoped>
.field {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.label {
  font-size: 12px;
  color: rgba(var(--v-theme-on-surface), 0.6);
  font-weight: 500;
}

.value {
  font-size: 14px;
  font-weight: 500;
  line-height: 1.4;
}
</style>