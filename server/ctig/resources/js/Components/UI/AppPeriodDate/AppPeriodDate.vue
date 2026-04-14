<script setup lang="ts">
import AppDateInput from '../AppDateInput/AppDateInput.vue'

const props = defineProps<{
  errors?: {
    dateFrom?: string
    dateTo?: string
  }
}>()

const dateFrom = defineModel<string | null>('dateFrom', {default:null})
const dateTo = defineModel<string | null>('dateTo', {default:null})
</script>

<template>
  <div class="d-flex flex-column ga-1">
    <span class="text-caption text-medium-emphasis">Период</span>
    <div class="flex ga-2">
        <div class="flex-1">
            <AppDateInput
                label=""
                v-model="dateFrom"
                :error-messages="props.errors?.dateFrom"
            />
        </div>
        <div class="flex-1">
            <AppDateInput
                v-model="dateTo"
                :error-messages="props.errors?.dateTo"
            />
        </div>
    </div>
  </div>
</template>