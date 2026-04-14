<script setup lang="ts">
import { usePage } from '@inertiajs/vue3'
import { watch, ref } from 'vue'

const page = usePage()

const snackbar = ref(false)
const text = ref('')
const color = ref('')

watch(
  () => page.flash,
  (flash:any) => {
    if (!flash) return
    if (flash?.success) {
      text.value = flash.success
      snackbar.value = true
      color.value="success"
    }
  }
)
</script>

<template>
    <v-snackbar
      v-model="snackbar"
      :timeout="5000"
      :color="color"
    >
      {{ text }}

      <template v-slot:actions>
        <v-btn
          color="white"
          variant="text"
          @click="snackbar = false"
        >
          Ok
        </v-btn>
      </template>
    </v-snackbar>
</template>