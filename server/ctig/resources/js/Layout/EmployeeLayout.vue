<template>
  <div>
    <main>
      <slot />
    </main>
    <v-snackbar
    v-model="snackbar"
      :timeout="5000"
      :color="color"
      prepend-icon="$complete"
      timer="bottom"
      timer-color="white"
      size="large"
    >
      {{ text }}

      <template v-slot:actions>
        <v-btn
          density="comfortable"
          variant="text"
          rounded="lg"
          contained
          @click="snackbar = false"
        >
          Ok
        </v-btn>
      </template>
    </v-snackbar>
  </div>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3'
import { watch, ref } from 'vue'

const page = usePage()

const snackbar = ref(false)
const text = ref('')
const color = ref('')

watch(
  () => page.props.flash,
  (flash) => {
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