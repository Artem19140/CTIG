<script setup lang="ts">
import { Center } from '@/interfaces/Center';

const props = defineProps<{
    center : Center
}>()
</script>

<template>
    <v-container fluid class="pa-0" v-if="center">
      <v-row dense>
        
        <v-col cols="12" md="6">
          <div class="field">
            <div class="label">Название</div>
            <div class="value">{{ center.name }}</div>
          </div>
        </v-col>

        <v-col cols="12" md="3">
          <div class="field">
            <div class="label">ОГРН</div>
            <div class="value">{{ center.ogrn }}</div>
          </div>
        </v-col>

        <v-col cols="12" md="3">
          <div class="field">
            <div class="label">ИНН</div>
            <div class="value">{{ center.inn }}</div>
          </div>
        </v-col>

        <v-col cols="12" md="6">
          <div class="field">
            <div class="label">Адрес центра</div>
            <div class="value text-wrap">{{ center.address }}</div>
          </div>
        </v-col>

        <v-col cols="12" md="6">
          <div class="field">
            <div class="label">Адрес выдачи сертификатов</div>
            <div class="value text-wrap">{{ center.certificatesIssueAddress }}</div>
          </div>
        </v-col>

        <v-col cols="12" md="4">
          <div class="field">
            <div class="label">Директор</div>
            <div class="value">{{ center.directorFio }}</div>
          </div>
        </v-col>

        <v-col cols="12" md="8">
          <div class="field">
            <div class="label">Название (в родительном падеже)</div>
            <div class="value text-wrap">{{ center.nameGenitive }}</div>
          </div>
        </v-col>

        <v-col cols="12" md="4">
          <div class="field">
            <div class="label">Временная зона</div>
            <div class="value">{{ center.timeZone }}</div>
          </div>
        </v-col>

      </v-row>
  </v-container>
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