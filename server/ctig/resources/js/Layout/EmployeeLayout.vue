<template>
  <div>
    <main>
      <slot />
    </main>
    <!-- Toast / уведомления -->
    <v-snackbar
    v-if="flash"
      :timeout="2000"
    >
      {{ flash.success }}

      <template v-slot:actions>
        <v-btn
          color="blue"
          variant="text"
          @click="snackbar = false"
        >
          Close
        </v-btn>
      </template>
    </v-snackbar>
  </div>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const page = usePage()

// Глобальный flash из Laravel
const flash = computed(() => page.value?.props?.flash || {})
</script>

<style>
    .toast { position: fixed; top: 20px; right: 20px; padding: 10px; border-radius: 4px; color: white; }
    .success { background-color: green; }
    .error { background-color: red; }
</style>