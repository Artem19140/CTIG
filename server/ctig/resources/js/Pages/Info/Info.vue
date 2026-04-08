<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
const props=defineProps<{
    exams:any
}>()

const login = () => {
    router.visit('/login')
}
const calendar = ref()
const focus = ref<string>('')
</script>

<template>
    
    <v-container>
    <!-- Header -->
    <v-row class="justify-space-between align-center mb-6">
      <h1>Справочный центр</h1>
      <v-btn color="primary" @click="login">Войти</v-btn>
    </v-row>

    <!-- Contact Info -->
    <v-card class="mb-6" elevation="3">
      <v-card-title>Контактная информация</v-card-title>
      <v-card-text>
        <p><strong>Название центра:</strong> Центр тестирования иностранных граждан</p>
        <p><strong>Телефон:</strong> +7 (999) 123-45-67</p>
        <p><strong>Email:</strong> info@udsu.ru</p>
        <p><strong>Адрес:</strong> г. Ижевск, ул. Университетская, д. 1</p>
      </v-card-text>
    </v-card>

    <!-- Exam Schedule -->
    <v-card elevation="3">
      <v-card-title>Расписание экзаменов</v-card-title>
      <v-card-text>
        <v-calendar
          ref="calendar"
          v-model="focus"
          :events="exams.data"
          color="primary"
          type="month"
        ></v-calendar>
      </v-card-text>
    </v-card>
  </v-container>
</template>