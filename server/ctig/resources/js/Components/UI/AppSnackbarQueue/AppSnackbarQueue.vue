<script setup lang="ts">
import { useSnackbarQueue } from '@/composables/useSnackbarQueue';
const {messages, queue} = useSnackbarQueue()

</script>

<template> 
    <v-snackbar-queue
      ref="queue"
      v-model="messages"
      :total-visible="3"
      location="bottom start"
      closable
      contained
    />
</template>