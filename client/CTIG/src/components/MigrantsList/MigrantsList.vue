<script setup lang="ts">
    import { ref } from 'vue';
    import migrantData from '../../migrantData.json'

    const search = ref('')
    const headers = [
    {
      align: 'start',
      key: 'name',
      sortable: false,
      title: 'Мигранты',
    },
    { key: 'regNumber', title: 'Рег номер' },
    { key: 'surname', title: 'Фамилия' },
    { key: 'name', title: 'Имя' },
    { key: 'passportNumber', title: 'Паспорт номер' },
    { key: 'passportSeries', title: 'Паспорт серия' },
  ]
</script>

<template>
    <v-card
    title="Nutrition"
    flat
  >
    <template v-slot:text>
      <v-text-field
        v-model="search"
        label="Search"
        prepend-inner-icon="mdi-magnify"
        variant="outlined"
        hide-details
        single-line
      ></v-text-field>
    </template>

    <v-data-table
      :headers="headers"
      :items="migrantData"
      :search="search"
    ></v-data-table>
  </v-card>
  
</template>

<style></style>