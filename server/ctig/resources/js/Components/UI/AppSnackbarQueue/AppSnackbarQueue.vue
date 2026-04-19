<script setup lang="ts">
import { useSnackbarQueue } from '@/composables/useSnackbarQueue';
const {messages, queue} = useSnackbarQueue()

</script>

<template> 
    <v-snackbar-queue
      ref="queue"
      v-model="messages"
      :total-visible="3"
      location="bottom center"
      closable
      contained
      timeout
      variant="flat"
      timer="bottom"
      transition="bouncy-slide-auto"
      origin="Anchor"
      prepend-icon="$success"
    >
      <template v-slot:actions>
        <v-btn
          class="px-3"
          density="comfortable"
          rounded="lg"
          variant="text"
          icon="mdi-close"
          @click.stop
        ></v-btn>
      </template>
    </v-snackbar-queue>
</template>