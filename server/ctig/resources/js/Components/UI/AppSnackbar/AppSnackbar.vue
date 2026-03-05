<script setup lang="ts">

const props = defineProps<{
  text?: string,
  time?:number,
  snackbar:boolean
}>()

const isOpen = ref(snackbar)

</script>

<template>
    <v-snackbar
      v-model="snackbar"
      :timeout="time"
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