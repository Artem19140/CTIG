<template>
  <div>
    <v-card>
    <v-layout>
      <v-navigation-drawer
        expand-on-hover
        permanent
        rail
      >
        <v-list>
          <v-list-item
            prepend-avatar="https://randomuser.me/api/portraits/women/85.jpg"
            subtitle="sandra_a88@gmailcom"
            title="Sandra Adams"
          ></v-list-item>
        </v-list>

        <v-divider></v-divider>

        <v-list density="compact" nav v-model="activeItem">
          <v-list-item prepend-icon="mdi-account-multiple" title="Студенты" @click="router.get('/students')"  value="myfiles"></v-list-item>
          <v-list-item prepend-icon="mdi-folder" value="shared" title="Экзамены" @click="router.get('/exams')"></v-list-item>
          <v-list-item prepend-icon="mdi-folder" title="Отчеты" value="starred"></v-list-item>
          <v-list-item prepend-icon="mdi-folder" title="Проверка" value="starred"></v-list-item>
        </v-list>
      </v-navigation-drawer>

    <v-main style="height: 100vh">
      
      <slot />
    </v-main>
    </v-layout>
  </v-card>









    <v-snackbar
    v-model="snackbar"
      :timeout="5000"
      :color="color"
      prepend-icon="$complete"
      timer="bottom"
      timer-color="white"
      size="large"
    >
      {{ text }}

      <template v-slot:actions>
        <v-btn
          density="comfortable"
          variant="text"
          rounded="lg"
          contained
          @click="snackbar = false"
        >
          Ok
        </v-btn>
      </template>
    </v-snackbar>
  </div>
</template>

<script setup>
import { router, usePage } from '@inertiajs/vue3'
import { watch, ref } from 'vue'

const activeItem = ref('')

const page = usePage()

const snackbar = ref(false)
const text = ref('')
const color = ref('')

watch(
  () => page.props.flash,
  (flash) => {
    if (!flash) return
    if (flash?.success) {
      text.value = flash.success
      snackbar.value = true
      color.value="success"
    }else if(flash?.error){
      text.value = flash.error
      snackbar.value = true
      color.value="error"
    }
  }
)
</script>