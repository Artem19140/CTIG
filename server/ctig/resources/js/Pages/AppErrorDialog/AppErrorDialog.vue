<script setup lang="ts">

    const props = defineProps<{
    message?:string,
    label?: string,
    }>()
</script>


<template>
  <div class="text-center pa-4">
    <v-dialog
      width="auto"
    >
      <v-card
        max-width="400"
        prepend-icon="mdi-update"
        :text="message"
        title="Ошибка"
      >
        <template v-slot:actions>
          <v-btn
            class="ms-auto"
            text="Ok"
            @click="dialog = false"
          ></v-btn>
        </template>
      </v-card>
    </v-dialog>
  </div>
</template>
