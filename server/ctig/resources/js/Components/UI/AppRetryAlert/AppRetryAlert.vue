<script setup lang="ts">
import AppRefreshButton from '@components/UI/AppRefreshButton/AppRefreshButton.vue';

const props = defineProps<{
  onRetry?:() => void,
  text:string
}>()
</script>

<template>
  <div class="mt-4">
    <v-alert
      density="compact"
      variant="tonal"
      type="error"
      prominent
    >
      <div class="flex items-center justify-between" >
        <span>
          {{ text }}
        </span>
        <AppRefreshButton
          icon-size="25"
          @click="onRetry"
        />
      </div>
    </v-alert>
  </div>
</template>