<template>
  <v-container fluid>
    <!-- Заголовок и кнопка добавить тест -->
    <v-row class="mb-4" align="center">
      <v-col cols="12" md="6">
        <h2>Управление онлайн-тестами</h2>
      </v-col>
      <v-col cols="12" md="6" class="text-right">
        <v-btn color="primary" dark @click="openAddTest">
          <v-icon left>mdi-plus</v-icon>
          Добавить тест
        </v-btn>
      </v-col>
    </v-row>

    <!-- Карточки тестов -->
    <v-row>
      <v-col
        v-for="test in tests"
        :key="test.id"
        cols="12"
        md="6"
        lg="4"
      >
        <v-card outlined hover class="pa-3">
          <v-card-title class="justify-space-between">
            {{ test.title }}
            <v-menu offset-y>
              <template #activator="{ props }">
                <v-btn icon v-bind="props">
                  <v-icon>mdi-dots-vertical</v-icon>
                </v-btn>
              </template>
              <v-list>
                <v-list-item @click="editTest(test)">
                  <v-list-item-title>Редактировать тест</v-list-item-title>
                </v-list-item>
                <v-list-item @click="deleteTest(test)">
                  <v-list-item-title>Удалить тест</v-list-item-title>
                </v-list-item>
              </v-list>
            </v-menu>
          </v-card-title>
          <v-card-subtitle class="mb-2">{{ test.subject }}</v-card-subtitle>
          <v-card-text>
            Вопросов: {{ test.questions.length }}
          </v-card-text>
          <v-card-actions>
            <v-btn small text color="primary" @click="openQuestions(test)">
              Посмотреть вопросы
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>

    <!-- Диалог добавления/редактирования теста -->
    <v-dialog v-model="testDialog" max-width="500px">
      <v-card>
        <v-card-title>{{ editMode ? 'Редактирование теста' : 'Добавление теста' }}</v-card-title>
        <v-card-text>
          <v-text-field v-model="currentTest.title" label="Название теста"></v-text-field>
          <v-select
            v-model="currentTest.subject"
            label="Предмет"
            :items="['Русский язык', 'История России', 'Математика']"
          ></v-select>
          <v-textarea v-model="currentTest.description" label="Описание теста" rows="3"></v-textarea>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn text @click="testDialog = false">Отмена</v-btn>
          <v-btn color="primary" text @click="saveTest">Сохранить</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Диалог со списком вопросов -->
    <v-dialog v-model="questionsDialog" max-width="700px">
      <v-card>
        <v-card-title>
          Вопросы: {{ currentTest.title }}
          <v-spacer></v-spacer>
          <v-btn small color="primary" @click="addQuestion">Добавить вопрос</v-btn>
        </v-card-title>
        <v-card-text>
          <v-list dense>
            <v-list-item
              v-for="(q, index) in currentTest.questions"
              :key="index"
              @click="editQuestion(index)"
            >
              <v-list-item-content>
                <v-list-item-title>{{ q.question }}</v-list-item-title>
                <v-list-item-subtitle>Ответ: {{ q.answer }}</v-list-item-subtitle>
              </v-list-item-content>
              <v-list-item-action>
                <v-btn icon @click.stop="deleteQuestion(index)">
                  <v-icon color="red">mdi-delete</v-icon>
                </v-btn>
              </v-list-item-action>
            </v-list-item>
          </v-list>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn text @click="questionsDialog = false">Закрыть</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Диалог редактирования вопроса -->
    <v-dialog v-model="questionDialog" max-width="500px">
      <v-card>
        <v-card-title>{{ editQuestionIndex !== null ? 'Редактировать вопрос' : 'Добавить вопрос' }}</v-card-title>
        <v-card-text>
          <v-text-field v-model="currentQuestion.question" label="Вопрос"></v-text-field>
          <v-text-field v-model="currentQuestion.answer" label="Ответ"></v-text-field>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn text @click="questionDialog = false">Отмена</v-btn>
          <v-btn color="primary" text @click="saveQuestion">Сохранить</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script setup>
import { ref } from 'vue'

const testDialog = ref(false)
const questionsDialog = ref(false)
const questionDialog = ref(false)
const editMode = ref(false)
const editQuestionIndex = ref(null)

const currentTest = ref({ title: '', subject: '', description: '', questions: [] })
const currentQuestion = ref({ question: '', answer: '' })

const tests = ref([
  { id: 1, title: 'Русский язык. Основы грамматики', subject: 'Русский язык', description: '', questions: [{ question: 'Что такое существительное?', answer: 'Часть речи' }] },
  { id: 2, title: 'История России. XIX век', subject: 'История России', description: '', questions: [{ question: 'Когда произошла Отечественная война 1812 года?', answer: '1812' }] },
  { id: 3, title: 'Русский язык. Орфография', subject: 'Русский язык', description: '', questions: [] }
])

// Добавление/редактирование теста
const openAddTest = () => {
  editMode.value = false
  currentTest.value = { title: '', subject: '', description: '', questions: [] }
  testDialog.value = true
}

const editTest = (test) => {
  editMode.value = true
  currentTest.value = test
  testDialog.value = true
}

const saveTest = () => {
  if (!editMode.value) {
    tests.value.push({ ...currentTest.value, id: tests.value.length + 1 })
  }
  testDialog.value = false
}

// Удаление теста
const deleteTest = (test) => {
  tests.value = tests.value.filter(t => t.id !== test.id)
}

// Открыть список вопросов
const openQuestions = (test) => {
  currentTest.value = test
  questionsDialog.value = true
}

// Добавление/редактирование вопроса
const addQuestion = () => {
  editQuestionIndex.value = null
  currentQuestion.value = { question: '', answer: '' }
  questionDialog.value = true
}

const editQuestion = (index) => {
  editQuestionIndex.value = index
  currentQuestion.value = { ...currentTest.value.questions[index] }
  questionDialog.value = true
}

const saveQuestion = () => {
  if (editQuestionIndex.value !== null) {
    currentTest.value.questions[editQuestionIndex.value] = { ...currentQuestion.value }
  } else {
    currentTest.value.questions.push({ ...currentQuestion.value })
  }
  questionDialog.value = false
}

// Удаление вопроса
const deleteQuestion = (index) => {
  currentTest.value.questions.splice(index, 1)
}
</script>
