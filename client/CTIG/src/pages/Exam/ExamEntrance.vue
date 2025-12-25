<script setup lang="ts">
import { ref } from 'vue';
const code = ref<number>()
</script>

<template>
    <v-card
    class="py-12 px-8 text-center mx-auto ma-4"
    max-width="420"
    width="100%"
  >
    <h3 class="text-h6 mb-2">
        Введите трехзначный код для начала экзамена
    </h3>

    <v-otp-input
      v-model="code"
      color="#9E9E9E"
      length="3"
      variant="filled"
    ></v-otp-input>

    <v-btn
      class="mt-6 text-none bg-surface-variant"
      height="40"
      text="Начать"
      variant="plain"
      width="135"
      border
      rounded
      to="/examming"
      @click=""
    ></v-btn>
  </v-card>
</template>

<style>

</style>