<script setup lang="ts">
const props = defineProps<{
  tasks: any[]
}>()

const emit = defineEmits<{
  (e: 'select', id: number): void
}>()
</script>

<template>
  <v-list density="compact" nav>
    <v-list-item
      v-for="task in tasks"
      :key="task.id"
      @click="emit('select', task.id)"
      class="cursor-pointer"
    >
      <template #prepend>
        <v-avatar size="24">
          {{ task.order }}
        </v-avatar>
      </template>

      <v-list-item-title>
        Задание {{ task.order }}
      </v-list-item-title>
    </v-list-item>
  </v-list>
</template>