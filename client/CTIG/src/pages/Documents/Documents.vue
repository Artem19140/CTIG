<script setup>
import { ref } from 'vue'
import myPdf from '../../assets/a.pdf'
const dialog = ref(false)
const previewDialog = ref(false)
const previewFile = ref('')
import axios from 'axios';

// Заглушка списка шаблонов
const templates = ref([
  { id: 1, title: 'Отчет по проекту', file: 'report.pdf', type: 'pdf' },
  { id: 2, title: 'Согласие на обработку перс данных', file: 'consent.pdf', type: 'pdf' },
  { id: 3, title: 'Заявка на экзамен', file: 'application.pdf', type: 'pdf' },
  { id: 4, title: 'Ведомость на экзамен', file: 'statement.pdf', type: 'pdf' }
])

const openPreview = (template) => {
  previewFile.value = template.file
  previewDialog.value = true
}

const clickw = () => {
        let token = '1|0pCHxC85opGgm7tmpKKx5u82E5L3SI4jV0U8Jb6C9d9ff3ec';//
        axios.post('http://ctig.local/api/tasks', {
            contain: "Что такое миграции в Laravel?",
            creator_id: 5,
            exam_block_id:1,
            fipi_guid:'123235345234dfsdhfgfjhdigsi'
        },
      {
        headers: {
          Authorization: `Bearer ${token}`,
          'Content-Type': 'application/json'
        }
      })

    }
</script>

<template>
  <v-container fluid>
    <!-- Кнопка для загрузки шаблона -->
    <v-row class="mb-4">
      <v-col cols="12" md="6">
        <v-btn @click="clickw" color="primary" dark >
          <v-icon left>mdi-upload</v-icon>
          Загрузить шаблон
        </v-btn>
      </v-col>
    </v-row>

    <!-- Диалог загрузки -->
    <v-dialog v-model="dialog" max-width="500px">
      <v-card>
        <v-card-title>Загрузка шаблона</v-card-title>
        <v-card-text>
          <v-text-field label="Название шаблона"></v-text-field>
          <v-file-input label="Выберите файл" accept=".doc,.docx,.pdf"></v-file-input>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn text @click="dialog = false">Отмена</v-btn>
          <v-btn color="primary" text @click="dialog = false">Загрузить</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Список шаблонов -->
    <v-row>
      <v-col
        v-for="template in templates"
        :key="template.id"
        cols="12"
        md="6"
        lg="4"
      >
        <v-card outlined hover class="pa-3">
          <v-card-title>
            <v-icon left>mdi-file-document-outline</v-icon>
            {{ template.title }}
          </v-card-title>
          <v-card-subtitle class="mb-2">{{ template.file }}</v-card-subtitle>
          <v-card-actions>
            <v-btn small text color="primary" @click="openPreview(template)">
              <v-icon left>mdi-eye</v-icon> Просмотр
            </v-btn>
            <v-btn small text color="primary">
              <v-icon left>mdi-download</v-icon> Скачать
            </v-btn>
            <v-btn small text color="primary">
              <v-icon left>mdi-file-replace-outline</v-icon> Заменить
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>

    <!-- Диалог просмотра документа -->
    <v-dialog v-model="previewDialog" max-width="800px" persistent>
      <v-card>
        <v-card-title>
          Просмотр документа
          <v-spacer></v-spacer>
          <v-btn icon @click="previewDialog = false">
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </v-card-title>
        <v-card-text class="pa-0">
          <iframe
            v-if="previewFile"
            :src="myPdf"
            width="100%"
            height="600"
            style="border: none;"
          ></iframe>
          <div v-else class="pa-4">
            Невозможно просмотреть документ
          </div>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-container>
</template>
