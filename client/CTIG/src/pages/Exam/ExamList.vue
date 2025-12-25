<template>
  <v-container fluid>
    <!-- Заголовок и кнопка добавить -->
    <v-row class="mb-4" align="center">
      <v-col cols="12" md="6">
        <h2>Экзамены</h2>
      </v-col>
      <v-col cols="12" md="6" class="text-right">
        <v-btn color="primary" @click="addDialog = true" dark>
          <v-icon left>mdi-plus</v-icon>
          Добавить экзамен
        </v-btn>
      </v-col>
    </v-row>

    <!-- Фильтр по дате -->
    <v-row class="mb-4">
      <v-col cols="12" md="4">
        <v-menu
          v-model="menu"
          :close-on-content-click="false"
          transition="scale-transition"
          offset-y
          min-width="auto"
        >
          <template #activator="{ on, attrs }">
            <v-text-field
              v-model="filterDate"
              label="Фильтр по дате"
              prepend-icon="mdi-calendar"
              
              type="date"
              v-bind="attrs"
              v-on="on"
            ></v-text-field>
          </template>
          <v-date-picker v-model="filterDate" @input="menu = false"></v-date-picker>
        </v-menu>
      </v-col>
    </v-row>

    <!-- Карточки экзаменов -->
    <v-row>
      <v-col
        v-for="exam in exams"
        :key="exam.id"
        cols="12"
        md="6"
        lg="4"
      >
        <v-card outlined hover class="pa-3">
          <v-card-title class="justify-space-between">
            {{ exam.subject }}
            <v-btn icon text @click.stop="openExam(exam)">
              <v-icon>mdi-eye</v-icon>
            </v-btn>
          </v-card-title>
          <v-card-subtitle>Дата: {{ exam.date }} • Записано: {{ exam.people.length }}</v-card-subtitle>
        </v-card>
      </v-col>
    </v-row>

    <!-- Диалог добавления экзамена -->
    <v-dialog v-model="addDialog" max-width="500px">
      <v-card>
        <v-card-title>Добавление экзамена</v-card-title>
        <v-card-text>
          <v-text-field v-model="newExam.subject" label="Название экзамена"></v-text-field>
          <v-menu
            v-model="menu2"
            :close-on-content-click="false"
            transition="scale-transition"
            offset-y
            min-width="auto"
          >
            <template #activator="{ on, attrs }">
              <v-text-field
                v-model="newExam.date"
                label="Дата экзамена"
                prepend-icon="mdi-calendar"
                
                type="date"
                v-bind="attrs"
                v-on="on"
              ></v-text-field>
              <v-text-field
                v-model="newExam.date"
                label="Время экзамена"
                prepend-icon="mdi-clock"
                
                type="time"
                v-bind="attrs"
                v-on="on"
              ></v-text-field>
            </template>
            <v-date-picker v-model="newExam.date" @input="menu2 = false"></v-date-picker>
          </v-menu>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn text @click="addDialog = false">Отмена</v-btn>
          <v-btn color="primary" text @click="saveExam">Сохранить</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Диалог со списком людей -->
    <v-dialog v-model="examDialog" max-width="600">
      <v-card>
        <v-card-title>
          {{ selectedExam.subject }} — {{ selectedExam.date }}
        </v-card-title>
        <v-card-text>
          <v-list>
            <v-list-item
              v-for="person in selectedExam.people"
              :key="person.id"
              @click="openPerson(person)"
            >
              <v-list-item-content>
                <v-list-item-title>{{ person.fullName }}</v-list-item-title>
                <v-list-item-subtitle>{{ person.citizenship }}</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
          </v-list>
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn text @click="examDialog = false">Закрыть</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Диалог с персональными данными -->
    <v-dialog v-model="personDialog" max-width="500">
      <v-card>
        <v-card-title>Персональные данные</v-card-title>
        <v-card-text>
          <v-list dense>
            <v-list-item>
              <v-list-item-title>ФИО</v-list-item-title>
              <v-list-item-subtitle>{{ selectedPerson.fullName }}</v-list-item-subtitle>
            </v-list-item>
            <v-list-item>
              <v-list-item-title>Гражданство</v-list-item-title>
              <v-list-item-subtitle>{{ selectedPerson.citizenship }}</v-list-item-subtitle>
            </v-list-item>
            <v-list-item>
              <v-list-item-title>Дата рождения</v-list-item-title>
              <v-list-item-subtitle>{{ selectedPerson.birthDate }}</v-list-item-subtitle>
            </v-list-item>
            <v-divider />
            <v-list-item>
              <v-list-item-title>Паспорт</v-list-item-title>
              <v-list-item-subtitle>
                Серия: {{ selectedPerson.passport.series }}<br>
                Номер: {{ selectedPerson.passport.number }}<br>
                Кем выдан: {{ selectedPerson.passport.issuedBy }}<br>
                Дата выдачи: {{ selectedPerson.passport.issueDate }}
              </v-list-item-subtitle>
            </v-list-item>
            <v-divider />
            <v-list-item>
              <v-list-item-title>Адрес проживания</v-list-item-title>
              <v-list-item-subtitle>{{ selectedPerson.address }}</v-list-item-subtitle>
            </v-list-item>
          </v-list>
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn text color="primary" @click="personDialog = false">Закрыть</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script setup>
import { ref } from 'vue'

