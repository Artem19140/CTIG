<script setup lang="ts">


</script>

<template>
    <v-container>
    <v-card class="pa-4" outlined>
      <v-card-title>Тест: Основы русского языка</v-card-title>

      <!-- Вопрос 1 -->
      <v-card-text>
        <div class="mb-2"><strong>Вопрос 1:</strong> Какая часть речи обозначает предмет?</div>
        <v-radio-group >
          <v-radio label="Существительное" value="noun"></v-radio>
          <v-radio label="Глагол" value="verb"></v-radio>
          <v-radio label="Прилагательное" value="adj"></v-radio>
          <v-radio label="Наречие" value="adv"></v-radio>
        </v-radio-group>
      </v-card-text>

      <!-- Вопрос 2 -->
      <v-card-text>
        <div class="mb-2"><strong>Вопрос 2:</strong> Какая часть речи обозначает действие?</div>
        <v-radio-group >
          <v-radio label="Глагол" value="verb"></v-radio>
          <v-radio label="Существительное" value="noun"></v-radio>
          <v-radio label="Предлог" value="prep"></v-radio>
          <v-radio label="Местоимение" value="pronoun"></v-radio>
        </v-radio-group>
      </v-card-text>

      <!-- Вопрос 3 -->
      <v-card-text>
        <div class="mb-2"><strong>Вопрос 3:</strong> Укажите правильное написание слова:</div>
        <v-radio-group >
          <v-radio label="Дерево" value="correct"></v-radio>
          <v-radio label="Дерево́о" value="wrong1"></v-radio>
          <v-radio label="Деревo" value="wrong2"></v-radio>
          <v-radio label="Дерев" value="wrong3"></v-radio>
        </v-radio-group>
      </v-card-text>

      <!-- Вопрос 4 -->
      <v-card-text>
        <div class="mb-2"><strong>Вопрос 4:</strong> Какая часть речи отвечает на вопрос «какой?»?</div>
        <v-radio-group >
          <v-radio label="Прилагательное" value="adj"></v-radio>
          <v-radio label="Глагол" value="verb"></v-radio>
          <v-radio label="Существительное" value="noun"></v-radio>
          <v-radio label="Наречие" value="adv"></v-radio>
        </v-radio-group>
      </v-card-text>

      <!-- Вопрос 5 -->
      <v-card-text>
        <div class="mb-2"><strong>Вопрос 5:</strong> Укажите правильное написание слова с мягким знаком:</div>
        <v-radio-group >
          <v-radio label="Мяч" value="correct"></v-radio>
          <v-radio label="Мячь" value="wrong1"></v-radio>
          <v-radio label="Мяц" value="wrong2"></v-radio>
          <v-radio label="Мя" value="wrong3"></v-radio>
        </v-radio-group>
      </v-card-text>

      <!-- Кнопка отправки -->
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="primary" >Отправить ответы</v-btn>
      </v-card-actions>
    </v-card>
  </v-container>
</template>