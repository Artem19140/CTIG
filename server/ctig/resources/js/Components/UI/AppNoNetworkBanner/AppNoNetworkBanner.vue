<template>
    <v-slide-y-transition>
      <div
        v-if="!isOnline && showOfflineBanner"
        class="offline-bar"
      >
        <div class="offline-content">
          <span>⚠ Нет соединения с интернетом</span>

          <v-btn
            size="small"
            variant="text"
            @click="showOfflineBanner = false"
          >
            скрыть
          </v-btn>
        </div>
      </div>
    </v-slide-y-transition>
</template>

<script setup lang="ts">
import { ref } from 'vue'
const isOnline = ref(navigator.onLine)
const showOfflineBanner = ref(true)

window.addEventListener('online', () => {
  isOnline.value = true
})

window.addEventListener('offline', () => {
  isOnline.value = false
})
</script>

<style scoped>
.offline-bar {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;

  height: 36px;

  background: #ff5252;
  color: white;

  display: flex;
  align-items: center;

  z-index: 9999;

  font-size: 13px;
}

.offline-content {
  width: 100%;
  display: flex;
  justify-content: center;
  gap: 12px;
  align-items: center;
}
</style>