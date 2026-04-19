<script setup lang="ts">
import {  ref } from 'vue'


const snackbar = ref(false)
const text = ref('')
const color = ref('')

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