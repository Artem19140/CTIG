<script setup lang="ts">
import { Center } from '@/interfaces/Center';

const props = defineProps<{
  center : Center
}>()

const centerData = [
  {label:'Название', value: props.center.name},
  {label:'ОГРН', value: props.center.ogrn},
  {label:'ИНН', value: props.center.inn},
  {label:'Адрес центра', value: props.center.address},
  {label:'Адрес выдачи сертификатов', value: props.center.certificatesIssueAddress},
  {label:'Директор', value:props.center.directorFio},
  {label:'Председатель комиссии', value:props.center.commissionChairman},
  {label:'Название (в родительном падеже)', value:props.center.nameGenitive}
]
</script>

<template>
  <v-card class="pa-4" variant="text">
    <div
      v-for="data in centerData"
    >
      <v-row 
        class="py-2" 
        align="start"
      >
        <v-col cols="12" sm="4" class="text-medium-emphasis">
          {{ data.label }}
        </v-col>
        <v-col cols="12" sm="8">
          <div class="break-words whitespace-pre-wrap">
            {{ data.value }}
          </div>
        </v-col>
      </v-row>
      <v-divider />
    </div>
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