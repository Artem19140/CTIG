<template>
  <v-navigation-drawer
    expand-on-hover
    permanent
    rail
  >
    <v-list>
      <v-list-item
        prepend-avatar="mdi-account"
        subtitle="ryazanova@gmailcom"
        title="Анна Рязанова"
      ></v-list-item>
    </v-list>

    <v-divider></v-divider>

    <v-list density="compact" nav>
      <v-list-item to="/migrants" prepend-icon="mdi-account-group-outline" title="Экзаменуемые" value="myfiles"></v-list-item>
      <v-list-item to="/documents" prepend-icon="mdi-folder" title="Документы" value="shared"></v-list-item>
      <v-list-item to="/exams" prepend-icon="mdi-account-school" title="Экзамены" value="starred"></v-list-item>
      <v-list-item to="/tests" prepend-icon="mdi-book" title="Тесты" value="starred"></v-list-item>
      <v-list-item to="/exam/calendar" prepend-icon="mdi-calendar-multiselect" title="Календарь" value="starred"></v-list-item>
    </v-list>
  </v-navigation-drawer>
</template>