const examDialog = ref(false)
const personDialog = ref(false)
const addDialog = ref(false)
const menu = ref(false)
const menu2 = ref(false)

const selectedExam = ref({ subject: '', date: '', people: [] })
const selectedPerson = ref({})
const newExam = ref({ subject: '', date: '', people: [] })

// Заглушки экзаменов и людей
const exams = ref([
  {
    id: 1,
    subject: 'Русский язык',
    date: '2025-12-25',
    people: [
      { id: 1, fullName: 'Абдуллаев Шахзод Б.', citizenship: 'Узбекистан', birthDate: '1998-04-12', passport: { series: 'AA', number: '1234567', issuedBy: 'ОВД г. Ташкент', issueDate: '2018-06-20' }, address: 'г. Ташкент' },
      { id: 2, fullName: 'Каримов Нозимжон Р.', citizenship: 'Узбекистан', birthDate: '2000-09-03', passport: { series: 'AB', number: '7654321', issuedBy: 'ОВД Самаркандской обл.', issueDate: '2020-01-15' }, address: 'Самарканд' },
      { id: 3, fullName: 'Рахмонов Фирдавс С.', citizenship: 'Таджикистан', birthDate: '1997-11-21', passport: { series: 'TJ', number: '9988776', issuedBy: 'МВД Душанбе', issueDate: '2017-03-10' }, address: 'Душанбе' }
    ]
  },
  {
    id: 2,
    subject: 'История',
    date: '2025-12-27',
    people: [
      { id: 4, fullName: 'Алиев Рашад Э.', citizenship: 'Азербайджан', birthDate: '1996-07-18', passport: { series: 'AZ', number: '5544332', issuedBy: 'ASAN Xidmət', issueDate: '2016-09-05' }, address: 'Баку' },
      { id: 5, fullName: 'Исмоилзода Мухаммад.', citizenship: 'Таджикистан', birthDate: '2001-02-09', passport: { series: 'TJ', number: '6677889', issuedBy: 'МВД Хатлон', issueDate: '2021-05-12' }, address: 'Хатлон' }
    ]
  }
])

const openExam = (exam) => {
  selectedExam.value = exam
  examDialog.value = true
}

const openPerson = (person) => {
  selectedPerson.value = person
  personDialog.value = true
}

// Заглушка добавления экзамена
const saveExam = () => {
  if (newExam.value.subject && newExam.value.date) {
    exams.value.push({ id: exams.value.length + 1, subject: newExam.value.subject, date: newExam.value.date, people: [] })
    newExam.value = { subject: '', date: '', people: [] }
    addDialog.value = false
  }
}
</script>
