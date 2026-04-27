<script setup lang="ts">
import { ref, watch } from 'vue'
import AppProgressCircular from '../UI/AppProgressCircular/AppProgressCircular.vue';

const props = defineProps<{
  url: string
}>()

const isOpen = defineModel<boolean>({ default: false })

const isLoading = ref(true)

watch(() => props.url, () => {
  isLoading.value = true
})
</script>

<template>
  <v-dialog
    v-model="isOpen"
    fullscreen
    transition="fade-transition"
  >
    <v-card class="h-100 d-flex flex-column">

      <!-- top bar -->
      <div class="d-flex justify-end align-center px-2 py-1" style="height: 25px;">
        <!-- <v-btn
          icon="mdi-download"
          :href="url"
          download
          size="small"
          variant="text"
        />

        <v-btn
          icon="mdi-open-in-new"
          :href="url"
          target="_blank"
          size="small"
          variant="text"
        /> -->

        <v-btn
          icon="mdi-close"
          @click="isOpen = false"
          size="small"
          variant="text"
        />
      </div>

      <div v-if="isLoading" class="flex-grow-1 d-flex align-center justify-center">
        <AppProgressCircular />
      </div>


      <iframe
        v-show="!isLoading"
        class="flex-grow-1 border-0"
        :src="url"
        @load="isLoading = false"
      />
    </v-card>
  </v-dialog>
</template>

<style scoped>
iframe {
  width: 100%;
  height: 100%;
  background: #111;
}
</style>