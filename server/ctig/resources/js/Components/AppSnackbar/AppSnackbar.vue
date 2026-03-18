<script setup lang="ts">
import { usePage } from '@inertiajs/vue3'
import { watch, ref } from 'vue'

const page = usePage()

const snackbar = ref(false)
const text = ref('')
const color = ref('')

watch(
  () => page.props.flash,
  (flash:any) => {
    if (!flash) return
    if (flash?.success) {
      text.value = flash.success
      snackbar.value = true
      color.value="success"
    }else if(flash?.error){
      text.value = flash.error
      snackbar.value = true
      color.value="error"
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
          color="blue"
          variant="text"
          @click="snackbar = false"
        >
          Close
        </v-btn>
      </template>
    </v-snackbar>
</template>