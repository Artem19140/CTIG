<script setup lang="ts">
const props = defineProps<{
    tasks:any
}>()
</script>

<template>
   
    <div style="display: flex; flex-wrap: wrap; row-gap: 12px; margin-top: 20px; max-height: 400px; overflow-y: auto;">
        <div
            v-for="task in tasks"
            :key="task.id"
            style="width: 50%; display: flex; align-items: center; column-gap: 8px;"
        >
            {{ task.order }}
            <v-icon color="grey">
            {{ task?.answer ? 'mdi-check-circle' : 'mdi-circle-outline' }}
            </v-icon>
        </div>
    </div>
</template>